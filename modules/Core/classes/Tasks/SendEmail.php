<?php

class SendEmail extends Task {

    public function run(): string {
        $language = $this->_container->get(Language::class);

        if (!$this->getEntityId()) {
            $this->setOutput([
                'errors' => [$language->get('admin', 'email_task_error')],
                'data' => ['field' => 'entityId'],
            ]);
            return Task::STATUS_ERROR;
        }

        $user = new User($this->getEntityId());

        if (!$user->exists()) {
            $this->setOutput([
                'errors' => [$language->get('admin', 'email_task_error')],
                'data' => ['field' => 'entityId'],
            ]);
            return Task::STATUS_ERROR;
        }

        $validate = Validate::check(
            $this->getData(),
            [
                'subject' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 1,
                ],
                'content' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 1,
                    Validate::MAX => Email::EMAIL_MAX_LENGTH,
                ],
            ],
        );

        if (!$validate->passed()) {
            $this->setOutput([
                'errors' => [$language->get('admin', 'email_task_error')],
                'data' => $validate->errors(),
            ]);
            return Task::STATUS_ERROR;
        }

        $sent = Email::sendRaw(
            $this->getData()['mailer'],
            $user,
            $this->getData()['subject'],
            $this->getData()['content'],
        );

        if (isset($sent['error'])) {
            $this->setOutput([
                'errors' => [$language->get('admin', 'email_task_error')],
                'data' => $sent['error'],
            ]);

            return Task::STATUS_ERROR;
        }

        return Task::STATUS_COMPLETED;
    }
}
