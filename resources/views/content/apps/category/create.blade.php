@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Category New'))

@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection

@section('content')
    @if (count($errors) > 0)
        <x-alert
                :message="$errors->all()" color="danger"
        />
    @endif
    <!-- Validation -->
    <section class="bs-validation">
        <div class="row">
            <!-- Bootstrap Validation -->
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('locale.Category New')}}</h4>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" action="{{route('category.store')}}" method="POST"
                              enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <x-ImputForm
                                            title="category"
                                            class="form-control"
                                            value=""
                                            type="text"
                                            :required="true"
                                    />
                                    <x-SelectForm
                                            title="disabled"
                                            class="form-group"
                                            :values="[0=>'Attivo',1=>'Disattivo']"
                                            defoult=""
                                            :required="true"
                                    />

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">{{__('locale.Save')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Bootstrap Validation -->

        </div>
    </section>
    <!-- /Validation -->
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->

@endsection
