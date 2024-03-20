<div class="row mb-5">
    <div class="col-12 col-xl-2 col-lg-2 mb-3">
      <div class="list-group div-scroll">
        @foreach($folders as $folder)
          <div class="list-group-item small">
            <?php
            \OCMS\LaravelLogViewer\LaravelLogViewer::DirectoryTreeStructure( $storage_path, $structure );
            ?>
          </div>
        @endforeach
        @foreach($files as $file)
          <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"
             class="list-group-item @if ($current_file == $file) llv-active @endif">
            {{$file}}
          </a>
        @endforeach
      </div>
    </div>
    <div class="col-12 col-xl-10 col-lg-10 table-container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ __('menu.system.logs') }}</h5>
                <div class="table-responsive">
                    @if ($logs === null)
                        <div>
                        @lang('log.size_is_too_large')
                        </div>
                    @else
                        <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
                        <thead>
                        <tr>
                            @if ($standardFormat)
                            <th>@lang('log.level')</th>
                            <th>@lang('log.context')</th>
                            <th>@lang('log.date')</th>
                            @else
                            <th>@lang('log.line_number')</th>
                            @endif
                            <th>@lang('log.content')</th>
                        </tr>
                        </thead>
                        <tbody>
            
                        @foreach($logs as $key => $log)
                            <tr data-display="stack{{{$key}}}">
                            @if ($standardFormat)
                                <td class="nowrap text-{{{$log['level_class']}}}">
                                <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                                </td>
                                <td class="text">{{$log['context']}}</td>
                            @endif
                            <td class="date">{{{$log['date']}}}</td>
                            <td class="text">
                                @if ($log['stack'])
                                <button type="button"
                                        class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                                        data-display="stack{{{$key}}}">
                                    <span class="fa fa-search"></span>
                                </button>
                                @endif
                                {{{$log['text']}}}
                                @if (isset($log['in_file']))
                                <br/>{{{$log['in_file']}}}
                                @endif
                                @if ($log['stack'])
                                <div class="stack" id="stack{{{$key}}}"
                                    style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                                </div>
                                @endif
                            </td>
                            </tr>
                        @endforeach
            
                        </tbody>
                        </table>
                    @endif
                    <div class="p-3">
                        @if($current_file)
                        <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                            <span class="fas fa-download"></span> @lang('log.download_file')
                        </a>
                        -
                        <a id="clean-log" href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                            <span class="fas fa-sync"></span> @lang('log.clean_file')
                        </a>
                        -
                        <a id="delete-log" href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                            <span class="fas fa-trash"></span> @lang('log.delete_file')
                        </a>
                        @if(count($files) > 1)
                            -
                            <a id="delete-all-log" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                            <span class="fa fa-trash-alt"></span> @lang('log.delete_all_files')
                            </a>
                        @endif
                        @endif
                    </div>  
                </div>              
            </div>
        </div>
    </div>
</div>