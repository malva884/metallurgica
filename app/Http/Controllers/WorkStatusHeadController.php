<?php

namespace App\Http\Controllers;

use App\Imports\WorkStatusImport;
use App\Models\Email;
use App\Models\Greneric;
use App\Models\MachineLaborCosts;
use App\Models\MaterialsCost;
use App\Models\WorkStatus;
use App\Models\WorkStatusHead;
use App\Notifications\UserNotify;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Console\Input\Input;
use Yajra\DataTables\Facades\DataTables;

class WorkStatusHeadController extends Controller
{

    function __construct()
    {
        $this->middleware(['role_or_permission:super-admin|workProgress_list'], ['only' => ['index']]);
        $this->middleware(['role_or_permission:super-admin|workProgress_deleted'], ['only' => ['destroy']]);
        $this->middleware(['role_or_permission:super-admin|workProgress_create'], ['only' => ['import','load']]);
        $this->middleware(['role_or_permission:super-admin|workProgress_view|workProgress_create'], ['only' => ['summary','list_summary']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */




    public function index()
    {

        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/workstatus/list', ['pageConfigs' => $pageConfigs]);

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
        $totalRecords = WorkStatusHead::select('count(*) as allcount')->count();
        $totalRecordswithFilter = WorkStatusHead::select('count(*) as allcount')
            ->Where(function ($query) use ($email, $title, $category, $role) {
                if ($category) {
                    // $query->Where('workflow_categories.id', '=', $category);
                }
            })
            ->count();


        $data_arr = DB::table('work_status_heads')
            //->skip($start)
            //->take($rowperpage)
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

        return DataTables::of($data_arr)
            ->setTotalRecords($totalRecords)
            ->addColumn('created_at', function ($row) {
                $time = strtotime($row->created_at);
                return '<a href="detail/'.$row->id.'" class="user_name text-truncate"><span class="font-weight-bold">'.
                    date('d-M-Y',$time) .
                    '</span></a>';

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
            ->addColumn('total_final_mp', function ($row) {
                return '<h5 class="font-weight-bolder">€ '.number_format($row->total_final_mp,2,",",".").'</h5>';
            })
            ->addColumn('total_raw_material', function ($row) {
                return '<h5 class="font-weight-bolder">€ '.number_format($row->total_raw_material,2,",",".").'</h5>';
            })
            ->addColumn('total_final', function ($row) {
                return '<h5 class="font-weight-bolder">€ '.number_format($row->total_final,2,",",".").'</h5>';
            })
            ->addColumn('raw_material_machine_manpower_cost', function ($row) {
                return '<h5 class="font-weight-bolder">€ '.number_format($row->raw_material_machine_manpower_cost,2,",",".").'</h5>';
            })
            ->addColumn('action', function($row){
                $user = Auth::user();
                $btn='<div class="btn-group" role="group" aria-label="Basic example">';
                if(($user->hasAnyPermission(['workProgress_withdrawals','workProgress_create']) && $user->hasRole(['admin','user'])) || $user->hasRole(['super-admin']))
                    $btn.= '<a href="'.route('workstatus.check',['id'=>$row->id]).'" class="btn btn-outline-primary">Prelievi</a>';
                if(($user->hasAnyPermission(['workProgress_create','workProgress_view']) && $user->hasRole(['admin','user'])) || $user->hasRole(['super-admin']))
                    $btn.= '<a href="'.route('workstatus.summary',['id'=>$row->id]).'" class="btn btn-outline-dark">Riepilogo</a>';
                if(($user->hasAnyPermission(['workProgress_deleted']) && $user->hasRole(['admin','user'])) || $user->hasRole(['super-admin']))
                    $btn.= '<button type="button"  class="btn btn-outline-danger" data-id="'.$row->id.'" data-toggle="modal" data-target="#DeleteProductModal"  data-backdrop="false" id="getDeleteId">Elimina</button>';

                $btn.='</div>';
                return $btn;
            })

            ->rawColumns(['created_at','status','total_final_mp','total_raw_material','total_final','raw_material_machine_manpower_cost','action'])
            ->make(true);

    }

    public function import()
    {
        $generic = Greneric::find(1);
        $materiali = MaterialsCost::all()->where('date_import', '=', date('Y-m-d'));
        $date_materiali = MaterialsCost::distinct()->orderBy('date_import','DESC')->get(['date_import']);
        $date_macchinari = MachineLaborCosts::distinct()->orderBy('date_import','DESC')->get(['date_import']);

        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/workstatus/import', ['pageConfigs' => $pageConfigs, 'generic' => $generic, 'matariali' => $date_materiali, 'macchinari' => $date_macchinari]);
    }



    public function load(Request $request)
    {
        if (empty($request->data_macchinari) || empty($request->data_matariali) || empty($request->file('file'))) {
            //\Session::flash('error', 'I campi "Lavorazione" e "File da elaborare" sono Obligatori.');
            return json_decode('ERRORE!!!!!');
        }

        $data_macchinari = $request->data_macchinari;
        $data_materiali = $request->data_matariali;
        $manpowerCost = Greneric::find(1);
        $machineCosts= MachineLaborCosts::select('machine','cost')->where('date_import','=',$data_macchinari)->get();
        $matirialCosts= MaterialsCost::select('material','cost')->where('date_import','=',$data_materiali)->get();
        $machines = [];
        foreach ($machineCosts as $machineCost)
            $machines[$machineCost->machine] = $machineCost->cost;
        $matirials = [];
        foreach ($matirialCosts as $matirialCost)
            $matirials[$matirialCost->material] = $matirialCost->cost;

        try {
            $file = Excel::toArray(new WorkStatusImport, $request->file('file'));
            $heaad = new WorkStatusHead;
            $heaad->status = 0;
            $heaad->save();
            $father = null;
            $save_cleck = false;
            $infoOrder = [];

            $order = 0;
            foreach ($file as $rows) {
                foreach ($rows as $row) {
                    if (!empty($row[0]) && $row[6] > 0)
                        $save_cleck = true;
                    elseif (!empty($row[0]) && $row[6] <= 0)
                        $save_cleck = false;

                    if (!empty($row[0])) {
                        $father = $row[0];
                        $infoOrder = [];
                    }

                    $work = new WorkStatus;
                    $work->order_row = $order;
                    if (!empty($row[0]))
                        $infoOrder = $work->stored($manpowerCost->manpower,$matirials, $machines, $row, $heaad, $save_cleck, null, null);
                    else
                        $infoOrder = $work->stored($manpowerCost->manpower,$matirials, $machines, $row, $heaad, $save_cleck, $father, $infoOrder);


                    $order++;
                }

            }

            $total_material = DB::table('work_statuses')
                ->where('head', '=', $heaad->id)
                ->sum(DB::raw('material_cost'));

            $total_manodopera = DB::table('work_statuses')
                ->where('head', '=', $heaad->id)
                ->sum(DB::raw('manpower_cost'));
            $total_macchina = DB::table('work_statuses')
                ->where('head', '=', $heaad->id)
                ->sum(DB::raw('machine_cost'));
            $manodopera_macchina = $total_manodopera + $total_macchina;
            $heaad->total_machine_manpower_cost = round($manodopera_macchina, 2);
            $heaad->total_raw_material = round($total_material, 2);
            $heaad->raw_material_machine_manpower_cost = round($manodopera_macchina + $total_material, 2);
            $heaad->status = 1;
            $heaad->save();
            $check = WorkStatus::all()->where('check_row', '=', true);

            if ($check->count()) {
                $item = [
                    'title' => 'Quantita Mancanti',
                    'message' =>'Ci sono delle quantità mancanti.',
                    'route' => 'workstatus/detail/check/' . $heaad->id,
                    'op' => 'PR',
                    'type' => 'prelievi',
                    'module' => 'workstatus',
                    'id_op' => $heaad->id
                ];

                $users = User::permission('workProgress_withdrawals')->get();
                Notification::send($users, new UserNotify($item));
                Log::channel('stderr')->info($users);
                foreach ($users as $user){
                    $name = $user->firstname.' '.$user->lastname;
                    Mail::to($user->email)->send(new \App\Mail\CorsoLavori($item,$name));
                }


            }
            \Session::put('success', __('locale.Import successfully'));
            //return redirect(route('users.index'));
        } catch (\Exception $e) {

            \Session::flash('error', json_encode($e->getMessage()));
            // return redirect(route('users.index'));
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
     * @param \App\Models\WorkStatusHead $workStatusHead
     * @return \Illuminate\Http\Response
     */
    public function show(WorkStatusHead $workStatusHead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\WorkStatusHead $workStatusHead
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkStatusHead $workStatusHead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\WorkStatusHead $workStatusHead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkStatusHead $workStatusHead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\WorkStatusHead $workStatusHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (empty($request->id))
            abort(404);

        $worck = WorkStatusHead::find($request->id);
        $worck->delete();

        return json_decode('ok');
    }
}
