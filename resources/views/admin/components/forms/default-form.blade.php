<form class="@if($errors->any()) was-validated @endif" action="{{ $action }}" method="{{ $method }}" {{ $multipart ? 'enctype=multipart/form-data' : ''}}>
    @csrf
    <div class="row">
        <div class="col-12 col-xl-9 col-lg-8">
            {{ $slot }}
        </div>
        <div class="col-12 col-xl-3 col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="fw-bold">{{__('management')}}</div>
                </div>
                <div class="card-body d-flex align-items-center">
                    <button type="submit" name="after-save" value="0" class="btn btn-success text-white me-2" data-loading-text="none">
                        <span class="icon icon-xs me-1">
                            <i class="fas fa-save"></i>
                        </span>
                        <span>@lang('messages.save')</span>
                    </button>
                    <button type="submit" name="after-save" value="1" class="btn btn-info" data-loading-text="none">
                        <span class="icon icon-xs me-1">
                            <i class="fas fa-edit"></i>
                        </span>
                        <span>@lang('messages.save_and_edit')</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
