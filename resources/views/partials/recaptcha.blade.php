@if(setting('regis_captcha_type')=='google')
    <script src="https://www.google.com/recaptcha/api.js?render={{ setting('regis_recaptcha_key') }}"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ setting('regis_recaptcha_key') }}', {action: 'homepage'}).then(function(token) {
                jQuery('.captcha_token').val(token);
            });
        });
    </script>
@endif
