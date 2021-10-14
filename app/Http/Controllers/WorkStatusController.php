<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Greneric;
use App\Models\WorkCourseSummary;
use App\Models\WorkStatus;
use App\Models\WorkStatusHead;
use App\Notifications\UserNotify;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class WorkStatusController extends Controller
{

    function __construct()
    {
        $this->middleware(['role_or_permission:super-admin|workProgress_withdrawals|workProgress_create'], ['only' => ['show_check','update_check']]);
        $this->middleware(['role_or_permission:super-admin|workProgress_view'], ['only' => ['show','list']]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkStatus  $workStatus
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $head = WorkStatusHead::find($request->id);
        if(empty($head->id))
            abort(404);

        UserNotify::read('workstatus',$request->id);
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "workstatus/index", 'name' => "Lista"],['name' => "Corso Lavori"]
        ];
        return view('/content/apps/workstatus/detail', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function show_check(Request $request)
    {

        $head = WorkStatusHead::find($request->id);
        if(empty($head->id))
            abort(404);

        UserNotify::read('workstatus',$request->id);
        if(!empty($request->nt)){
            $user = auth()->user();
            $notification = $user->notifications()->where('id', $request->nt)->first();
            if($notification){
                $notification->markAsRead();
            }
        }
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "workstatus/index", 'name' => "Lista"], ['name' => "Prelievi Mancanti"]
        ];
        return view('/content/apps/workstatus/check', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function list (Request $request){


        $return = DB::table('work_statuses')->select('*')
            ->where('head','=',$request->id)
            ->where('view','=',true)
            ->orderBy("order_row", "asc")
            ->get();

        //Log::channel('stderr')->info($return.'('.$request->id.')');
        return json_encode($return);
    }

    public function check (Request $request){

        $return = DB::table('work_statuses')->select('*')
            ->where('check_row','=',true)
            ->where('head','=',$request->id)
            //->where('view','=',true)
            ->orderBy("order_row", "asc")
            ->get();


        return json_encode($return);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkStatus  $workStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkStatus $workStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkStatus  $workStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $return[] = 'ok';
        $workStatus = WorkStatus::find($request->get('id'));
        $column = $request->get('column');
        $workStatus->$column = $request->get('value');
        if($column == 'quantity_2'){
            $workStatus->material_cost = round($workStatus->cost_material * $workStatus->$column,2);
            $return['material_cost'] = $workStatus->material_cost;
            $this->check_order($workStatus->father,$workStatus->head);
        }else
            $workStatus->$column = $request->get('value');
        $workStatus->save();

        return json_encode($return);
    }

    public function update_check(Request $request)
    {

        $workStatus = WorkStatus::find($request->get('id'));
        $column = $request->get('column');
        $workStatus->$column = $request->get('value');
        if($column == 'quantity_2') {
            $workStatus->check_row = false;
            $workStatus->material_cost =  round($workStatus->cost_material * $workStatus->$column,2);
            $workStatus->save();
            $this->check_order($workStatus->father, $workStatus->head);
        }
        return 'ok';
    }

    public function update_summary(Request $request)
    {
        $workStatus = WorkCourseSummary::find($request->get('id'));
        $column = $request->get('column');
        $workStatus->$column = $request->get('value');
        $workStatus->save();
        return 'ok';
    }

    private function check_order($order,$head){
        $Works = WorkStatus::all()
            ->where('father', '=',$order)
            ->where('head','=',$head)
            ->where('check_row','=',true);
        if(!$Works->count()){
                $Works_father = WorkStatus::all()
                    ->where('order', '=',$order)
                    ->first();
                $Works_father->check_row = false;
                $Works_father->save();

        }
    }

    public function finalWork(Request $request){

        if(empty($request->id))
            abort('404');

        $head = WorkStatusHead::find($request->id);
        WorkCourseSummary::where('head','=',$head->id)->delete();
        $material = DB::table('work_statuses')
            ->where('head', '=', $head->id)
            ->sum(DB::raw('material_cost'));

        $total_manodopera = DB::table('work_statuses')
            ->where('head', '=', $head->id)
            ->sum(DB::raw('manpower_cost'));
        $total_macchina = DB::table('work_statuses')
            ->where('head', '=', $head->id)
            ->sum(DB::raw('machine_cost'));

        $head->total_raw_material =  (float)$material;
        $head->total_machine_manpower_cost = (float)$total_manodopera + (float)$total_macchina;
        $head->raw_material_machine_manpower_cost = (float)$material + (float)$total_manodopera + (float)$total_macchina;
        $head->save();

        $orders = WorkStatus::all()
            ->where('head','=',$request->id)
            ->where('view','=',true)
            ->where('father','=','');

        foreach ($orders as $order){
            $material1 = WorkStatus::where('father', '=', $order->order)
                ->where('head', '=', $head->id)
                ->sum('result_2');
            $temp = WorkStatus::where('father', '=', $order->order)
                ->where('head', '=', $head->id)
                ->sum('result_1');
            $consultivo = $material1 + $temp;

            if(!$material1)
                $material1 = 0.00;
            if(!$consultivo)
                $consultivo = 0.00;

            $workCourseSummarie = new WorkCourseSummary;
            $workCourseSummarie->order = $order->order;
            $workCourseSummarie->head = $order->head;
            $workCourseSummarie->total_material = round($material1,2);
            $workCourseSummarie->total_consuntivo = round($consultivo,2);
            $workCourseSummarie->save();
        }

        $total_material = DB::table('work_course_summaries')
            ->where('head', '=', $head->id)
            ->sum(DB::raw('total_material'));

        $total_consultivo = DB::table('work_course_summaries')
            ->where('head', '=', $head->id)
            ->sum(DB::raw('total_consuntivo'));


        $head->total_final_mp = (float)$head->total_raw_material - (float)$total_material;
        $head->total_final = (float)$head->raw_material_machine_manpower_cost - (float)$total_consultivo;
        $head->status = 2;
        $head->save();
    }

    public function summary(Request $request){

        if(empty($request->id))
            abort('404');

        $head = WorkStatusHead::all()->where('id','=',$request->id)->first();
        $summary = WorkCourseSummary::all()->where('head','=',$request->id);

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Riepilogo Stato Lavori"]
        ];
        return view('/content/apps/workstatus/summary', [
            'breadcrumbs' => $breadcrumbs,
            'head'  => $head
        ]);

    }

    public function list_summary(Request $request){
        if(empty($request->id))
            abort('404');

        $return = DB::table('work_course_summaries')->select('*')
            ->where('head','=',$request->id)
            ->get();


        return json_encode($return);

    }

    public function setting_costs(Request $request){
        $pageConfigs = ['pageHeader' => false];
        $generic = Greneric::find(1);
        return view('/content/apps/workstatus/cost/import', ['pageConfigs' => $pageConfigs, 'generic' => $generic]);
    }

    public function setting(){

        Email::send();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkStatus  $workStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        if(empty($request->id))
            abort(404);


    }
}
