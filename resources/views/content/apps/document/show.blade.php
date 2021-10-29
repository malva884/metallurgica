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
            <div class="col-xl-2 col-md-4 col-12 invoice-actions mt-md-0 mt-2"></div>
            <!-- Invoice Add Left starts -->
            <div style="width: 793px;">
                <?php $page_numero = 1; ?>
                @foreach($pages as $page)
                    <div class="documents">
                        <div class="card invoice-preview-card origin mt-0">
                            <!-- Header starts -->
                            <div class="card-body invoice-padding  pb-0">
                                <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                    <div class="table-responsive mt-0">
                                        <div class="row mt-0 py-0">
                                            <div class="col-12 mt-0 py-0" style="text-align: center ">
                                                <img
                                                        class="img-fluid rounded"
                                                        src="{{asset('/images/logo/stl.png')}}"
                                                        height="60"
                                                        width="60"
                                                />
                                                <p class="font-weight-bolder mt-0 py-0"
                                                   style="font-family:'Palace Script MT', sans-serif; font-weight: bold; display: inline; font-size: 50px;">
                                                    &nbsp;&nbsp;Metallutgica Bresciana s.p.a.</p>
                                            </div>
                                        </div>

                                        <table class="table table-bordered mt-0">
                                            <tbody>
                                            <tr style=" height: 15px;">
                                                <td style="font-size: 10px; ">

                                                    <label>Technical
                                                        Dept.</label>

                                                </td>
                                                <td style="font-size: 10px;">

                                                    <label>TECHNICAL
                                                        DATA SHEET</label>

                                                </td>
                                                <td style="font-size: 10px;">
                                                    <p class="form-control-static"
                                                       id="staticInput">Specification
                                                        N° {{$document->specific_number}}</p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Header ends -->

                            <div class="row py-0" style="height: 850px; ">
                                <div class="col-sm-11 py-0" style="display: inline;margin-left: 5%;">
                                    <div id="full-wrapper">
                                        <div id="full-container">
                                            <?php echo $page->text; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body invoice-padding py-0">
                                <!-- Invoice Note starts -->
                                <div class="row">
                                    <div style="width: 850px;">
                                        <div class="table-responsive">
                                            <table class="table table-bordered " style="width: 750px;">
                                                <tbody>


                                                @foreach($rows as $row)
                                                    <tr>
                                                        <td style="padding: 0.5rem; text-align: center">
                                                            {{$row['row']}}
                                                        </td>
                                                        <td style="padding: 0.5rem; text-align: center">
                                                            {{$row['Description']}}
                                                        </td>
                                                        <td style="padding: 0.5rem; text-align: center">
                                                            {{$row['Date']}}
                                                        </td>
                                                        <td style="padding: 0rem; text-align: center">
                                                            @if(!empty($row['Drawn up']))
                                                                <img
                                                                        src="{{asset('/images/users/'.$row['Drawn up'])}}"
                                                                        alt=""
                                                                        class="user-avatar users-avatar-shadow rounded mr-0 my-0 cursor-pointer"
                                                                        height="20"
                                                                        width="50"
                                                                />
                                                            @endif
                                                        </td>
                                                        <td style="padding: 0rem; text-align: center">
                                                            @if(!empty($row['Controlled']))
                                                                <img
                                                                        src="{{asset('/images/users/'.$row['Controlled'])}}"
                                                                        alt=""
                                                                        class="user-avatar users-avatar-shadow rounded mr-0 my-0 cursor-pointer"
                                                                        height="20"
                                                                        width="50"
                                                                />
                                                            @endif

                                                        </td>
                                                        <td style="padding: 0rem; text-align: center">
                                                            @if(!empty($row['See']))
                                                                <img
                                                                        src="{{asset('/images/users/'.$row['See'])}}"
                                                                        alt=""
                                                                        class="user-avatar users-avatar-shadow rounded mr-0 my-0 cursor-pointer"
                                                                        height="20"
                                                                        width="50"
                                                                />
                                                            @endif

                                                        </td>
                                                        <td style="padding: 0rem; text-align: center">
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
            <div class="col-xl-3 col-md-3 col-12 invoice-actions mt-md-0 mt-2">
                <div class="card">
                    <div class="card-body">
                        @if((auth()->user()->can('documents_create') || auth()->user()->hasanyrole('super-admin')) && $document->status == 1)
                            <a class="btn btn-primary btn-block mb-75" href="#" onclick="workflow()">
                                Start Work-flow
                            </a>
                        @endif
                        @if((auth()->user()->can('documents_create') || auth()->user()->can('documents_see') || auth()->user()->can('documents_check')) && $userApproval)
                            <a class="btn btn-primary btn-block mb-75" href="#" onclick="workflow()">
                                Approva
                            </a>
                        @endif
                        @if(auth()->user()->can('documents_view') || auth()->user()->hasanyrole('super-admin'))
                            <a class="btn btn-outline-warning btn-block mb-75"
                               href="{{url('document/print',['id'=> $document->id])}}"
                               target="_blank">
                                Print
                            </a>
                        @endif
                        @if(auth()->user()->can('documents_create') || auth()->user()->hasanyrole('super-admin'))
                            @if((auth()->user()->can('documents_create') || auth()->user()->hasanyrole('super-admin')) && $document->status == 1)
                                <a class="btn btn-outline-success btn-block mb-75"
                                   href="{{url('document/edit',['id'=> $document->id])}}">
                                    Edit </a>
                            @endif
                            <a class="btn btn-outline-info btn-block mb-75"
                               href="{{url('document/clone',['id'=> $document->id])}}">
                                Clone
                            </a>
                            @if((auth()->user()->can('documents_create') || auth()->user()->hasanyrole('super-admin')) && $document->status == 3)
                                <a class="btn btn-success btn-block"
                                   href="{{url('document/revision',['id'=> $document->id])}}">
                                    Revisiona
                                </a>
                            @endif
                        @endif
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
                    if (data.code === 100)
                        alert("Errore: Contattare l'administrator (Cod. Errore: '100')");
                    else
                        window.location = "/document/index";
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    </script>
    <style>

        .table-responsive {
            display: table;
        }

        .table tbody tr td {
            font-size: 8px;
            border: ridge #000 1px !important;
        }

        th {
            font-family: "Trebuchet MS", Arial, Verdana;
            font-size: 8px !important;
            padding: 5px;
            border: ridge #000 1px !important;
        }

    </style>
@endsection

