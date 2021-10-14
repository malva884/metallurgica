@extends('layouts/contentLayoutMaster')

@section('title', 'Invoice Preview')

@section('vendor-style')

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">

@endsection
@section('page-style')
    <link rel="stylesheet" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice.css')}}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-quill-editor.css')) }}">
@endsection

@section('content')
    <section class="invoice-add-wrapper">
        <div class="row invoice-add">

            <!-- Invoice Add Left starts -->
            <div class="col-xl-9 col-md-8 col-12">
                <?php $page_numero= 1; ?>
                @foreach($pages as $page)
                    <div class="documents">
                        <div class="card invoice-preview-card origin">
                            <!-- Header starts -->
                            <div class="card-body invoice-padding  pb-0">
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

                            <div class="row">
                                <div class="col-sm-11" style="display: inline;margin-left: 5%;">
                                    <div id="full-wrapper">
                                        <div id="full-container">
                                            <?php echo $page->text; ?>
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
                                                    <th>Sheet {{$page_numero}} of {{$pages->count()}}</th>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Invoice Note ends -->
                            </div>
                        </div>
                    </div>
                    <?php $page_numero++; ?>
                @endforeach
            </div>
            <!-- Invoice Add Left ends -->

            <!-- Invoice Add Right starts -->
            <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                <div class="card">
                    <div class="card-body">
                        <a class="btn btn-primary btn-block mb-75" href="">
                            Start Work-flow
                        </a>
                        <a class="btn btn-outline-warning btn-block mb-75" href="{{url('document/print',['id'=> $document->id])}}"
                           target="_blank">
                            Print
                        </a>
                        <a class="btn btn-outline-success btn-block mb-75" href="{{url('document/edit',['id'=> $document->id])}}">
                            Edit </a>
                        <a class="btn btn-outline-info btn-block mb-75" href="{{url('document/clone',['id'=> $document->id])}}">
                            Clone
                        </a>
                        <a class="btn btn-success btn-block" href="{{url('document/revision',['id'=> $document->id])}}">
                            Revisiona
                        </a>
                    </div>
                </div>
            </div>
            <!-- Invoice Add Right ends -->
        </div>
    </section>


@endsection

@section('vendor-script')
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@endsection

@section('page-script')

    <script>
        function workflow($id) {
            $.ajax({
                type: "POST",
                url: '{{route('document.workflow')}}',
                data: {
                    "id": '{{app('request')->id}}',
                    "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                    if(data.code === 100)
                        alert("Errore: Contattare l'administrator (Cod. Errore: '100')");
                }
            });
        }
    </script>
    <style>
        .ql-editor {
            height: 950px;
            max-height: 950px;
            min-height:950px;
        }

        .table-responsive {
            display: table;
        }
    </style>
@endsection

