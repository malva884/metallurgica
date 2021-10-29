<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    use HasFactory,UsesUuid;

    protected $fillable = [
        'id','user_creator','commessa','status','end_date','type'
    ];

    public static function get_user_workflow($user=null,$approved=null,$type=null,$status=null){
        if($user) {
            return Workflow::select('count(*) as allcount')
                ->leftJoin('workflow_users', 'workflow_users.Workflow', 'workflows.id')
                ->Where(function ($query) use ($user) {
                    if ($user) {
                        $query->Where('workflow_users.user', '=', $user);
                    }
                })
                ->Where(function ($query) use ($approved) {
                    if ($approved) {
                        $query->WhereNull('workflow_users.aprovato');
                    }
                })
                ->Where(function ($query) use ($type) {
                    if ($type) {
                        $query->Where('workflows.type', '=', $type);
                    }
                })
                ->Where(function ($query) use ($status) {
                    if ($status) {
                        $query->Where('workflows.status', '=', $status);
                    }
                })
                ->count();
        }else{
            return Workflow::select('count(*) as allcount')
                ->Where(function ($query) use ($status) {
                    if ($status) {
                        $query->Where('workflows.status', '=', $status);
                    }
                })
                ->count();
        }
    }
}
