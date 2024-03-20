@component('admin.pages.theme-options.layout')
<x-forms::group
    mode="input"
    :label="__('messages.form.seo_title')"
    name="seo_title"
    :value="old('seo_title', @$seo_title)"
/>
<x-forms::group
    mode="textarea"
    :label="__('messages.form.seo_description')"
    name="seo_description"
    :value="old('seo_description', @$seo_description)"
/>
<x-forms::thumbnail
    :label="__('messages.form.seo_og_image')"
    name="seo_og_image"            
    :value="isset($seo_og_image) ? uploadPath($seo_og_image) : ''"                       
/>
@endcomponent