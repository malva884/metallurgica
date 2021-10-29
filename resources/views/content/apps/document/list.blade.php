@extends('layouts/contentLayoutMaster')

@section('title', 'Ducument List')

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
  <!-- users filter start -->
    <div class="card">
        <h5 class="card-header">{{__('locale.Search Filter')}}</h5>
        <div class="d-flex  align-items-center mx-50 row pt-0 pb-2">
            <div class="col-xl-6 col-md-6 col-12 mb-1">
                <div class="form-group">
                    <label for="basicInput">{{__('locale.Specifica Tecnica')}}</label>
                    <input type="text" class="form-control" id="Specifica" placeholder="{{__('locale.Specifica Tecnica')}}" />
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-12 mb-1">
                <div class="form-group">
                    <label for="basicInput">{{__('locale.View')}}</label>
                    <select id="UserView" class="form-control text-capitalize mb-md-0 mb-2xx">
                        <option value="1" {{((auth()->user()->hasAnyPermission(['documents_check','documents_see']) ? 'Selected':''))}}> {{__('locale.Not Signed')}} </option>
                        <option value="2"> {{__('locale.Signed')}} </option>
                        <option value="3" {{((auth()->user()->hasAnyPermission(['documents_create']) ? 'Selected':''))}}> {{__('locale.All')}} </option>
                    </select>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-12 mb-1">
                <div class="form-group">
                    <label for="basicInput">{{__('locale.Categories')}}</label>
                    <select id="Category" class="form-control text-capitalize mb-md-0 mb-2xx">
                        <option value=""> {{__('locale.Select Category')}} </option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->category}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 col-12 mb-1">
                <div class="form-group">
                    <label for="basicInput">{{__('locale.Status')}}</label>
                    <select id="Status" class="form-control text-capitalize mb-md-0 mb-2xx">
                        <option value="2" {{((auth()->user()->hasAnyPermission(['documents_check','documents_see']) ? 'Selected':''))}}> In Approvazione </option>
                        <option value="3"> Completato </option>
                        <option value="1"> Creazione </option>
                        <option value="" {{((auth()->user()->hasAnyPermission(['documents_create']) ? 'Selected':''))}}> Tutti </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!-- users filter end -->

    <!-- list section start -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Lista Specifiche</h4>
            @if(auth()->user()->can('documents_create') || auth()->user()->hasanyrole('super-admin'))
                <a href="{{route('document.create')}}" class="btn btn-primary">{{__('locale.Document New')}}</a>
            @endif
        </div>
        <div class="card-datatable table-responsive pt-0">
            <table class="dt-document table" id="dt-document">
                <thead class="thead-light">
                <tr>
                    <th>Specif. Numero</th>
                    <th>Tipo</th>
                    <th>Catagoria</th>
                    @if(auth()->user()->can('documents_create') || auth()->user()->hasanyrole('super-admin'))
                        <th>Stato Workflow</th>
                    @else
                        <th>Creato Da</th>
                    @endif
                    <th>Data Creazione</th>
                    <th>Stato</th>
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
      var dtDocumentTable = $('.dt-document'),
          statusObj = {
             // 1: { title: 'Pending', class: 'badge-light-warning' },
              1: { title: 'Active', class: 'badge-light-success' },
              0: { title: 'Inactive', class: 'badge-light-secondary' }
          };

      if ($('body').attr('data-framework') === 'laravel') {
          assetPath = $('body').attr('data-asset-path');
          documentView = assetPath + 'document/show';
          documentEdit = assetPath + 'document/edit';
          documentClone = assetPath + 'document/clone';

      }

      // Users List datatable
      if (dtDocumentTable.length) {
          dtDocumentTable.DataTable({
              processing: true,
              serverSide: true,
              autoWidth: false,
              searching: false,
              ajax: {
                  url: '{{ route('document.list') }}',
                  data: function (d) {
                      d.category =$('#Category').val();
                      d.specifica =$('#Specifica').val();
                      d.status =$('#Status').val();
                      d.view =$('#UserView').val();
                  }
              },
              columns: [
                  // columns according to JSON
                  { data: 'specific_number' },
                  { data: 'document_type' },
                  { data: 'category' },
                  { data: 'user' },
                  { data: 'created_at' },
                  { data: 'status' },
                  {data: 'action', name: 'action',orderable:false,serachable:false, sClass:'text-center'},
              ],
              order: [[4, 'desc']],

          });
      }


      $('#Category').change(function(){
          $('#dt-document').DataTable().draw(true);
      });
      $('#Status').change(function(){
          $('#dt-document').DataTable().draw(true);
      });
      $('#Specifica').change(function(){
          $('#dt-document').DataTable().draw(true);
      });
      $('#UserView').change(function(){
          $('#dt-document').DataTable().draw(true);
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

  </script>
@endsection
