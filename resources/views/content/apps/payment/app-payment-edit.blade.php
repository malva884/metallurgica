
@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Manage payment'))


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
  <!-- Validation -->
  <section class="bs-validation">
    <div class="row">
      <!-- Bootstrap Validation -->
      <div class="col-md-6 col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">{{($payment->id ? __('locale.Edit payment'):__('locale.New payment'))}}</h4>
          </div>
          <div class="card-body">
            <form class="needs-validation" action="{{route('payment-save',['id'=>request()->id])}}" method="POST" enctype="multipart/form-data" novalidate>
              @csrf
              <x-ImputForm
                      title="name"
                      class="form-control"
                      :value="$payment->name"
                      type="text"
                      :required="true"
              />
              <x-ImputForm
                      title="position"
                      class="form-control"
                      :value="$payment->position"
                      type="number"
                      :required="false"
              />
              <x-CheckBoxForm
                      title="riba"
                      class=""
                      value=""
                      label="riba"
                      :default="$payment->riba"
              />

              <x-ImputForm
                      title="first_payment"
                      class="form-control"
                      :value="$payment->first_payment"
                      type="number"
                      :required="false"
              />
              <x-ImputForm
                      title="num_payments"
                      class="form-control"
                      :value="$payment->num_payments"
                      type="number"
                      :required="false"
              />
              <x-ImputForm
                      title="periodicity"
                      class="form-control"
                      :value="$payment->periodicity"
                      type="number"
                      :required="false"
              />
              <x-Impot_icon_form
                      title="bank_expense"
                      class="form-control"
                      :value="$payment->bank_expense"
                      icon="€"
                      type="price"
                      :required="false"
              />
              <x-Impot_icon_form
                      title="cash_discount"
                      class="form-control"
                      :value="$payment->cash_discount"
                      icon="%"
                      type="number"
                      :required="false"
              />

              <x-CheckBoxForm
                      title="end_month"
                      class=""
                      value=""
                      label="end_month"
                      :default="$payment->end_month"
              />
              <x-SelectForm
                      title="status"
                      class="form-group"
                      values=""
                      :defoult="$payment->status"
                      :required="true"
                      type="status"
              />
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
