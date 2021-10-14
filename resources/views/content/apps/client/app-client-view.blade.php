@extends('layouts/contentLayoutMaster')

@section('title', 'User View')

@section('vendor-style')
<link rel="stylesheet" href="{{asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css'))}}">
<link rel="stylesheet" href="{{asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css'))}}">
<link rel="stylesheet" href="{{asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css'))}}">
@endsection
@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-invoice-list.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
  <link rel="stylesheet" href="{{asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
@endsection

@section('content')
<section class="app-user-view">
  <!-- User Card & Plan Starts -->
  <div class="row">
    <!-- User Card starts-->
    <div class="col-xl-12 col-lg-8 col-md-7">
      <div class="card user-card">
        <div class="card-body">
          <div class="row">
            <div class="col-xl-3 col-lg-12 d-flex flex-column justify-content-between border-container-lg">
              <div class="user-avatar-section">
                <div class="d-flex justify-content-start">
                  <img
                    class="img-fluid rounded"
                    @if($client->id || $client->logo)
                    src="{{asset('/images/client/'.$client->id.'/med_'.$client->logo)}}"
                    @else
                    src="{{asset('/images/default/200x200.svg')}}"
                    @endif
                    height="90"
                    width="200"
                    alt="User avatar"
                  />
                  <div class="d-flex flex-column ml-1">
                    <div class="user-info mb-1">
                      <h4 class="mb-0">{{$client->company}}
                        @if($client->of)
                         Di {{$client->of}}
                        @endif
                      </h4>
                      <span class="card-text">{{$client->email}}</span>
                    </div>
                    <div class="d-flex flex-wrap">
                      <a href="{{url('client/edit/'.$client->id)}}" class="btn btn-primary">{{__('locale.Edit')}}</a>
                      <button type="button" class="btn btn-outline-danger ml-1" id="confirm-color">{{__('locale.Delete')}}</button>
                      <!--button class="btn btn-outline-danger ml-1">{{__('locale.Delete')}}</button -->
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex align-items-center user-total-numbers">
                <div class="d-flex align-items-center mr-2">
                  <div class="color-box bg-light-primary">
                    <i data-feather="dollar-sign" class="text-primary"></i>
                  </div>
                  <div class="ml-1">
                    <h5 class="mb-0">23.3k</h5>
                    <small>Monthly Sales</small>
                  </div>
                </div>
                <div class="d-flex align-items-center">
                  <div class="color-box bg-light-success">
                    <i data-feather="trending-up" class="text-success"></i>
                  </div>
                  <div class="ml-1">
                    <h5 class="mb-0">$99.87K</h5>
                    <small>Annual Profit</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-lg-12 mt-2 mt-xl-0">
              <div class="user-info-wrapper">
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="check" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Company')}}</span>
                  </div>
                  <p class="card-text mb-0">
                    {{$client->company}}
                    @if($client->of)
                      Di {{$client->of}}
                    @endif
                  </p>
                </div>

                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="user" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Peference_person_light')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->sign}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="type" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Sign')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->sign}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="type" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Weekly_closing_light')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->weekly_closing}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="phone" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Phone')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->phone}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="phone" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Mobile')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->mobile}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="phone" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Fax')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->fax}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="home" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Www')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->www}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="credit-card" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.riba')}}</span>
                  </div>
                  <p class="card-text mb-0">{{($client->riba ? __('Yes'):__('No'))}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="command" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Sdi')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->sdi}}</p>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-lg-12 mt-2 mt-xl-0">
              <div class="user-info-wrapper">

                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="check" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Status')}}</span>
                  </div>
                  <p class="card-text mb-0">
                    <div class="badge badge-pill badge-light-{{($client->status ? 'success': 'danger')}}">{{($client->status ? __('locale.Active'): __('locale.Disabled'))}}
                  </div>
                    </p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="flag" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Region')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->regione->nome_regione}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="flag" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Provincie')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->provincia->provincia}}</p>
                </div>
                <div class="d-flex flex-wrap">
                  <div class="user-info-title">
                    <i data-feather="flag" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.City')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->city}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="navigation" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Address')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->address}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="mail" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Post_code')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->post_code}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="dollar-sign" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Tax_code')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->tax_code}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="dollar-sign" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Tax Code')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->tax_code}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="dollar-sign" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Discount')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->discount}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="info" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Note')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$client->note}}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /User Card Ends-->


  </div>
  <!-- User Card & Plan Ends -->

  <!-- User Timeline & Permissions Starts -->
  <div class="row">
    <!-- information starts -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title mb-2">User Timeline</h4>
        </div>
        <div class="card-body">
          <ul class="timeline">
            <li class="timeline-item">
              <span class="timeline-point timeline-point-indicator"></span>
              <div class="timeline-event">
                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                  <h6>12 Invoices have been paid</h6>
                  <span class="timeline-event-time">12 min ago</span>
                </div>
                <p>Invoices have been paid to the company.</p>
                <div class="media align-items-center">
                  <img
                    class="mr-1"
                    src="{{asset('images/icons/file-icons/pdf.png')}}"
                    alt="invoice"
                    height="23"
                  />
                  <div class="media-body">invoice.pdf</div>
                </div>
              </div>
            </li>
            <li class="timeline-item">
              <span class="timeline-point timeline-point-warning timeline-point-indicator"></span>
              <div class="timeline-event">
                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                  <h6>Client Meeting</h6>
                  <span class="timeline-event-time">45 min ago</span>
                </div>
                <p>Project meeting with john @10:15am.</p>
                <div class="media align-items-center">
                  <div class="avatar">
                    <img src="{{asset('images/avatars/12-small.png')}}" alt="avatar" height="38" width="38" />
                  </div>
                  <div class="media-body ml-50">
                    <h6 class="mb-0">John Doe (Client)</h6>
                    <span>CEO of Infibeam</span>
                  </div>
                </div>
              </div>
            </li>
            <li class="timeline-item">
              <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
              <div class="timeline-event">
                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                  <h6>Create a new project for client</h6>
                  <span class="timeline-event-time">2 days ago</span>
                </div>
                <p class="mb-0">Add files to new design folder</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- information Ends -->

    <!-- User Permissions Starts -->
    <div class="col-md-6">
      <!-- User Permissions -->
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Permissions</h4>
        </div>
        <p class="card-text ml-2">Permission according to roles</p>
        <div class="table-responsive">
          <table class="table table-striped table-borderless">
            <thead class="thead-light">
              <tr>
                <th>Module</th>
                <th>Read</th>
                <th>Write</th>
                <th>Create</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Admin</td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="admin-read" checked disabled />
                    <label class="custom-control-label" for="admin-read"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="admin-write" disabled />
                    <label class="custom-control-label" for="admin-write"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="admin-create" disabled />
                    <label class="custom-control-label" for="admin-create"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="admin-delete" disabled />
                    <label class="custom-control-label" for="admin-delete"></label>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Staff</td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="staff-read" disabled />
                    <label class="custom-control-label" for="staff-read"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="staff-write" checked disabled />
                    <label class="custom-control-label" for="staff-write"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="staff-create" disabled />
                    <label class="custom-control-label" for="staff-create"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="staff-delete" disabled />
                    <label class="custom-control-label" for="staff-delete"></label>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Author</td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="author-read" checked disabled />
                    <label class="custom-control-label" for="author-read"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="author-write" disabled />
                    <label class="custom-control-label" for="author-write"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="author-create" checked disabled />
                    <label class="custom-control-label" for="author-create"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="author-delete" disabled />
                    <label class="custom-control-label" for="author-delete"></label>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Contributor</td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="contributor-read" disabled />
                    <label class="custom-control-label" for="contributor-read"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="contributor-write" disabled />
                    <label class="custom-control-label" for="contributor-write"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="contributor-create" disabled />
                    <label class="custom-control-label" for="contributor-create"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="contributor-delete" disabled />
                    <label class="custom-control-label" for="contributor-delete"></label>
                  </div>
                </td>
              </tr>
              <tr>
                <td>User</td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="user-read" disabled />
                    <label class="custom-control-label" for="user-read"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="user-create" disabled />
                    <label class="custom-control-label" for="user-create"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="user-write" disabled />
                    <label class="custom-control-label" for="user-write"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="user-delete" checked disabled />
                    <label class="custom-control-label" for="user-delete"></label>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- /User Permissions -->
    </div>
    <!-- User Permissions Ends -->
  </div>
  <!-- User Timeline & Permissions Ends -->

  <!-- User Invoice Starts-->
  <div class="row invoice-list-wrapper">
    <div class="col-12">
      <div class="card">
        <div class="card-datatable table-responsive">
          <table class="invoice-list-table table">
            <thead>
              <tr>
                <th></th>
                <th>#</th>
                <th><i data-feather="trending-up"></i></th>
                <th>Client</th>
                <th>Total</th>
                <th class="text-truncate">Issued Date</th>
                <th>Balance</th>
                <th>Invoice Status</th>
                <th class="cell-fit">Actions</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- /User Invoice Ends-->
