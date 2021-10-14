<?php

namespace App\Http\Controllers;


use App\Exports\UserExport;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\Facades\DataTables;


class UserController extends Controller
{

    function __construct()
    {
        $this->middleware(['role_or_permission:super-admin|user_list'], ['only' => ['index']]);
        $this->middleware(['role_or_permission:super-admin|user_edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['role_or_permission:super-admin|user_permission'], ['only' => ['account_settings_permissions','set_permissions']]);
        $this->middleware(['role_or_permission:super-admin|user_edit'], ['only' => ['account_settings_account', 'update']]);
        $this->middleware(['role_or_permission:super-admin|user_create'], ['only' => ['create', 'store']]);
        $this->middleware(['role_or_permission:super-admin|user_delete'], ['only' => ['destroy']]);
        // $this->middleware(['role_or_permission:super-admin|user_edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['role_or_permission:super-admin|user_view'], ['only' => ['show']]);
        $this->middleware(['role_or_permission:super-admin|user_permission'], ['only' => ['permessi']]);

    }

    // Account Settings account
    public function account_settings_account(Request $request)
    {
        $this->CheckPremission($request);

        if(empty($request->id))
            $user = Auth::user();
        else{
            $user = User::find($request->id);
            if(empty($user))
                abort(404);
        }

        $roles = Role::pluck('name', 'id')->all();

        $userRole = $user->roles->pluck('id')->toArray();

        if (empty($userRole))
            $userRole = [0 => 0];

        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "user/index", 'name' => "Utenti"], ['name' => "Account"]];

        return view('/content/apps/user/account-settings-account', compact('breadcrumbs','user','roles','userRole'));
    }

