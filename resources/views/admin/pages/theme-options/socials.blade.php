@component('admin.pages.theme-options.layout')
    <x-forms::group
        mode="input"
        label="{{__('Facebook Link')}}"
        placeholder="https://facebook.com/..."
        name="facebook"
        :value="old('facebook', $facebook)"
        required
    />
    <x-forms::group
        mode="input"
        label="{{__('Twitter Link')}}"
        placeholder="https://twitter.com/..."
        name="twitter"
        :value="old('twitter', $twitter)"
        required
    />
    <x-forms::group
        mode="input"
        label="{{__('Instagram Link')}}"
        placeholder="https://instagram.com/..."
        name="instagram"
        :value="old('instagram', $instagram)"
        required
    />
    <x-forms::group
        mode="input"
        label="{{__('Youtube Link')}}"
        placeholder="https://youtube.com/..."
        name="youtube"
        :value="old('youtube', $youtube)"
        required
    />
    <x-forms::group
        mode="input"
        label="{{__('Linkedin Link')}}"
        placeholder="https://linkedin.com/..."
        name="linkedin"
        :value="old('linkedin', $linkedin)"
        required
    />
@endcomponent
