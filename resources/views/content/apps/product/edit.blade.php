@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Product manager'))

@section('vendor-style')
  {{-- Vendor Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection

@section('content')
  @if(session()->get('message'))
    <x-alert
            :message="session()->get('message')"
    />
  @endif
<!-- users edit start -->
<section class="app-user-edit">
  <div class="card">
    <div class="card-body">
      <ul class="nav nav-pills" role="tablist">
        <li class="nav-item">
          <a
            class="nav-link d-flex align-items-center active"
            id="account-tab"
            data-toggle="tab"
            href="#account"
            aria-controls="account"
            role="tab"
            aria-selected="true"
          >
            <i data-feather="info"></i><span class="d-none d-sm-block">{{__('locale.Info product')}}</span>
          </a>
        </li>
        <li class="nav-item">
          <a
            class="nav-link d-flex align-items-center"
            id="information-tab"
            data-toggle="tab"
            href="#information"
            aria-controls="information"
            role="tab"
            aria-selected="false"
          >
            <i data-feather="info"></i><span class="d-none d-sm-block">{{__('locale.Immage manager')}}</span>
          </a>
        </li>


      </ul>
      <div class="tab-content">
        <!-- Account Tab starts -->
        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

          <!-- users edit account form start -->
          <form class="needs-validation" action="{{route('product.update',['id'=>request()->id])}}"  method="POST"   novalidate>
            @csrf
            <!-- users edit media object start -->
            @if($product->image)
              <div class="media mb-2">
                <img
                        src="{{asset('/images/product/'.$product->image)}}"
                        alt="users avatar"
                        class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                        height="90"
                        width="90"
                />
              </div>
            @endif
              <!-- users edit media object ends -->
            <div class="row">
              <div class="col-md-6 col-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">{{__('locale.Info product')}}</h4>
                  </div>
                  <div class="card-body">
                    <form class="form form-horizontal">
                      <div class="row">
                        <div class="col-12">
                          <x-imput_horizontal
                                  title="name"
                                  class="form-control"
                                  :value="$product->name"
                                  type="text"
                                  :required="true"
                          />
                        </div>
                        <div class="col-12">
                          <x-texarea_horizontal
                                  title="description"
                                  class="form-control"
                                  :value="$product->description"
                                  type="text"
                                  :required="true"
                          />
                        </div>
                        <div class="col-12">
                          <x-imput_horizontal
                                  title="price"
                                  class="form-control"
                                  :value="$product->price"
                                  type="number"
                                  :required="true"
                          />
                        </div>
                        <div class="col-12">
                          <x-imput_horizontal
                                  title="price_sale"
                                  class="form-control"
                                  :value="$product->price_sale"
                                  type="price"
                                  :required="false"
                          />
                        </div>
                        <div class="col-12">
                          <x-SelectHorizontalForm
                                  title="linee"
                                  class="form-group"
                                  :values="$linee"
                                  :defoult="$product->linee"
                                  :required="true"
                                  :type="Null"
                          />
                        </div>

                        <div class="col-12">
                          <x-SelectHorizontalForm
                                  title="collection"
                                  class="form-group"
                                  :values="$collections"
                                  :defoult="$product->collection"
                                  :required="false"
                                  :type="Null"
                          />
                        </div>
                        <div class="col-12">
                          <x-imput_horizontal
                                  title="shelf"
                                  class="form-control"
                                  :value="$product->shelf"
                                  type="text"
                                  :required="false"
                          />
                        </div>
                        <div class="col-12">
                          <x-imput_horizontal
                                  title="stock_qty"
                                  class="form-control"
                                  :value="$product->stock_qty"
                                  type="number"
                                  :required="false"
                          />
                        </div>
                        <div class="col-12">
                          <x-SelectHorizontalForm
                                  title="exclude_stat"
                                  class="form-group"
                                  values=""
                                  :defoult="$product->exclude_stat"
                                  :required="true"
                                  type="Yes_no"
                          />
                        </div>
                        <div class="col-12">
                          <x-SelectHorizontalForm
                                  title="status"
                                  class="form-group"
                                  values=""
                                  :defoult="$product->status"
                                  :required="true"
                                  type="status"
                          />
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">{{__('locale.Save Changes')}}</button>
                <button type="reset" class="btn btn-outline-secondary">{{__('locale.Reset')}}</button>
              </div>
            </div>
          </form>
          <!-- users edit account form ends -->
        </div>
        <!-- Account Tab ends -->

        <!-- Information Tab starts -->
        <div class="tab-pane" id="information" aria-labelledby="information-tab" role="tabpanel">
          <!-- users edit Info form start -->
          <form class="" action="{{route('product-image-store',['id'=>request()->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-3 col-6 mb-1">
                <div class="input-group">
                  <input
                          type="file"
                          class="form-control"
                          placeholder="Image"
                          aria-describedby="button-addon2"
                          name="image"
                  />
                  <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                    <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">{{__('locale.Load')}}</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <!-- users edit Info form ends -->
          <div class="col-md-6">
            <!-- User Permissions -->
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">{{__('locale.Images product')}}</h4>
              </div>

              <div class="table-responsive">
                <table class="table table-striped table-borderless">
                  <thead class="thead-light">
                  <tr>
                    <th></th>
                    <th>Default</th>
                    <th>Ations</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($images as $image)
                    <tr>
                      <td>
                        <div class="avatar-wrapper">
                          <div class=  mr-1">
                            <img src="{{asset('images/product/'.$image->product.'/med_'.$image->name)}}" alt="image" height="100" width="100">
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="admin-read" {{($image->default ? 'checked':'')}} disabled />
                          <label class="custom-control-label" for="admin-read"></label>
                        </div>
                      </td>
                      <td>
                        <a href="javascript:;" class="item-edit">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit font-small-4">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                          </svg>
                        </a>
                        <a href="javascript:;" class="item-edit">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 font-small-4 mr-50">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line>
                          </svg>
                        </a>
                      </td>
                    </tr>
                  @endforeach

                  </tbody>
                </table>
              </div>
            </div>
            <!-- /User Permissions -->
          </div>
        </div>
        <!-- Information Tab ends -->
      </div>
    </div>
  </div>
</section>
<!-- users edit ends -->
@endsection

@section('vendor-script')
  {{-- Vendor js files --}}
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

@endsection

@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset(mix('js/scripts/pages/app-user-edit.js')) }}"></script>
<script>
  (() => {

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation');

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms).forEach((form) => {
      form.addEventListener('submit', (event) => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })();

  $(document).ready(function(){

    $("#linee").change(function(){
      var linea = $(this).val();

      $.ajax({
        url: '{{ route('collection.listView') }}',
        type: 'get',
        data: {linea:linea},
        dataType: 'json',
        success:function(response){
          console.log(response);
          var len = response.length;

          $("#collection").empty();
          $("#collection").append("<option value=''>-- Seleziona Collezione-- </option>");
          for( var i = 0; i<len; i++){
            var id = response[i]['id'];
            var name = response[i]['name'];

            $("#collection").append("<option value='"+id+"'>"+name+"</option>");

          }
        }
      });
    });

  });


</script>
@endsection
