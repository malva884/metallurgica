@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Client New'))

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
                        <h4 class="card-title">{{__('locale.Client New')}}</h4>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" action="{{route('client.store')}}" method="POST"
                              enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <x-ImputForm
                                            title="company"
                                            class="form-control"
                                            value=""
                                            type="text"
                                            :required="true"
                                    />
                                    <x-ImputForm
                                            title="of"
                                            class="form-control"
                                            value=""
                                            type="text"
                                    />
                                    <x-ImputForm
                                            title="sign"
                                            class="form-control"
                                            value=""
                                            type="text"
                                    />
                                    <x-ImputForm
                                            title="reference_person"
                                            class="form-control"
                                            value=""
                                            type="text"
                                    />
                                    <x-SelectForm
                                            title="region"
                                            class="form-group"
                                            :values="$regioni"
                                            defoult=""
                                            :required="true"
                                            type=""
                                    />
                                    <x-SelectForm
                                            title="province"
                                            class="form-group"
                                            :values="$provincie"
                                            defoult=""
                                            :required="true"

                                    />
                                    <x-ImputForm
                                            title="city"
                                            class="form-control"
                                            value=""
                                            type="text"
                                            :required="true"
                                    />
                                    <x-ImputForm
                                            title="address"
                                            class="form-control"
                                            value=""
                                            type="text"
                                            :required="true"
                                    />
                                    <x-ImputForm
                                            title="post_code"
                                            class="form-control"
                                            value=""
                                            type="text"
                                            :required="true"
                                    />
                                    <x-ImputForm
                                            title="phone"
                                            class="form-control"
                                            value=""
                                            type="number"
                                            :required="true"
                                    />
                                </div>
                                <div class="col-md-6 col-12">
                                    <x-ImputForm
                                            title="mobile"
                                            class="form-control"
                                            value=""
                                            type="number"
                                    />
                                    <x-ImputForm
                                            title="fax"
                                            class="form-control"
                                            value=""
                                            type="number"
                                    />
                                    <x-ImputForm
                                            title="email"
                                            class="form-control"
                                            value=""
                                            type="email"
                                            :required="true"
                                    />
                                    <x-ImputForm
                                            title="tax_code"
                                            class="form-control"
                                            value=""
                                            type="number"
                                            :required="true"
                                    />
                                    <x-ImputForm
                                            title="fiscal_code"
                                            class="form-control"
                                            value=""
                                            type="text"
                                            :required="true"
                                    />
                                    <x-ImputForm
                                            title="weekly_closing"
                                            class="form-control"
                                            value=""
                                            type="text"
                                    />
                                    <x-SelectForm
                                            title="status"
                                            class="form-group"
                                            values=""
                                            defoult=""
                                            :required="true"
                                            type="status"
                                    />
                                    <x-CheckBoxForm
                                            title="riba"
                                            class=""
                                            value=""
                                            label="riba"
                                            default=""
                                    />
                                    <x-ImputForm
                                            title="sdi"
                                            class="form-control"
                                            value=""
                                            type="text"
                                            :required="true"
                                    />
                                    <x-ImputForm
                                            title="discount"
                                            class="form-control"
                                            value=""
                                            type="text"
                                    />
                                    <x-ImputForm
                                            title="note"
                                            class="form-control"
                                            value=""
                                            type="text"
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
    <script>
        (() = > {

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation');

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach((form) = > {
            form.addEventListener('submit', (event) = > {
                if(
        !form.checkValidity()
        )
        {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
        },
        false
        )
        ;
        })
        ;
        })
        ();
    </script>
@endsection
