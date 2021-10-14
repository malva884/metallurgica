<?php

namespace App\Http\Controllers;

use App\Models\Advisory;
use App\Models\AdvisoryHead;
use App\Models\AdvisorySummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdvisoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function list (Request $request){

        $return = DB::table('advisories')->select('*')
            ->where('head','=',$request->id)
            ->where('view','=',true)
            ->orderBy("order_row2", "asc")
            ->get();

        return json_encode($return);
    }

    public function summary(Request $request){

        if(empty($request->id))
            abort('404');

        $head = AdvisoryHead::all()->where('id','=',$request->id)->first();
        $summary = AdvisorySummary::all()->where('head','=',$request->id);

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Riepilogo Consultivo"]
        ];
        return view('/content/apps/workstatus/advisory/summary', [
            'breadcrumbs' => $breadcrumbs,
            'head'  => $head
        ]);

    }

    public function list_summary(Request $request){
        if(empty($request->id))
            abort('404');

        $return = DB::table('advisory_summaries')->select('*')
            ->where('head','=',$request->id)
            ->get();


        return json_encode($return);

    }

    public function finalWork(Request $request)
    {

        if (empty($request->id))
            abort('404');
        try {
            AdvisorySummary::where('head', '=', $request->id)->delete();
            Advisory::where('head', '=', $request->id)->update(['calculated' => 0]);
            $head = AdvisoryHead::find($request->id);

            $head->getBlockCalculation();

            $totals = AdvisorySummary::Select('*')
                ->where('head', 'like', $head->id)
                ->where('order', '=', $head->ol)
                ->first();

            $head->status = 2;
            $head->total_real = (float)$totals->total_real;
            Log::channel('stderr')->info('QUI NO');
            $head->total_theoretical = (float)$totals->total_theoretical;

            Log::channel('stderr')->info($head);
            $head->save();
        }
        catch (\Exception $e) {
            Log::channel('stderr')->info('@@@@');
            Log::channel('stderr')->info($e->getMessage());

        }

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advisory  $advisory
     * @return \Illuminate\Http\Response
     */
    public function show(Advisory $advisory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Advisory  $advisory
     * @return \Illuminate\Http\Response
     */
    public function edit(Advisory $advisory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Advisory  $advisory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Log::channel('stderr')->info($request->all());

        $return[] = 'ok';
        $workStatus = Advisory::find($request->get('id'));
        $column = $request->get('column');
        $workStatus->$column = $request->get('value');

        $workStatus->save();

        return json_encode($return);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advisory  $advisory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advisory $advisory)
    {
        //
    }
}
