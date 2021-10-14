
@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Manage linee'))

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
  <!-- Validation -->
  <section class="bs-validation">
    <div class="row">
      <!-- Bootstrap Validation -->
      <div class="col-md-6 col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">{{($linee->id ? __('locale.Edit linee'):__('locale.New linee'))}}</h4>
          </div>
          <div class="card-body">
            <form class="needs-validation" action="{{route('linee-save',['id'=>request()->id])}}" method="POST" enctype="multipart/form-data" novalidate>
              @csrf
              <x-ImputForm
                      title="name"
                      class="form-control"
                      :value="$linee->name"
                      type="text"
                      :required="true"
              />
              <x-ImputForm
                      title="tag"
                      class="form-control"
                      :value="$linee->tag"
                      type="text"
                      :required="true"
              />
              <x-ImputForm
                      title="sale"
                      class="form-control"
                      :value="$linee->sale"
                      type="number"
                      :required="false"
                      length="4"
              />
              <x-SelectForm
                      title="stock"
                      class="form-group"
                      values=""
                      :defoult="$linee->stock"
                      :required="true"
                      type="stock"
              />

              <x-ImputForm
                      title="qty_min"
                      class="form-control"
                      :value="$linee->qty_min"
                      type="number"
                      :required="false"
              />
              <x-ImputForm
                      title="color"
                      class="form-control"
                      :value="$linee->color"
                      type="color"
                      :required="false"
              />
              <div class="media mb-2">
                <img
                        src="{{asset($linee->get_image())}}"
                        alt="image linee"
                        class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                        height="90"
                        width="90"
                />
                <div class="media-body mt-50">
                  <div class="col-12 d-flex mt-1 px-0">
                    <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                      <span class="d-none d-sm-block">{{__('locale.Change')}}</span>
                      <input
                              class="form-control"
                              type="file"
                              id="change-picture"
                              name="image"
                              hidden
                              accept="image/png, image/jpeg, image/jpg"
                      />
                      <span class="d-block d-sm-none">
                    <i class="mr-0" data-feather="edit"></i>
                  </span>
                    </label>
                  </div>
                </div>

              </div>
              <div class="row">
                <div class="col-12">
                  <button type="submit" class="btn btn-primary">{{__('locale.Save Changes')}}</button>
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
