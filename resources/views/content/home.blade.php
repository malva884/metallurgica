@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboards')

@section('content')
    <section id="statistics-card">
        <!-- Miscellaneous Charts -->
        <div class="row match-height">
            <!-- Bar Chart -Orders -->
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="card card-congratulations">
                    <div class="card-body text-center">
                        <img
                                src="{{asset('images/elements/decore-left.png')}}"
                                class="congratulations-img-left"
                                alt="card-img-left"
                        />
                        <img
                                src="{{asset('images/elements/decore-right.png')}}"
                                class="congratulations-img-right"
                                alt="card-img-right"
                        />
                        <div class="avatar avatar-xl bg-primary shadow">
                            <div class="avatar-content">
                                <i data-feather="award" class="font-large-1"></i>
                            </div>
                        </div>
                        <div class="text-center">
                            <h1 class="mb-1 text-white">Ciao {{Auth::user()->firstname.' '.Auth::user()->lastname}}
                                ,</h1>
                            <p class="card-text m-auto w-75">
                                Benvenuti sul portale di Metallurgica Bresciana.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Bar Chart -->
        </div>
        <!--/ Miscellaneous Charts -->

        <!-- Stats Vertical Card -->
        <div class="row" >
            @if(Auth::user()->hasAnyPermission(['workflow_approval','variation_approval']) && ($commessa || $confermeOrdeine || $revisioni || $variationApproval) )
                <div class="col-xl-6 col-md-12 col-12">
                    @include('content.apps._partials.dashboard-approval',['commessa'=>$commessa,'confermeOrdeine'=>$confermeOrdeine,'revisioni'=>$revisioni,'variationApproval'=>$variationApproval])
                </div>
            @endif
            @if(Auth::user()->hasAnyPermission(['workflow_create','variation_create']) && ($workflowProcessing || $workflowCompleted || $variation_processing || $variation_completed))
                    <div class="col-xl-4 col-md-12 col-12">
                    @include('content/apps/_partials/dashboard-create',['workflowProcessing'=> $workflowProcessing,'workflowCompleted'=>$workflowCompleted,'variationProcessing'=> $variation_processing,'variationCompleted'=>$variation_completed])
                </div>
            @endif
        </div>
        <!--/ Stats Vertical Card -->
    </section>
    <!--/ Statistics Card section-->
@endsection
