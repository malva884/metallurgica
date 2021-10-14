@extends('layouts/contentLayoutMaster')

@section('title', 'Form Layouts')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')) }}">
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
    @endif

    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Nuovo Consultivo</h4>
                    </div>
                    <div class="card-body">
                        <form class="form form-horizontal" method="POST" action="{{route('advisory.load')}}" enctype="multipart/form-data" id="my_form">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label for="first-name">Lavorazione</label>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <div class="custom-file">
                                                    <select class="form-control" id="lavorazione" name="lavorazione" required>
                                                        <option value="">-- Seleziona Lavorazione --</option>
                                                            <option value="rm">Rame</option>
                                                            <option value="ot">Ottico</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label for="first-name">Costo Macchinari Del</label>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <div class="custom-file">
                                                    <select class="form-control" id="data_macchinari" name="data_macchinari" required>
                                                        <option value="">-- Seleziona Data Materiali --</option>
                                                        @foreach($macchinari as $data)
                                                            <option value="{{$data->date_import}}" {{($data->date_import == date('Y-m-d') ? 'selected':'')}}>{{$data->date_import}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 ">
                                            <a href="{{route('workstatus.setting.costs')}}" class="btn btn-flat-danger waves-effect mr-1">Import</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label for="first-name">Costo Materiali Del</label>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <div class="custom-file">
                                                    <select class="form-control" id="data_matariali" name="data_matariali" required>
                                                        <option value="">-- Seleziona Data Materiali --</option>
                                                        @foreach($matariali as $materiali)
                                                            <option value="{{$materiali->date_import}}" {{($materiali->date_import == date('Y-m-d') ? 'selected':'')}}>{{$materiali->date_import}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 ">
                                            <a href="{{route('workstatus.setting.costs')}}" class="btn btn-flat-danger waves-effect mr-1">Import</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label for="first-name">File Da Elaborare *</label>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="file" name="file" required/>
                                                    <label class="custom-file-label" for="customFile">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 ">
                                            <button type="submit" class="btn btn-primary mr-1">Import</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="loading">
        <div id="loading-content"></div>
    </section>
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')) }}"></script>
@endsection

@section('page-script')
    <!-- Page js files -->

    <script>
        $("#my_form").submit(function(event){
            event.preventDefault(); //prevent default action
            var post_url = $(this).attr("action"); //get form action url
            var request_method = $(this).attr("method"); //get form GET/POST method
            var formData = new FormData(this);

            $.ajax({
                url : post_url,
                type: request_method,
                data : formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    showLoading();
                },
                complete: function(){
                    hideLoading();
                    window.location.href = "{{ route('advisorys.index')}}";
                },
                success: function() {}

            });
        });

        function showLoading() {
            document.querySelector('#loading').classList.add('loading');
            document.querySelector('#loading-content').classList.add('loading-content');
        }

        function hideLoading() {
            document.querySelector('#loading').classList.remove('loading');
            document.querySelector('#loading-content').classList.remove('loading-content');
        }
    </script>

    <style>
        .loading {
            z-index: 20;
            position: absolute;
            top: 0;
            left:-5px;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .loading-content {
            position: absolute;
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            top: 40%;
            left:35%;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

@endsection
