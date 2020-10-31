<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Chinese Simplified Language - Installation
 *  Translation(Chinese Simplified) by ahdg,lian20,Dreta
 *  Translation progress(v2-pr8) : 100%
 */

$language = array(
    /*
     *  Installation
     */
    'install' => '安装',
    'pre-release' => '预览版本',
    'installer_welcome' => '欢迎使用 NamelessMC 版本 2.0 预览版。',
    'pre-release_warning' => '请注意，此预发行版不适用于公共站点。',
    'installer_information' => '安装程序将指导您完成安装过程。',
    'terms_and_conditions' => '您在继续前必须同意 NamelessMC 条款与条件。',
    'new_installation_question' => '首先，这是您的首次安装吗?',
    'new_installation' => '首次安装 &raquo;',
    'upgrading_from_v1' => '从 v1 升级 &raquo;',
    'requirements' => '需求:',
    'config_writable' => 'core/config.php 可写',
    'cache_writable' => 'Cache 可写',
    'template_cache_writable' => 'Template Cache 可写',
    'exif_imagetype_banners_disabled' => '如果PHP没有启用 exif_imagetype 函数，服务器条幅将被禁用。',
    'requirements_error' => '您必须已安装所有必需的扩展，并设置了正确的权限才能继续安装。',
    'proceed' => '继续',
    'database_configuration' => '数据库设置',
    'database_address' => '数据库地址',
    'database_port' => '数据库端口',
    'database_username' => '数据库用户名',
    'database_password' => '数据库密码',
    'database_name' => '数据库名称',
    'nameless_path' => '安装路径',
    'nameless_path_info' => '这是 Nameless 所安装的路径, 与您的域名产生联系. 打个比方, 如果 Nameless 安装在 example.com/forum, 这将要填写 <strong>forum</strong>。如果 Nameless 不在子文件夹中，请留空。',
    'friendly_urls' => '友好 URLs',
    'friendly_urls_info' => '友好的 URL 将提高浏览器中 URL 的可读性。<br />打个比方: <br />example.com/index.php?route=/forum<br />将会变成<br />example.com/forum.<br /><strong>注意！</strong><br />您的服务器必须正确配置才能正常工作。您可以通过单击查看是否可以启用此选项 <a href=\'./rewrite_test\' target=\'_blank\'>查看</a>.',
    'enabled' => '启用',
    'disabled' => '禁用',
    'character_set' => '字符集',
    'database_engine' => '数据库存储引擎',
    'host' => '主机名',
    'host_help' => '主机名是您网站的 <strong>基本 URL</strong>。 不要将安装文件路径 或 http(s):// 输入进去!',
    'database_error' => '请确保所有字段均已填写。',
    'submit' => '提交',
    'installer_now_initialising_database' => '现在，安装程序正在初始化数据库。可能还要等一下...',
    'configuration' => '配置',
    'configuration_info' => '请输入有关您网站的基本信息。这些值可以稍后通过管理面板进行更改。',
    'configuration_error' => '请输入有效的网站名称，长度在 1 到 32 个字符之间，有效的电子邮箱地址在 4 到 64 个字符之间。',
    'site_name' => '网站名称',
    'contact_email' => '联系邮箱',
    'outgoing_email' => '发件箱',
    'language' => 'Language',
    'initialising_database_and_cache' => '初始化数据库和缓存，请稍候...',
    'unable_to_login' => '无法登录。',
    'unable_to_create_account' => '无法创建新用户',
    'input_required' => '请输入有效的用户名，电子邮箱地址和密码。',
    'input_minimum' => '请确保您的用户名至少 3 个字符，您的电子邮箱地址至少 4 个字符，密码至少 6 个字符。',
    'input_maximum' => '请确保您的用户名不超过 20 个字符，您的电子邮件地址和密码不超过 64 个字符。',
    'email_invalid' => '您的电子邮箱地址无效。',
    'passwords_must_match' => '您的密码必须匹配',
    'creating_admin_account' => '创建管理员帐号',
    'enter_admin_details' => '请输入管理员帐号的详细信息。',
    'username' => '用户名',
    'email_address' => '邮箱地址',
    'password' => '密码',
    'confirm_password' => '二次确认密码',
    'upgrade' => '升级',
    'input_v1_details' => '请输入您的 Nameless v1 安装的数据库详细信息。',
    'installer_upgrading_database' => '请等待安装程序升级数据库...',
    'errors_logged' => '错误已记录。单击继续以继续升级。.',
    'continue' => '继续',
    'convert' => '转换',
    'convert_message' => '最后，您是否要从其他论坛软件转换数据?',
    'yes' => '是',
    'no' => '否',
    'converter' => '转换器',
    'back' => '返回',
    'unable_to_load_converter' => '无法加载转换器!',
    'finish' => '完成',
    'finish_message' => '感谢安装 NamelessMC! 现在，您可以进入管理员面板，在那里可以进一步配置您的网站。',
    'support_message' => '如果你需要任何帮助，请在 <a href="https://namelessmc.com" target="_blank">我们的网站</a> 上查找 , 或者你可以访问我们的 <a href="https://discord.gg/9vk93VR" target="_blank">Discord 服务器</a> 或 <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub 资料库</a>.',
    'credits' => '开发人员',
    'credits_message' => '向所有自2014年以来的 <a href="https://github.com/NamelessMC/Nameless#full-contributor-list" target="_blank">NamelessMC 代码贡献者</a> 致敬',

	'step_home' => '开始',
	'step_requirements' => '环境需求',
	'step_general_config' => '基本配置',
	'step_database_config' => '数据库配置',
	'step_site_config' => '站点配置',
	'step_admin_account' => '管理员账户',
	'step_conversion' => 'Conversion',
	'step_finish' => '完成',

	'general_configuration' => '基本配置',
	'reload' => '刷新',
	'reload_page' => '刷新页面',
	'no_converters_available' => '没有可用的转换器',
	'config_not_writable' => '配置文件无法写入',

	'session_doesnt_exist' => '无法检测会话。若想使用 Nameless，您必须启用会话。请您再试一次，若此问题始终存在，请咨询您的网页提供商。'
);
