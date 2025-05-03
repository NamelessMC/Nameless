<?php

abstract class EmailTemplate
{
    /**
     * @var array<string, LanguageKey|string> Placeholders for this email template
     */
    private array $_placeholders = [];

    public function __construct()
    {
        $this->addPlaceholder('[Sitename]', Output::getClean(SITE_NAME));
        $this->addPlaceholder('[Greeting]', new LanguageKey('emails', 'greeting'));
        $this->addPlaceholder('[Thanks]', new LanguageKey('emails', 'thanks'));
    }

    /**
     * Returns the snake_case representation of the email template name,
     * derived from the class name with "EmailTemplate" removed.
     * For example: RegisterEmailTemplate -> "register", ForgotPasswordEmailTemplate -> "forgot_password".
     */
    private function name(): string
    {
        $baseName = str_replace('EmailTemplate', '', static::class);

        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $baseName));
    }

    abstract public function subject(): LanguageKey|string;

    /**
     * Add a custom placeholder/variable for email messages.
     *
     * @param string                                   $key   The key to use for the placeholder, should be enclosed in square brackets.
     * @param string|Closure(Language, string): string $value The value to replace the placeholder with.
     */
    final public function addPlaceholder(string $key, $value): void
    {
        $this->_placeholders[$key] = $value;
    }

    final public function renderContent(string $languageCode): string
    {
        $placeholderKeys = array_keys($this->_placeholders);
        $placeholderValues = [];

        foreach ($this->_placeholders as $placeholder) {
            if ($placeholder instanceof LanguageKey) {
                $placeholderValues[] = $placeholder->translate($languageCode);
            } else {
                $placeholderValues[] = $placeholder;
            }
        }

        return str_replace(
            $placeholderKeys,
            $placeholderValues,
            file_get_contents($this->getPath()),
        );
    }

    private function getPath(): string
    {
        $name = $this->name();

        $customPath = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', TEMPLATE, 'email', $name . '.html']);
        if (file_exists($customPath)) {
            return $customPath;
        }

        $defaultPath = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', 'DefaultRevamp', 'email', $name . '.html']);
        if (file_exists($defaultPath)) {
            return $defaultPath;
        }

        throw new Exception('Email template not found: ' . $name);
    }
}
