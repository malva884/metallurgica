<?php

namespace App\Http\Controllers;

use App\Models\Variation;
use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StaterkitController extends Controller
{
  // home


  public function home(){
      $workflow_cnfOrdine = $workflow_commessa = $workflow_processing = $workflow_completed = $workflow_Revisione = $variation_processing = $variation_completed = $variation_approval = null;
      if(Auth::user()->hasAnyPermission('workflow_approval')){
          $workflow_commessa = Workflow::get_user_workflow(Auth::id(),true,1);
          $workflow_cnfOrdine = Workflow::get_user_workflow(Auth::id(),true,2);
          $workflow_Revisione = Workflow::get_user_workflow(Auth::id(),true,3);
      }
      if(Auth::user()->hasAnyPermission('variation_approval')){
          $variation_approval = Variation::get_user_workflow(Auth::id(),true);
      }

      if(Auth::user()->hasAnyPermission('workflow_create')){
          $workflow_processing = Workflow::get_user_workflow(null,false,null,2);

          $workflow_completed = Workflow::get_user_workflow(null,false,null,3);
      }

      if(Auth::user()->hasAnyPermission('variation_create')){
          $variation_processing = Variation::get_user_workflow(null,false,null,2);
          $variation_completed = Variation::get_user_workflow(null,false,null,3);
      }



      $breadcrumbs = [

      ];
      return view('/content/home', ['breadcrumbs' => $breadcrumbs, 'commessa' => $workflow_commessa,
          'confermeOrdeine' => $workflow_cnfOrdine,'revisioni'=> $workflow_Revisione ,'workflowProcessing'=>$workflow_processing,
          'workflowCompleted' => $workflow_completed,'variation_processing'=> $variation_processing,'variation_completed'=>$variation_completed,
          'variationApproval' =>$variation_approval
      ]);
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
