<?php

namespace App\Http\Controllers;

use App\Imports\AdvisoryImport;
use App\Imports\WorkStatusImport;
use App\Models\Advisory;
use App\Models\AdvisoryHead;
use App\Models\Greneric;
use App\Models\MachineLaborCosts;
use App\Models\MaterialsCost;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class AdvisoryHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/workstatus/advisory/list', ['pageConfigs' => $pageConfigs]);
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
        $totalRecords = AdvisoryHead::select('count(*) as allcount')->count();
        $totalRecordswithFilter = AdvisoryHead::select('count(*) as allcount')
            ->Where(function ($query) use ($email, $title, $category, $role) {
                if ($category) {
                    // $query->Where('workflow_categories.id', '=', $category);
                }
            })
            ->count();


        $data_arr = DB::table('advisory_heads')
            //->skip($start)
            //->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();
        //->toSql();
        //Log::channel('stderr')->info($totalRecords);//->where('model_has_roles.model_type', '=', 'App\User')


        return DataTables::of($data_arr)
            ->setTotalRecords($totalRecords)
            ->addColumn('ol', function ($row) {
                $user = Auth::user();

                $row_output =
                    '<div class="d-flex justify-content-left align-items-center">' .
                    '<div class="d-flex flex-column">';
                if(($user->hasAnyPermission(['user_view']) && $user->hasRole(['admin','user'])) || $user->hasRole(['super-admin']))
                    $row_output.='<a href="detail/'.$row->id.'" class="user_name text-truncate"><span class="font-weight-bold">'.
                        $row->ol .
                        '</span></a>';
                else
                    $row_output.='<span class="font-weight-bold">'.
                        $row->ol .
                        '</span>';
                $row_output.='</div>' .
                    '</div>';
                return $row_output;
            })
            ->addColumn('created_at', function ($row) {
                $time = strtotime($row->created_at);
                return date('d-M-Y',$time) ;

            })
            ->addColumn('type', function ($row) {
                $type =[
                    'ot' => 'Ottico',
                    'rm' => 'Rame'
                ];

                $html = '<h5 class="font-weight-bolder">';
                $html.= $type[$row->type].'</h5>';

                return $html;
            })
            ->addColumn('total_real', function ($row) {
                return '<h5 class="font-weight-bolder">€ '.number_format($row->total_real,2,",",".").'</h5>';
            })
            ->addColumn('total_theoretical', function ($row) {
                return '<h5 class="font-weight-bolder">€ '.number_format($row->total_theoretical,2,",",".").'</h5>';
            })
            ->addColumn('status', function ($row) {
                $statusObj =[
                    0 => ['title'=> __('locale.Import'), 'class'=>'badge-light-secondary'],
                    1 => ['title'=> __('locale.Processing'), 'class'=>'badge-light-primary'],
                    2 => ['title'=> __('locale.Completed'), 'class'=>'badge-light-success'],
                ];

                $html = '<span class="badge badge-pill '.$statusObj[$row->status]['class'].' text-capitalized">';
                $html.= $statusObj[$row->status]['title'].'</span>';

                return $html;
            })
            ->addColumn('action', function($row){
                $user = Auth::user();
                $btn='<div class="btn-group" role="group" aria-label="Basic example">';
                if(($user->hasAnyPermission(['adivisory_create','adivisory_view']) && $user->hasRole(['admin','user'])) || $user->hasRole(['super-admin']))
                    $btn.= '<a href="'.route('advisorys.summary',['id'=>$row->id]).'" class="btn btn-outline-dark">Riepilogo</a>';
                if(($user->hasAnyPermission(['adivisory_deleted']) && $user->hasRole(['admin','user'])) || $user->hasRole(['super-admin']))
                    $btn.= '<button type="button"  class="btn btn-outline-danger" data-id="'.$row->id.'" data-toggle="modal" data-target="#DeleteProductModal"  data-backdrop="false" id="getDeleteId">Elimina</button>';
                 $btn.='</div>';
                return $btn;
            })
            ->rawColumns(['ol','created_at','type','total_real','total_theoretical','status','action'])
            ->make(true);
    }

    public function import()
    {
        $generic = Greneric::find(1);
        $materiali = MaterialsCost::all()->where('date_import', '=', date('Y-m-d'));
        $date_materiali = MaterialsCost::distinct()->orderBy('date_import', 'DESC')->get(['date_import']);
        $date_macchinari = MachineLaborCosts::distinct()->orderBy('date_import', 'DESC')->get(['date_import']);

        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/workstatus/advisory/import', ['pageConfigs' => $pageConfigs, 'generic' => $generic, 'matariali' => $date_materiali, 'macchinari' => $date_macchinari]);
    }

    public function load(Request $request)
    {

        if (empty($request->lavorazione) || empty($request->data_macchinari) || empty($request->data_matariali) || empty($request->file('file'))) {
            \Session::flash('error', 'I campi "Lavorazione" e "File da elaborare" sono Obligatori.');
            return json_decode('ERRORE!!!!!');
        }
        Log::channel('stderr')->info('o@@@@@@OK');
        try {
            $data_macchinari = $request->data_macchinari;
            $data_materiali = $request->data_matariali;
            $head = new AdvisoryHead;
            $head->date_material = $data_materiali;
            $head->date_macchine = $data_macchinari;
            $head->status = 0;
            $head->type = $request->lavorazione;
            $head->save();
            $file = Excel::toArray(new AdvisoryImport, $request->file('file'));
            $father = null;
            $fase = null;
            $i = 1;
            foreach ($file as $rows) {
                foreach ($rows as $row) {

                    if(!empty($row[7]))
                        $fase = $row[7];
                    if (!empty($row[0])){
                        $father = $row[0];
                        $fase = null;
                    }

                    $advisory = new Advisory;
                    $advisory->head = $head->id;
                    $advisory->order = $row[0];
                    $advisory->order_row = $i++;
                    if($request->lavorazione == 'rm')
                        $advisory->order_row2 = $advisory->order_row;
                    if (empty($row[0]))
                        $advisory->father = $father;
                    $advisory->material_1 = $row[1];
                    $advisory->material_description_1 = $row[2];
                    $advisory->division = $row[3];
                    $advisory->total_order_quantity = $row[4];
                    $advisory->um = $row[5];
                    $advisory->quantity_1 = $row[6];
                    $advisory->operation_activity = (!empty(trim($row[7])) ? trim($row[7]) : null );
                    $advisory->operation_activity_row = $fase;
                    $advisory->work_center = $row[8];
                    $advisory->confirmed_quantity = $row[9];  #TODO controllare se corrisponde!
                    $advisory->activity_machine_minutes = $row[11];
                    $advisory->activity_manpower_minutes = $row[13];
                    $advisory->activity_machine_theoretic_minutes = $row[22];
                    $advisory->activity_manpower_theoretic_minutes = $row[23];
                    $advisory->material_2 = $row[15];
                    $advisory->material_description_2 = $row[16];
                    $advisory->requirement_quantity = $row[17];
                    $advisory->base_unit_measure = $row[18];
                    $advisory->quantity_2 = $row[19];
                    $advisory->um_fase = $row[20];
                    $advisory->qty_perv_fase = $row[21];
                    $advisory->date_created = date('Y-m-d');
                    $advisory->check_row = false;
                    $advisory->view = true;
                    $advisory->save();
                }
            }

            if($head->type == 'ot')
                $head->orderAdvisory();
            else{
                $order = Advisory::select('order')
                    ->where('head', '=', $head->id)
                    ->orderBy('order_row', "ASC")
                    ->first();
                $head->ol = $order->order;
                $head->save();
            }


            $head->getBlockCalculation();

            $head->status = 1;
            $head->save();
            \Session::put('success', __('locale.Import successfully'));

        } catch (\Exception $e) {
            Log::channel('stderr')->info('@@@@');
            Log::channel('stderr')->info($e->getMessage());
            \Session::flash('error', json_encode($e->getMessage()));
        }
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AdvisoryHead $advisoryHead
     * @return \Illuminate\Http\Response
     */
    public function show(AdvisoryHead $advisoryHead)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Consultivo"]
        ];
        return view('/content/apps/workstatus/advisory/detail', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AdvisoryHead $advisoryHead
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvisoryHead $advisoryHead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AdvisoryHead $advisoryHead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvisoryHead $advisoryHead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AdvisoryHead $advisoryHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        if (empty($request->id))
            abort(404);

        $worck = AdvisoryHead::find($request->id);
        $worck->delete();

        return json_decode('ok');
    }
}
