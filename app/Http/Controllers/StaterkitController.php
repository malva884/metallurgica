<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StaterkitController extends Controller
{
  // home
  public function home(){
      $workflow_cnfOrdine = $workflow_commessa = null;
     if(Auth::user()->hasAnyPermission('workflow_approval')){
         $workflow_commessa = Workflow::select('count(*) as allcount')
             ->leftJoin('workflow_users', 'workflow_users.Workflow', 'workflows.id')
             ->Where('workflow_users.user', '=', Auth::user()->id)
             ->Where('workflows.type', '=', 1)
             ->count();
         $workflow_cnfOrdine = Workflow::select('count(*) as allcount')
             ->leftJoin('workflow_users', 'workflow_users.Workflow', 'workflows.id')
             ->Where('workflow_users.user', '=', Auth::user()->id)
             ->Where('workflows.type', '=', 2)
             ->count();
     }


    $breadcrumbs = [
        ['link'=>"home",'name'=>"Home"], ['name'=>"Index"]
    ];
    return view('/content/home', ['breadcrumbs' => $breadcrumbs, 'commessa' => $workflow_commessa, 'confermeOrdeine' => $workflow_cnfOrdine]);
  }

  // Layout collapsed menu
  public function collapsed_menu(){
    $pageConfigs = ['sidebarCollapsed' => true];
    $breadcrumbs = [
        ['link'=>"home",'name'=>"Home"],['link'=>"javascript:void(0)",'name'=>"Layouts"], ['name'=>"Collapsed menu"]
    ];
    return view('/content/layout-collapsed-menu', ['breadcrumbs' => $breadcrumbs, 'pageConfigs' => $pageConfigs]);
  }

  // layout boxed
  public function layout_boxed(){
    $pageConfigs = ['layoutWidth' => 'boxed'];

    $breadcrumbs = [
        ['link'=>"home",'name'=>"Home"],['name'=>"Layouts"], ['name'=>"Layout Boxed"]
    ];
    return view('/content/layout-boxed', [ 'pageConfigs' => $pageConfigs,'breadcrumbs' => $breadcrumbs]);
  }

  // without menu
  public function without_menu(){
    $pageConfigs = ['showMenu' => false];
    $breadcrumbs = [
        ['link'=>"home",'name'=>"Home"],['link'=>"javascript:void(0)",'name'=>"Layouts"], ['name'=>"Layout without menu"]
    ];
    return view('/content/layout-without-menu', [ 'breadcrumbs' => $breadcrumbs,'pageConfigs'=>$pageConfigs]);
  }

  // Empty Layout
  public function layout_empty()
  {
    $breadcrumbs = [['link' => "/dashboard/analytics", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Layouts"], ['name' => "Layout Empty"]];
    return view('/content/layout-empty', ['breadcrumbs' => $breadcrumbs]);
  }
  // Blank Layout
  public function layout_blank()
  {
    $pageConfigs = ['blankPage' => true];
    $breadcrumbs = [['link' => "/dashboard/analytics", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Layouts"], ['name' => "Layout Blank"]];
    return view('/content/layout-blank', ['pageConfigs' => $pageConfigs, 'breadcrumbs' => $breadcrumbs]);
  }
}
