@php
  $configData = Helper::applClasses();
@endphp
@extends('layouts/fullLayoutMaster')

@section('title', 'Maintenance')

@section('page-style')
  <link rel="stylesheet" href="{{asset(mix('css/base/pages/page-misc.css'))}}">
@endsection

@section('content')
  <!-- Under maintenance-->
  <div class="misc-wrapper">
    <a class="brand-logo" href="#">
      <img src="{{asset('images/logo/logo1.ico')}}" height="28">
      <h2 class="brand-text text-primary ms-1">Metallurgica Bresciana</h2>
    </a>
    <div class="misc-inner p-2 p-sm-3">
      <div class="w-100 text-center">
        <h2 class="mb-1">In manutenzione 🛠</h2>
        <p class="mb-3">Ci scusiamo per l'inconveniente ma al momento stiamo eseguendo un po' di manutenzione</p>
        @if($configData['theme'] === 'dark')
          <img class="img-fluid" src="{{asset('images/pages/under-maintenance-dark.svg')}}" alt="Under maintenance page" />
        @else
          <img class="img-fluid" src="{{asset('images/pages/under-maintenance.svg')}}" alt="Under maintenance page" />
        @endif
      </div>
    </div>
@endsection
