@php
    $breadcrumb = [['title' => __('menu.system'), 'url' => '#'], ['title' => __('menu.system.information'), 'url' => '#', 'active' => true]];
    $phpinfo = \App\Supports\PhpInfo::get();
    $system  = \OCMS\Larinfo\LarinfoFacade::getServerInfo();
    $database  = \OCMS\Larinfo\LarinfoFacade::getDatabaseInfo();
@endphp

@extends('admin.layouts.master')

@section('title', "System Information")
@section('content')
    <x-breadcrumb :items="$breadcrumb">
        <div class="h4 mb-0">@lang('menu.system.information')</div>
    </x-breadcrumb>
    <div class="row mb-5">
        @include('admin.pages.system.block', [
            'variant' => 'primary',
            'icon' => 'memory',
            'title' => 'RAM',
            'value' => $system['hardware']['ram']['human_total'],
            'sub_value' => 'Free: '.$system['hardware']['ram']['human_free']
        ])
        @include('admin.pages.system.block', [
            'variant' => 'success',
            'icon' => 'microchip',
            'title' => 'CPU',
            'value' => $system['hardware']['cpu_count'].' Core',
            'sub_value' => $system['hardware']['cpu']
        ])
        @include('admin.pages.system.block', [
            'variant' => 'warning',
            'icon' => 'hard-drive',
            'title' => 'Storage',
            'value' => $system['hardware']['disk']['human_total'],
            'sub_value' => 'Free: '.$system['hardware']['disk']['human_free']
        ])
        @include('admin.pages.system.block', [
            'variant' => 'danger',
            'icon' => 'database',
            'title' => 'Database',
            'value' => $database['driver'],
            'sub_value' => $database['version']
        ])
        <div class="col-12 col-xl-8 col-lg-8">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <h5 class="card-title mb-0">Installed Packages</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hovered">
                            <thead>
                                <tr>
                                    <th width="50%" scope="col" class="text-left">Name</th>
                                    <th width="25%" scope="col" class="text-left">Version</th>
                                    <th width="25%" scope="col" class="text-left">Type</th>
                                </tr>
                            </thead>
                        </table>
                        <div style="height: 350px; overflow-y: auto">
                            @foreach(composerPackages() as $package)
                            <div class="d-flex align-items-center">
                                <div class="py-3 px-4 w-50 small">{{$package['name']}}</div>
                                <div class="py-3 px-4 w-25 small">{{$package['version']}}</div>
                                <div class="py-3 px-4 w-25 small">{{$package['type']}}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4 col-lg-4">
            <div class="card">
                <div class="card-body p-3">
                    <h5 class="card-title mb-0">Environment</h5>
                </div>
                <div class="card-body p-0 pb-3">
                    <ul class="list-group" style="height: 378px; overflow-y: auto">
                        <li class="list-group-item list-group-item-action rounded-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>Database</div>
                                <div class="fw-bold">{{ ucfirst(config('database.default')) }}</div>
                            </div>
                        </li>
                        <li class="list-group-item list-group-item-action rounded-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>Timezone</div>
                                <div class="fw-bold">{{ config('app.timezone') }}</div>
                            </div>
                        </li>
                        <li class="list-group-item list-group-item-action rounded-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>Debug Mode</div>
                                @if(config('app.debug') == 1)
                                    <div class="fw-bold text-success"><i class="fas fa-check"></i></div>
                                @else
                                    <div class="fw-bold text-danger"><i class="fas fa-times"></i></div>
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item list-group-item-action rounded-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>Cache driver</div>
                                <div class="fw-bold">{{ ucfirst(config('cache.default')) }}</div>
                            </div>
                        </li>                        
                        <li class="list-group-item list-group-item-action rounded-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>Session driver</div>
                                <div class="fw-bold">{{ ucfirst(config('session.driver')) }}</div>
                            </div>
                        </li>                        
                        <li class="list-group-item list-group-item-action rounded-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>Queue connection</div>
                                <div class="fw-bold">{{ ucfirst(config('queue.default')) }}</div>
                            </div>
                        </li>                        
                        <li class="list-group-item list-group-item-action rounded-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>PHP</div>
                                <div class="fw-bold">{{ $phpinfo['Core']['php-version'] }}</div>
                            </div>
                        </li>                        
                        <li class="list-group-item list-group-item-action rounded-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>Memory limit</div>
                                <div class="fw-bold">{{ $phpinfo['Core']['memory-limit'][0] }}</div>
                            </div>
                        </li>                        
                        <li class="list-group-item list-group-item-action rounded-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>Max execution time</div>
                                <div class="fw-bold">{{ $phpinfo['Core']['max-execution-time'][0] }}</div>
                            </div>
                        </li>                        
                        <li class="list-group-item list-group-item-action rounded-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>Max file uploads</div>
                                <div class="fw-bold">{{ $phpinfo['Core']['max-file-uploads'][0] }}</div>
                            </div>
                        </li>                        
                        <li class="list-group-item list-group-item-action rounded-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>Post max size</div>
                                <div class="fw-bold">{{ $phpinfo['Core']['post-max-size'][0] }}</div>
                            </div>
                        </li>          
                        @include('admin.pages.system.list_item_check', [
                            'title' => "OpenSSL Extension",
                            'enabled' => (isset($phpinfo['openssl']) ? $phpinfo['openssl']['openssl-support'] : null) == 'enabled'
                        ])                                     
                        @include('admin.pages.system.list_item_check', [
                            'title' => "Mbstring Extension",
                            'enabled' => (isset($phpinfo['mbstring']) ? $phpinfo['mbstring']['multibyte-support'] : null) == 'enabled'
                        ])                                                           
                        @include('admin.pages.system.list_item_check', [
                            'title' => "cURL Extension",
                            'enabled' => (isset($phpinfo['curl']) ? $phpinfo['curl']['curl-support'] : null) == 'enabled'
                        ])                                                                                  
                        @include('admin.pages.system.list_item_check', [
                            'title' => "EXIF Extension",
                            'enabled' => (isset($phpinfo['exif']) ? $phpinfo['exif']['exif-support'] : null) == 'enabled'
                        ])                                                                                                                                
                        @include('admin.pages.system.list_item_check', [
                            'title' => "FileInfo Extension",
                            'enabled' => (isset($phpinfo['tokenizer']) ? $phpinfo['fileinfo']['fileinfo-support'] : null) == 'enabled'
                        ])                                                                                                                                
                        @include('admin.pages.system.list_item_check', [
                            'title' => "Tokenizer Extension",
                            'enabled' => (isset($phpinfo['tokenizer']) ? $phpinfo['tokenizer']['tokenizer-support'] : null) == 'enabled'
                        ])                                                                                                                                
                        @include('admin.pages.system.list_item_check', [
                            'title' => "Imagick Extension",
                            'enabled' => (isset($phpinfo['imagick']) ? $phpinfo['imagick']['imagick-module'] : null) == 'enabled'
                        ])                                                                                                                                
                        @include('admin.pages.system.list_item_check', [
                            'title' => "Zip Extension",
                            'enabled' => (isset($phpinfo['zip']) ? $phpinfo['zip']['zip'] : null) == 'enabled'
                        ])                                                                                                                                
                        @include('admin.pages.system.list_item_check', [
                            'title' => "Sodium Extension",
                            'enabled' => (isset($phpinfo['sodium']) ? $phpinfo['sodium']['sodium-support'] : null) == 'enabled'
                        ])                                                                                                                                
                    </ul>
                </div>
            </div>            
        </div>
    </div>
@endsection