    // Account Settings security
    public function account_settings_security()
    {
        $user = Auth::user();
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "user/index", 'name' => "Utenti"], ['name' => "Security"]];
        return view('/content/apps/user/account-settings-security', compact('breadcrumbs','user'));
    }

    public function account_settings_permissions(Request $request)
    {

        if(empty($request->id))
            $user = Auth::user();
        else{
            $user = User::find($request->id);

            if(empty($user))
                abort(404);
        }

        $perm_user = $user->getPermissionNames();
        $permessi_user = [];
        foreach ($perm_user as $p)
            $permessi_user[] = $p;

        $permissionsOBJ = Permission::all();

        $permissions = [];
        $titoli = [];
        foreach ($permissionsOBJ as $permission) {
            $permissions[$permission->name] = $permission->name;
            $name = explode("_", $permission->name);
            $titoli[$name[1]] = $name[1];
        }

        rsort($titoli);
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "user/index", 'name' => "Utenti"], ['name' => "Permessi Utente"]];
        return view('/content/apps/user/account-settings-permissions',compact('breadcrumbs','titoli','permessi_user','user'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::pluck('name', 'name')->all();
        $pageConfigs = ['pageHeader' => false];
        Auth::user()->getPermissionNames();

        return view('/content/apps/user/list', ['pageConfigs' => $pageConfigs, 'roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $workflow = [
            1 => 'Drawn uo',
            2 => 'Controlled',
            3 => 'Seen',
        ];
        return View::make('/content.apps.user.create', ['roles' => $roles, 'workflow' => $workflow]);
    }

    public function reset(Request $request)
    {

        if (empty($request->userReset))
            abort('404');
        //Log::channel('stderr')->info($request->password);
        $user = User::find($request->userReset);
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('user.index')
            ->with('success', __('locale.Reset Success'));
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
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'acl' => 'required',
            'status' => 'required',
        ]);
        $input = $request->except(['acl', 'image']);
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        if ($user->sex == 'm')
            $user->image = 'default/m_' . rand(1, 4) . '.png';
        else
            $user->image = 'default/f_' . rand(1, 4) . '.png';
        $user->save();
        $user->assignRole($request->input('acl'));

        return redirect()->route('user.show', ['id' => $user->id])
            ->with('success', __('locale.User created'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $perm_user = $user->getPermissionNames();
        $permessi_user = [];
        foreach ($perm_user as $p)
            $permessi_user[] = $p;

        $permissionsOBJ = Permission::all();
        $all_permessi = $user->all_permission;
        $titoli = [];
        foreach ($permissionsOBJ as $permission) {
            $permissions[$permission->name] = $permission->name;
            $name = explode("_", $permission->name);
            $titoli[$name[1]] = $name[1];
        }

        rsort($titoli);
        $pageConfigs = ['pageHeader' => false];
        return view('/content.apps.user.show', compact('pageConfigs', 'user', 'titoli', 'all_permessi', 'permessi_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'id')->all();
        $userRole = $user->roles->pluck('id')->toArray();
        if (empty($userRole))
            $userRole = [0 => 0];

        $perm_user = $user->getPermissionNames();
        $permessi_user = [];
        foreach ($perm_user as $p)
            $permessi_user[] = $p;

        $all_permessi = $user->all_permission;
        $titoli_permessi = $user->title_permission;
        $workflow = [
            1 => 'Drawn uo',
            2 => 'Controlled',
            3 => 'Seen',
        ];
        $permissionsOBJ = Permission::all();

        $permissions = [];
        $titoli = [];
        foreach ($permissionsOBJ as $permission) {
            $permissions[$permission->name] = $permission->name;
            $name = explode("_", $permission->name);
            $titoli[$name[1]] = $name[1];
        }

        rsort($titoli);
        $pageConfigs = ['pageHeader' => false];
        return view('/content.apps.user.edit', compact('user', 'titoli', 'roles', 'userRole', 'titoli_permessi', 'pageConfigs', 'all_permessi', 'permessi_user', 'workflow', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = request()->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'acl' => 'required',
        ]);
        $input = $request->except(['acl', 'image']);
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }
        $user = User::find($id);

        if (request()->file('image')) {
            $imageName = $user->id . '.' . request()->image->extension();
            request()->image->move(public_path('images\users'), $imageName);
            $user->image = $imageName;
        } elseif (!$user->image) {
            if ($user->sex == 'm')
                $user->image = 'default/m_' . rand(1, 4) . '.png';
            else
                $user->image = 'default/f_' . rand(1, 4) . '.png';
        }
        $user->update($input);
        if (empty($user->roles->first()->id))
            $user->set_role($request->input('acl'));
        elseif ($user->roles->first()->id != $request->input('acl'))
            $user->change_role($request->input('acl'));


        return redirect()->route('user.account', ['id' => $user->id])
            ->with('success', __('locale.Changes saved'));
    }


    public function changePassword(Request $request){
        $validated = request()->validate([
            'password' => 'required',
            'new_password' => 'required',
            'confirm_new_password' => 'required',
        ]);

        if($request->new_password != $request->confirm_new_password){
            session()->flash('error','New password & Retype New Password They do not match');
            return redirect()->back();
        }

        $user= Auth::user();
        $hashedPassword = $user->password;

        if (Hash::check($request->password , $hashedPassword)) {
            if (!Hash::check($request->new_password , $hashedPassword)) {

                $users = User::find($user->id);
                $users->password = bcrypt($request->new_password);
                $users->save();
                session()->flash('success','password updated successfully');
                return redirect()->back();
            }
            else{
                session()->flash('error','new password can not be the old password!');
                return redirect()->back();
            }
        }
        else{
            session()->flash('error','old password doesnt matched');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function getUsers(Request $request, User $ser)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));

        $data = User::where('is_active', '1');
        if (isset($input['username'])) {
            $data = $data->where('username', 'like', '%' . $input['username'] . '%');
        }
        if (isset($input['country'])) {
            $data = $data->where('country', $input['country']);
        }
        $data = $data->get();
        return DataTables::of($data)
            ->addColumn('Actions', function ($data) {
                return '<button type="button" data-id="' . $data->id . '" data-toggle="modal" data-target="#DeleteUserModal" class="btn btn-danger btn-sm" id="getDeleteId">Delete</button>';
            })
            ->rawColumns(['Actions'])
            ->make(true);
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
        $totalRecords = User::select('count(*) as allcount')->count();
        $totalRecordswithFilter = User::select('count(*) as allcount')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->Where(function ($query) use ($email, $username, $status, $role) {
                if ($role) {
                    $query->Where('roles.name', '=', $role);
                }
                if ($status) {
                    $value = 1;
                    if ($status != 'Active')
                        $value = 0;
                    $query->Where('users.status', '=', $value);
                }
                if ($email) {
                    $query->Where('email', 'like', '%' . $email . '%');
                }
                if ($username) {
                    $query->Where('firstname', 'like', '%' . $username . '%')
                        ->orWhere('lastname', 'like', '%' . $username . '%');
                }
            })
            ->count();


        $data_arr = DB::table('users')->select('.users.id', 'firstname', 'lastname', 'image', 'email', 'name as role', 'users.status')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->Where(function ($query) use ($email, $username, $status, $role) {
                if ($role) {
                    $query->Where('roles.name', '=', $role);
                }
                if ($status) {
                    $value = 1;
                    if ($status != 'Active')
                        $value = 0;
                    $query->Where('users.status', '=', $value);
                }
                if ($email) {
                    $query->Where('email', 'like', '%' . $email . '%');
                }
                if ($username) {
                    $query->Where('firstname', 'like', '%' . $username . '%')
                        ->orWhere('lastname', 'like', '%' . $username . '%');
                }
            })
            //->skip($start)
            //->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();
        //->toSql();

       return  DataTables::of($data_arr)
            ->setTotalRecords($totalRecords)
            ->addColumn('firstname', function ($row) {
                $user = Auth::user();
                $PathImage = URL::asset('/images/users/');
                $states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                $state = $states[1];
                $name = $row->firstname . ' ' . $row->lastname;
                $image = $row->image;
                if ($image) {
                    // For Avatar image
                    $output = '<img src="' . $PathImage . '/' . $image . '" alt="" height="32" width="32">';
                }
                $colorClass = ($image === '' ? ' bg-light-' . $state . ' ' : '');
                // Creates full output for row
                $row_output =
                    '<div class="d-flex justify-content-left align-items-center">' .
                    '<div class="avatar-wrapper">' .
                    '<div class="avatar ' .
                    $colorClass .
                    ' mr-1">' .
                    $output .
                    '</div>' .
                    '</div>' .
                    '<div class="d-flex flex-column">';
                if(($user->hasAnyPermission(['user_view']) && $user->hasRole(['admin','user'])) || $user->hasRole(['super-admin']))
                    $row_output.='<a href="show/'.$row->id.'" class="user_name text-truncate"><span class="font-weight-bold">'.
                    $name .
                    '</span></a>';
                else
                    $row_output.='<span class="font-weight-bold">'.
                        $name .
                        '</span>';
                $row_output.='</div>' .
                    '</div>';
                return $row_output;
            })
            ->addColumn('role', function ($row) {
                $role_style = [
                    'user' => 'text-primary',//feather.icons['user'].toSvg({ class: 'font-medium-3 text-primary mr-50' }),
                    'staff' => 'text-info',//feather.icons['settings'].toSvg({ class: 'font-medium-3 text-warning mr-50' }),
                    'client' => 'text-primary',//feather.icons['gift'].toSvg({ class: 'font-medium-3 text-success mr-50' }),
                    'super-admin' => 'text-danger',//feather.icons['slack'].toSvg({ class: 'font-medium-3 text-danger mr-50' }),
                    'admin' => 'text-warning',//feather.icons['slack'].toSvg({ class: 'font-medium-3 text-warning mr-50' }),
                    'undefinednull' => 'text-danger',//feather.icons['x'].toSvg({ class: 'font-medium-3 text-danger mr-50' })
                ];
                if (!empty($role_style[$row->role]))
                    return '<span class="text-truncate align-middle ' . $role_style[$row->role] . '">' . $row->role . '</span>';
                else
                    return '';
            })
            ->addColumn('status', function ($row) {
                $statusObj = [
                    1 => ['title' => 'Active', 'class' => 'badge-light-success'],
                    0 => ['title' => 'Inactive', 'class' => 'badge-light-secondary'],
                ];
                $status = $row->status;
                return '<span class="badge badge-pill ' .
                    $statusObj[$status]['class'] .
                '" text-capitalized>' .
                    $statusObj[$status]['title'] .
                '</span>';
            })
            ->addColumn('action', function($row){
                $user = Auth::user();
                $btn='<div class="btn-group" role="group" aria-label="Basic example">';
                if(($user->hasAnyPermission(['user_edit']) && $user->hasRole(['admin','user'])) || $user->hasRole(['super-admin']))
                    $btn.= '<a href="'.route('user.account',['id'=>$row->id]).'"class="btn btn-outline-primary">Edit</a>';
                if(($user->hasAnyPermission(['user_edit']) && $user->hasRole(['admin'])) || $user->hasRole(['super-admin']))
                    $btn.= '<a href="javascript:void(0)"  data-id="'.$row->id.'" class="resetPassword btn btn-outline-warning" data-toggle="modal"  data-target="#resetPassword">Reset Pass</a>';

               $btn.=' </div> ';
                return $btn;
            })
            ->rawColumns(['firstname', 'role', 'status', 'action'])
            ->make(true);

    }

    public function set_permissions($id)
    {
        $user = User::find($id);

        $user->remove_permission($user->permissions);
        foreach (Request()->except('_token') as $key => $value)
            $user->set_permissions($key);
        \Session::put('success', __('locale.Permissions Add Success'));
        return redirect()->route('user.permissions', ['id' => $user->id]);
    }


    private function get_columns()
    {
        return [
            'email' => 'Email',
            'firstname' => 'Nome',
            'lastname' => 'Cognome',
            'sex' => 'Sesso',
            'vat' => 'Partita iva',
            'tax_code' => 'Codice Fisale',
            'phone' => 'Telefono',
            'code_sarco' => 'Codice Enasarco',
            'note' => 'Note',
            'region' => 'Regione',
            'status' => 'Stato',
        ];
    }


    public function exportCsv(Request $request)
    {
        $user = auth()->user();
        $type = $request->get('type');

        Excel::store(new UserExport($request), $user->id . '_users.' . $type, 'customer_uploads');
        return $user->id;
    }

    public function createPDF(Request $request)
    {

        $role = $request->get('role');
        $status = $request->get('status');
        $username = $request->get('username');
        $email = $request->get('email');
        $type = $request->get('type');


        $employee = DB::table('users')->select('*', 'name as role')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->Where(function ($query) use ($email, $username, $status, $role) {
                if ($role) {
                    $query->Where('roles.name', '=', $role);
                }
                if ($status) {
                    $value = 1;
                    if ($status != 'Active')
                        $value = 0;
                    $query->Where('users.status', '=', $value);
                }
                if ($email) {
                    $query->Where('email', 'like', '%' . $email . '%');
                }
                if ($username) {
                    $query->Where('firstname', 'like', '%' . $username . '%')
                        ->orWhere('lastname', 'like', '%' . $username . '%');
                }
            })
            ->get();


        $pdf = PDF::loadView('/content.apps.user.users-pdf', ['employee' => $employee])->setPaper('a4', 'landscape');
        $user = auth()->user();
        $path = public_path('exports/');
        $fileName = $user->id . '_users.' . 'pdf';
        $pdf->save($path . '/' . $fileName);

        $pdf = public_path('exports/' . $fileName);
        return response()->download($pdf);
    }


    // Chat App
    public function chatApp()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'pageClass' => 'chat-application',
        ];

        return view('/content/apps/chat/app-chat', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    // Calender App
    public function calendarApp()
    {
        $pageConfigs = [
            'pageHeader' => false
        ];

        return view('/content/apps/calendar/app-calendar', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    // Email App
    public function emailApp()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'pageClass' => 'email-application',
        ];

        return view('/content/apps/email/app-email', ['pageConfigs' => $pageConfigs]);
    }

    // ToDo App
    public function todoApp()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'pageClass' => 'todo-application',
        ];

        return view('/content/apps/todo/app-todo', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    // File manager App
    public function file_manager()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'pageClass' => 'file-manager-application',
        ];

        return view('/content/apps/fileManager/app-file-manager', ['pageConfigs' => $pageConfigs]);
    }

    // Kanban App
    public function kanbanApp()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'pageClass' => 'kanban-application',
        ];

        return view('/content/apps/kanban/app-kanban', ['pageConfigs' => $pageConfigs]);
    }

    // Ecommerce Shop
    public function ecommerce_shop()
    {
        $pageConfigs = [
            'contentLayout' => "content-detached-left-sidebar",
            'pageClass' => 'ecommerce-application',
        ];

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "eCommerce"], ['name' => "Shop"]
        ];

        return view('/content/apps/ecommerce/app-ecommerce-shop', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    // Ecommerce Details
    public function ecommerce_details()
    {
        $pageConfigs = [
            'pageClass' => 'ecommerce-application',
        ];

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "eCommerce"], ['link' => "/app/ecommerce/shop", 'name' => "Shop"], ['name' => "Details"]
        ];

        return view('/content/apps/ecommerce/app-ecommerce-details', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    // Ecommerce Wish List
    public function ecommerce_wishlist()
    {
        $pageConfigs = [
            'pageClass' => 'ecommerce-application',
        ];

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "eCommerce"], ['name' => "Wish List"]
        ];

        return view('/content/apps/ecommerce/app-ecommerce-wishlist', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    // Ecommerce Checkout
    public function ecommerce_checkout()
    {
        $pageConfigs = [
            'pageClass' => 'ecommerce-application',
        ];

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "eCommerce"], ['name' => "Checkout"]
        ];

        return view('/content/apps/ecommerce/app-ecommerce-checkout', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    private function CheckPremission($request){
        if(auth()->user()->hasRole('user')){
            if(!empty($request->id)){
                abort(403);
            }

        }
    }
}
