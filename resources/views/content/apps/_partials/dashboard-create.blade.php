<div class="card card-statistics">
    <div class="card-header">
        <h4 class="card-title">Riepilogo Commesse / Conf. Ordine</h4>
    </div>
    <div class="card-body statistics-body">
        <div class="row">
            <div class="col-xl-6 col-sm-6 col-6 mb-2 mb-xl-0">
                <div class="d-flex flex-row">
                    <div class="avatar bg-light-secondary me-2">
                        <div class="avatar-content">
                            <i data-feather="mail" class="avatar-icon"></i>
                        </div>
                    </div>
                    <div class="my-auto">
                        <h4 class="fw-bolder mb-0">&nbsp; {{$workflowProcessing}}</h4>
                        <a href="{{route('variation.index')}}"><p class="card-text font-small-3 mb-0">&nbsp;In lavorazione</p></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-6">
                <div class="d-flex flex-row">
                    <div class="avatar bg-light-primary me-2">
                        <div class="avatar-content">
                            <i data-feather="mail" class="avatar-icon"></i>
                        </div>
                    </div>
                    <div class="my-auto">
                        <h4 class="fw-bolder mb-0">&nbsp; {{$workflowCompleted}}</h4>
                        <a href="{{route('variation.index')}}"><p class="card-text font-small-3 mb-0">&nbsp;Completati</p></a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="card-header">
        <h4 class="card-title">Riepilogo Variazioni</h4>
    </div>
    <div class="card-body statistics-body">
        <div class="row">
            <div class="col-xl-6 col-sm-6 col-6 mb-2 mb-xl-0">
                <div class="d-flex flex-row">
                    <div class="avatar bg-light-secondary me-2">
                        <div class="avatar-content">
                            <i data-feather="mail" class="avatar-icon"></i>
                        </div>
                    </div>
                    <div class="my-auto">
                        <h4 class="fw-bolder mb-0">&nbsp; {{$variationProcessing}}</h4>
                        <a href="{{route('variation.index')}}"><p class="card-text font-small-3 mb-0">&nbsp;In lavorazione</p></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-6">
                <div class="d-flex flex-row">
                    <div class="avatar bg-light-primary me-2">
                        <div class="avatar-content">
                            <i data-feather="mail" class="avatar-icon"></i>
                        </div>
                    </div>
                    <div class="my-auto">
                        <h4 class="fw-bolder mb-0">&nbsp; {{$variationCompleted}}</h4>
                        <a href="{{route('variation.index')}}"><p class="card-text font-small-3 mb-0">&nbsp;Completati</p></a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
