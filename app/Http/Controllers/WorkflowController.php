<?php

namespace App\Http\Controllers;


use App\Notifications\UserNotify;
use App\Models\Workflow;
use App\Models\WorkflowFile;
use App\Models\WorkflowUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\DataTables;

class WorkflowController extends Controller
{
    function __construct()
    {
        $this->middleware(['role_or_permission:super-admin|workflow_list'], ['only' => ['index', 'list']]);
        $this->middleware(['role_or_permission:super-admin|workflow_create'], ['only' => ['create', 'store']]);
        $this->middleware(['role_or_permission:super-admin|workflow_approval|workflow_create|workflow_view'], ['only' => ['show']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];


        return view('/content/apps/workflow/index', ['pageConfigs' => $pageConfigs]);
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
        $commessa = $request->get('commessa');
        $status = $request->get('status');
        $view = $request->get('view');
        if (empty($view))
            $view = 1;

        if ($view == 3)
            $view = null;
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        //$searchValue = $search_arr['value']; // Search value

        if (!$columnName && !$columnSortOrder) {
            $columnName = 'id';
            $columnSortOrder = 'asc';
        }

        $totalRecordswithFilter = Workflow::select('count(*) as allcount')
            ->Where('workflows.commessa', 'like', '%' . $commessa)
            ->Where(function ($query) use ($status) {
                if ($status == 2)
                    $query->Where('workflows.status', '=', 2);
                if ($status == 3)
                    $query->Where('workflows.status', '=', 3);
                if ($status == 4)
                    $query->Where('workflows.status', '=', 4);
            })
            ->count();

        $arr = DB::table('workflows')
            ->select('workflows.*')
            ->Where('workflows.commessa', 'like', '%' . $commessa . '%')
            ->Where(function ($query) use ($status) {
                if ($status == 2)
                    $query->Where('workflows.status', '=', 2);
                if ($status == 3)
                    $query->Where('workflows.status', '=', 3);
                if ($status == 4)
                    $query->Where('workflows.status', '=', 4);
            })
            ->orderBy($columnName, $columnSortOrder)
            ->get();
        $result = collect();

        foreach ($arr as $data) {
            $temp = [
                'id' => $data->id,
                'status' => $data->status,
                'user_creator' => $data->user_creator,
                'commessa' => $data->commessa,
                'type' => $data->type,
                'created_at' => $data->created_at,
                'aprovato' => null,
            ];

            $workfloUser = WorkflowUser::select('*')
                ->where('user', '=', Auth::user()->id)
                ->where('Workflow', '=', $data->id)
                ->first();
            $sing = 1;
            if (!empty($workfloUser->id)) {
                $sing = 2;
                if ($workfloUser->aprovato)
                    $sing = 3;
            } else {
                $workfloUser = WorkflowUser::select('*')
                    ->where('Workflow', '=', $data->id)
                    ->get();
                $temp['users'] = $workfloUser;
            }
            $temp['aprovato'] = $sing;
            if (($view == 1 && $sing == 2) || ($view == 2 && $sing == 3) || $view === null)
                $result->push($temp);
        }

        $data_arr = $result;

        return DataTables::of(json_decode($data_arr, true))
            ->setTotalRecords($totalRecordswithFilter)
            ->addColumn('commessa', function ($row) {
                return '<a href="show/' . $row['id'] . '" class="user_name text-truncate"><span class="font-weight-bold">' .
                    $row['commessa'] .
                    '</span></a>';
            })
            ->addColumn('type', function ($row) {
                $type = [
                    1 => ['title' => 'Commessa', 'class' => 'badge-light-dark'],
                    2 => ['title' => 'Conferma ordine', 'class' => 'badge-light-warning'],
                    3 => ['title' => 'Revisione', 'class' => 'badge-light-danger'],
                ];
                $html = '<span class="badge badge-pill ' . $type[$row['type']]['class'] . '">';
                $html .= $type[$row['type']]['title'] . '</span>';
                return $html;
            })
            ->addColumn('created_at', function ($row) {

                $time = strtotime($row['created_at']);
                return date('d-M-Y', $time);
            })
            ->addColumn('status', function ($row) {
                $statusObj = [
                    1 => ['title' => __('locale.Import'), 'class' => 'badge-light-secondary'],
                    2 => ['title' => __('locale.Processing'), 'class' => 'badge-light-secondary'],
                    3 => ['title' => __('locale.Completed'), 'class' => 'badge-light-primary'],
                    4 => ['title' => __('locale.End'), 'class' => 'badge-light-success'],
                ];
                $html = '<span class="badge badge-pill ' . $statusObj[$row['status']]['class'] . ' text-capitalized">';
                $html .= $statusObj[$row['status']]['title'] . '</span>';

                return $html;
            })
            ->addColumn('aprovato', function ($row) {
                $html = '';
                if (!empty($row['aprovato']) && $row['aprovato'] > 1) {
                    $statusObj = [
                        2 => ['title' => __('locale.Not Signed'), 'class' => 'badge-light-primary'],
                        3 => ['title' => __('locale.Signed'), 'class' => 'badge-light-info'],
                    ];
                    $html .= '<span class="badge badge-pill ' . $statusObj[$row['aprovato']]['class'] . ' text-capitalized">';
                    $html .= $statusObj[$row['aprovato']]['title'] . '</span>';
                } else {
                    if (!empty($row['users'])) {
                        $html .= '<div class="avatar-group">';
                        foreach ($row['users'] as $user) {
                            if (!$user['aprovato']) {
                                $userObj = User::find($user['user']);
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
                      style="box-shadow:0 0 0 2px ' . ($user['aprovato'] ? '#228b22' : '#ff0000') . ', inset 0 0 0 1px; "
                    />
                  </div>';
                            }
                        }
                        $html .= '</div>';
                    }
                }

                return $html;
            })
            ->addColumn('action', function ($row) {
                $user = Auth::user();
                $btn = '<div class="btn-group" role="group" aria-label="Basic example">';
                if (($user->hasAnyPermission(['workflow_deleted']) && $user->hasRole(['admin', 'user'])) || $user->hasRole(['super-admin']))
                    $btn .= '<button type="button"  class="btn btn-outline-danger" data-id="' . $row['id'] . '" data-toggle="modal" data-target="#DeleteProductModal"  data-backdrop="false" id="getDeleteId">Elimina</button>';

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['commessa', 'type', 'created_at', 'status', 'aprovato', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageHeader' => false];
        $users = User::permission('workflow_approval')->get();
        return view('/content/apps/workflow/create', ['pageConfigs' => $pageConfigs, 'users' => $users]);
    }

    public function check(Request $request)
    {
        $commessa = $request->commessa;
        $type = $request->type;
        if ($type == 2) {
            $commessa = 'CO_'.$commessa;
        }elseif ($type == 3){
            $commessa = 'RV_'.$commessa;
        }

        $workflow = Workflow::select('count(*) as allcount')
            ->where('commessa', '=', $commessa)
            ->where('type', '=', $type)
            ->count();

        return ($workflow ? true : false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = request()->validate([
            'file' => 'required',
            'commessa' => 'required',
            'type' => 'required',
        ]);

        $stati = config('global.statiWorkflow');
        $file = new WorkflowFile;
        $workflow = new Workflow;

        $workflow->user_creator = Auth::id();
        $workflow->status = $stati['Started'];
        $workflow->type = $request->type;
        $type = '';
        $message = 'Commessa ';
        if ($workflow->type == 2) {
            $type = 'CO_';
            $message = 'Conf. D\'ordine: ';
        }elseif ($workflow->type == 3){
            $type = 'RV_';
            $message = 'Revisione: ';
        }

        $workflow->commessa = $type . $request['commessa'];
        if (!empty($request['nomeFile'])) {
            $file->nomeFile = $request['nomeFile'];
            $nameFile = $type . $request['nomeFile'] . '.' . request()->file->extension();
        } else {
            $nameFile = $type . request()->file->getClientOriginalName();
        }

        if (file_exists(public_path('workflow/' . $workflow->commessa) . $nameFile)) {
            $nameFile = date('YmdHis') . $nameFile;
        } else {
            $path = public_path('workflow/' . $workflow->commessa);
            if (!File::isDirectory($path))
                File::makeDirectory($path, 0777, true, true);
        }

        // $nameFile = $nameFile . '.' . request()->file->extension();
        request()->file->move(public_path('workflow/' . $workflow->commessa), $nameFile);
        $file->path_local = $workflow->commessa . '/' . $nameFile;
        $file->user = Auth::id();
        $workflow->save();
        $file->Workflow = $workflow->id;
        $file->save();
        $users = User::permission('workflow_approval')->get();
        foreach ($users as $user) {
            $workflowUser = new WorkflowUser;
            $workflowUser->Workflow = $workflow->id;
            $workflowUser->user = $user->id;
            $workflowUser->save();
            //Email::send();
        }
        $item = [
            'title' => 'Necessaria Aprovazione',
            'message' => $message . $workflow->commessa . ' .',
            'route' => 'workflow/show/' . $workflow->id,
            'op' => 'GP',
            'module' => 'workflow',
            'id_op' => $workflow->id
        ];
        Notification::send($users, new UserNotify($item));
        return redirect()->route('workflow.index')
            ->with('success', __('locale.Created Workflow'));
    }



    /**
     * Display the specified resource.
     *
     * @param \App\Models\Workflow $workflow
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $id = $request->id;
        $user = Auth::user();
        $workflow = Workflow::find($id);
        UserNotify::read('workflow', $request->id);
        if (empty($workflow))
            abort(404);

        $workflowFile = WorkflowFile::select('*')->where('Workflow', '=', $workflow->id)->first();
        if($workflowFile->path_drive)
            $file = 'https://drive.google.com/file/d/'. $workflowFile->path_drive.'/preview';
        else
            $file = URL::asset('/workflow') . '/' . $workflowFile->path_local;
        $workflowUser = WorkflowUser::all()
            ->where('Workflow', '=', $workflow->id)
            ->where('user', '=', $user->id)
            ->first();

        $myApproved = false;
        $onlyView = false;
        $approvato = false;
        $log_create = false;
        $start = false;
        $dataFirma = '';
        $users = null;
        $stati = config('global.statiWorkflow');

        if ($workflowUser) {
            $myApproved = true;
            if (empty($workflowUser->data_view)) {
                $workflowUser->data_view = date('Y-m-d H:i:s');
                $workflowUser->save();
            }

            if (($user->id == $workflow->user_creator && $user->hasAnyPermission('workflow_create')) || $user->hasAnyPermission('workflow_create')) {
                if ($workflow->status === $stati['Approved'] || $workflow->status === $stati['End']) {
                    $log_create = true;
                } elseif ($workflowUser->aprovato) {
                    $start = true;
                }
            } else {
                if ($workflowUser->aprovato) {
                    $approvato = true;
                    $dataFirma = $workflowUser->updated_at;
                }

            }

        } else {
            if ($user->hasAnyPermission('workflow_create')) {
                if ($workflow->status === $stati['Approved'] || $workflow->status === $stati['End']) {
                    $log_create = true;
                } else
                    $start = true;
            }
            if ($user->hasAnyPermission('workflow_view'))
                $onlyView = true;
        }

        if ($user->hasAnyPermission('workflow_create')) {
            $users = WorkflowUser::select('workflow_users.*', 'users.firstname', 'users.lastname', 'users.image')
                ->join('users', 'users.id', 'workflow_users.user')
                ->where('workflow_users.Workflow', '=', $workflow->id)
                ->orderBy('users.firstname', 'asc')
                ->get();
        }


        $pageConfigs = ['pageHeader' => false];
        return view('/content.apps.workflow.show', compact('pageConfigs', 'workflow', 'file', 'user', 'approvato', 'log_create', 'dataFirma', 'start', 'onlyView', 'users','myApproved','workflowFile'));

    }

    public function sing(Request $request)
    {

        $workflowUser = WorkflowUser::all()
            ->where('Workflow', '=', $request->id)
            ->where('user', '=', $request->user)
            ->first();

        if (!$workflowUser->aprovato) {
            $workflowUser->aprovato = true;
            $workflowUser->save();

            $userNotppproved = WorkflowUser::all()
                ->where('Workflow', '=', $request->id)
                ->where('aprovato', '=', null)
                ->count();

            if (!$userNotppproved) {
                $stati = config('global.statiWorkflow');
                $workflow = Workflow::find($workflowUser->Workflow);
                $workflow->status = $stati['Approved'];
                $workflow->save();
                $item = [
                    'title' => 'Workflow Terminato',
                    'message' => ($workflow->type == 1 ? 'Commessa: ' : 'Conf d\'ordine: ') . $workflow->commessa,
                    'route' => 'workflow/show/' . $workflow->id,
                    'op' => 'GP',
                    'module' => 'workflow',
                    'id_op' => $workflow->id
                ];
                Notification::send(User::find($workflow->user_creator), new UserNotify($item));

            }
        }
    }

    // Generate PDF
    public function createPDF(Request $request)
    {
        // retreive all records from db
        $workflow = Workflow::find($request->id);
        $workflowUsers = WorkflowUser::select('users.lastname', 'users.firstname', 'workflow_users.aprovato', 'workflow_users.updated_at')
            ->join('users', 'users.id', 'workflow_users.user')
            ->where('workflow_users.Workflow', '=', $request->id)->get();

        $workflowFile = WorkflowFile::select('*')->where('Workflow', '=', $request->id)->first();

        $stati = config('global.statiWorkflow');
         if($workflow->status != $stati['End']){
            $workflow->status = $stati['End'];
            $workflow->end_date = date('Y-m-d');
            $workflow->save();
        }
        $data = [
            'workflow' => $workflow,
            'workflowUsers' => $workflowUsers,
            'workflowFile' => $workflowFile,
            'logo' => public_path('/images/logo/metallurgica.png')
        ];
        // share data to view
        //view()->share('employee',$data);
        $pdf = PDF::loadView('/content/apps/workflow/pdf/log', compact('data'));

        // download PDF file with download method
        return $pdf->download($workflow->commessa . '_' . date('Y-m-d') . '.pdf');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Workflow $workflow
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(empty($request->id))
            abort(404);

        $workflow = Workflow::find($request->id);
        $file = WorkflowFile::all()->where('Workflow','=',$request->id)->first();
         $pageConfigs = ['pageHeader' => false];
        $users = User::permission('workflow_approval')->get();
        return view('/content/apps/workflow/edit', ['pageConfigs' => $pageConfigs, 'workflow' => $workflow,'file'=>$file]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Workflow $workflow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Workflow $workflow)
    {
        if(empty($request->id))
            abort(404);

        $file = WorkflowFile::all()->where('Workflow','=',$request->id)->first();
        if($file){
            if(!empty($request['file'])){
                $rqt_id = explode('id=',$request['file']);
                if(!empty($rqt_id[1])){
                    $id_file = explode('&',$rqt_id[1]);
                    $file->path_drive = $id_file[0];
                }
            }
            $rqt_id_folder = explode('/',$request['folder']);
            $file->path_folder_drive = $rqt_id_folder[count($rqt_id_folder)-1];
            $file->save();
            if($file->path_drive){
                $path = public_path('workflow/' . $request['commessa']);
                if (\File::exists($path))
                    \File::deleteDirectory($path);
            }

        }

        return redirect()->route('workflow.show',['id'=> $request->id])
            ->with('success', __('locale.Edit Workflow'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Workflow $workflow
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (empty($request->id))
            abort(404);

        $worck = Workflow::find($request->id);
        $worck->delete();

        return json_decode('ok');
    }
}
