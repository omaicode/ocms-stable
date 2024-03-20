<div class="col-12 col-sm-6 col-xl-3 mb-4">
    <div class="card border-0 shadow">
        <div class="card-body">
            <div class="row d-block d-xl-flex align-items-center">
                <div class="col-12 col-xl-4 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                    <div class="icon-shape icon-shape-{{$variant}} rounded me-4 me-sm-0">
                        <i class="fas fa-{{$icon}} icon"></i>
                    </div>
                    <div class="d-sm-none">
                        <h2 class="h5">{{$title}}</h2>
                        <h3 class="fw-extrabold mb-1">{{$value}}</h3>
                    </div>
                </div>
                <div class="col-12 col-xl-8 px-xl-0">
                    <div class="d-none d-sm-block">
                        <h2 class="h6 text-gray-400 mb-0">{{$title}}</h2>
                        <h3 class="fw-extrabold mb-0">{{$value}}</h3>
                    </div>
                    <small class="d-block align-items-center text-gray-500 text-truncate">
                        {{$sub_value}}
                    </small> 
                </div>
            </div>
        </div>
    </div>
</div>