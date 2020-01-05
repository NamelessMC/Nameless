{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
    <div class="row">
        <div class="col-md-3">
            {include file='user/navigation.tpl'}
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">{$SETTINGS}</h2>

                    {if $ERROR}
                        <div class="alert alert-danger">
                            {$ERROR}
                        </div>
                    {/if}

                    {if $SUCCESS}
                        <div class="alert alert-success">
                            {$SUCCESS}
                        </div>
                    {/if}

                    <form action="" method="post">
                        {nocache}
                            <div class="form-group">
                                <label for="inputLanguage">{$ACTIVE_LANGUAGE}</label>
                                <select name="language" id="inputLanguage" class="form-control">
                                    {foreach from=$LANGUAGES item=language}
                                        <option value="{$language.name}"{if $language.active == true} selected{/if}>{$language.name}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputTemplate">{$ACTIVE_TEMPLATE}</label>
                                <select name="template" id="inputTemplate" class="form-control">
                                    {foreach from=$TEMPLATES item=template}
                                        <option value="{$template.id}"{if $template.active == true} selected{/if}>{$template.name}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputTimezone">{$TIMEZONE}</label>
                                <select name="timezone" class="form-control" id="inputTimezone">
                                    {foreach from=$TIMEZONES key=KEY item=ITEM}
                                        <option value="{$KEY}"{if $SELECTED_TIMEZONE eq $KEY} selected{/if}>
                                            ({$ITEM.offset}) - {$ITEM.name} ({$ITEM.time})
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
							{if isset($PRIVATE_PROFILE)}
                                <div class="form-group">
                                    <label for="inputPrivateProfile">{$PRIVATE_PROFILE}</label>
                                        <select name="privateProfile" class="form-control" id="inputPrivateProfile">
                                            <option value="1"{if $PRIVATE_PROFILE_ENABLED == true} selected {/if}>{$ENABLED}</option>
                                            <option value="0"{if $PRIVATE_PROFILE_ENABLED == false} selected {/if}>{$DISABLED}</option>
                                        </select>
                                </div>
                            {/if}
                            {foreach from=$PROFILE_FIELDS item=field}
                                <div class="form-group">

                                    {if !isset($field.disabled)}
                                        <label for="input{$field.id}">{$field.name}</label>
                                        {if $field.type == "text"}
                                            <input type="text" class="form-control" name="{$field.id}"
                                                   id="input{$field.id}" value="{$field.value}"
                                                   placeholder="{$field.name}">
                                        {elseif $field.type == "textarea"}
                                            <textarea class="form-control" name="{$field.id}"
                                                      id="input{$field.id}">{$field.value}</textarea>
                                        {elseif $field.type == "date"}
                                            <input name="{$field.id}" id="input{$field.id}" value="{$field.value}"
                                                   type="text" class="form-control datepicker">
                                        {/if}

                                    {/if}
                                </div>
                            {/foreach}

                            {if isset($SIGNATURE)}
                            <label for="inputSignature">{$SIGNATURE}</label>
                            {if !isset($MARKDOWN)}
                                <textarea style="width:100%" name="signature" id="inputSignature"
                                          rows="15">{$SIGNATURE_VALUE}</textarea>
                            {else}
                                <div class="form-group">
                                    <textarea class="form-control" style="width:100%" id="inputSignature" name="signature"
                                              rows="20">{$SIGNATURE_VALUE}</textarea>
                                    <span class="float-md-right"><i data-toggle="popover" data-placement="top"
                                                                data-html="true" data-content="{$MARKDOWN_HELP}"
                                                                class="fa fa-question-circle text-info"
                                                                aria-hidden="true"></i></span>
                                </div>
                            {/if}
                            {/if}

                        {/nocache}

                        <div class="form-group">
                            <br />
                            <input type="hidden" name="action" value="settings">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                        </div>
                    </form>

                    <hr/>

                    {nocache}
                        <h4>{$CHANGE_EMAIL_ADDRESS}</h4>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="inputPassword">{$CURRENT_PASSWORD}</label>
                                <input type="password" name="password" id="inputPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputEmail">{$EMAIL_ADDRESS}</label>
                                <input type="email" name="email" id="inputEmail" class="form-control" value="{$CURRENT_EMAIL}">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="action" value="email">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                            </div>
                        </form>

                        <hr />

                        <h4>{$CHANGE_PASSWORD}</h4>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="inputOldPassword">{$CURRENT_PASSWORD}</label>
                                <input type="password" name="old_password" id="inputOldPassword" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="inputNewPassword">{$NEW_PASSWORD}</label>
                                <input type="password" name="new_password" id="inputNewPassword" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="inputNewPasswordAgain">{$CONFIRM_NEW_PASSWORD}</label>
                                <input type="password" name="new_password_again" id="inputNewPasswordAgain"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="action" value="password">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                            </div>
                        </form>
                    {/nocache}

                    <hr/>

                    <h4>{$TWO_FACTOR_AUTH}</h4>
                    {nocache}
                        {if isset($ENABLE)}
                            <a href="{$ENABLE_LINK}" class="btn btn-success">{$ENABLE}</a>
                        {else}
                            <a href="{$DISABLE_LINK}" class="btn btn-danger">{$DISABLE}</a>
                        {/if}
                    {/nocache}

                    {if isset($CUSTOM_AVATARS)}
                        <hr/>
                        <h4>{$UPLOAD_NEW_PROFILE_IMAGE}</h4>
                        <form action="{$CUSTOM_AVATARS_SCRIPT}" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="btn btn-secondary">
                                    {$BROWSE} <input type="file" name="file" hidden/>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="type" value="avatar">
                                <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                            </div>
                        </form>
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}