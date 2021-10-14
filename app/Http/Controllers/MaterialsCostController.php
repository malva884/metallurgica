<?php

namespace App\Http\Controllers;

use App\Imports\MaterialsImport;
use App\Models\MaterialsCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class MaterialsCostController extends Controller
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

    public function import(Request $request)
    {
        //MaterialsCost::truncate();
        MaterialsCost::where('date_import','=',date('Y-m-d'))->delete();
        Excel::import(new MaterialsImport, $request->file('materials')->store('temp'));
        \Session::put('success', __('locale.Import Materials'));

        return back();
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
     * @param  \App\Models\MaterialsCost  $materialsCost
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialsCost $materialsCost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaterialsCost  $materialsCost
     * @return \Illuminate\Http\Response
     */
    public function edit(MaterialsCost $materialsCost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialsCost  $materialsCost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaterialsCost $materialsCost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialsCost  $materialsCost
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialsCost $materialsCost)
    {
        //
    }
}
