<!-- form start -->
{!! $form->open() !!}
@csrf
<div class="row">
    <div class="col-12 col-xl-9 col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __($form->title()) }}</h5>
                    @if($form->canPreview())
                        <span class="text-info fw-bolder" style="cursor: pointer; text-decoration: underline" id="preview-link">
                            {{__('messages.preview')}}
                        </span>
                    @endif
                </div>
                @if ($form->hasRows())
                    @foreach ($form->getRows() as $row)
                        {!! $row->render() !!}
                    @endforeach
                @else
                    @foreach ($layout->columns() as $column)
                        @foreach ($column->fields() as $field)
                            {!! $field->render() !!}
                        @endforeach
                    @endforeach
                @endif
                @if ($layout->customContent())
                    <div class="mt-2">
                        {!! $layout->customContent() !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-3 col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <div class="fw-bold">{{__('messages.actions')}}</div>
            </div>
            <div class="card-body d-flex align-items-center">
                @php
                    $paths = request()->segments();

                    if($form->isEditing()) {
                        $paths = array_slice($paths, 0, count($paths) - 2);
                    } else {
                        $paths = array_slice($paths, 0, count($paths) - 1);
                    }

                    $previous_url = sprintf("/%s",implode("/", $paths));
                @endphp
                <a href="{{$previous_url}}" class="btn btn-dark border me-2" data-loading-text="none">
                    <span class="icon icon-xs me-1">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                    <span>@lang('messages.back')</span>
                </a>
                <button type="submit" name="after-save" value="1" class="btn btn-info" data-loading-text="none" @if(config('admin.is_demo')) disabled @endif>
                    <span class="icon icon-xs me-1">
                        <i class="fas fa-edit"></i>
                    </span>
                    <span>@lang('messages.save_and_edit')</span>
                </button>
            </div>
        </div>
        {!! $tools !!}
    </div>
</div>

@foreach ($form->getHiddenFields() as $field)
    {!! $field->render() !!}
@endforeach

{!! $form->close() !!}
