<script>
    function toggleSection(val) {
        const smtp = document.getElementById('smtp_section')
        const sendmail = document.getElementById('sendmail_section')
        const mailgun = document.getElementById('mailgun_section')
        const postmark = document.getElementById('postmark_section')
        const ses = document.getElementById('ses_section')
        const log = document.getElementById('log_section')

        switch(val) {
            case 'smtp':
                smtp.style.display = 'block'
                sendmail.style.display = 'none'
                mailgun.style.display = 'none'
                ses.style.display = 'none'
                log.style.display = 'none'
            break;
            case 'sendmail':
                smtp.style.display = 'none'
                sendmail.style.display = 'block'
                mailgun.style.display = 'none'
                ses.style.display = 'none'
                log.style.display = 'none'
            break;
            case 'mailgun':
                smtp.style.display = 'none'
                sendmail.style.display = 'none'
                mailgun.style.display = 'block'
                ses.style.display = 'none'
                log.style.display = 'none'
            break;
            case 'postmark':
                smtp.style.display = 'none'
                sendmail.style.display = 'none'
                mailgun.style.display = 'none'
                ses.style.display = 'none'
                log.style.display = 'none'
            break;
            case 'ses':
                smtp.style.display = 'none'
                sendmail.style.display = 'none'
                mailgun.style.display = 'none'
                ses.style.display = 'block'
                log.style.display = 'none'
            break;
            case 'log':
                smtp.style.display = 'none'
                sendmail.style.display = 'none'
                mailgun.style.display = 'none'
                ses.style.display = 'none'
                log.style.display = 'block'                
        }
    }

    (function() {
        'use strict';
        
        let   selected_template;
        const mailer  = document.querySelector('select[name="mail__default"]');
        const buttons = document.querySelector('.btn[data-toggle="edit-template-modal"]');

        toggleSection(mailer.value)
        mailer.addEventListener('change', function(e) {
            const { value } = e.target
            toggleSection(value)
        })

        buttons.addEventListener('click', function(e) {
            const id = e.currentTarget.getAttribute('data-id')
            const title = e.currentTarget.getAttribute('data-title')
            const view = e.currentTarget.getAttribute('data-view')

            Utils.toggleLoading({
                el: e.currentTarget,
                loading_text: false,
                callback: function(el, completed) {
                    if(view) {
                        document.querySelector('textarea[name="email_content"]').setAttribute("data-view", view)
                    }

                    selected_template = id
                    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('edit-template-modal'), {
                        keyboard: false,
                        backdrop: true
                    });

                    document.getElementById('edit-template-modal').addEventListener('shown.bs.modal', function() {
                        window.templateEditor_email_content.codemirror.refresh()
                    })
                    
                    axios.post('{{ route("admin.settings.email.template") }}', {template: id})
                    .then(function({data}) {
                        if(data.success) {
                            window.templateEditor_email_content.value(data.data.content)
                            document.getElementById('templateModalTitle').innerHTML = title
                            modal.show();  
                        } else {
                            Notyf.error(data.message)
                        }          
                    })
                    .finally(function() {
                        completed()
                    })
                }
            })
        })

        document.getElementById('saveTemplate').addEventListener('click', function(e) {
            const content  = window.templateEditor_email_content.value()
            
            Utils.toggleLoading({
                el: e.currentTarget,
                loading_text: false,
                callback: function(el, completed) {
                    axios.post('{{ route("admin.ajax.email-template.update") }}', {content, template: selected_template})
                    .then(function({data}) {
                        if(data.success) {
                            Notyf.success("{{ __('messages.saved') }}")
                        } else {
                            Notyf.error(data.message)
                        }
                    })
                    .catch(function(err) {
                        Notyf.error(err)
                    })
                    .finally(function() {
                        completed()
                    })
                }
            })
        })
    })();
</script>