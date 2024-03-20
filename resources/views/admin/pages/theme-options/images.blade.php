@component('admin.pages.theme-options.layout')
<x-forms::thumbnail
    label="{{__('Logo')}}"
    name="logo"
    :value="strlen($logo) > 0 ? uploadPath($logo) : ''"
/>
<x-forms::thumbnail
    label="{{__('Logo Light')}}"
    name="logo_light"
    :value="strlen($logo_light) > 0 ? uploadPath($logo_light) : ''"
/>
<x-forms::thumbnail
    label="{{__('Favicon')}}"
    name="favicon"
    :value="strlen($favicon) > 0 ? uploadPath($favicon) : ''"
/>
<x-forms::thumbnail
    label={{__('Page Background')}}
    name="page_background"
    :value="strlen($page_background) > 0 ? uploadPath($page_background) : ''"
/>
<x-forms::thumbnail
    label="{{__('Title Background')}}"
    name="title_background"
    :value="strlen($title_background) > 0 ? uploadPath($title_background) : ''"
/>
<x-forms::thumbnail
    label="{{__('Thumbnail Background')}}"
    name="thumbnail_background"
    :value="strlen($thumbnail_background) > 0 ? uploadPath($thumbnail_background) : ''"
/>
<x-forms::thumbnail
    label="{{__('Footer Background')}}"
    name="footer_background"
    :value="strlen($footer_background) > 0 ? uploadPath($footer_background) : ''"
/>
@endcomponent
