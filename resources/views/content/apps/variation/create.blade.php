@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Variation New'))

@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/base/pages/page-blog.css') }}" />
    <link rel="stylesheet" href="{{asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
@endsection

@section('content')
    @if (count($errors) > 0)

        <x-alert
                :message="$errors->all()" color="danger"
        />
    @endif
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-9">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('locale.Variation New')}}</h4>
                    </div>
                    <form class="needs-validation" action="{{route('variation.store')}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country-floating">Ol</label>
                                        <input
                                                type="text"
                                                id="ol"
                                                class="form-control"
                                                name="ol"
                                                placeholder="Ol"
                                                required="true"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country-floating">File</label>
                                        <input type="file" class="form-control" id="file" name="file" accept="application/pdf" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary mr-1">Crea</button>
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-75 text-primary">{{__('locale.Users')}}</h4>
                        <ul class="p-0 mb-2">
                        @forelse($users as $user)
                                <li class="d-block">
                                    <span class="me-25">-</span>
                                    <span class="text-warning">{{$user->firstname.' '.$user->lastname}}</span>
                                </li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>

@endsection
@section('page-script')

    <!-- Page js files -->
<script>
    $('#commessa').change(function(){
        check();
    });
    $('#inlineRadio1').change(function(){
        check();
    });
    $('#inlineRadio2').change(function(){
        check();
    });
    $('#inlineRadio3').change(function(){
        check();
    });

    function check(){
        var ol =  $('#ol').val();
        if(commessa && selectedValue){
            $.ajax({
                url: '{{ route('variation.check') }}',
                type: "get",
                data:{
                    'ol':  ol,
                },
                success:function(data){
                    if(data == true){
                        Swal.fire({
                            title: 'Attenzione!',
                            text: "L'Ol " +commessa+ " è già presente nel sistema!",
                            icon: 'warning',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        $(':input[type="submit"]').prop('disabled', true);
                    }
                    else
                        $(':input[type="submit"]').prop('disabled', false);

                }
            });
        }
    }
</script>
@endsection
