<?php
/**
 * Contains namespaced API error messages for the Core module.
 * These have no versioning, and are not meant to be used by any other modules.
 *
 * @package Modules\Core\Misc
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class CoreApiErrors {

    public const ERROR_UNABLE_TO_FIND_GROUP = 'core:unable_to_find_group';
    public const ERROR_BANNED_FROM_WEBSITE = 'core:banned_from_website';

    public const ERROR_REPORT_CONTENT_TOO_LONG = 'core:report_content_too_long';
    public const ERROR_CANNOT_REPORT_YOURSELF = 'core:cannot_report_yourself';
    public const ERROR_OPEN_REPORT_ALREADY = 'core:open_report_already';

    public const ERROR_UNABLE_TO_UPDATE_SERVER_INFO = 'core:unable_to_update_server_info';

    public const ERROR_INVALID_SERVER_ID = 'core:invalid_server_id';

    public const ERROR_EMAIL_ALREADY_EXISTS = 'core:email_already_exists';
    public const ERROR_USERNAME_ALREADY_EXISTS = 'core:username_already_exists';
    public const ERROR_INVALID_EMAIL_ADDRESS = 'core:invalid_email_address';
    public const ERROR_INVALID_USERNAME = 'core:invalid_username';
    public const ERROR_UNABLE_TO_CREATE_ACCOUNT = 'core:unable_to_create_account';
    public const ERROR_UNABLE_TO_SEND_REGISTRATION_EMAIL = 'core:unable_to_send_registration_email';

    public const ERROR_INVALID_INTEGRATION = 'core:invalid_integration';
    public const ERROR_INVALID_CODE = 'core:invalid_code';
    public const ERROR_INTEGRATION_NOT_LINKED = 'core:integration_not_linked';
    public const ERROR_INTEGRATION_ALREADY_VERIFIED = 'core:integration_already_verified';
    public const ERROR_INTEGRATION_ALREADY_LINKED = 'core:integration_already_linked';
    public const ERROR_USER_ALREADY_ACTIVE = 'core:user_already_active';

    public const ERROR_UNABLE_TO_UPDATE_USERNAME = 'core:unable_to_update_username';

    public const ERROR_INTEGRATION_IDENTIFIER_ERRORS = 'core:integration_identifier_errors';
    public const ERROR_INTEGRATION_USERNAME_ERRORS = 'core:integration_username_errors';

    public const ERROR_WEBHOOK_NAME_INCORRECT_LENGTH = 'core:webhook_name_incorrect_length';
    public const ERROR_WEBHOOK_URL_INCORRECT_LENGTH = 'core:webhook_url_incorrect_length';
    public const ERROR_WEBHOOK_INVALID_TYPE = 'core:webhook_type_invalid';
    public const ERROR_WEBHOOK_INVALID_EVENT = 'core:webhook_event_invalid';
}
