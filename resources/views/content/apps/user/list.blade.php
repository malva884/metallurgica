@extends('layouts/contentLayoutMaster')

@section('title', 'User List')

@section('vendor-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection

@section('content')
<!-- users list start -->
<section class="app-user-list">
  <!-- users filter start -->
    <div class="card">
        <h5 class="card-header">{{__('locale.Search Filter')}}</h5>
        <div class="d-flex  align-items-center mx-50 row pt-0 pb-2">
            <div class="col-xl-6 col-md-6 col-12 mb-1">
                <div class="form-group">
                    <label for="basicInput">{{__('locale.Firstname')}}/{{__('locale.Lastname')}}</label>
                    <input type="text" class="form-control" id="UserUsername" placeholder="{{__('locale.Firstname')}}/{{__('locale.Lastname')}}" />
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-12 mb-1">
                <div class="form-group">
                    <label for="basicInput">{{__('locale.Email')}}</label>
                    <input type="text" class="form-control" id="UserEmail" placeholder="{{__('locale.Email')}}" />
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-12 mb-1">
                <div class="form-group">
                    <label for="basicInput">{{__('locale.Roles')}}</label>
                    <select id="UserRole" class="form-control text-capitalize mb-md-0 mb-2xx">
                        <option value=""> {{__('locale.Select role')}} </option>
                        @foreach($roles as $key=>$value)
                        <option value="{{$key}}"> {{$value}} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-12 mb-1">
                <div class="form-group">
                    <label for="basicInput">{{__('locale.Status')}}</label>
                    <select id="UserStatus" class="form-control text-capitalize mb-md-0 mb-2xx">
                        <option value=""> {{__('locale.Select status')}} </option>
                        <option value="Active"> Active </option>
                        <option value="Inactive"> Inactive </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!-- users filter end -->
    <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Export
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" id="exportCsv" href="#">CSV</a>
                <a class="dropdown-item" id="exportExcel" href="#">EXCEL</a>
                <a class="dropdown-item" id="exportPdf" href="#">PDF</a>
            </div>
    </div>
    <!-- list section start -->
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <table class="dt-column-search table" id="dt-column-search">
                <thead class="thead-light">
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>status</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
  <!-- list section end -->
    <div class="modal fade" id="resetPassword" tabindex="-1" role="dialog" aria-labelledby="resetPassword" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModal">{{ __('locale.Reset Password') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('resetPassword')}}">
                        @csrf
                        <input type="hidden" id="userReset" name="userReset">
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('locale.New Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('locale.Reset') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- users list ends -->
@endsection

@section('vendor-script')
  {{-- Vendor js files --}}
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

  <script type="text/javascript">
      var dtUserTable = $('.dt-column-search'),
      PathImage = '{{ URL::asset('/images/users/') }}';
      // Users List datatable
      if (dtUserTable.length) {
          dtUserTable.DataTable({
              processing: true,
              serverSide: true,
              ajax: {
                  url: '{{ route('user.list') }}',
                  data: function (d) {
                      d.role = $('#UserRole').val();
                      d.status =$('#UserStatus').val();
                      d.username =$('#UserUsername').val();
                      d.email =$('#UserEmail').val();
                  },
                  error:function(data){
                      console.log('ok');
                  }
              },
              columns: [
                  { data: 'firstname' },
                  { data: 'email' },
                  { data: 'role' },
                  { data: 'status' },
                  {data: 'action', name: 'action',orderable:false,serachable:false},
              ],
			  order: [1, 'asc'],
          });
		  

      }



      $('#UserRole').change(function(){
          $('#dt-column-search').DataTable().draw(true);
      });
      $('#UserStatus').change(function(){
          $('#dt-column-search').DataTable().draw(true);
      });
      $('#UserEmail').change(function(){
          $('#dt-column-search').DataTable().draw(true);
      });
      $('#UserUsername').change(function(){
          $('#dt-column-search').DataTable().draw(true);
      });

      $('body').on('click', '.resetPassword', function () {

          var id = $(this).data("id");
          $('#userReset').val(id);
      })

      $(document).ready(function () {
          $('#exportCsv').click(function () {
              $.ajax({
                  url: "{{route('users.export')}}",
                  type: "post",
                  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  data:{
                      'role':  $('#UserRole').val(),
                      'status': $('#UserStatus').val(),
                      'username': $('#UserUsername').val(),
                      'email': $('#UserEmail').val(),
                      'type': 'csv'
                  },
                  success:function(data){
                      window.location.href = '/exports/'+data+'_users.csv';
                  }
              })
          });
          $('#exportExcel').click(function () {
              $.ajax({
                  url: "{{route('users.export')}}",
                  type: "post",
                  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  data:{
                      'role':  $('#UserRole').val(),
                      'status': $('#UserStatus').val(),
                      'username': $('#UserUsername').val(),
                      'email': $('#UserEmail').val(),
                      'type': 'xlsx'
                  },
                  success:function(data){
                      window.location.href = '/exports/'+data+'_users.xlsx';
                  }
              })
          })

          $('#exportPdf').click(function () {
              $.ajax({
                  url: "{{route('users.export.pdf')}}",
                  type: "post",
                  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  data:{
                      'role':  $('#UserRole').val(),
                      'status': $('#UserStatus').val(),
                      'username': $('#UserUsername').val(),
                      'email': $('#UserEmail').val(),
                      'type': 'pdf'
                  },
                  xhrFields: {
                      responseType: 'blob'
                  },
                  success: function(response){
                      var blob = new Blob([response]);
                      var link = document.createElement('a');
                      link.href = window.URL.createObjectURL(blob);
                      link.download = "Users.pdf";
                      link.click();
                  },
                  error: function(blob){
                      console.log(blob);
                  }
              })
          })
      })

  </script>
@endsection
