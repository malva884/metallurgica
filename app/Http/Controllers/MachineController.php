<?php

namespace App\Http\Controllers;

use App\Imports\MachinesImport;
use App\Models\MachineLaborCosts;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MachineController extends Controller
{
    public function import(Request $request)
    {
        //MachineLaborCosts::truncate();
        MachineLaborCosts::where('date_import','=',date('Y-m-d'))->delete();
        Excel::import(new MachinesImport(), $request->file('machine')->store('temp'));
        \Session::put('success', __('locale.Import Machine'));
        return back();
    }
}
