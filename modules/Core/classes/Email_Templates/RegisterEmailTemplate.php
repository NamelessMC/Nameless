<?php

class RegisterEmailTemplate extends EmailTemplate
{
    public function __construct(string $code)
    {
        // TODO sometimes this needs to be complete_signup?
        $link = rtrim(URL::getSelfURL(), '/') . URL::build('/validate/', 'c=' . urlencode($code));

        $this->addPlaceholder('[Link]', $link);
        $this->addPlaceholder('[Message]', new LanguageKey('emails', 'register_message'));

        parent::__construct();
    }

    public function subject(): LanguageKey
    {
        return new LanguageKey('emails', 'register_subject');
    }
}
