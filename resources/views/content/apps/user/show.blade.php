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
@endsection

@section('content')
<section class="app-user-view">
  <!-- User Card & Plan Starts -->
  <div class="row">
    <!-- User Card starts-->
    <div class="col-xl-12 col-lg-9 col-md-8">
      <div class="card user-card">
        <div class="card-body">
          <div class="row">
            <div class="col-xl-4 col-lg-6 d-flex flex-column justify-content-between border-container-lg">
              <div class="user-avatar-section">
                <div class="d-flex justify-content-start">
                  <img
                    class="img-fluid rounded"
                    src="{{asset('/images/users/'.$user->image)}}"
                    height="104"
                    width="104"
                    alt="User avatar"
                  />
                  <div class="d-flex flex-column ml-1">
                    <div class="user-info mb-1">
                      <h4 class="mb-0">{{$user->nome.' '.$user->cognome}}</h4>
                      <span class="card-text">{{$user->email}}</span>
                    </div>
                    <div class="d-flex flex-wrap">
                      <a href="{{url('user/setting/'.$user->id)}}" class="btn btn-primary">{{__('locale.Edit')}}</a>
                      <button class="btn btn-outline-danger ml-1">{{__('locale.Delete')}}</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 mt-2 mt-xl-0">
              <div class="user-info-wrapper">

                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="user" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Firstname')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$user->firstname}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="user" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Lastname')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$user->lastname}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="flag" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Sex')}}</span>
                  </div>
                  <p class="card-text mb-0">{{($user->sex =='m' ? __('locale.Male'):__('locale.Female'))}}</p>
                </div>
                <div class="d-flex flex-wrap">
                  <div class="user-info-title">
                    <i data-feather="phone" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Phone')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$user->phone}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="phone" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Extension')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$user->extension}}</p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 mt-2 mt-xl-0">
              <div class="user-info-wrapper">

                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="check" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Status')}}</span>
                  </div>
                  <p class="card-text mb-0">{{($user->status ? __('locale.Active'):__('locale.Disabled'))}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="star" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Role')}}</span>
                  </div>
                  <p class="card-text mb-0">{{__('locale.'.$user->roles->pluck('name')->toArray()[0])}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="navigation" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Region')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$user->region}}</p>
                </div>
                <div class="d-flex flex-wrap my-50">
                  <div class="user-info-title">
                    <i data-feather="flag" class="mr-1"></i>
                    <span class="card-text user-info-title font-weight-bold mb-0">{{__('locale.Note')}}</span>
                  </div>
                  <p class="card-text mb-0">{{$user->note}}</p>
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

    @hasanyrole('super-admin|admin')
    <!-- User Permissions Starts -->
    <div class="col-md-6">
      <!-- User Permissions -->
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{{__('locale.Permissions user')}}</h4>
        </div>
        <p class="card-text ml-2">{{__('locale.Permissions assigned')}}</p>
        <div class="table-responsive">
          <table class="table table-striped table-borderless">
            <thead class="thead-light">
            <tr>
              <th class="text-start">{{__('locale.Permissions')}}</th>
              @foreach(\App\User::$modules as $module)
                <th class="text-start">{{__('locale.'.$module)}}</th>
              @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($titoli  as $titolo)
              <tr>
                <td>{{__('locale.'.ucwords($titolo))}}</td>

                @foreach(\App\User::$modules as $module)
                  @if(\App\User::getPermission($module, $titolo))
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="{{$module.'_'.$titolo}}" id="{{$module.'_'.$titolo}}" {{(in_array($module.'_'.$titolo,$permessi_user) ? 'checked':'') }} disabled  />
                        <label class="custom-control-label" for="{{$module.'_'.$titolo}}"></label>
                      </div>
                    </td>
                  @else
                    <td></td>
                  @endif
                @endforeach
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!-- /User Permissions -->
    </div>
    @endhasallroles
    <!-- User Permissions Ends -->
  </div>
  <!-- User Timeline & Permissions Ends -->

  <!-- User Invoice Starts-->
  <div class="row invoice-list-wrapper">

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
@endsection
@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset(mix('js/scripts/pages/app-user-view.js')) }}"></script>
@endsection
