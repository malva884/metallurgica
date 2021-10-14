@extends('layouts/fullLayoutMaster')

@section('title', 'Invoice Print')

@section('vendor-style')

  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">

@endsection
@section('page-style')

@endsection

@section('content')
  <section class="invoice-add-wrapper" >
    <div class="row invoice-add" >

      <!-- Invoice Add Left starts -->
      <div class="col-xl-12 col-md-12 col-12" >
        <?php $page_numero =1;?>
        @foreach($pages as $page)
          <div class="documents" style=" height: 1530px;">
            <div class="card invoice-preview-card origin">
              <!-- Header starts -->

              <div class="card-body invoice-padding  pb-0" style="height: 200px;">
                <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                  <div class="table-responsive">
                    <div class="row">
                      <div class="col-12" style="text-align: center">
                        <img
                                class="img-fluid rounded"
                                src="{{asset('/images/logo/stl.png')}}"
                                height="104"
                                width="104"
                                alt="User avatar"
                        />
                        <p class="font-weight-bolder" style="display: inline; font-size: 45px;">
                          &nbsp;&nbsp;Metallutgica Bresciana S.p.a.</p>
                      </div>
                    </div>

                    <table class="table table-bordered ">
                      <tbody>
                      <tr>
                        <td>
                          <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-12 col-form-label">s.p.aTechnical
                              Dept.</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-12 col-form-label">TECHNICAL
                              DATA SHEET</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-5 col-form-label">Specification
                              N°</label>
                            <div class="col-sm-6">
                              <p class="form-control-static"
                                 id="staticInput">{{$document->specific_number}}</p>
                            </div>
                          </div>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- Header ends -->

              <hr class="invoice-spacing"/>

              <div class="row" >
                <div class="col-sm-11" style="display: inline; height: 1050px;">
                  <div id="full-wrapper">
                    <div id="full-container">
                      <?php echo html_entity_decode($page->text); ?>
                    </div>
                  </div>
                </div>
              </div>
              <hr class="invoice-spacing mt-0"/>
              <div class="card-body invoice-padding py-0">
                <!-- Invoice Note starts -->
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table class="table table-bordered ">
                        <tbody>
                        <tr>
                          <td>

                          </td>
                          <td>

                          </td>
                          <td>

                          </td>
                          <td>

                          </td>
                          <td>

                          </td>
                          <td>

                          </td>
                          <td>
                            CABLE CODE
                          </td>
                        </tr>

                        @foreach($rows as $row)
                          <tr>
                            <td>
                              {{$row['row']}}
                            </td>
                            <td>
                              {{$row['Description']}}
                            </td>
                            <td>
                              {{$row['Date']}}
                            </td>
                            <td>
                              {{$row['Drawn up']}}
                            </td>
                            <td>
                              {{$row['Controlled']}}
                            </td>
                            <td>
                              {{$row['Seen']}}
                            </td>
                            <td>
                              {{$row['Sheet']}}
                            </td>
                          </tr>

                        @endforeach
                        </tbody>
                        <tfoot>
                        <th>Rev.</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Drawn up</th>
                        <th>Controlled</th>
                        <th>Seen</th>
                        <th>Sheet {{$page_numero}} of {{$page->count()-1}}</th>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- Invoice Note ends -->
              </div>
            </div>
          </div>
          <?php $page_numero++;?>
        @endforeach
      </div>
      <!-- Invoice Add Left ends -->


    </div>
  </section>


@endsection

@section('vendor-script')
  <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
  <script src="{{asset('js/scripts/pages/app-invoice-print.js')}}"></script>
  <script>
    $('*[media="screen"]').attr('media', '');$('*[media="print"]').remove();
  </script>
@endsection

@section('page-script')

  <style>
    .ql-editor {
      height: 1140px;
      max-height: 1140px;
      min-height:1140px;
    }
    html .content.app-content {
       padding: 0 !important;
    }

    .table-responsive {
      display: table;
    }
  </style>
@endsection

