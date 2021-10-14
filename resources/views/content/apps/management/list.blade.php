@extends('layouts/contentLayoutMaster')

@section('title', 'Management Role/Permissioneeee')

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
        <div class="row">
            <!-- Role List -->
            <div class="col-md-6">
                <!-- User Permissions -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('locale.Roles')}}</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="role_table table" id="dt-column-search">
                            <thead class="thead-light">
                            <tr>
                                <th></th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /User Permissions -->
            </div>
            <!-- Role List Ends -->
            <!-- Permission List -->
            <div class="col-md-6">
                <!-- User Permissions -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('locale.Permissions')}}</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="permission_table table" id="permission_table">
                            <thead class="thead-light">
                            <tr>
                                <th></th>
                                <th>Permesso</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /User Permissions -->
            </div>
            <!-- Permission List Ends -->
        </div>

        <!-- User Invoice Starts-->
        <div class="row invoice-list-wrapper">
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive">

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

    <script>
        var dtRoleTable = $('.role_table'),
            dtPermissionTable = $('.permission_table');

        if ($('body').attr('data-framework') === 'laravel') {
            assetPath = $('body').attr('data-asset-path');
            roleView = assetPath + '';
            roleEdit = assetPath + 'role/edit';
            permissionView = assetPath + '';
            permissionEdit = assetPath + 'permission/edit';
        }

        // Users List datatable
        if (dtRoleTable.length) {
            dtRoleTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('management.list.role') }}',
                    data: function (d) {
                    }
                },
                columns: [
                    // columns according to JSON
                    {data: 'id'},
                    {data: 'name'},
                    {data: ''}
                ],
                columnDefs: [
                    {
                        // For Responsive
                        className: 'control',
                        orderable: false,
                        responsivePriority: 2,
                        targets: 0
                    },
                    /*
                    {
                        // User Role
                        targets: 1,
                        render: function (data, type, full, meta) {
                            var $role = full['name'];
                            var roleBadgeObj = {
                                user: 'text-primary',//feather.icons['user'].toSvg({ class: 'font-medium-3 text-primary mr-50' }),
                                staff: 'text-info',//feather.icons['settings'].toSvg({ class: 'font-medium-3 text-warning mr-50' }),
                                client: 'text-primary',//feather.icons['gift'].toSvg({ class: 'font-medium-3 text-success mr-50' }),
                                'super-admin': 'text-danger',//feather.icons['slack'].toSvg({ class: 'font-medium-3 text-danger mr-50' }),
                                admin: 'text-warning',//feather.icons['slack'].toSvg({ class: 'font-medium-3 text-warning mr-50' }),
                                'undefinednull': 'text-danger',//feather.icons['x'].toSvg({ class: 'font-medium-3 text-danger mr-50' })
                            };

                            return "<span class='text-truncate align-middle "+roleBadgeObj[$role]+"  '>" + $role + '</span>';
                        }
                    },
                    */

                    {
                        // Actions
                        targets: -1,
                        title: 'Actions',
                        orderable: false,
                        render: function (data, type, full, meta) {
                            return (
                                '<div class="btn-group">' +
                                '<a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">' +
                                feather.icons['more-vertical'].toSvg({class: 'font-small-4'}) +
                                '</a>' +
                                '<div class="dropdown-menu dropdown-menu-right">' +
                                '<a href="' +
                                roleView + '/' + full['id'] +
                                '" class="dropdown-item">' +
                                feather.icons['file-text'].toSvg({class: 'font-small-4 mr-50'}) +
                                'Details</a>' +
                                '<a href="' +
                                roleEdit + '/' + full['id'] +
                                '" class="dropdown-item">' +
                                feather.icons['archive'].toSvg({class: 'font-small-4 mr-50'}) +
                                'Edit</a>' +
                                '<a href="javascript:;" class="dropdown-item delete-record">' +
                                feather.icons['trash-2'].toSvg({class: 'font-small-4 mr-50'}) +
                                'Delete</a></div>' +
                                '</div>' +
                                '</div>'
                            );
                        }
                    }
                ],
                //order: [[1, 'asc']],
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
                        text: '{{__('locale.Add New Role')}}',
                        className: 'add-new btn btn-primary mt-50',
                        identifierName: 'ajax-request',
                        action: function (e, dt, button, config) {
                            //window.location = '/role/create';
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
                                return 'Details of ' + data['name'];
                            }
                        }),
                        type: 'column',
                        renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                            tableClass: 'table',
                            columnDefs: [
                                {
                                    targets: 2,
                                    visible: false
                                },
                                {
                                    targets: 3,
                                    visible: false
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
        }

        if (dtPermissionTable.length) {
            dtPermissionTable.DataTable({
                processing: true,
                ajax: {
                    url: '{{ route('management.list.permission') }}',
                    data: function (d) {
                    }
                },
                columns: [
                    // columns according to JSON
                    {data: 'id'},
                    {data: 'name'},
                    {data: ''}
                ],
                columnDefs: [
                    {
                        // For Responsive
                        className: 'control',
                        orderable: false,
                        responsivePriority: 2,
                        targets: 0
                    },
                    /*
                    {
                        // User Role
                        targets: 1,
                        render: function (data, type, full, meta) {
                            var $role = full['name'];
                            var roleBadgeObj = {
                                user: 'text-primary',//feather.icons['user'].toSvg({ class: 'font-medium-3 text-primary mr-50' }),
                                staff: 'text-info',//feather.icons['settings'].toSvg({ class: 'font-medium-3 text-warning mr-50' }),
                                client: 'text-primary',//feather.icons['gift'].toSvg({ class: 'font-medium-3 text-success mr-50' }),
                                'super-admin': 'text-danger',//feather.icons['slack'].toSvg({ class: 'font-medium-3 text-danger mr-50' }),
                                admin: 'text-warning',//feather.icons['slack'].toSvg({ class: 'font-medium-3 text-warning mr-50' }),
                                'undefinednull': 'text-danger',//feather.icons['x'].toSvg({ class: 'font-medium-3 text-danger mr-50' })
                            };

                            return "<span class='text-truncate align-middle "+roleBadgeObj[$role]+"  '>" + $role + '</span>';
                        }
                    },
                    */

                    {
                        // Actions
                        targets: -1,
                        title: 'Actions',
                        orderable: false,
                        render: function (data, type, full, meta) {
                            return (
                                '<div class="btn-group">' +
                                '<a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">' +
                                feather.icons['more-vertical'].toSvg({class: 'font-small-4'}) +
                                '</a>' +
                                '<div class="dropdown-menu dropdown-menu-right">' +
                                '<a href="' +
                                roleView + '/' + full['id'] +
                                '" class="dropdown-item">' +
                                feather.icons['file-text'].toSvg({class: 'font-small-4 mr-50'}) +
                                'Details</a>' +
                                '<a class="dropdown-item" id="ajax-request">' +
                                feather.icons['archive'].toSvg({class: 'font-small-4 mr-50'}) +
                                'Edit</a>' +
                                '<a href="javascript:;" class="dropdown-item delete-record">' +
                                feather.icons['trash-2'].toSvg({class: 'font-small-4 mr-50'}) +
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
                        text: '{{__('locale.Add New Permission')}}',
                        className: 'add-new btn btn-primary mt-50',
                        attr: {id: 'permission-request'},

                        action: function (e, dt, button, config) {
                            //window.location = '/role/create';
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
                                return 'Details of ' + data['name'];
                            }
                        }),
                        type: 'column',
                        renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                            tableClass: 'table',
                            columnDefs: [
                                {
                                    targets: 2,
                                    visible: false
                                },
                                {
                                    targets: 3,
                                    visible: false
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
        }
        $(function () {
            'use strict';
            var ajax = $('#permission-request');
            if (ajax.length) {
                ajax.on('click', function () {
                    swal.fire({
                            title: "Nuovo Permesso",
                            input: 'text',
                            showCancelButton: true,
                            confirmButtonColor: "#006400",
                            confirmButtonText: "Salva",
                            closeOnConfirm: false,
                            preConfirm: function (permesso) {
                                console.log(permesso);
                                $.ajax({
                                    url: "{{route('management.add.permission')}}",
                                    type: "POST",
                                    data: {
                                        permsso: permesso,
                                        "_token": "{{ csrf_token() }}",
                                    },
                                    dataType: "html",
                                    success: function () {
                                        swal.fire("Fatto!", "Permesso Aggiunto!", "success");
                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {
                                        swal.fire("HOOPS!", "Permesso Già Presente!", "danger");
                                    },
                                });
                            }
                        },
                    )

                });
            }
        });
    </script>
@endsection
