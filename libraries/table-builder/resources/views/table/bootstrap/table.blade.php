<div id="theme-{{$theme}}" class="omc-table" v-bind:class="{initialized: isComponentLoaded === true}">
    @include('omc::table.bootstrap.header')
    @include('omc::table.bootstrap.body')
    @include('omc::table.bootstrap.footer')    
</div>