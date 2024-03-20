@component('admin.pages.theme-options.layout')
    <x-forms::group
        mode="input"
        :label="__('messages.form.site_name')"
        :placeholder="__('messages.form.site_name_placeholder')"
        name="site_name"
        :value="old('site_name', $site_name)"
        required
    />
    <x-forms::group
        mode="input"
        :label="__('messages.form.site_title')"
        :placeholder="__('messages.form.site_title_placeholder')"
        name="site_title"
        :value="old('site_title', $site_title)"
        required
    />
    <x-forms::group
        mode="textarea"
        :label="__('messages.form.site_description')"
        :placeholder="__('messages.form.site_description_placeholder')"
        name="site_description"
        :value="old('site_description', $site_description)"
    />
    <x-forms::group
        mode="textarea"
        :label="__('messages.form.site_keywords')"
        :placeholder="__('messages.form.site_keywords_placeholder')"
        name="site_keywords"
        :value="old('site_keywords', $site_keywords)"
    />
    <x-forms::group
        mode="input"
        :label="__('messages.form.address')"
        name="address"
        :value="old('address', $address)"
    />
    <x-forms::group
        mode="input"
        :label="__('messages.form.website')"
        name="website"
        :value="old('website', $website)"
    />                        
    <x-forms::group
        mode="input"
        :label="__('messages.form.email')"
        name="email"
        :value="old('email', $email)"
    />                        
    <x-forms::group
        mode="input"
        :label="__('messages.form.phone')"
        name="phone"
        :value="old('phone', $phone)"
    />                        
    <x-forms::group
        mode="input"
        :label="__('messages.form.copyright')"
        name="copyright"
        :value="old('copyright', $copyright)"
    />                        
@endcomponent