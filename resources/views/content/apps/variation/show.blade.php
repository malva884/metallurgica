@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Variation'))

@section('vendor-style')

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">

@endsection
@section('page-style')
    <link rel="stylesheet" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice.css')}}">
@endsection

@section('content')
    <section class="invoice-add-wrapper">
        <div class="row invoice-add">
            <!-- Invoice Add Left starts -->
            <div class="col-xl-10 col-md-8 col-12">

                <embed type="application/pdf"
                       src="{{$file}}"
                       width="100%" height="700" alt="pdf"
                       background-color="0xFF525659"
                       top-toolbar-height="56"
                       full-frame=""
                       internalinstanceid="21"
                       title="CHROME"
                >
            </div>
            <!-- Invoice Add Left ends -->

            <!-- Invoice Add Right starts -->
            <div class="col-lg-2 col-12 order-3 ">
                <div class="card">
                    <div class="card-body">

                        @if(!empty($log_create))
                            <a class="btn btn-outline-info btn-block mb-75"
                               href="{{url('variation/log',['id'=> $variation->id])}}">
                                Download Log
                            </a>
                        @elseif(!empty($start) || !empty($onlyView))
                            <h5 class="text-primary">Workflow avviato! </h5>
                            <h6 class="text-info">{{$variation->created_at}}</h6>
                        @elseif(!empty($approvato) && empty($log_create))
                            <h5 class="text-success">Data firma: </h5><h5 class="text-info">{{$dataFirma}}</h5>
                        @elseif(empty($approvato) && empty($onlyView) && !empty($myApproved))
                            <a class="btn btn-success btn-block mb-75"
                               href="javascript:void(0);"
                               id="confirm-text"
                               data-id="{{$variation->id}}"
                               data-value="{{$user->id}}"
                            >
                                Approva
                            </a>
                        @else
                            <h5 class="text-primary">Workflow avviato! </h5>
                            <h6 class="text-info">{{$variation->created_at}}</h6>
                        @endif
                        @if(auth()->user()->can('variation_create') || auth()->user()->hasanyrole('super-admin'))
                            <a href="{{route('variation.edit',['id'=>$variation->id])}}"
                               class="btn btn-primary btn-block mb-75">{{__('locale.Edit')}}</a>
                        @endif
                        @if((auth()->user()->can('variation_create') || auth()->user()->hasanyrole('super-admin')) && $variationFile->path_folder_drive)
                                <a target="_blank" href="https://drive.google.com/drive/folders/{{$variationFile->path_folder_drive}}"
                                   class="btn btn-outline-primary btn-block mb-75"><i data-feather='hard-drive'></i> <span>Cartella Google Drive</span></a>
                        @endif
                    </div>
                </div>
                <!-- users -->
                @if(!empty($users))
                    <div class="card">
                        <div class="card-body">
                            <h5>{{__('locale.Users')}}</h5>

                            @foreach($users as $user)
                                <div class="d-flex justify-content-start align-items-center mt-1">
                                    <div class="avatar mr-75">
                                        <img
                                                src="{{asset('/images/users/'.$user->image)}}"
                                                alt="avatar"
                                                height="40"
                                                width="40"
                                        />
                                    </div>
                                    <div class="profile-user-info">
                                        <h6 class="mb-0">{{$user->firstname.' '.$user->lastname}}</h6>

                                    </div>
                                    @if($user->aprovato)
                                        <button type="button" class="btn btn-success btn-sm btn-icon ml-auto">
                                            <i data-feather="check"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-sm btn-icon ml-auto">
                                            <i data-feather="minus"></i>
                                        </button>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                    </div>
            @endif
            <!--/ users -->
            </div>
            <!-- Invoice Add Right ends -->

        </div>
    </section>

    <!-- profile info section -->

@endsection

@section('vendor-script')
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
@endsection

@section('page-script')

    <script src="{{ asset('js/scripts/extensions/variations-sweet-alerts.js') }}"></script>
@endsection

