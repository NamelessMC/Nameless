<?php

class GlobalWarningsMiddleware extends AbstractMiddleware
{
    public MiddlewareType $type = MiddlewareType::Frontend;

    public function handle(User $user, Language $language, TemplateBase $template): void
    {
        $warnings = DB::getInstance()->query('SELECT * FROM nl2_infractions WHERE punished = ? AND revoked = 0 AND acknowledged = 0', [$user->data()->id])->results();
        foreach ($warnings as $warning) {
            $template->getEngine()->addVariables([
                'GLOBAL_WARNING_TITLE' => $language->get('user', 'you_have_received_a_warning'),
                'GLOBAL_WARNING_REASON' => Output::getClean($warning->reason),
                'GLOBAL_WARNING_ACKNOWLEDGE' => $language->get('user', 'acknowledge'),
                'GLOBAL_WARNING_ACKNOWLEDGE_LINK' => URL::build('/user/acknowledge/' . urlencode($warning->id)),
            ]);
            break;
        }
    }
}
