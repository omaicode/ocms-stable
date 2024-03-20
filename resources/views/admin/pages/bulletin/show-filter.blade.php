@php
    $req_order_by = request()->get('order_by', 'id');
    $req_order_dir = request()->get('order_dir', 'desc');
    $req_status = request()->get('status', 'all');
    $req_search = request()->get('search');
@endphp
<div class="col-12 col-xl-3 col-lg-4">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fas fa-filter"></i>
                {{__('messages.filter')}}
            </h5>
            <form method="GET">
                <input type="hidden" name="page" value="{{ request()->get('page', 1) }}">
                <x-forms::group
                    mode="select"
                    name="order_by"
                    :label="__('messages.order_by')"

                >
                    <option value="id" @if($req_order_by === 'id') selected @endif>ID</option>
                    <option value="title" @if($req_order_by === 'title') selected @endif>{{__('messages.title')}}</option>
                    <option value="created_at" @if($req_order_by === 'created_at') selected @endif>{{__('messages.created_date')}}</option>
                    <option value="total_read" @if($req_order_by === 'total_read') selected @endif>{{__('messages.total_read')}}</option>
                    <option value="total_like" @if($req_order_by === 'total_like') selected @endif>{{__('messages.total_like')}}</option>
                    <option value="total_comment" @if($req_order_by === 'total_comment') selected @endif>{{__('messages.total_comment')}}</option>
                </x-forms::group>
                <x-forms::group
                    mode="select"
                    name="order_dir"
                    :label="__('messages.order_direction')"

                >
                    <option value="asc" @if($req_order_dir === 'asc') selected @endif>ASC</option>
                    <option value="desc" @if($req_order_dir === 'desc') selected @endif>
                        DESC
                    </option>
                </x-forms::group>
                <x-forms::group
                    mode="select"
                    name="status"
                    :label="__('messages.status')"
                >
                    <option value="all" @if($req_status === 'all') selected @endif>{{__('messages.all')}}</option>
                    @foreach(\App\Enums\PostStatusEnum::asSelectArray() as $val => $name)
                        <option value="{{$val}}" @if($req_status === $val) selected @endif>{{$name}}</option>
                    @endforeach
                </x-forms::group>
                <x-forms::group
                    mode="input"
                    name="search"
                    :label="__('messages.search')"
                    :placeholder="__('messages.search_by_title_or_id')"
                    value="{{$req_search}}"
                />
                <button type="submit" class="btn btn-success text-white w-100 mb-3">
                    <i class="fas fa-filter"></i>
                    {{__('messages.apply')}}
                </button>
                <a href="{{route('admin.bulletin.boards.show', $board->id)}}" class="btn btn-outline-gray-600 w-100">
                    <i class="fas fa-refresh"></i>
                    {{__('messages.clear')}}
                </a>
            </form>
        </div>
    </div>
</div>
