<div class="card card-statistics">
    <div class="card-header">
        <h4 class="card-title">Ducumenti Da Firmare</h4>
    </div>
    <div class="card-body statistics-body">
        <div class="row">
            @if($commessa)
            <div class="col-xl-3 col-sm-3 col-12 mb-2 mb-xl-0">
                <div class="d-flex flex-row">
                    <div class="avatar bg-light-dark me-2">
                        <div class="avatar-content">
                            <i data-feather="mail" class="avatar-icon"></i>
                        </div>
                    </div>
                    <div class="my-auto">
                        <h4 class="fw-bolder mb-0">&nbsp; {{$commessa}}</h4>
                        <a href="{{route('workflow.index')}}"><p class="card-text font-small-3 mb-0">&nbsp;Commesse</p></a>
                    </div>
                </div>
            </div>
            @endif
            @if($confermeOrdeine)
            <div class="col-xl-3 col-sm-3 col-12">
                <div class="d-flex flex-row">
                    <div class="avatar bg-light-warning me-2">
                        <div class="avatar-content">
                            <i data-feather="mail" class="avatar-icon"></i>
                        </div>
                    </div>
                    <div class="my-auto">
                        <h4 class="fw-bolder mb-0">&nbsp; {{$confermeOrdeine}}</h4>
                        <a href="{{route('workflow.index')}}"><p class="card-text font-small-3 mb-0">&nbsp;Conf. Ordine</p></a>
                    </div>
                </div>
            </div>
            @endif
            @if($revisioni)
            <div class="col-xl-3 col-sm-3 col-12">
                <div class="d-flex flex-row">
                    <div class="avatar bg-light-danger me-2">
                        <div class="avatar-content">
                            <i data-feather="mail" class="avatar-icon"></i>
                        </div>
                    </div>
                    <div class="my-auto">
                        <h4 class="fw-bolder mb-0">&nbsp; {{$revisioni}}</h4>
                        <a href="{{route('workflow.index')}}"><p class="card-text font-small-3 mb-0">&nbsp;Revisioni</p></a>
                    </div>
                </div>
            </div>
            @endif
            @if($variationApproval)
            <div class="col-xl-3 col-sm-2 col-12">
                <div class="d-flex flex-row">
                    <div class="avatar bg-light-danger me-2">
                        <div class="avatar-content">
                            <i data-feather="mail" class="avatar-icon"></i>
                        </div>
                    </div>
                    <div class="my-auto">
                        <h4 class="fw-bolder mb-0">&nbsp; {{$variationApproval}}</h4>
                        <a href="{{route('variation.index')}}"><p class="card-text font-small-3 mb-0">&nbsp;Variazioni</p></a>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>