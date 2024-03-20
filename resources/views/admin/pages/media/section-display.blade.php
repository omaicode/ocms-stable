@php
    $tabs = [
        ['icon' => 'fa-th-list', 'title' => __('All'), 'value' => "all"],
        ['icon' => 'fa-image', 'title' => __('Images'), 'value' => "images"],
        ['icon' => 'fa-video', 'title' => __('Video'), 'value' => "video"],
        ['icon' => 'fa-volume-up', 'title' => __('Audio'), 'value' => "audio"],
        ['icon' => 'fa-file', 'title' => __('Documents'), 'value' => "documents"],
    ];
@endphp
<div class="media-tab-display--title">
    <div class="media-tab-display">
        <div class="text-gray-400 font-bold p-3">{{__('Display')}}</div>
        <ul class="display-list">
            @foreach($tabs as $tab)
                <li
                    class="display-list--item tab-item @if($loop->first) active @endif"
                    data-tab-id="{{$tab['value']}}"
                    data-type="media-button"
                    data-trigger="changeDisplay"
                >
                    <span class="icon"><i class="fas {{$tab['icon']}}"></i></span>
                    <span class="title">{{$tab['title']}}</span>
                </li>
            @endforeach
        </ul>
        <div class="form-group px-3 pb-3 pt-0">
            <label class="text-gray-400 fw-normal" for="order_by">{{__('Order by')}}</label>
            <select class="form-select form-select-sm" id="mediaOrderBy">
                <option value="title">{{__('File Title')}}</option>
                <option value="size">{{__('Size')}}</option>
                <option value="last_modified">{{__('Last Modified')}}</option>
            </select>
        </div>
        <div class="form-group px-3 pb-3 pt-0">
            <label class="text-gray-400 fw-normal" for="order_by">{{__('Direction')}}</label>
            <select class="form-select form-select-sm" id="mediaOrderDirection">
                <option value="asc">{{__('Ascending')}}</option>
                <option value="desc">{{__('Descending')}}</option>
            </select>
        </div>
    </div>
</div>
