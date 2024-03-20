<div class="row mb-5">
    <div class="col-md-4 d-none d-md-block">
        <div class="card">
            <div class="card-body">
                <nav class="nav flex-column nav-pills nav-gap-y-1">
                    <a href="#server" data-bs-toggle="tab" class="nav-item nav-link has-icon nav-link-faded mb-2 active">
                        <i class="fas fa-server icon icon-sm"></i>
                        @lang('messages.email.server')
                    </a>
                    <a href="#templates" data-bs-toggle="tab" class="nav-item nav-link has-icon nav-link-faded mb-2">
                        <i class="fas fa-envelope icon icon-sm"></i>
                        @lang('messages.email.templates')
                    </a>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom mb-3 d-flex d-md-none">
                <ul class="nav nav-tabs card-header-tabs nav-gap-x-1" role="tablist">
                    <li class="nav-item">
                        <a href="#server" data-bs-toggle="tab" class="nav-link has-icon active"><i class="fas fa-server icon icon-sm"></i></a>
                    </li>
                    <li class="nav-item">
                        <a href="#templates" data-bs-toggle="tab" class="nav-link has-icon"><i class="fas fa-envelop icon icon-sm"></i></a>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane active" id="server">
                    <h6>{{__('SERVER')}}</h6>
                    <hr>
                    <x-forms::base-form method="POST" :action="route('admin.settings.email.post')">
                        <x-forms::group
                            mode="select"
                            label="{{__('Mailer')}}"
                            name="mail__default"
                            :required="true"
                        >
                            <option value="smtp" @if(config('mail.default') == 'smtp')selected @endif>SMTP</option>
                            <option value="mailgun" @if(config('mail.default') == 'mailgun')selected @endif>Mailgun</option>
                            <option value="sendmail" @if(config('mail.default') == 'sendmail')selected @endif>Sendmail</option>
                            <option value="postmark" @if(config('mail.default') == 'postmark')selected @endif>Postmark</option>
                            <option value="ses" @if(config('mail.default') == 'ses')selected @endif>SES</option>
                            <option value="log" @if(config('mail.default') == 'log')selected @endif>Log</option>
                            <option value="array" @if(config('mail.default') == 'array')selected @endif>Array</option>
                        </x-forms::group>
                        <div id="smtp_section">
                            <x-forms::group
                                label="Host"
                                name="mail__mailers__smtp__host"
                                placeholder="smtp.gmail.com"
                                :value="config('mail.mailers.smtp.host')"
                            />
                            <x-forms::group
                                type="number"
                                label="Port"
                                name="mail__mailers__smtp__port"
                                placeholder="587"
                                :value="config('mail.mailers.smtp.port')"
                            />
                            <x-forms::group
                                mode="select"
                                label="Encryption"
                                name="mail__mailers__smtp__encryption"
                            >
                                <option value="" @if(config('mail.mailers.smtp.encryption') == null)selected @endif>None</option>
                                <option value="tls" @if(config('mail.mailers.smtp.encryption') == 'tls')selected @endif>TLS</option>
                                <option value="ssl" @if(config('mail.mailers.smtp.encryption') == 'ssl')selected @endif>SSL</option>
                                <option value="starttls" @if(config('mail.mailers.smtp.encryption') == 'starttls')selected @endif>STARTTLS</option>
                            </x-forms::group>
                            <x-forms::group
                                label="{{__('Username')}}"
                                name="mail__mailers__smtp__username"
                                placeholder="Enter SMTP username"
                                :value="config('mail.mailers.smtp.username')"
                            />
                            <x-forms::group
                                label="{{__('Password')}}"
                                name="mail__mailers__smtp__password"
                                placeholder="Enter SMTP password"
                                :value="config('mail.mailers.smtp.password')"
                            />
                        </div>
                        <div id="sendmail_section" style="display: none">
                            <x-forms::group
                                label="Sendmail Path"
                                name="mail__mailers__sendmail__path"
                                help="Default: /usr/sbin/sendmail -bs -i"
                                :value="config('mail.mailers.sendmail.path')"
                            />
                        </div>
                        <div id="mailgun_section" style="display: none">
                            <x-forms::group
                                label="Domain"
                                name="mail__mailers__mailgun__domain"
                                :value="config('mail.mailers.mailgun.domain')"
                            />
                            <x-forms::group
                                label="Endpoint"
                                name="mail__mailers__mailgun__endpoint"
                                :value="config('mail.mailers.mailgun.endpoint')"
                            />
                        </div>
                        <div id="log_section" style="display: none">
                            <x-forms::group
                                mode="select"
                                label="Log channel"
                                name="mail__mailers__log__channel"
                                :value="config('mail.mailers.log.channel')"
                            />
                        </div>
                        <div id="ses_section" style="display: none">
                            <x-forms::group
                                label="Key"
                                name="mail__mailers__ses__key"
                                :value="config('mail.mailers.ses.key')"
                            />
                            <x-forms::group
                                label="Endpoint"
                                name="mail__mailers__ses__region"
                                :value="config('mail.mailers.ses.region')"
                            />
                        </div>
                        <x-forms::group
                            label="{{__('Sender Name')}}"
                            name="mail__from__name"
                            placeholder="John Doe"
                            :value="config('mail.from.name')"
                        />
                        <x-forms::group
                            label="{{__('Sender Address')}}"
                            name="mail__from__address"
                            placeholder="noreply@example.com"
                            :value="config('mail.from.address')"
                        />
                        <x-forms::group
                            mode="switch"
                            name="mail__enable"
                            label="{{__('Enable')}}"
                            help="활성/비활성"
                            :checked="config('mail.enable', false)"
                        />
                        <!--
                        <x-forms::group
                            mode="switch"
                            name="mail__queue"
                            label="{{__('Queue Mode')}}"
                            help="{{__()}}Turn it on if your site have large users (<a href='https://laravel.com/docs/8.x/queues#running-the-queue-worker'>Documentation</a>)."
                            :checked="config('mail.queue', false)"
                        />
                        -->
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            @lang('messages.save_changes')
                        </button>
                    </x-forms::base-form>
                </div>
                <div class="tab-pane" id="templates">
                    <h6>{{__('TEMPLATES')}}</h6>
                    <hr>
                    <ul class="list-group">
                        @foreach(\Email::all() as $name => $data)
                            <li class="list-group-item list-group-item-action border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ isset($data['title']) ? $data['title'] : $name }}</div>
                                        <small class="text-muted">{{ isset($data['description']) ? $data['description'] : '' }}</small>
                                    </div>
                                    <div>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning"
                                            data-toggle="edit-template-modal"
                                            data-title="{{ isset($data['title']) ? $data['title'] : $name }}"
                                            data-id="{{$name}}"
                                            @if($data['type'] == 'view')
                                            data-view="{{$data['content']}}"
                                            @endif
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
