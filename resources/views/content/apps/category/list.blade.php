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
    <h5 class="card-header">Search Filter</h5>
    <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
      <div class="col-md-4 user_role"></div>
      <div class="col-md-4 user_plan"></div>
      <div class="col-md-4 user_status"></div>
    </div>
  </div>
  <!-- users filter end -->
  <!-- list section start -->
  <div class="card">
    <div class="card-header">
      <h4 class="card-title">Lista Categorie</h4>
      @if(auth()->user()->can('documents_create') || auth()->user()->hasanyrole('super-admin'))
        <a href="{{route('category.create')}}" class="btn btn-primary">{{__('locale.Category New')}}</a>
      @endif
    </div>
    <div class="card-datatable table-responsive pt-0">
      <table class="user-list-table table">
        <thead class="thead-light">
        <tr>
            <th>Categoria</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
      </table>
    </div>
  </div>
  <!-- list section end -->
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
    var assetPath = '../../../app-assets/',
            userView = 'app-linee-view.html',
            userEdit = 'app-linee-edit.html';
    if ($('body').attr('data-framework') === 'laravel') {
      assetPath = $('body').attr('data-asset-path');
      userView = assetPath + 'category/view';
      userEdit = assetPath + 'category/edit';
    }
    $(document).ready(function() {
      $('.user-list-table').DataTable({
          processing: true,
          serverSide: true,
          autoWidth: true,
        ajax: "{{ route('category.list') }}",
        columns: [
            { data: 'category' },
            { data: 'disabled'},
            {data: 'action', name: 'action',orderable:false,serachable:false, sClass:'text-center'},
        ],
          order: [[1, 'asc']],
      });
    } );
  </script>
@endsection
