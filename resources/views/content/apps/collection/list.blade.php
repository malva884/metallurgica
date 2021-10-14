@extends('layouts/contentLayoutMaster')

@section('title', 'Collectio List')

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
                        <label for="basicInput">{{__('locale.Referenza')}}</label>
                        <input type="text" class="form-control" id="referenza" placeholder="{{__('locale.Referenza')}}" />
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-12 mb-1">
                    <div class="form-group">
                        <label for="basicInput">{{__('locale.Shelf')}}</label>
                        <input type="text" class="form-control" id="shelf" placeholder="{{__('locale.Shelf')}}" />
                    </div>
                </div>

                <div class="col-xl-6 col-md-6 col-12 mb-1">
                    <div class="form-group">
                        <label for="basicInput">{{__('locale.Status')}}</label>
                        <select id="product_status" class="form-control text-capitalize mb-md-0 mb-2xx">
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
                        <th></th>
                        <th>Collezione</th>
                        <th>Linea</th>
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
        var dtUserTable = $('.dt-column-search'),
            statusObj = {
                // 1: { title: 'Pending', class: 'badge-light-warning' },
                1: { title: 'Active', class: 'badge-light-success' },
                0: { title: 'Inactive', class: 'badge-light-secondary' }
            };


        PathImage = '{{ URL::asset('/images/product/') }}';
        if ($('body').attr('data-framework') === 'laravel') {
            assetPath = $('body').attr('data-asset-path');
            productView = assetPath + 'collection/show';
            productEdit = assetPath + 'collection/edit';
        }

        // Product List datatable
        if (dtUserTable.length) {
            dtUserTable.DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                searching: false,
                ajax: {
                    url: '{{ route('collection.list') }}',
                    data: function (d) {

                    }
                },
                columns: [
                    // columns according to JSON
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'linea_name' },
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
                        // Label
                        targets: 2,
                        render: function (data, type, full, meta) {
                            var $linea_name = full['linea_name'];
                            var $linea_color = full['linea_color'];
                            if(!$linea_color)
                                $linea_color = '#6e6b7b';
                            return (
                                '<span class="badge badge-pill "' +
                                'style = "color: ' + $linea_color +
                                '">' +
                                $linea_name +
                                '</span>'
                            );
                        }
                    },

                  /*  {
                    // Product Status
                        targets: -2,
                        render: function (data, type, full, meta) {
                            var $status = full['status'];

                            return (
                                '<span class="badge badge-pill ' +
                                statusObj[$status].class +
                                '" text-capitalized>' +
                                statusObj[$status].title +
                                '</span>'
                            );
                        }
                    },*/
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
                                productView + '/' + full['id'] +
                                '" class="dropdown-item">' +
                                feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) +
                                'Details</a>' +
                                '<a href="' +
                                productEdit + '/' + full['id'] +
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
                order: [[1, 'asc']],
                dom:
                    '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                    '<"col-lg-12 col-xl-6" l>' +
                    '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',

                // Buttons with Dropdown
                buttons: [
                    {
                        text: '{{__('locale.Add New Collection')}}',
                        className: 'add-new btn btn-primary mt-50',
                        action: function ( e, dt, button, config ) {
                            window.location = '/collection/create';
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    },
                ],
                // For responsive popup
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function (row) {
                                var data = row.data();
                                return 'Details of ' + data['id'];
                            }
                        }),
                        type: 'column',
                        renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                            tableClass: 'table',
                            columnDefs: [

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
        }
        $('#referenza').change(function(){
            $('#dt-column-search').DataTable().draw(true);
        });
        $('#shelf').change(function(){
            $('#dt-column-search').DataTable().draw(true);
        });
        $('#linea').change(function(){
            $('#dt-column-search').DataTable().draw(true);
        });
        $('#product_status').change(function(){
            $('#dt-column-search').DataTable().draw(true);
        });

    </script>
@endsection
