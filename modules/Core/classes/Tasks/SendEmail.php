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
                'title' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 1,
                ],
                'content' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 1,
                    Validate::MAX => EMAIL_MAX_LENGTH,
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

        $username = $user->getDisplayname();
        $title = Output::getPurified($this->getData()['title']);

        $content = $this->getData()['content'];

        $sent = Email::send(
            ['email' => $user->data()->email, 'name' => $username],
            $title,
            $content,
        );

        if (isset($sent['error'])) {
            DB::getInstance()->insert('email_errors', [
                'type' => Email::MASS_MESSAGE,
                'content' => $sent['error'],
                'at' => date('U'),
                'user_id' => $this->getEntityId(),
            ]);

            $this->setOutput([
                'errors' => [$language->get('admin', 'email_task_error')],
                'data' => $sent['error'],
            ]);

            return Task::STATUS_ERROR;
        }

        return Task::STATUS_COMPLETED;
    }
}
