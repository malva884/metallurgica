<?php

namespace App\Http\Controllers;

use App\Models\Greneric;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GenericController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function manpower(Request $request)
    {
       $generic = Greneric::find(1);

        $input = $request->all();
       if(!$generic->count())
           $generic = new Greneric;


        $generic->manpower = $input['manpower'];
        $generic->save();
        \Session::put('success', __('locale.Update Manpower'));
        return back();
    }

    public function notAuthorized(){
        $pageConfigs = ['blankPage' => true];

        return view('/content/app/generic/not-authorized', ['pageConfigs' => $pageConfigs]);
    }
}