</section>


@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/extensions/moment.min.js'))}}"></script>
<script src="{{asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js'))}}"></script>
<script src="{{asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js'))}}"></script>
<script src="{{asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js'))}}"></script>
<script src="{{asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js'))}}"></script>
<script src="{{asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js'))}}"></script>
<script src="{{asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js'))}}"></script>
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
@endsection
@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset(mix('js/scripts/pages/app-user-view.js')) }}"></script>
  <script >
    $(function () {
      'use strict';
      var confirmColor = $('#confirm-color');

      // Confirm Color
      if (confirmColor.length) {
        confirmColor.on('click', function () {
          Swal.fire({
            title: '{{__('locale.Title delete')}}',
            text: "{{__('locale.Msg_confirm_delete_client')}}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{__('locale.Yes_delete')}}',
            customClass: {
              confirmButton: 'btn btn-primary',
              cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
          }).then(function (result) {
            if (result.value) {
              $.ajax({
                type:'POST',
                url:'{{url("/client/del")}}/' + {{$client->id}},
                data:{
                  "_token": "{{ csrf_token() }}",
                }
              });
              Swal.fire({
                icon: 'success',
                title: '{{__('locale.Deleted')}}',
                text: '{{__('locale.Deleted confirm')}}',
                customClass: {
                  confirmButton: 'btn btn-success'
                }
              });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              Swal.fire({
                title: '{{__('locale.Cancelled')}}',
                text: '{{__('locale.Deleted cancelled')}}',
                icon: 'error',
                customClass: {
                  confirmButton: 'btn btn-success'
                }
              });
            }
          });
        });
      }
    });
  </script>
@endsection


