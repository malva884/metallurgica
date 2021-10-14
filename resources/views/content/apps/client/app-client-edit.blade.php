@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Clinet manager'))

@section('vendor-style')
  {{-- Vendor Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection

@section('content')
<!-- users edit start -->
@if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
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
            <i data-feather="user"></i><span class="d-none d-sm-block">{{__('locale.Information client')}}</span>
          </a>
        </li>
        <li class="nav-item">
          <a
            class="nav-link d-flex align-items-center"
            id="information-tab"
            data-toggle="tab"
            href="#information"
            aria-controls="information"
            role="tab"
            aria-selected="false"
          >
            <i data-feather="info"></i><span class="d-none d-sm-block">{{__('locale.Address manager')}}</span>
          </a>
        </li>
        <li class="nav-item">
          <a
            class="nav-link d-flex align-items-center"
            id="social-tab"
            data-toggle="tab"
            href="#social"
            aria-controls="social"
            role="tab"
            aria-selected="false"
          >
            <i data-feather="share-2"></i><span class="d-none d-sm-block">{{__('locale.Bank manager')}}</span>
          </a>
        </li>
        <li class="nav-item">
          <a
                  class="nav-link d-flex align-items-center"
                  id="notify-tab"
                  data-toggle="tab"
                  href="#notify"
                  aria-controls="notify"
                  role="tab"
                  aria-selected="false"
          >
            <i data-feather="share-2"></i><span class="d-none d-sm-block">{{__('locale.Notify manager')}}</span>
          </a>
        </li>
      </ul>
      <div class="tab-content">
        <!-- Account Tab starts -->
        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

          <!-- users edit account form start -->
          <form class="needs-validation" action="{{route('client-save',['id'=>request()->id])}}"  method="POST" enctype="multipart/form-data"  novalidate>
            @csrf
            <!-- users edit media object start -->

              <div class="media mb-2">

                <img
                        @if($client->id)
                          src="{{asset('/images/client/'.$client->id.'/med_'.$client->logo)}}"
                        @else
                          src="{{asset('/images/default/200x200.svg')}}"
                        @endif
                        alt="client avatar"
                        class="client-avatar client-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                        height="90"
                        width="200"
                        name="image"
                        accept="image/png, image/jpeg, image/jpg"
                />

                <div class="media-body mt-50">
                  @if($client->id)
                    <h4>{{$client->company.' '.__('locale.Of').' '.$client->of}}
                      @if($client->_deleted)
                        <span class="text-danger">({{__('locale.Client deleted')}})</span>
                      @endif
                    </h4>

                  @endif
                  <div class="col-12 d-flex mt-1 px-0">
                    <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                      <span class="d-none d-sm-block">{{__('locale.Change')}}</span>
                      <input
                              class="form-control"
                              type="file"
                              id="change-picture"
                              hidden
                              name="image"
                              accept="image/png, image/jpeg, image/jpg"
                      />
                      <span class="d-block d-sm-none">
                    <i class="mr-0" data-feather="edit"></i>
                  </span>
                    </label>
                    <button class="btn btn-outline-secondary d-none d-sm-block">{{__('locale.Remove')}}</button>
                    <button class="btn btn-outline-secondary d-block d-sm-none">
                      <i class="mr-0" data-feather="trash-2"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- users edit media object ends -->
            <div class="row">
              <div class="col-md-4">
                <x-ImputForm
                        title="company"
                        class="form-control"
                        :value="$client->company"
                        type="text"
                        :required="true"
                />
              </div>
              <div class="col-md-2">
                <x-ImputForm
                        title="of"
                        class="form-control"
                        :value="$client->of"
                        type="text"
                />
              </div>
              <div class="col-md-2">
                <x-ImputForm
                        title="sign"
                        class="form-control"
                        :value="$client->sign"
                        type="text"
                />
              </div>
              <div class="col-md-4">
                <x-ImputForm
                        title="reference_person"
                        class="form-control"
                        :value="$client->reference_person"
                        type="text"
                />
              </div>
              <div class="col-md-2">
                <x-SelectForm
                        title="region"
                        class="form-group"
                        :values="$regioni"
                        :defoult="$client->region"
                        :required="true"
                        type=""
                />
              </div>
              <div class="col-md-2">
                <x-SelectForm
                        title="province"
                        class="form-group"
                        :values="$provincie"
                        :defoult="$client->province"
                        :required="true"

                />
              </div>
              <div class="col-md-2">
                <x-ImputForm
                        title="city"
                        class="form-control"
                        :value="$client->city"
                        type="text"
                        :required="true"
                />
              </div>
              <div class="col-md-2">
                <x-ImputForm
                        title="address"
                        class="form-control"
                        :value="$client->address"
                        type="text"
                        :required="true"
                />
              </div>
              <div class="col-md-4">
                <x-ImputForm
                        title="post_code"
                        class="form-control"
                        :value="$client->post_code"
                        type="text"
                        :required="true"
                />
              </div>
              <div class="col-md-2">
                <x-ImputForm
                        title="phone"
                        class="form-control"
                        :value="$client->phone"
                        type="number"
                        :required="true"
                />
              </div>
              <div class="col-md-1">
                <x-ImputForm
                        title="mobile"
                        class="form-control"
                        :value="$client->mobile"
                        type="number"
                />
              </div>
              <div class="col-md-1">
                <x-ImputForm
                        title="fax"
                        class="form-control"
                        :value="$client->fax"
                        type="number"
                />
              </div>
              <div class="col-md-2">
                <x-ImputForm
                        title="email"
                        class="form-control"
                        :value="$client->email"
                        type="email"
                        :required="true"
                />
              </div>
              <div class="col-md-2">
                <x-ImputForm
                        title="www"
                        class="form-control"
                        :value="$client->www"
                        type="text"
                />
              </div>
              <div class="col-md-2">
                <x-ImputForm
                        title="tax_code"
                        class="form-control"
                        :value="$client->tax_code"
                        type="number"
                        :required="true"
                />
              </div>
              <div class="col-md-2">
                <x-ImputForm
                        title="fiscal_code"
                        class="form-control"
                        :value="$client->fiscal_code"
                        type="text"
                        :required="true"
                />
              </div>
              <div class="col-md-4">
                <x-ImputForm
                        title="weekly_closing"
                        class="form-control"
                        :value="$client->weekly_closing"
                        type="text"
                />
              </div>
              <div class="col-md-4">
                <x-SelectForm
                        title="status"
                        class="form-group"
                        values=""
                        :defoult="$client->status"
                        :required="true"
                        type="status"
                />
              </div>
              <div class="col-md-4">
                <x-CheckBoxForm
                        title="riba"
                        class=""
                        value=""
                        label="riba"
                        :default="$client->riba"
                />
              </div>
              <div class="col-md-4">
                <x-ImputForm
                        title="sdi"
                        class="form-control"
                        :value="$client->sdi"
                        type="text"
                        :required="true"
                />
              </div>
              <div class="col-md-4">
                <x-ImputForm
                        title="discount"
                        class="form-control"
                        :value="$client->discount"
                        type="text"
                />
              </div>
              <div class="col-md-4">
                <x-ImputForm
                        title="note"
                        class="form-control"
                        :value="$client->note"
                        type="text"
                />
              </div>
              <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">{{__('locale.Save Changes')}}</button>
                <button type="reset" class="btn btn-outline-secondary">{{__('locale.Reset')}}</button>
              </div>
            </div>
          </form>
          <!-- users edit account form ends -->
        </div>
        <!-- Account Tab ends -->

        <!-- Information Tab starts -->
        <div class="tab-pane" id="information" aria-labelledby="information-tab" role="tabpanel">
          <!-- users edit Info form start -->
          <form class="form-validate" method="get">
            @csrf
            <div class="row mt-1">
              <div class="col-12">
                <h4 class="mb-1">
                  <i data-feather="user" class="font-medium-4 mr-25"></i>
                  <span class="align-middle">{{__('locale.Linee manager')}}</span>
                </h4>
              </div>              <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Save Changes</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
              </div>
            </div>
          </form>
          <!-- users edit Info form ends -->

        </div>
        <!-- Information Tab ends -->

        <!-- Social Tab starts -->
        <div class="tab-pane" id="social" aria-labelledby="social-tab" role="tabpanel">
          <!-- users edit social form start -->
          <form class="form-validate">
            <div class="row">
              <div class="col-lg-4 col-md-6 form-group">
                <label for="twitter-input">Twitter</label>
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon3">
                      <i data-feather="twitter" class="font-medium-2"></i>
                    </span>
                  </div>
                  <input
                    id="twitter-input"
                    type="text"
                    class="form-control"
                    value="https://www.twitter.com/adoptionism744"
                    placeholder="https://www.twitter.com/"
                    aria-describedby="basic-addon3"
                  />
                </div>
              </div>
              <div class="col-lg-4 col-md-6 form-group">
                <label for="facebook-input">Facebook</label>
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon4">
                      <i data-feather="facebook" class="font-medium-2"></i>
                    </span>
                  </div>
                  <input
                    id="facebook-input"
                    type="text"
                    class="form-control"
                    value="https://www.facebook.com/adoptionism664"
                    placeholder="https://www.facebook.com/"
                    aria-describedby="basic-addon4"
                  />
                </div>
              </div>
              <div class="col-lg-4 col-md-6 form-group">
                <label for="instagram-input">Instagram</label>
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon5">
                      <i data-feather="instagram" class="font-medium-2"></i>
                    </span>
                  </div>
                  <input
                    id="instagram-input"
                    type="text"
                    class="form-control"
                    value="https://www.instagram.com/adopt-ionism744"
                    placeholder="https://www.instagram.com/"
                    aria-describedby="basic-addon5"
                  />
                </div>
              </div>
              <div class="col-lg-4 col-md-6 form-group">
                <label for="github-input">Github</label>
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon9">
                      <i data-feather="github" class="font-medium-2"></i>
                    </span>
                  </div>
                  <input
                    id="github-input"
                    type="text"
                    class="form-control"
                    value="https://www.github.com/madop818"
                    placeholder="https://www.github.com/"
                    aria-describedby="basic-addon9"
                  />
                </div>
              </div>
              <div class="col-lg-4 col-md-6 form-group">
                <label for="codepen-input">Codepen</label>
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon12">
                      <i data-feather="codepen" class="font-medium-2"></i>
                    </span>
                  </div>
                  <input
                    id="codepen-input"
                    type="text"
                    class="form-control"
                    value="https://www.codepen.com/adoptism243"
                    placeholder="https://www.codepen.com/"
                    aria-describedby="basic-addon12"
                  />
                </div>
              </div>
              <div class="col-lg-4 col-md-6 form-group">
                <label for="slack-input">Slack</label>
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon11">
                      <i data-feather="slack" class="font-medium-2"></i>
                    </span>
                  </div>
                  <input
                    id="slack-input"
                    type="text"
                    class="form-control"
                    value="@adoptionism744"
                    placeholder="https://www.slack.com/"
                    aria-describedby="basic-addon11"
                  />
                </div>
              </div>

              <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Save Changes</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
              </div>
            </div>
          </form>
          <!-- users edit social form ends -->
        </div>
        <!-- Social Tab ends -->
        <!-- Notify Tab starts -->
        <div class="tab-pane" id="notify" aria-labelledby="notify-tab" role="tabpanel">
          <!-- users edit notify form start -->
          <form class="needs-validation" method="GET" action="/app/user/edit/notify/{{$client->id}}"  novalidate>
            @csrf
            <div class="row">
              <div class="col-lg-4 col-md-6">
                <!--x-CheckBoxForm title="Notify manager" class="" :values="$notifyes" /-->
              </div>
              <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">{{__('locale.Save Changes')}}</button>
                <button type="reset" class="btn btn-outline-secondary">{{__('locale.Reset')}}</button>
              </div>
            </div>
          </form>
          <!-- users edit social form ends -->
        </div>
        <!-- Social Tab ends -->
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
