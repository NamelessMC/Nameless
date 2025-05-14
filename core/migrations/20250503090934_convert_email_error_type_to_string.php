<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ConvertEmailErrorTypeToString extends AbstractMigration
{
    private const CONVERSION_MAP = [
        1 => RegisterEmailTemplate::class,
        3 => ForgotPasswordEmailTemplate::class,
        5 => ForumTopicReplyEmailTemplate::class,
        6 => MassMessageEmailTemplate::class,
    ];

    public function change(): void
    {
        $this->table('nl2_email_errors')
            ->renameColumn('type', 'mailer')
            ->changeColumn('mailer', 'string', ['limit' => 255])
            ->update();

        $email_errors = DB::getInstance()->query('SELECT * FROM nl2_email_errors')->results();

        foreach ($email_errors as $error) {
            $type = $error->mailer;
            if (isset(self::CONVERSION_MAP[$type])) {
                DB::getInstance()->update('email_errors', $error->id, [
                    'mailer' => self::CONVERSION_MAP[$type],
                ]);
            } else {
                DB::getInstance()->update('email_errors', $error->id, [
                    'mailer' => 'unknown',
                ]);
            }
        }
    }
}
