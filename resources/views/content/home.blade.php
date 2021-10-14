@extends('layouts/contentLayoutMaster')

@section('title', 'Home')

@section('content')
    <section id="dashboard-ecommerce">
        <div class="row match-height">
            <!-- Greetings Card starts -->
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
                            <h1 class="mb-1 text-white">Ciao {{Auth::user()->firstname.' '.Auth::user()->lastname}},</h1>
                            <p class="card-text m-auto w-75">
                                Benvenuti sul portale di Metallurgica Bresciana.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Statistics Card -->
            <div class="col-xl-6 col-md-6 col-12">
                @if(Auth::user()->hasAnyPermission('workflow_approval'))
                <div class="card card-statistics">
                    <div class="card-header">
                        <h4 class="card-title">Documenti Da Firmare</h4>
                    </div>
                    <div class="card-body statistics-body">
                        <div class="row">
                            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-primary me-2">
                                        <div class="avatar-content">
                                            <i data-feather="mail" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{$commessa}}</h4>
                                        <a href="{{route('workflow.index')}}"><p class="card-text font-small-3 mb-0">Commesse</p></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 col-12">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-info me-2">
                                        <div class="avatar-content">
                                            <i data-feather="mail" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{$confermeOrdeine}}</h4>
                                        <a href="{{route('workflow.index')}}"><p class="card-text font-small-3 mb-0">Conferma Ordine</p></a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!--/ Statistics Card -->
        </div>
    </section>







@endsection
