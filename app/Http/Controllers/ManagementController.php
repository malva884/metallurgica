<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManagementController extends Controller
{
    function __construct()
    {
        $this->middleware(['role_or_permission:super-admin|role_list'], ['only' => ['index']]);
        $this->middleware(['role_or_permission:super-admin|permission_create'], ['only' => ['add_permission']]);
        $this->middleware(['role_or_permission:super-admin|permission_deleted'], ['only' => ['del_permission']]);
    }

    public function index()
    {
        $user = Auth::user();
        $roles = Role::pluck('name', 'name')->all();
        $perm_user = $user->getPermissionNames();
        $permessi_user = [];
        foreach ($perm_user as $p)
            $permessi_user[] = $p;
        $pageConfigs = ['pageHeader' => false];

        return view('/content/apps/management/list', ['pageConfigs' => $pageConfigs, 'roles' => $roles, 'all_permessi' => $user->all_permission, 'permessi_user' => $permessi_user]);

    }

    public function list_role(Request $request)
    {

        $draw = $request->get('draw');
        // Total records
        $totalRecords = Role::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Role::select('count(*) as allcount')->count();

        $data_arr = DB::table('roles')->get();

        //->where('model_has_roles.model_type', '=', 'App\User')
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        return json_encode($response);
    }

    public function list_permission(Request $request)
    {

        $draw = $request->get('draw');
        // Total records
        $totalRecords = Permission::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Permission::select('count(*) as allcount')->count();

        $data_arr = DB::table('permissions')->orderBy('name', 'asc')->get();

        //->where('model_has_roles.model_type', '=', 'App\User')
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        return json_encode($response);
    }

    public function add_permission(Request $request)
    {
        $permission = Permission::all()->where('name', '=', $request->permsso)->first();
        Log::channel('stderr')->info($permission);
        if (empty($permission)) {
            Permission::create(['name' => $request->permsso]);
        } else
            return abort('0001');

        return json_decode('');
    }

    public function del_permission(Request $request){

    }

}
