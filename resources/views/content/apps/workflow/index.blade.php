@extends('layouts.contentLayoutMaster')

@section('title', 'Workflow')

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
    @if($message = Session::get('success'))
        <x-alert
                :message="$message" color="success"
        />
        {!! Session::forget('success') !!}
    @elseif($message = Session::get('error'))
        <x-alert
                :message="$message" color="danger"
        />
        {!! Session::forget('error') !!}
    @endif
    <!-- users list start -->
    <section class="app-user-list">
    @csrf
        <!-- users filter start -->
        <div class="card">
            <h5 class="card-header">Search Filter</h5>
            <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                <div class="col-xl-6 col-md-6 col-12 mb-1">
                    <div class="form-group">
                        <label for="basicInput">{{__('locale.Status Workflow')}}</label>
                        <select id="status" class="form-control text-capitalize mb-md-0 mb-2xx">
                            <option value="2"> {{__('locale.Processing')}} </option>
                            <option value="3"> {{__('locale.Completed')}} </option>
							<option value="4"> {{__('locale.End')}} </option>
                            <option value=""> {{__('locale.All')}} </option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-12 mb-1">
                    <div class="form-group">
                        <label for="basicInput">{{__('locale.View')}}</label>
                        <select id="userView" class="form-control text-capitalize mb-md-0 mb-2xx">
                            <option value="1"> {{__('locale.Not Signed')}} </option>
                            <option value="2"> {{__('locale.Signed')}} </option>
                            <option value="3"> {{__('locale.All')}} </option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-12 mb-1">
                    <div class="form-group">
                        <label for="basicInput">{{__('locale.Commessa')}}</label>
                        <input type="text" class="form-control" id="WorkCommessa" placeholder="{{__('locale.Commessa')}}" />
                    </div>
                </div>
            </div>
        </div>
        <!-- users filter end -->
        <!-- list section start -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Lista Workflow</h4>
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table" id="user-list-table">
                    <thead class="thead-light">
                    <tr>
                        <th>Commessa</th>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>Stato Workflow</th>
                        <th>Stato Firma</th>
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
        <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px;">
            <div class="toast" style="position: absolute; top: 0; right: 0;">
                <div class="toast-header">
                    <img src="..." class="rounded mr-2" alt="...">
                    <strong class="mr-auto">Bootstrap</strong>
                    <small>11 mins ago</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Hello, world! This is a toast message.
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
        $('#toast').toast('show')
        var dtUserTable = $('.user-list-table');
        if (dtUserTable.length) {
            dtUserTable.DataTable({
                processing: true,
                serverSide: true,
				"searching": false,
                ajax: {
                    url: '{{ route('workflow.list') }}',
                    data: function (d) {
                        d.view = $('#userView').val();
                        d.status = $('#status').val();
                        d.commessa = $('#WorkCommessa').val();
                    },
                    error:function(data){
                        console.log(data);
                    }
                },
                columns: [
                    {data: 'commessa'},
                    {data: 'type'},
                    {data: 'created_at'},
                    {data: 'status'},
                    {data: 'aprovato'},
                    {data: 'action', name: 'action',orderable:false,serachable:false, sClass:'text-center'},
                ],
                order: [[2, 'desc']],
            });

        }

        $('#userView').change(function(){
            $('#user-list-table').DataTable().draw(true);
        });
        $('#status').change(function(){
            $('#user-list-table').DataTable().draw(true);
        });
        $('#WorkCommessa').change(function(){
            $('#user-list-table').DataTable().draw(true);
        });


        // Delete product Ajax request.
        var deleteID;
        $('body').on('click', '#getDeleteId', function () {
            deleteID = $(this).data('id');
        })
        $('#SubmitDeleteProductForm').click(function (e) {
            e.preventDefault();
            var id = deleteID;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "destroy/" + id,
                method: 'DELETE',
                success: function (result) {
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


    </script>
@endsection
