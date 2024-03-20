<div class="media-tab-grid">
    <div class="media-tab-grid--list">
        @include('admin.pages.media.media-list')
    </div>
    <div class="media-tab-grid--detail">
        <div class="detail-empty" id="emptySelected">
            <div class="text-center">
                <img src="{{ adminAsset('vendors/media/img/select-empty.png') }}" height="48" width="48">
                <div class="mt-2 small text-gray-400">{{__('Nothing is selected')}}</div>
            </div>
        </div>
        <div class="detail-empty" id="multipleSelected" style="display: none">
            <div class="text-center">
                <img src="{{ adminAsset('vendors/media/img/select-empty.png') }}" height="48" width="48">
                <div class="mt-2 small text-gray-400">{{__('Multiple items selected')}}</div>
            </div>
        </div>
        <div class="detail-info" id="fileSelected" style="display: none">
            <div data-label="thumbnail" class="thumbnail" style="display: none">
                <img src="#">
            </div>
            <label>{{__('File Title')}}</label>
            <p data-label="title">item.name</p>
            <label>{{__('Last modifed at')}}</label>
            <p data-label="last_modified_at">item.modified_at</p>
            <label v-if="!selectedItem.is_dir">URL</label>
            <p data-label="url">
                <a href="#" target="_blank" class="text-decoration-underline">{{__("Click here")}}</a>
            </p>
            <div class="mt-3" id="mediaSelectable" style="display: none">
                <button type="button" class="btn btn-success w-100">
                    {{__('Select')}}
                </button>
            </div>
        </div>
    </div>
</div>
