@component('admin.email_templates.components.layout')

@lang('email.hello')  

@lang('email.reset_password.content')

@component('admin.email_templates.components.button', ['url'   => $url ?? '#'])
    {{ __('email.reset_password.button')}}
@endcomponent

@lang('email.reset_password.sub_content')

@endcomponent