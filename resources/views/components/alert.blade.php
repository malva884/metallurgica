<section id="alerts-closable">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div >
                    <div class="demo-spacing-0">
                        @foreach ($errors->all() as $error)

                            <div class="alert alert-{{$color}} alert-dismissible fade show" role="alert">
                                <div class="alert-body">
                                    {{$error}}
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endforeach
                        @if(!is_array($message))
                            <div class="alert alert-{{$color}} alert-dismissible fade show" role="alert">
                                <div class="alert-body">
                                    {{$message}}
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>