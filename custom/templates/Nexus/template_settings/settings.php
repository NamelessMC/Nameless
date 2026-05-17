<?php

/**
 * Nexus template settings.
 *
 * @license MIT
 *
 * @var Cache        $cache
 * @var Language     $language
 * @var TemplateBase $current_template
 */

if (Input::exists()) {
    if (Token::check()) {
        $cache->setCache('template_settings');

        if (isset($_POST['nexusAccent'])) {
            $accentInput = trim($_POST['nexusAccent']);
            // Whitelist hex colours only.
            if (preg_match('/^#[0-9A-Fa-f]{6}$/', $accentInput)) {
                $cache->store('nexusAccent', $accentInput);
            }
        }

        Session::flash('admin_templates', $language->get('admin', 'successfully_updated'));
    } else {
        $errors = [$language->get('general', 'invalid_token')];
    }
}

$cache->setCache('template_settings');

if ($cache->isCached('nexusAccent')) {
    $nexusAccent = $cache->retrieve('nexusAccent');
} else {
    $nexusAccent = '#7C5CFF';
    $cache->store('nexusAccent', $nexusAccent);
}

$current_template->getEngine()->addVariables([
    'NEXUS_ACCENT' => $nexusAccent,
    'NEXUS_ACCENT_LABEL' => 'Accent colour',
    'NEXUS_NOTE' => 'Nexus is dark-mode first with an optional light mode toggled by users via the navbar. The accent colour below drives buttons, focus rings, and the brand gradient. After changing it, rebuild theme assets (npm run build) so Tailwind picks up the new token value.',
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
    'SETTINGS_TEMPLATE' => ROOT_PATH . '/custom/templates/Nexus/template_settings/settings.tpl',
]);

if (isset($errors)) {
    $current_template->getEngine()->addVariable('TEMPLATE_ERRORS', $errors);
}
