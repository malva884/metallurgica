@extends('layouts/contentLayoutMaster')

@section('title', 'Knowledge Base')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-knowledge-base.css')) }}">
@endsection

@section('content')
  <!-- Knowledge base Jumbotron -->
  <section id="knowledge-base-search">
    <div class="row">
      <div class="col-12">
        <div
                class="card knowledge-base-bg text-center"
                style="background-image: url('{{asset('images/banner/banner.png')}}')"
        >
          <div class="card-body">
            <h2 class="text-primary">{{__('locale.Linee')}}</h2>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--/ Knowledge base Jumbotron -->

  <!-- Knowledge base -->
  <section id="knowledge-base-content">
    <div class="row kb-search-content-info match-height">
      <!-- sales card -->
      @foreach($linee as $linea)
      <div class="col-md-2 col-sm-4 col-12 kb-search-content">
        <div class="card">
          <a href="{{asset('product/linea/'.$linea->id)}}">
            <img
                    src="{{asset('images/linee/'.$linea->image)}}"
                    class="card-img-top"
                    alt="knowledge-base-image"
            />

            <div class="card-body text-center">
              <h4 style="color: {{$linea->color}}">{{$linea->name}}</h4>

            </div>
          </a>
        </div>
      </div>
    @endforeach



  </section>
  <!-- Knowledge base ends -->
@endsection

@section('page-script')

@endsection
