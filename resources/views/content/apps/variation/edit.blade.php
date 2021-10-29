@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Variation Edit'))

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
                        <h4 class="card-title">{{__('locale.Variation Edit')}}</h4>
                    </div>
                    <form class="needs-validation" action="{{route('variation.update',['id'=>$variation->id])}}" method="POST"
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
                                                value="{{$variation->ol}}"
                                                readonly="readonly"
                                        />
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country-floating">Percorso File</label>
                                        <input
                                                type="text"f
                                                id="file"
                                                class="form-control"
                                                name="file"
                                                placeholder="Percorso File"
                                                value="{{$file->path_drive}}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country-floating">Percorso Cartella</label>
                                        <input
                                                type="text"
                                                id="folder"
                                                class="form-control"
                                                name="folder"
                                                placeholder="Percorso Cartella"
                                                value="{{$file->path_folder_drive}}"
                                        />
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
        var commessa =  $('#commessa').val();
        const rbs = document.querySelectorAll('input[name="type"]');
        let selectedValue;
        for (const rb of rbs) {
            if (rb.checked) {
                selectedValue = rb.value;
                break;
            }
        }

        if(commessa && selectedValue){
            $.ajax({
                url: '{{ route('workflow.check') }}',
                type: "get",
                data:{
                    'commessa':  commessa,
                    'type':  selectedValue,
                },
                success:function(data){
                    if(data == true){
                        Swal.fire({
                            title: 'Attenzione!',
                            text: 'La commessa " '+commessa+' " è già presente nel sistema!',
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
