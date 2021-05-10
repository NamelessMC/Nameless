<?php
/*
 *  Made by Samerton
 *  Translated by Я научу тебя шить XIMI
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Russian Language - Installation
 */

$language = array(
    /*
     *  Installation
     */
    'install' => 'Установка',
    'pre-release' => 'пред-релиз',
    'installer_welcome' => 'Добро пожаловать в NamelessMC пред-релизной версии 2.0.',
    'pre-release_warning' => 'Внимание! Эта версия не предназначена для повседневного использования.',
    'installer_information' => 'Установщик поможет вам пройти весь процесс установки.',
    'terms_and_conditions' => 'Продолжая установку, вы соглашаетесь с нашими условиями использования.',
    'new_installation_question' => 'Вы устанавливаете движок в первый раз?',
    'new_installation' => 'Я устанавливаю в первый раз &raquo;',
    'upgrading_from_v1' => 'Я перехожу с v1 &raquo;',
    'requirements' => 'Требования:',
    'config_writable' => 'core/config.php доступен для записи',
    'cache_writable' => 'Cache доступен для записи',
    'template_cache_writable' => 'Кеш стилей доступен для записи',
    'exif_imagetype_banners_disabled' => 'Без функции exif_imagetype баннеры сервера будут отключены.',
    'requirements_error' => 'Для продолжения установки необходимо установить все необходимые расширения и установить правильные разрешения.',
    'proceed' => 'Продолжить',
    'database_configuration' => 'Конфигурация базы данных',
    'database_address' => 'Адрес базы данных',
    'database_port' => 'Порт базы данных',
    'database_username' => 'Имя пользователя базы данных базы данных',
    'database_password' => 'Пароль от базы данных',
    'database_name' => 'Название базы данных',
    'nameless_path' => 'Путь установки',
    'nameless_path_info' => 'Это путь, по которому устанавливается Nameless относительно вашего домена. Например, если Nameless устанавливается по пути example.com/forum, то вы должны написать <strong>forum</strong>. Оставьте поле пустым, если Nameless не находится в подпапке.',
    'friendly_urls' => 'Дружественные URL',
    'friendly_urls_info' => 'Дружественные URL-адреса улучшают читабельность URL-адресов в вашем браузере.<br />Например: <br /><code>example.com/index.php?route=/forum</code><br />станет<br /><code>example.com/forum</code><br /><div class="ui inverted orange segment"><i class="exclamation circle icon"></i><strong>Важно!</strong><br />Ваш сервер должен быть правильно настроен. Вы можете увидеть, можно ли включить эту опцию, нажав на <a href=\'./rewrite_test\' target=\'_blank\' style="color:#2185D0">эту ссылку</a>.</div>',
    'enabled' => 'Включено',
    'disabled' => 'Выключено',
    'character_set' => 'Кодировка',
    'database_engine' => 'Движок базы данных',
    'host' => 'Адрес',
    'host_help' => 'Адрес это <strong>базовый URL</strong> для вашего веб-сайта. Не включайте вложенные каталоги или http(s):// в поле адреса сайта!',
    'database_error' => 'Пожалуйста, убедитесь, что все поля были заполнены.',
    'submit' => 'Подтвердить',
    'installer_now_initialising_database' => 'Установщик инициализирует базу данных. Это может занять некоторое время...',
    'configuration' => 'Конфигурация',
    'configuration_info' => 'Пожалуйста, введите основную информацию о вашем сайте. Эти значения могут быть изменены позже через панель администратора.',
    'configuration_error' => 'Пожалуйста, введите действительное название сайта длиной от 1 до 32 символов и действительные адреса электронной почты длиной от 4 до 64 символов.',
    'site_name' => 'Название сайта',
    'contact_email' => 'Адрес электронной почты для контактов',
    'outgoing_email' => 'Исходящий адрес электронной почты',
    'language' => 'Язык для использования на сайте',
    'initialising_database_and_cache' => 'Инициализация базы данных и кэша, пожалуйста, подождите...',
    'unable_to_login' => 'Не удалось войти.',
    'unable_to_create_account' => 'Невозможно создать учетную запись',
    'input_required' => 'Пожалуйста, введите действительное имя пользователя, адрес электронной почты и пароль.',
    'input_minimum' => 'Пожалуйста, убедитесь, что ваше имя пользователя состоит минимум из 3 символов, адрес электронной почты - минимум из 4 символов, а пароль - минимум из 6 символов.',
    'input_maximum' => 'Пожалуйста, убедитесь, что ваше имя пользователя содержит не более 20 символов, а адрес электронной почты и пароль - не более 64 символов.',
    'email_invalid' => 'Ваш адрес электронной почты недействителен.',
    'passwords_must_match' => 'Ваши пароли должны совпадать.',
    'creating_admin_account' => 'Создание учётной записи администратора',
    'enter_admin_details' => 'Пожалуйста, введите данные для учётной записи администратора.',
    'username' => 'Имя пользователя',
    'email_address' => 'Адрес электронной почты',
    'password' => 'Пароль',
    'confirm_password' => 'Подтверждение пароля',
    'upgrade' => 'Обновить',
    'input_v1_details' => 'Пожалуйста, введите данные базы данных для вашего Nameless версии 1.',
    'installer_upgrading_database' => 'Пожалуйста, подождите пока установщик обновит вашу базу данных...',
    'errors_logged' => 'Были зарегистрированы ошибки. Нажмите кнопку "Продолжить", чтобы продолжить обновление.',
    'continue' => 'Продолжить',
    'convert' => 'Конвертация',
    'convert_message' => 'Вы хотите конвертировать данные из других CMS?',
    'yes' => 'Да',
    'no' => 'Нет',
    'converter' => 'Конвертер',
    'back' => 'Назад',
    'unable_to_load_converter' => 'Не удалось загрузить конвертер!',
    'finish' => 'Благодарим за установку',
    'finish_message' => 'Спасибо за установку NamelessMC! Теперь вы можете перейти к панели администрации, где вы можете дополнительно настроить свой веб-сайт.',
    'support_message' => 'Если вам нужна какая-либо поддержка, посетите наш веб-сайт <a href="https://namelessmc.com" target="_blank">здесь</a>, или вы также можете посетить наш <a href="https://discord.gg/nameless" target="_blank">Дискорд сервер</a>, или наш <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub репозиторий</a>.',
    'credits' => 'Помощники проекта',
    'credits_message' => 'Огромное спасибо всем <a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">NamelessMC участникам</a> с 2014 года',

    'step_home' => 'Добро пожаловать',
    'step_requirements' => 'Проверка сервера',
    'step_general_config' => 'Базовая настройка',
    'step_database_config' => 'Настройка базы данных',
    'step_site_config' => 'Конфигурация сайта',
    'step_admin_account' => 'Подготовка к запуску',
    'step_conversion' => 'Перенос',
    'step_finish' => 'Завершение установки',

    'general_configuration' => 'Базовая настройка',
    'reload' => 'Сброс',
    'reload_page' => 'Перезагрузить страницу',
    'no_converters_available' => 'Ничего не найдено для переноса.',
    'config_not_writable' => 'Файл конфигурации недоступен для записи.',

    'session_doesnt_exist' => 'Не определенасессия. Сессии используются при работе с NamelessMC. Свяжитесь с тех. поддержкой вашего провайдера.'
);
