@extends('layouts.contentLayoutMaster')

@section('title', 'Corso Lavori')

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
                <h4 class="card-title">Lista Corso Lavori</h4>
                @if(auth()->user()->can('workProgress_create') || auth()->user()->hasanyrole('super-admin'))
                <a href="{{route('workstatus.import')}}" class="btn btn-primary">{{__('locale.New Work Progress')}}</a>
                @endif
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table">
                    <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total Materiali</th>
                        <th>Total Material Iniziale</th>
                        <th>Total  </th>
                        <th>Total Iniziale</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>
        <!-- list section end -->
    </section>

    <!-- users list ends -->
    <!-- Delete Product Modal -->
    <div class="modal" id="DeleteProductModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Eliminazione Corso Lavoro</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <h4>Sei sicuro di voler eliminare?</h4>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="SubmitDeleteProductForm">Si</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    {{-- Page js files --}}

    <script type="text/javascript">
        var statusObj = {
                // 1: { title: 'Pending', class: 'badge-light-warning' },
                2: { title: '{{__('locale.Completed')}}', class: 'badge-light-success' },
                1: { title: '{{__('locale.Processing')}}', class: 'badge-light-primary' },
                0: { title: '{{__('locale.Import')}}', class: 'badge-light-secondary' }
            };
        var assetPath = '../../../app-assets/',
            userView = 'app-payment-view.html',
            userEdit = 'app-payment-edit.html';
        if ($('body').attr('data-framework') === 'laravel') {
            assetPath = $('body').attr('data-asset-path');
            userView = assetPath + 'workstatus/detail';
            userEdit = assetPath + 'workstatus/summary';
            prelievi = assetPath + 'workstatus/detail/check';
        }
        $(document).ready(function () {
            $('.user-list-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('workstatus.head.list') }}",
                columns: [
                    {data: 'created_at'},
                    {data: 'status'},
                    {data: 'total_final_mp'},
                    {data: 'total_raw_material'},
                    {data: 'total_final'},
                    {data: 'raw_material_machine_manpower_cost'},
                    {data: 'action', name: 'action',orderable:false,serachable:false, sClass:'text-center'},
                ],
                order: [[0, 'desc']],

            });

            // Delete product Ajax request.
            var deleteID;
            $('body').on('click', '#getDeleteId', function(){
                deleteID = $(this).data('id');

            })
            $('#SubmitDeleteProductForm').click(function(e) {
                e.preventDefault();
                var id = deleteID;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "destroy/"+id,
                    method: 'DELETE',
                    success: function(result) {
                        $('.user-list-table').DataTable().ajax.reload();
                        $('#DeleteProductModal').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }
                });
            });

            function currencyFormatDE(num) {
                return (
                    num
                        .toFixed(2) // always two decimal digits
                        .replace('.', ',') // replace decimal point character with ,
                        .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + ' €'
                ) // use . as a separator
            }
        });

    </script>
@endsection
