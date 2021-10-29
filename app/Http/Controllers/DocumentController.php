<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Document_structure;
use App\Models\DocumentPage;
use App\Models\DocumentSignatures;
use App\Models\Email;
use App\Models\WorkflowCategory;
use App\Notifications\UserNotify;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use phpDocumentor\Reflection\Types\Collection;
use Yajra\DataTables\DataTables;

class DocumentController extends Controller
{

    function __construct()
    {
        $this->middleware(['role_or_permission:super-admin|documents_list'], ['only' => ['index', 'list']]);
        $this->middleware(['role_or_permission:super-admin|documents_create'], ['only' => ['create', 'store', 'revision', 'clone']]);
        $this->middleware(['role_or_permission:super-admin|documents_edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['role_or_permission:super-admin|documents_create|documents_see|documents_check'], ['only' => ['workflow']]);
        $this->middleware(['role_or_permission:super-admin|documents_create|documents_see|documents_check|documents_view'], ['only' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];

        $categories = WorkflowCategory::all()->where('disabled', '=', false);

        return view('/content/apps/document/list', ['pageConfigs' => $pageConfigs, 'categories' => $categories,]);

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

        $category = $request->get('category');
        $specifica = $request->get('specifica');
        $status = $request->get('status');
        $view = $request->get('view');

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
            ->join('users', 'users.id', 'documents.user')
            ->leftJoin('workflow_categories', 'workflow_categories.id', 'documents.category')
            ->Where(function ($query) use ($status, $specifica, $category) {
                if ($category) {
                    $query->Where('workflow_categories.id', '=', $category);
                }
                if ($specifica) {
                    $query->Where('specific_number', 'like', '%' . $specifica . '%');
                }
                if ($status) {
                    $query->Where('documents.status', '=', $status);
                }

            })
            ->count();


        $arr = DB::table('documents')->select('documents.*', 'users.firstname', 'users.lastname', 'workflow_categories.category', 'document_structures.document_type')
            ->join('document_structures', 'document_structures.document', 'documents.id')
            ->join('users', 'users.id', 'documents.user')
            ->leftJoin('workflow_categories', 'workflow_categories.id', 'documents.category')
            ->Where(function ($query) use ($status, $specifica, $category) {
                if ($category) {
                    $query->Where('workflow_categories.id', '=', $category);
                }
                if ($specifica) {
                    $query->Where('specific_number', 'like', '%' . $specifica . '%');
                }
                if ($status) {
                    $query->Where('documents.status', '=', $status);
                }

            })
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();

        //Log::channel('stderr')->info($totalRecords);//->where('model_has_roles.model_type', '=', 'App\User')
        $result = collect();

        foreach ($arr as $data) {
            $temp = [
                'id' => $data->id,
                'specific_number' => $data->specific_number,
                'document_type' => $data->document_type,
                'status' => $data->status,
                'category' => $data->category,
                'firstname' => $data->firstname,
                'lastname' => $data->lastname,
                'created_at' => $data->created_at,
            ];
            $workfloUser = DocumentSignatures::select('*')
                ->where('document', '=', $data->id)
                ->get();

            $temp['users'] = $workfloUser;


            $user = Auth::user();
            $viewUser = DocumentSignatures::select('*')
                ->where('document', '=', $data->id)
                ->where('user', '=', $user->id)
                ->first();
            Log::channel('stderr')->info($viewUser);
            if(($view == 1 && !empty($viewUser->id) && empty($viewUser->signed))){
                $result->push($temp);
            }elseif ($view == 2 && !empty($viewUser->id) && !empty($viewUser->signed)){
                $result->push($temp);
            }elseif($view == 3){
                $result->push($temp);
            }










        }
        $data_arr = $result;

        return DataTables::of(json_decode($data_arr, true))
            ->setTotalRecords($totalRecordswithFilter)
            ->addColumn('specific_number', function ($row) {
                return '<a href="show/' . $row['id'] . '" class="user_name text-truncate"><span class="font-weight-bold">' .
                    $row['specific_number'] .
                    '</span></a>';
            })
            ->addColumn('document_type', function ($row) {
                $type = [
                    'EMISSION' => ['title' => 'EMISSION', 'class' => 'badge-light-primary'],
                    'REVISION' => ['title' => 'REVISION', 'class' => 'badge-light-warning'],
                ];
                $html = '<span class="badge badge-pill ' . $type[$row['document_type']]['class'] . '">';
                $html .= $type[$row['document_type']]['title'] . '</span>';
                return $html;
            })
            ->addColumn('status', function ($row) {
                $statusObj = [
                    1 => ['title' => __('locale.Creation'), 'class' => 'badge-light-secondary'],
                    2 => ['title' => __('locale.In Approved'), 'class' => 'badge-light-primary'],
                    3 => ['title' => __('locale.Completed'), 'class' => 'badge-light-success'],
                ];
                $html = '<span class="badge badge-pill ' . $statusObj[$row['status']]['class'] . ' text-capitalized">';
                $html .= $statusObj[$row['status']]['title'] . '</span>';

                return $html;
            })
            ->addColumn('user', function ($row) {
                $user = Auth::user();
                if ($user->hasAnyPermission(['documents_create'])) {
                    $html = '<div class="avatar-group">';
                    $n = count($row['users']);
                    $i = 1;
                    foreach ($row['users'] as $user) {

                        $userObj = User::find($user['user']);
                        Log::channel('stderr')->info($userObj);
                        $html .= '<div
                    data-bs-toggle="tooltip"
                    data-popup="tooltip-custom"
                    data-bs-placement="top"
                    class="avatar pull-up my-0"
                    title="' . $userObj->firstname . ' ' . $userObj->lastname . ' "
                  >
                    <img
                      src="' . asset("/images/users/$userObj->image") . '"
                      alt="Avatar"
                      height="26"
                      width="26"
                      style="box-shadow:0 0 0 2px  '.(($i == $n && $row['status'] != 3) ? '#ff0000':'#228b22').', inset 0 0 0 1px; "
                    />
                  </div>';
                        $i++;
                    }
                    $html .= '</div>';
                    return $html;
                } else
                    return ucwords($row['firstname']) . ' ' . ucwords($row['lastname']);
            })
            ->addColumn('created_at', function ($row) {

                $time = strtotime($row['created_at']);
                return date('d-M-Y', $time);
            })
            ->addColumn('action', function ($row) {
                $user = Auth::user();
                $btn = '<div class="btn-group" role="group" aria-label="Basic example">';
                if (($user->hasAnyPermission(['documents_deleted']) && $user->hasRole(['admin', 'user'])) || $user->hasRole(['super-admin']))
                    $btn .= '<button type="button"  class="btn btn-outline-danger" data-id="' . $row['id'] . '" data-toggle="modal" data-target="#DeleteProductModal"  data-backdrop="false" id="getDeleteId">Elimina</button>';

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['specific_number', 'user', 'document_type', 'status', 'created_at', 'action'])
            ->make(true);


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
        $height_page = 870;
        $pageConfigs = ['pageHeader' => false];

        $categories = WorkflowCategory::all()->where('disabled', '=', false);

        return view('/content/apps/document/create', ['pageConfigs' => $pageConfigs, 'categories' => $categories, 'height_page' => $height_page]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
        $validated = request()->validate([
            //'title' => 'required',
            'specification' => 'required',
            'editors' => 'required',
        ]);
        */
        $vales = $request->all();

        if (empty($vales['specification']) || empty($vales['editors']))
            return json_encode('error');
        // CREO IL DUCUMENTO
        $document = new Document;
        $document->status = 1;
        $document->specific_number = $vales['specification'];
        $document->category = $vales['category'];
        if (!empty($vales['document_father']))
            $document->document_father = $vales['document_father'];
        $document->user = Auth::id();
        if (!empty($vales['father']))
            $document->document_father = $vales['father'];
        $document->save();
        if ($document->id) {
            //$html ='<input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL">';
            unset($vales['editors'][0]);
            foreach ($vales['editors'] as $key => $editor) {
                // CREO LE PAGINE DEL DUCUMENTO
                $documentPage = new DocumentPage;
                $documentPage->document = $document->id;
                //$text = str_replace('contenteditable="true"', 'contenteditable="false"', $editor);
                //$text = str_replace($html, '', $text);
                $documentPage->text = $editor;
                $documentPage->save();
            }

            // CREO LA STRUTTURA DEL DUCUMENTO
            $structure = new Document_structure;
            $structure->document = $document->id;
            $structure->document_date = $document->created_at;
            $structure->document_type = 'EMISSION';
            $structure->document_father = $document->id;
            $structure->revision_num = 0;
            if (!empty($vales['father'])) {
                $structureOld = Document_structure::all()->where('document', '=', $vales['father'])->first();
                $structure->document_father = $structureOld->document_father;
                $structure->document_type = 'REVISION';
                $structure->revision_num = $structureOld->revision_num + 1;
            }
            Log::channel('stderr')->info($structure->save());
            $structure->save();

        }
        //Log::channel('stderr')->info($vales['editors']);

        return $document->id;
    }

    public function image_upload(Request $request)
    {

        $imageName = time() . '.' . $request->file->extension();
        $path = public_path() . '/images/document/';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $request->file->move($path, $imageName);


        return json_encode(['location' => url('/images/document/' . $imageName)]);
    }

    public function workflow(Request $request)
    {
        if (empty($request->id))
            return json_encode(['code' => 100]);

        $document = Document::find($request->id);
        $users = DocumentSignatures::all()->where('document', '=', $document->id);
        $structure = Document_structure::all()->where('document', '=', $document->id)->first();
        $message = 'ERROE';
        if (Auth::user()->hasAnyPermission('documents_create')) {
            $message = 'Start Workflow';
            $userCreator = User::find($document->user);//User::permission('documents_create')->first();
            $documentSing = new DocumentSignatures;
            $documentSing->document = $document->id;
            $documentSing->user = $userCreator->id;
            $documentSing->document_date = $document->created_at;
            $documentSing->signed = true;
            $documentSing->signed_date = date('Y-m-d');
            $documentSing->document_type = $structure->document_type;
            $documentSing->type_approved = 'Drawn up';
            $documentSing->save();

            $item = [
                'title' => 'Approvazione S. T. Avviata!',
                'message' => 'Specifica Tecnica n°: ' . $document->specific_number . ' .',
                'route' => 'document/show/' . $document->id,
                'op' => 'SP',
                'module' => 'document',
                'id_op' => $document->id
            ];
            Notification::send($userCreator, new UserNotify($item));

            $document->status = 2;
            $document->save();
            $userCheck = User::permission('documents_check')->first();
            $documentSing = new DocumentSignatures;
            $documentSing->document = $document->id;
            $documentSing->user = $userCheck->id;
            $documentSing->document_date = $document->created_at;
            $documentSing->signed = false;
            $documentSing->document_type = $structure->document_type;
            $documentSing->type_approved = 'Controlled';
            $documentSing->save();
            $item = [
                'title' => 'Necessaria Aprovazione',
                'message' => 'Specifica Tecnica n°: ' . $document->specific_number . ' .',
                'route' => 'document/show/' . $document->id,
                'op' => 'SP',
                'module' => 'document',
                'id_op' => $document->id
            ];
            Notification::send($userCheck, new UserNotify($item));
            Mail::to($userCheck->email)->send(new \App\Mail\Document($item, $userCheck->firstname . ' ' . $userCheck->lastname));


        } elseif (Auth::user()->hasAnyPermission('documents_check')) {
            $message = 'Approved Workflow';
            $userApproval = DocumentSignatures::all()
                ->where('document', '=', $document->id)
                ->where('user', '=', Auth::user()->id)->first();
            if ($userApproval->user) {
                $userApproval->signed = true;
                $userApproval->signed_date = date('Y-m-d');
                $userApproval->save();
            }
            $userSee = User::permission('documents_see')->first();
            $documentSing = new DocumentSignatures;
            $documentSing->document = $document->id;
            $documentSing->user = $userSee->id;
            $documentSing->document_date = $document->created_at;
            $documentSing->signed = false;
            $documentSing->document_type = $structure->document_type;
            $documentSing->type_approved = 'See';
            $documentSing->save();
            $item = [
                'title' => 'Necessaria Aprovazione',
                'message' => 'Specifica Tecnica n°: ' . $document->specific_number . ' .',
                'route' => 'document/show/' . $document->id,
                'op' => 'SP',
                'module' => 'document',
                'id_op' => $document->id
            ];
            Notification::send($userSee, new UserNotify($item));
            Mail::to($userSee->email)->send(new \App\Mail\Document($item, $userSee->firstname . ' ' . $userSee->lastname));

        } elseif (Auth::user()->hasAnyPermission('documents_see')) {
            $message = 'Approved Workflow';
            $userApproval = DocumentSignatures::all()
                ->where('document', '=', $document->id)
                ->where('user', '=', Auth::user()->id)->first();
            if ($userApproval->user) {
                $userApproval->signed = true;
                $userApproval->signed_date = date('Y-m-d');
                $userApproval->save();
                $document->status = 3;
                $document->save();
                $userCreator = User::permission('documents_create')->first();
                $item = [
                    'title' => 'Specifica Tecnica Apprvata',
                    'message' => 'Specifica Tecnica n°: ' . $document->specific_number . ' .',
                    'route' => 'document/show/' . $document->id,
                    'op' => 'SP',
                    'module' => 'document',
                    'id_op' => $document->id
                ];
                Notification::send($userCreator, new UserNotify($item));
                Mail::to($userCreator->email)->send(new \App\Mail\Document($item, $userCreator->firstname . ' ' . $userCreator->lastname));

            }
        }

        \Session::put('success', __('locale.' . $message));
        return json_encode(['code' => 001]);
    }

    public function finish(Request $request)
    {
        if (empty($request->id))
            return json_encode(['code' => 100]);


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Document $document
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        UserNotify::read('document', $request->id);
        if (empty($request->id))
            abort('404');

        $documentOBJ = Document::find($request->id);
        $documentPage = DocumentPage::all()->where('document', '=', $documentOBJ->id);
        $structure = Document_structure::all()->where('document', '=', $documentOBJ->id)->first();
        $userApproval = false;

        $sings = collect();
        if ($structure->document_father != $documentOBJ->id) {
            $documents = Document_structure::select('document')
                ->where('document_father', '=', $structure->document_father)
                ->where('revision_num', '<=', $structure->revision_num)->get();
            foreach ($documents as $document) {
                $tmp = DocumentSignatures::
                select('document_signatures.*', 'users.workflow')
                    ->join('users', 'users.id', '=', 'document_signatures.user')
                    ->where('document', '=', $document->document)
                    ->orderBy('document_date', 'DESC')
                    ->get();
                $sings->add($tmp);
            }
        } else {
            $tmp = DocumentSignatures::
            select('document_signatures.*', 'users.workflow')
                ->leftJoin('users', 'users.id', '=', 'document_signatures.user')
                ->where('document', '=', $request->id)
                ->orderBy('document_date', 'DESC')
                ->get();
            $sings->add($tmp);
        }
        $rows = [];
        $i = 0;
        foreach ($sings as $sing) {
            foreach ($sing as $a) {
                $n = count($sings);
                if (empty($rows[$a->document])) {
                    $rows[$a->document] = [
                        'row' => $i,
                        'Description' => $a->document_type,
                        'Date' => Carbon::parse($a->document_date)->format('d/m/Y'),
                        'Sheet' => ($i == $n - 1 ? 'Of' : 'Replace n.')
                    ];
                    $i++;
                }

                $rows[$a->document][$a->type_approved] = ($a->signed ? User::find($a->user)->img_signature : '');
                if ($a->user == Auth::user()->id && !$a->signed)
                    $userApproval = true;
            }
        }
        /*
        $file_location = '\\\192.168.1.10\Specifiche_tecniche_MB\specifiche F1\375F1620.docx';
        exec("start \"\" \"{$file_location}\"");
        */

        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/document/show', ['pageConfigs' => $pageConfigs, 'document' => $documentOBJ, 'pages' => $documentPage, 'rows' => $rows, 'userApproval' => $userApproval]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Document $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if (empty($request->id))
            abort('404');

        $height_page = 813;
        $document = Document::find($request->id);
        $pages = DocumentPage::all()->where('document', '=', $request->id);
        $categories = WorkflowCategory::all()->where('disabled', '=', false);
        $page_n = 0;
        foreach ($pages as $page) {
            $page->text = html_entity_decode($page->text);
            $page_n++;
        }

        return view('/content.apps.document.edit', compact('document', 'pages', 'page_n', 'categories', 'height_page'));
    }

    public function clone(Request $request)
    {
        if (empty($request->id))
            abort('404');

        $height_page = 870;
        $document = Document::find($request->id);
        $pages = DocumentPage::all()->where('document', '=', $request->id);
        $categories = WorkflowCategory::all()->where('disabled', '=', false);
        $page_n = 0;
        foreach ($pages as $page) {
            $page->text = html_entity_decode($page->text);
            $page_n++;
        }

        return view('/content.apps.document.clone', compact('document', 'pages', 'page_n', 'categories', 'height_page'));
    }

    public function revision(Request $request)
    {
        if (empty($request->id))
            abort('404');

        $document = Document::find($request->id);
        $pages = DocumentPage::all()->where('document', '=', $request->id);
        $categories = WorkflowCategory::all()->where('disabled', '=', false);
        $structures = Document_structure::all()->where('document', '=', $request->id)->first();

        $page_n = 0;
        foreach ($pages as $page) {
            $page->text = html_entity_decode($page->text);
            $page_n++;
        }
        $num_revision = $structures->revision_num;
        $height_page = 870 - (30 * $num_revision);

        return view('/content.apps.document.revision', compact('document', 'pages', 'page_n', 'categories', 'height_page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Document $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $vales = $request->all();
        if (empty($vales['id']))
            abort('404');

        $document = Document::find($vales['id']);
        $document->specific_number = $vales['specification'];
        $document->category = $vales['category'];
        $document->save();

        if ($document->id) {
            // ELIMINO LE VECCHIE PAGINE DEL DOCUMENTO
            $documentPageDel = DocumentPage::where('document', '=', $vales['id'])->delete();
            unset($vales['editors'][0]);
            foreach ($vales['editors'] as $key => $editor) {
                // CREO LE PAGINE DEL DUCUMENTO
                $documentPage = new DocumentPage;
                $documentPage->document = $document->id;
                $documentPage->text = $editor;
                $documentPage->save();
            }
        }
        return $document->id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Document $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        //
    }

    public function print(Request $request)
    {
        if (empty($request->id))
            abort('404');


        $documentOBJ = Document::find($request->id);
        $documentPage = DocumentPage::all()->where('document', '=', $documentOBJ->id);
        $pages = DocumentPage::all()->where('document', '=', $documentOBJ->id);
        $structure = Document_structure::all()->where('document', '=', $documentOBJ->id)->first();
        $num_revision = $structure->revision_num;
        $height_page = 870 - (30 * $num_revision);

        $sings = collect();
        if ($structure->document_father != $documentOBJ->id) {
            $documents = Document_structure::select('document')
                ->where('document_father', '=', $structure->document_father)
                ->where('revision_num', '<=', $structure->revision_num)->get();
            foreach ($documents as $document) {
                $tmp = DocumentSignatures::
                select('document_signatures.*', 'users.workflow')
                    ->join('users', 'users.id', '=', 'document_signatures.user')
                    ->where('document', '=', $document->document)
                    ->orderBy('document_date', 'DESC')
                    ->get();
                $sings->add($tmp);
            }
        } else {
            $tmp = DocumentSignatures::
            select('document_signatures.*', 'users.workflow')
                ->leftJoin('users', 'users.id', '=', 'document_signatures.user')
                ->where('document', '=', $request->id)
                ->orderBy('document_date', 'DESC')
                ->get();
            $sings->add($tmp);
        }
        $rows = [];
        $i = 0;
        foreach ($sings as $sing) {
            foreach ($sing as $a) {
                $n = count($sings);
                if (empty($rows[$a->document])) {
                    $rows[$a->document] = [
                        'row' => $i,
                        'Description' => $a->document_type,
                        'Date' => Carbon::parse($a->document_date)->format('d/m/Y'),
                        'Sheet' => ($i == $n - 1 ? 'Of' : 'Replace n.')
                    ];
                    $i++;
                }

                $rows[$a->document][$a->type_approved] = ($a->signed ? User::find($a->user)->img_signature : '');
                if ($a->user == Auth::user()->id && !$a->signed)
                    $userApproval = true;
            }
        }
        $pageConfigs = ['pageHeader' => false];

        // share data to view
        //view()->share('employee',$data);
        //$pdf = PDF::loadView('/content/apps/document/print', compact('pageConfigs','document','rows','pages'));

        // download PDF file with download method
        //return $pdf->download('PPPPP_' . date('Y-m-d') . '.pdf');

        return view('/content/apps/document/print', ['pageConfigs' => $pageConfigs, 'document' => $documentOBJ, 'pages' => $pages, 'rows' => $rows, 'height_page' => $height_page]);
    }
}
