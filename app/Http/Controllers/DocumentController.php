<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Document_structure;
use App\Models\DocumentPage;
use App\Models\DocumentSignatures;
use App\Models\Email;
use App\Models\WorkflowCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];
        $categories = WorkflowCategory::all()->where('disabled', '=', false);

        return view('/content/apps/document/list', ['pageConfigs' => $pageConfigs,'categories'=>$categories]);

    }
    public function list(Request $request)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        // $search_arr = $request->get('search');
        $role = $request->get('role');
        $category = $request->get('category');
        $title = $request->get('title');
        $email = $request->get('email');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        //$searchValue = $search_arr['value']; // Search value

        if (!$columnName && !$columnSortOrder) {
            $columnName = 'id';
            $columnSortOrder = 'asc';
        }

        // Total records
        $totalRecords = Document::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Document::select('count(*) as allcount')
            ->join('users','users.id','documents.user')
            ->leftJoin('workflow_categories','workflow_categories.id','documents.category')
            ->Where(function ($query) use ($email, $title, $category, $role) {
                if ($category) {
                    $query->Where('workflow_categories.id', '=', $category);
                }
                if ($title) {
                    $query->Where('title', 'like', $title);
                }

            })
            ->count();


        $data_arr = DB::table('documents')->select('documents.*','users.firstname','users.lastname','workflow_categories.category')
            ->join('users','users.id','documents.user')
            ->leftJoin('workflow_categories','workflow_categories.id','documents.category')
            ->Where(function ($query) use ($email, $title, $category, $role) {
                if ($category) {
                    $query->Where('workflow_categories.id', '=', $category);
                }
                if ($title) {
                    $query->Where('title', 'like', '%'.$title.'%');
                }

            })
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();
        //->toSql();
        //Log::channel('stderr')->info($totalRecords);//->where('model_has_roles.model_type', '=', 'App\User')

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        return json_encode($response);


        //return datatables()->of($response)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageHeader' => false];

        $categories = WorkflowCategory::all()->where('disabled', '=', false);

        return view('/content/apps/document/create', ['pageConfigs' => $pageConfigs, 'categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {/*
        $validated = request()->validate([
            'title' => 'required',
            'specification' => 'required',
            'editors' => 'required',
        ]);
    */
        $vales = $request->all();

        if(empty($vales['title']) || empty($vales['specification']) || empty($vales['editors']))
            return json_encode('error');
        // CREO IL DUCUMENTO
        $document = New Document;
        $document->title = $vales['title'];
        $document->specific_number = $vales['specification'];
        $document->category = $vales['category'];
        if(!empty($vales['document_father']))
            $document->document_father = $vales['document_father'];
        $document->user = Auth::id();
        if(!empty($vales['father']))
            $document->document_father = $vales['father'];
        $document->save();
        if($document->id){
            //$html ='<input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL">';
            unset($vales['editors'][0]);
            foreach ($vales['editors'] as $key=> $editor){
                // CREO LE PAGINE DEL DUCUMENTO
                $documentPage = new DocumentPage;
                $documentPage->document = $document->id;
               //$text = str_replace('contenteditable="true"', 'contenteditable="false"', $editor);
                //$text = str_replace($html, '', $text);
                $documentPage->text = $editor;
                $documentPage->save();

                // CREO LA STRUTTURA DEL DUCUMENTO
                $structure = new Document_structure;
                $structure->document = $document->id;
                $structure->document_date = $document->created_at;
                $structure->document_type = 'EMISSION';
                if(!empty($vales['father'])){
                    $structure->document_father = $vales['father'];
                    $structure->document_type = 'REVISION';
                }
                $structure->save();

                $workflow_sings = [1,2,3];
                foreach ($workflow_sings as $workflow_sing){
                    $user = User::all()->where('workflow','=',$workflow_sing)->first();
                    // ASSOCIO GLI UTENTI CHE FIRMERANNO IL DOCUEMNTIO DUCUMENTO
                    $documentSing = new DocumentSignatures;
                    $documentSing->document = $document->id;
                    $documentSing->user = $user->id;
                    $documentSing->document_date = $document->created_at;
                    $documentSing->document_type = 'EMISSION';
                    if(!empty($vales['father'])){
                        $documentSing->document_father = $vales['father'];
                        $documentSing->document_type = 'REVISION';
                    }
                    $documentSing->save();
                }

            }
        }
        //Log::channel('stderr')->info($vales['editors']);

        return $document->id;
    }

    public function image_upload(Request $request){

        $imageName = time().'.'.$request->file->extension();
        $path = public_path().'/images/document/';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $request->file->move($path, $imageName);


        return json_encode(['location'=>url('/images/document/'.$imageName)]);
    }

    public function workflow(Request $request){
        if(empty($request->id))
            return json_encode(['code' => 100]);

        $sings = DocumentSignatures::all()->where('document','=',$request->id);
        foreach ($sings as $sing){
            //Email::send();
        }

        return json_encode(['code' => 001]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        if(empty($request->id))
            abort('404');

        $document = Document::find($request->id);
        $documentPage = DocumentPage::all()->where('document','=',$document->id);
        $structure = Document_structure::all()->where('document','=',$document->id)->first();
        if($structure->document_father){
            $sings = DocumentSignatures::all()
                ->select('document_signatures.*','users.workflow')
                ->join('users','users.id', '=', 'document_signatures.user')
                ->where('document_father' ,'=',$structure->document_father)
                ->orderBy('document_date','DESC')
                ->get();
        }else{
            $sings = DocumentSignatures::
                select('document_signatures.*','users.workflow')
                ->leftJoin('users', 'users.id', '=', 'document_signatures.user')
                ->where('document' ,'=',$request->id)
                ->orderBy('document_date','DESC')
                ->get();
        }
        $rows = [];
        $i = 0;
        foreach ($sings as $sing){
            if(empty($rows[$sing->document])){
                $rows[$sing->document]=[
                    'row' => $i,
                    'Description' => $sing->document_type,
                    'Date' => Carbon::parse($sing->document_date)->format('d/m/Y'),
                    'Sheet' => 'Replace n.'
                ];
                $i++;
            }
            if($sing->workflow == 1)
                $rows[$sing->document]['Drawn up'] = $sing->user;
            if($sing->workflow == 2)
                $rows[$sing->document]['Controlled'] = $sing->user;
            if($sing->workflow == 3)
                $rows[$sing->document]['Seen'] = $sing->user;

        }
        /*
        $file_location = '\\\192.168.1.10\Specifiche_tecniche_MB\specifiche F1\375F1620.docx';
        exec("start \"\" \"{$file_location}\"");
        */

        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/document/show', ['pageConfigs' => $pageConfigs, 'document'=>$document, 'pages'=>$documentPage,'rows'=> $rows]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(empty($request->id))
            abort('404');

        $document = Document::find($request->id);
        $pages  = DocumentPage::all()->where('document','=',$request->id);
        $categories = WorkflowCategory::all()->where('disabled', '=', false);
        $page_n = 0;
        foreach ($pages as $page){
            $page->text = html_entity_decode($page->text);
            $page_n++;
        }

        return view('/content.apps.document.edit', compact('document','pages','page_n','categories'));
    }

    public function clone(Request $request)
    {
        if(empty($request->id))
            abort('404');

        $document = Document::find($request->id);
        $document->title = $document->title.' (Copy)';
        $pages  = DocumentPage::all()->where('document','=',$request->id);
        $categories = WorkflowCategory::all()->where('disabled', '=', false);
        $page_n = 0;
        foreach ($pages as $page){
            $page->text = html_entity_decode($page->text);
            $page_n++;
        }

        return view('/content.apps.document.clone', compact('document','pages','page_n','categories'));
    }

    public function revision(Request $request)
    {
        if(empty($request->id))
        abort('404');

        $document = Document::find($request->id);
        $document->title = $document->title.' (Revision)';
        $pages  = DocumentPage::all()->where('document','=',$request->id);
        $categories = WorkflowCategory::all()->where('disabled', '=', false);
        $page_n = 0;
        foreach ($pages as $page){
            $page->text = html_entity_decode($page->text);
            $page_n++;
        }

        return view('/content.apps.document.revision', compact('document','pages','page_n','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $vales = $request->all();
        if(empty($vales['id']))
            abort('404');

        $document = Document::find($vales['id']);
        $document->title = $vales['title'];
        $document->specific_number = $vales['specification'];
        $document->category = $vales['category'];
        $document->save();

        if($document->id){

            // ELIMINO LE VECCHIE PAGINE DEL DOCUMENTO
            $documentPageDel = DocumentPage::where('document','=',$vales['id'])->delete();
           // $html ='<input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL">';
            unset($vales['editors'][0]);
            foreach ($vales['editors'] as $key=> $editor){
                // CREO LE PAGINE DEL DUCUMENTO
                $documentPage = new DocumentPage;
                $documentPage->document = $document->id;
               // $text = str_replace('contenteditable="true"', 'contenteditable="false"', $editor);
               // $text = str_replace($html, '', $text);
                $documentPage->text = $editor;
                $documentPage->save();

                // RECUPERO LA STRUTTURA DEL DUCUMENTO
                $structure = Document_structure::all()->where('document','=',$vales['id'])->first();
                $structure->document = $document->id;
                $structure->document_date = $document->created_at;
                $structure->document_type = 'EMISSION';
                if(!empty($vales['father'])){
                    $structure->document_father = $vales['father'];
                    $structure->document_type = 'REVISION';
                }
                $structure->save();

                $workflow_sings = [1,2,3];
                foreach ($workflow_sings as $workflow_sing){
                    $user = User::all()->where('workflow','=',$workflow_sing)->first();
                    // ASSOCIO GLI UTENTI CHE FIRMERANNO IL DOCUEMNTIO DUCUMENTO
                    $documentSing = new DocumentSignatures;
                    $documentSing->document = $document->id;
                    $documentSing->user = $user->id;
                    $documentSing->document_date = $document->created_at;
                    $documentSing->document_type = 'EMISSION';
                    if(!empty($vales['father'])){
                        $documentSing->document_father = $vales['father'];
                        $documentSing->document_type = 'REVISION';
                    }
                    $documentSing->save();
                }

            }
        }
        return $document->id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        //
    }

    public function print(Request $request)
    {
        if(empty($request->id))
            abort('404');

        $document = Document::find($request->id);
        $documentPage = DocumentPage::all()->where('document','=',$document->id);
        $structure = Document_structure::all()->where('document','=',$document->id)->first();
        if($structure->document_father){
            $sings = DocumentSignatures::all()
                ->select('document_signatures.*','users.workflow')
                ->join('users','users.id', '=', 'document_signatures.user')
                ->where('document_father' ,'=',$structure->document_father)
                ->orderBy('document_date','DESC')
                ->get();
        }else{
            $sings = DocumentSignatures::
            select('document_signatures.*','users.workflow')
                ->leftJoin('users', 'users.id', '=', 'document_signatures.user')
                ->where('document' ,'=',$request->id)
                ->orderBy('document_date','DESC')
                ->get();
        }
        $rows = [];
        $i = 0;
        foreach ($sings as $sing){
            if(empty($rows[$sing->document])){
                $rows[$sing->document]=[
                    'row' => $i,
                    'Description' => $sing->document_type,
                    'Date' => Carbon::parse($sing->document_date)->format('d/m/Y'),
                    'Sheet' => 'Replace n.'
                ];
                $i++;
            }
            if($sing->workflow == 1)
                $rows[$sing->document]['Drawn up'] = $sing->user;
            if($sing->workflow == 2)
                $rows[$sing->document]['Controlled'] = $sing->user;
            if($sing->workflow == 3)
                $rows[$sing->document]['Seen'] = $sing->user;

        }
        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/document/print', ['pageConfigs' => $pageConfigs,'document'=>$document, 'pages'=>$documentPage,'rows'=> $rows]);
    }
}
