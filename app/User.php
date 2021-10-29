<?php

namespace App;

use App\Models\Users_linee;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use \Illuminate\Support\Collection;


class User extends Authenticatable implements MustVerifyEmail
{
    use  Notifiable, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'email', 'password', 'firstname', 'lastname', 'sex', 'phone', 'extension', 'workflow',
        'region', 'status', 'acl', 'image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $title_permission = [
        'edit' => 'edit', 'deleted' => 'deleted', 'create' => 'create', 'view' => 'view',
        'list' => 'list', 'sing' => 'sing', 'start' => 'start', 'categories' => 'categories',
        //'setting' => 'setting',
        'check' => 'check',
    ];

    public $all_permission = [
        'user' => ['user_edit', 'user_deleted', 'user_create', 'user_view', 'user_list',],
        'workflow' => ['workflow_edit', 'workflow_deleted', 'workflow_create', 'workflow_view', 'workflow_list', 'workflow_sing', 'workflow_start', 'workflow_categories', 'workflow_setting'],
        'workProgress' => ['workProgress_create', 'workProgress_deleted', 'workProgress_view', 'workProgress_edit', 'workProgress_list', 'workProgress_check'],
        'system' => ['system_edit', 'system_view',],
        ['role_edit', 'role_deleted', 'role_create', 'role_view', 'role_list',],
        'permission' => ['permission_user',],
    ];

    public static $modules = [
        'adivisory',
        'documents',
        'user',
        'workflow',
        'variation',
        'workProgress',
    ];

    public $super_admin_permission = [
        'user_edit', 'user_deleted', 'user_create', 'user_view', 'user_list',
        'system_edit', 'system_view',
        //'role_edit','role_deleted','role_create','role_view','role_list',
        'permission_user',
    ];

    public $admin_permission = [
        'user_edit', 'user_deleted', 'user_create', 'user_view', 'user_list',
    ];
    public $staff_permission = [
        'user_view', 'user_list',
    ];
    public $manager_permission = [

    ];
    public $user_permission = [

    ];


    public function isRole($role)
    {
        return $this->hasRole($role);
    }

    public static function getPermission($module, $permission)
    {
        return Permission::select('name')->where('name', 'like', $module . '_' . $permission)->first();
    }


    public function acl()
    {
        return $this->belongsToMany(Role::class);
    }


    public function change_role($role)
    {
        $roleID = $this->roles->first()->id;
        DB::table('model_has_roles')->where('model_id', $this->id)->delete();
        $this->set_role($role);
        $perm_da_ass = str_replace('-', '_', $this->roles->first()->name) . '_permission';

        if ($roleID != $role) {
            $permissions = $this->permissions;
            $this->remove_permission($permissions);
            $this->syncPermissions($this->$perm_da_ass);
        }
    }

    public function set_role($role)
    {
        $this->assignRole($role);
    }

    public function remove_permission($permissions)
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission)
                $this->revokePermissionTo($permission);
        } else
            $this->revokePermissionTo($permissions);
    }

    public function set_permissions($prmissions)
    {
        if (is_array($prmissions))
            $this->syncPermissions($prmissions);
        else
            $this->givePermissionTo($prmissions);

    }

    private function getPermissions()
    {
        return $this->getPermissionNames();
    }


}
