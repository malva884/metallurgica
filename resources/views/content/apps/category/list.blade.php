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
    <div class="card-datatable table-responsive pt-0">
      <table class="user-list-table table">
        <thead class="thead-light">
        <tr>
            <th></th>
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
            { data: 'id' },
            { data: 'category' },
            { data: 'disabled'},
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
                // status
                targets: -2,
                render: function (data, type, full, meta) {
                    var $status_number = full['disabled'];
                    var $status = {
                        0: { title: '{{__('locale.Active')}}', class: 'badge-light-success' },
                        1: { title: '{{__('locale.Disabled')}}', class: 'badge-light-warning' },
                    };
                    if (typeof $status[$status_number] === 'undefined') {
                        return data;
                    }
                    return (
                        '<span class="badge badge-pill ' +
                        $status[$status_number].class +
                        '">' +
                        $status[$status_number].title +
                        '</span>'
                    );
                }
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
        //order: [[1, 'DESC'],[2, 'asc']],
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
                  text: '{{__('locale.Category New')}}',
                  className: 'add-new btn btn-primary mt-50',
                  action: function ( e, dt, button, config ) {
                      window.location = '/category/create';
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
