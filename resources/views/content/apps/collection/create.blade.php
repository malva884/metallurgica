@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Collection manager'))

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
                      class="d-none d-sm-block">{{__('locale.Info collection')}}</span>
            </a>
          </li>

        </ul>
        <div class="tab-content">
          <!-- Account Tab starts -->
          <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

            <!-- users edit account form start -->
            <form class="needs-validation" action="{{route('collection.store')}}"
                  method="POST" novalidate>
            @csrf
            <!-- users edit media object start -->

              <!-- users edit media object ends -->
              <div class="row">
                <div class="col-md-6 col-12">
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">{{__('locale.Info collection')}}</h4>
                    </div>
                    <div class="card-body">
                      <form class="form form-horizontal" action="{{route('collection.store')}}" method="POST"
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
                            <x-SelectHorizontalForm
                                    title="linea"
                                    class="form-group"
                                    :values="$linee"
                                    defoult=""
                                    :required="true"
                                    :type="Null"
                            />
                          </div><div class="col-12">
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


  </script>
@endsection
