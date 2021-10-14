@section('vendorr-style')
    {{-- vendorr css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('contentt')
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
                        <th>Logo</th>
                        <th>Company</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Regione</th>
                        <th>Province</th>
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
@section('vendorr-script')
    {{-- vendorr files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('pagee-script')
    {{-- Page js files --}}
    {{-- Page js files --}}

    <script type="text/javascript">
        var assetPath = '../../../app-assets/',
            userView = 'app-client-view.html',
            userEdit = 'app-client-edit.html';
        PathImage = '{{ URL::asset('/images/client/') }}';
        if ($('body').attr('data-framework') === 'laravel') {
            assetPath = $('body').attr('data-asset-path');
            userView = assetPath + 'client/view';
            userEdit = assetPath + 'client/edit';
        }
        $(document).ready(function() {
            $('.user-list-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('client-list') }}",
                columns: [
                    { data: 'id' },
                    { data: 'logo' },
                    { data: 'company' },
                    { data: 'address' },
                    { data: 'city' },
                    { data: 'nome_regione' },
                    { data: 'provincia' },
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
                        // logo
                        targets: 1,
                        render: function (data, type, full, meta) {
                            if(full['logo'])
                                return '<img src="'+ PathImage + '/'+full['id']+'/med_'+ full['logo'] +' " width="200" height="50">';
                            else
                                return null;
                        }
                    },
                    {
                        // company
                        targets: 2,
                        render: function (data, type, full, meta) {
                            console.log(full);
                            if (full['of']  === undefined || full['of'] === null) {
                                return data ;
                            }else{
                                return data + ' Di ' + full['of'];
                            }

                        }
                    },
                    {
                        // Status
                        targets: -2,
                        render: function (data, type, full, meta) {
                            var $status_number = full['active'];
                            var $status = {
                                0: { title: '{{__('locale.Disabled')}}', class: 'badge-light-warning' },
                                1: { title: '{{__('locale.Active')}}', class: 'badge-light-success' },
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
                                '' +
                                userView +'<a href="/" class="dropdown-item">'+ full['id'] +
                                '' +
                                feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) +
                                'Details</a>' +
                                '' +
                                userEdit +'<a href="/" class="dropdown-item">'+ full['id'] +
                                '' +
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
                        text: '{{__('locale.Add New Linee')}}',
                        className: 'add-new btn btn-primary mt-50',
                        action: function ( e, dt, button, config ) {
                            window.location = '/linee/edit';
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