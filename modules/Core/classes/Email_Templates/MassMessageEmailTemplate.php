<?php

class MassMessageEmailTemplate extends EmailTemplate
{
    private string $subject;

    public function __construct(string $subject, string $content)
    {
        $this->subject = $subject;

        $this->addPlaceholder('[Message]', $content);

        parent::__construct();
    }

    public function subject(): string
    {
        return $this->subject;
    }
}
