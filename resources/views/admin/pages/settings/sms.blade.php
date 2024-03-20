<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6>{{__('SMS')}}</h6>
                <hr>
                <x-forms::base-form method="POST" :action="route('admin.settings.sms.post')">
                    <x-forms::group
                        label="{{__('Endpoint URL')}}"
                        name="ppurio__base_url"
                        :required="true"
                        :value="config('ppurio.base_url')"
                    />
                    <x-forms::group
                        label="{{__('Account')}}"
                        name="ppurio__account"
                        help="{{__('Your login ID')}}"
                        :required="true"
                        :value="config('ppurio.account')"
                    />
                    <x-forms::group
                        label="{{__('Access Key')}}"
                        name="ppurio__access_key"
                        help="{{__('Get access key from: https://www.ppurio.com/send-api/info')}}"
                        :required="true"
                        :value="config('ppurio.access_key')"
                    />
                    <x-forms::group
                        label="{{__('Phone From')}}"
                        name="ppurio__phone_from"
                        help="{{__('Get phone number from: https://www.ppurio.com/send-api/info')}}"
                        :required="true"
                        :value="config('ppurio.phone_from')"
                    />
                    <x-forms::group
                        label="{{__('Phone To')}}"
                        name="ppurio__phone_to"
                        help="{{__('Your phone number for receive new notifications')}}"
                        :required="true"
                        :value="config('ppurio.phone_to')"
                    />
                    <x-forms::group
                        mode="textarea"
                        label="{{__('Message')}}"
                        name="ppurio__default_message"
                        :required="true"
                        :value="config('ppurio.default_message')"
                    />
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        @lang('messages.save_changes')
                    </button>
                </x-forms::base-form>
            </div>
        </div>
    </div>
</div>
