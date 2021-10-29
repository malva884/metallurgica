<?php

namespace App\Http\Controllers;

use App\Models\WorkflowCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class WorkflowCategoryController extends Controller
{

    function __construct()
    {
        $this->middleware(['role_or_permission:super-admin|documents_create'], ['only' => ['index', 'list']]);
        $this->middleware(['role_or_permission:super-admin|documents_create'], ['only' => ['create', 'store']]);
        $this->middleware(['role_or_permission:super-admin|documents_edit'], ['only' => ['edit', 'update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/category/list', ['pageConfigs' => $pageConfigs]);
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
        $status = $request->get('status');
        $username = $request->get('username');
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
        $totalRecords = WorkflowCategory::select('count(*) as allcount')->count();
        $totalRecordswithFilter = WorkflowCategory::select('count(*) as allcount')
            ->count();


        $data_arr = DB::table('workflow_categories')->select('workflow_categories.*')
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();
        //->toSql();
        //Log::channel('stderr')->info($totalRecords);//->where('model_has_roles.model_type', '=', 'App\User')

        return DataTables::of(json_decode($data_arr, true))
            ->setTotalRecords($totalRecordswithFilter)
            ->addColumn('category', function ($row) {
                return '<a href="edit/' . $row['id'] . '" class="user_name text-truncate"><span class="font-weight-bold">' .
                    $row['category'] .
                    '</span></a>';
            })

            ->addColumn('disabled', function ($row) {
                $statusObj = [
                    0 => ['title' => __('locale.Active'), 'class' => 'badge-light-success'],
                    1 => ['title' => __('locale.Disabled'), 'class' => 'badge-light-danger']
                ];
                $html = '<span class="badge badge-pill ' . $statusObj[$row['disabled']]['class'] . ' text-capitalized">';
                $html .= $statusObj[$row['disabled']]['title'] . '</span>';

                return $html;
            })

            ->addColumn('action', function ($row) {
                $user = Auth::user();
                $btn = '<div class="btn-group" role="group" aria-label="Basic example">';
                if (($user->hasAnyPermission(['documents_deleted']) && $user->hasRole(['admin', 'user'])) || $user->hasRole(['super-admin']))
                    $btn .= '<button type="button"  class="btn btn-outline-danger" data-id="' . $row['id'] . '" data-toggle="modal" data-target="#DeleteProductModal"  data-backdrop="false" id="getDeleteId">Elimina</button>';

                $btn .= '</div>';
                return '';
            })
            ->rawColumns(['category', 'disabled', 'action'])
            ->make(true);


        return json_encode($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/content/apps/category/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = request()->validate([
            'category' => 'required',
            'disabled' => 'required',
        ]);
        $input = $request->all();


        $category = WorkflowCategory::create($input);
        $category->save();

        return redirect()->route('category.index')
            ->with('success', __('locale.Category created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkflowCategory  $workflowCategory
     * @return \Illuminate\Http\Response
     */
    public function show(WorkflowCategory $workflowCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkflowCategory  $workflowCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(empty($request->id))
            abort('404');

        $category = WorkflowCategory::find($request->id);

        $pageConfigs = ['pageHeader' => false];
        return view('/content.apps.category.edit', compact('category',));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkflowCategory  $workflowCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(empty($request->id))
            abort('404');
        $validated = request()->validate([
            'category' => 'required',
            'disabled' => 'required',
        ]);
        $input = $request->all();
        $category = WorkflowCategory::find($request->id);
        $category->category = $input['category'];
        $category->disabled = $input['disabled'];
        $category->save();

        return redirect()->route('category.index')
            ->with('success', __('locale.Category created'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkflowCategory  $workflowCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkflowCategory $workflowCategory)
    {
        //
    }
}
