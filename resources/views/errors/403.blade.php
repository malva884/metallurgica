@php
    $configData = Helper::applClasses();
@endphp
@extends('layouts/fullLayoutMaster')

@section('title', 'Not Authorized')

@section('page-style')
    <link rel="stylesheet" href="{{asset(mix('css/base/pages/page-misc.css'))}}">
@endsection

@section('content')
    <!-- Not authorized-->
    <div class="misc-wrapper">
        <a class="brand-logo" href="javascript:void(0);">
            <img src="{{asset('images/logo/logo1.ico')}}" height="28">
            <h2 class="brand-text text-primary ml-1">Metallutgica Bresciana</h2>
        </a>
        <div class="misc-inner p-2 p-sm-3">
            <div class="w-100 text-center">
                <h2 class="mb-1">Non sei autorizzato! 🔐</h2>
                <p class="mb-2">Contattare l'amministatore se il problema persiste.</p>
                <a class="btn btn-primary mb-1 btn-sm-block" href="{{url('/')}}">Torna Alla Dashboard.</a>
                @if($configData['theme'] === 'dark')
                    <img class="img-fluid" src="{{asset('images/pages/not-authorized-dark.svg')}}" alt="Not authorized page" />
                @else
                    <img class="img-fluid" src="{{asset('images/pages/not-authorized.svg')}}" alt="Not authorized page" />
                @endif
            </div>
        </div>
    </div>
    <!-- / Not authorized-->
    </section>
    <!-- maintenance end -->
@endsection
