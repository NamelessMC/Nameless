<?php

class ForgotPasswordEmailTemplate extends EmailTemplate
{
    public function __construct(string $code)
    {
        $link = rtrim(URL::getSelfURL(), '/') . URL::build('/forgot_password/', 'c=' . urlencode($code));

        $this->addPlaceholder('[Link]', $link);
        $this->addPlaceholder('[Message]', new LanguageKey('emails', 'forgot_password_message'));

        parent::__construct();
    }

    public function subject(): LanguageKey
    {
        return new LanguageKey('emails', 'forgot_password_subject');
    }
}
