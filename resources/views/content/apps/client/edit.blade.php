@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Clinet manager'))

@section('vendor-style')
  {{-- Vendor Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">

  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">

@endsection

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
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
            <i data-feather="user"></i><span class="d-none d-sm-block" >{{__('locale.Information client')}}</span>
          </a>
        </li>
        <li class="nav-item" >
          <a
            class="nav-link d-flex align-items-center"
            id="information-tab"
            data-toggle="tab"
            href="#information"
            aria-controls="information"
            role="tab"
            aria-selected="false"
          >
            <i data-feather="info"></i><span class="d-none d-sm-block" >{{__('locale.Address manager')}}</span>
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
          <form class="needs-validation" action="{{route('client.update',['id'=>$client->id])}}"  method="POST" enctype="multipart/form-data"  novalidate>
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
                        :default="(boolean)$client->riba"
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
          <!-- list section start -->
          <div class="card">
            <div class="card-datatable table-responsive pt-0">
              <table class="address-list-table table">
                <thead class="thead-light">
                <tr>
                  <th></th>
                  <th>Destination</th>
                  <th>Address</th>
                  <th>Zip</th>
                  <th>City</th>
                  <th>Region</th>
                  <th>Province</th>
                  <th>Actions</th>
                </tr>
                </thead>
              </table>
            </div>
            <!-- Modal to add new user starts-->
            <div class="modal modal-slide-in new-address-modal fade" id="modals-slide-in">
              <div class="modal-dialog">
                <form class="new-address modal-content pt-0" action="{{route('address.store',['id'=>$client->id])}}"  method="post" novalidate>
                 @csrf
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                  <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                  </div>
                  <div class="modal-body flex-grow-1">
                    <div class="form-group">
                      <label class="form-label" for="basic-icon-default-fullname">{{__('locale.Destination')}}</label>
                      <input
                              type="text"
                              class="form-control dt-destination"
                              id="basic-icon-default-destination"
                              placeholder="{{__('locale.Destination')}}"
                              name="destination"
                              aria-label="{{__('locale.Destination')}}"
                              aria-describedby="basic-icon-default-destination"
                              required
                      />
                    </div>
                    <div class="form-group">
                      <label class="form-label" for="basic-icon-default-uname">{{__('locale.Address')}}</label>
                      <input
                              type="text"
                              id="basic-icon-default-address"
                              class="form-control dt-address"
                              placeholder="{{__('locale.Address')}}"
                              aria-label="{{__('locale.Address')}}"
                              aria-describedby="basic-icon-default-address"
                              name="address"
                      />
                    </div>
                    <div class="form-group">
                      <label class="form-label" for="basic-icon-default-email">{{__('locale.Zip_code')}}</label>
                      <input
                              type="text"
                              id="basic-icon-default-zip_code"
                              class="form-control dt-zip_code"
                              placeholder="{{__('locale.Zip_codeity')}}"
                              aria-label="{{__('locale.Zip_code')}}"
                              aria-describedby="basic-icon-default-zip_code"
                              name="zip_code"
                      />
                    </div>
                    <div class="form-group">
                      <label class="form-label" for="basic-icon-default-email">{{__('locale.City')}}</label>
                      <input
                              type="text"
                              id="basic-icon-default-city"
                              class="form-control dt-city"
                              placeholder="{{__('locale.City')}}"
                              aria-label="{{__('locale.City')}}"
                              aria-describedby="basic-icon-default-city"
                              name="city"
                      />
                    </div>
                    <div class="form-group">
                      <label class="form-label" for="region">{{__('locale.Region')}}</label>
                      <select id="region" name="region" class="form-control">
                        <option value="basic">--Seleziona Regione--</option>
                        @foreach($regioni as $key=>$regione)
                          <option value="{{$key}}">{{$regione}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group mb-2">
                      <label class="form-label" for="province">{{__('locale.Province')}}</label>
                      <select id="province" name="province" class="form-control">
                        <option value="basic">--Seleziona Provincia--</option>
                        @foreach($provincie as $key=>$provincia)
                          <option value="{{$key}}">{{$provincia}}</option>
                        @endforeach

                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary mr-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- Modal to add new user Ends-->
          </div>

        </div>
        <!-- Information Tab ends -->

        <!-- Social Tab starts -->
        <div class="tab-pane" id="social" aria-labelledby="social-tab" role="tabpanel">
         #TODO
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

  <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

@endsection

@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset(mix('js/scripts/pages/app-user-edit.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/tables_view/client-address-list.js')) }}"></script>

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


  var assetPath = '../../../app-assets/',
          userView = 'view.html',
          userEdit = 'edit.html';
  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
    userView = assetPath + 'user/show';
    userEdit = assetPath + 'user/edit';
  }
  $(document).ready(function() {
    $('.address-list-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('address.list',['id'=>$client->id]) }}",
      columns: [
        { data: 'id' },
        { data: 'destination' },
        { data: 'address' },
        { data: 'zip_code' },
        { data: 'region' },
        { data: 'province' },
        { data: '' }

      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          orderable: false,
          responsivePriority: 2,
          targets: 0
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          orderable: false,
          render: function (data, type, full, meta) {
            return (
                    '<div class="btn-group">' +
                    '<a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">' +
                    feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                    '</a>' +
                    '<div class="dropdown-menu dropdown-menu-right">' +
                    '<a href="' +
                    userView +'/'+ full['id'] +
                    '" class="dropdown-item">' +
                    feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) +
                    'Details</a>' +
                    '<a href="' +
                    userEdit +'/'+ full['id'] +
                    '" class="dropdown-item">' +
                    feather.icons['archive'].toSvg({ class: 'font-small-4 mr-50' }) +
                    'Edit</a>' +
                    '<a href="javascript:;" class="dropdown-item delete-record">' +
                    feather.icons['trash-2'].toSvg({ class: 'font-small-4 mr-50' }) +
                    'Delete</a></div>' +
                    '</div>' +
                    '</div>'
            );
          }
        }
      ],
      order: [[1, 'asc'],[2, 'asc']],
      dom:
              '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
              '<"col-lg-12 col-xl-6" l>' +
              '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
              '>t' +
              '<"d-flex justify-content-between mx-2 row mb-1"' +
              '<"col-sm-12 col-md-6"i>' +
              '<"col-sm-12 col-md-6"p>' +
              '>',
      language: {
        sLengthMenu: 'Show _MENU_',
        search: 'Search',
        searchPlaceholder: 'Search..'
      },
      // Buttons with Dropdown
      buttons: [
        {
          text: '{{__('locale.Add New User')}}',
          className: 'add-new btn btn-primary mt-50',
          action: function ( e, dt, button, config ) {
            window.location = 'create';
          },
          init: function (api, node, config) {
            $(node).removeClass('btn-secondary');
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['name'];
            }
          }),
          type: 'column',
          renderer: $.fn.dataTable.Responsive.renderer.tableAll({
            tableClass: 'table',
            columnDefs: [
              {
                targets: 2,
                visible: true
              },
              {
                targets: 3,
                visible: true
              }
            ]
          })
        }
      },
      language: {
        paginate: {
          // remove previous & next text from pagination
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      },
    });
  } );
</script>
@endsection
