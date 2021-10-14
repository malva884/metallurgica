@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Product manager'))

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
    @if(session()->get('message'))
        <x-alert
                :message="session()->get('message')"
        />
    @endif
    <!-- users edit start -->
    <section class="app-user-edit">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a
                                class="nav-link d-flex align-items-center active"
                                id="account-tab"
                                data-toggle="tab"
                                href="#account"
                                aria-controls="account"
                                role="tab"
                                aria-selected="true"
                        >
                            <i data-feather="info"></i><span
                                    class="d-none d-sm-block">{{__('locale.Info product')}}</span>
                        </a>
                    </li>

                </ul>
                <div class="tab-content">
                    <!-- Account Tab starts -->
                    <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                        <!-- users edit account form start -->
                        <form class="needs-validation" action="{{route('product.store',['id'=>request()->id])}}"
                              method="POST" novalidate>
                        @csrf
                        <!-- users edit media object start -->

                            <!-- users edit media object ends -->
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">{{__('locale.Info product')}}</h4>
                                        </div>
                                        <div class="card-body">
                                                <form class="form form-horizontal" action="{{route('product.store')}}" method="POST"
                                                      enctype="multipart/form-data" >
                                                <div class="row">
                                                    <div class="col-12">
                                                        <x-imput_horizontal
                                                                title="name"
                                                                class="form-control"
                                                                value=""
                                                                type="text"
                                                                :required="true"
                                                        />
                                                    </div>
                                                    <div class="col-12">
                                                        <x-texarea_horizontal
                                                                title="description"
                                                                class="form-control"
                                                                value=""
                                                                type="text"
                                                                :required="true"
                                                        />
                                                    </div>
                                                    <div class="col-12">
                                                        <x-imput_horizontal
                                                                title="price"
                                                                class="form-control"
                                                                value=""
                                                                type="number"
                                                                :required="true"
                                                        />
                                                    </div>
                                                    <div class="col-12">
                                                        <x-imput_horizontal
                                                                title="price_sale"
                                                                class="form-control"
                                                                value=""
                                                                type="number"
                                                                :required="false"
                                                        />
                                                    </div>
                                                    <div class="col-12">
                                                        <x-SelectHorizontalForm
                                                                title="linee"
                                                                class="form-group"
                                                                :values="$linee"
                                                                defoult=""
                                                                :required="true"
                                                                :type="Null"
                                                        />
                                                    </div>
                                                    <div class="col-12">
                                                        <x-SelectHorizontalForm
                                                                title="category"
                                                                class="form-group"
                                                                :values="[]"
                                                                defoult=""
                                                                :required="false"
                                                                :type="Null"
                                                        />

                                                    </div>
                                                    <div class="col-12">
                                                        <x-SelectHorizontalForm
                                                                title="collection"
                                                                class="form-group"
                                                                :values="[]"
                                                                defoult=""
                                                                :required="false"
                                                                :type="Null"
                                                        />

                                                    </div>
                                                    <div class="col-12">
                                                        <x-imput_horizontal
                                                                title="shelf"
                                                                class="form-control"
                                                                value=""
                                                                type="text"
                                                                :required="false"
                                                        />
                                                    </div>
                                                    <div class="col-12">
                                                        <x-imput_horizontal
                                                                title="stock_qty"
                                                                class="form-control"
                                                                value=""
                                                                type="number"
                                                                :required="false"
                                                        />
                                                    </div>
                                                    <div class="col-12">
                                                        <x-SelectHorizontalForm
                                                                title="exclude_stat"
                                                                class="form-group"
                                                                values=""
                                                                defoult=""
                                                                :required="true"
                                                                type="Yes_no"
                                                        />
                                                    </div>
                                                    <div class="col-12">
                                                        <x-SelectHorizontalForm
                                                                title="status"
                                                                class="form-group"
                                                                values=""
                                                                defoult=""
                                                                :required="true"
                                                                type="status"
                                                        />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                    <button type="submit"
                                            class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">{{__('locale.Save Changes')}}</button>
                                    <button type="reset"
                                            class="btn btn-outline-secondary">{{__('locale.Reset')}}</button>
                                </div>
                            </div>
                        </form>
                        <!-- users edit account form ends -->
                    </div>
                    <!-- Account Tab ends -->

                    <!-- Information Tab starts -->
                    <div class="tab-pane" id="information" aria-labelledby="information-tab" role="tabpanel">
                        <!-- users edit Info form start -->
                        <form class="" action="{{route('product-image-store',['id'=>request()->id])}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-3 col-6 mb-1">
                                    <div class="input-group">
                                        <input
                                                type="file"
                                                class="form-control"
                                                placeholder="Image"
                                                aria-describedby="button-addon2"
                                                name="image"
                                        />
                                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                            <button type="submit"
                                                    class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">{{__('locale.Load')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- users edit Info form ends -->
                    </div>
                    <!-- Information Tab ends -->
                </div>
            </div>
        </div>
    </section>
    <!-- users edit ends -->
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/app-user-edit.js')) }}"></script>
    <script>
        (() => {

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation');

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        $(document).ready(function(){

            $("#linee").change(function(){
                var linea = $(this).val();

                $.ajax({
                    url: '{{ route('collection.listView') }}',
                    type: 'get',
                    data: {linea:linea},
                    dataType: 'json',
                    success:function(response){
                        var len = response.length;

                        $("#collection").empty();
                        $("#collection").append("<option value=''>-- Seleziona Collezione-- </option>");
                        for( var i = 0; i<len; i++){
                            var id = response[i]['id'];
                            var name = response[i]['name'];

                            $("#collection").append("<option value='"+id+"'>"+name+"</option>");

                        }
                    }
                });

                $.ajax({
                    url: '{{ route('category.listView') }}',
                    type: 'get',
                    data: {linea:linea},
                    dataType: 'json',
                    success:function(response){
                        var len = response.length;

                        $("#category").empty();
                        $("#category").append("<option value=''>-- Seleziona Catagoria-- </option>");
                        for( var i = 0; i<len; i++){
                            var id = response[i]['id'];
                            var name = response[i]['name'];

                            $("#category").append("<option value='"+id+"'>"+name+"</option>");

                        }
                    }
                });
            });

        });

    </script>
@endsection
