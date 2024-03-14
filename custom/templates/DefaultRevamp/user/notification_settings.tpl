{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$TITLE}
</h2>

<div class="ui stackable grid" id="notification_settings">
    <div class="ui centered row">
        <div class="ui six wide tablet four wide computer column">
            {include file='user/navigation.tpl'}
        </div>
        <div class="ui ten wide tablet twelve wide computer column">
            <div class="ui segment">
                <h3 class="ui header">{$NOTIFICATION_SETTINGS_TITLE}</h3>

                {if isset($SUCCESS)}
                    <div class="ui success icon message">
                        <i class="check icon"></i>
                        <div class="content">
                            <div class="header">{$SUCCESS_TITLE}</div>
                            {$SUCCESS}
                        </div>
                    </div>
                {/if}

                {if isset($ERRORS)}
                    <div class="ui error icon message">
                        <i class="x icon"></i>
                        <div class="content">
                            <ul class="list">
                                {foreach from=$ERRORS item=error}
                                    <li>{$error}</li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                {/if}

                <form action="" method="post">
                    <table class="ui definition celled table">
                        <thead>
                            <tr>
                                <th class="four wide"></th>
                                <th class="six wide">{$ALERT}</th>
                                <th class="six wide">{$EMAIL}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $NOTIFICATION_SETTINGS as $setting}
                                <tr>
                                    <td>{$setting.value}</td>
                                    <td>
                                        <div class="ui toggle checkbox">
                                            <input type="checkbox" name="{$setting.type}:alert"{if $setting.alert} checked{/if}>
                                            <label class="screenreader-only">{$ALERT}</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ui toggle checkbox">
                                            <input type="checkbox" name="{$setting.type}:email"{if $setting.email} checked{/if}>
                                            <label class="screenreader-only">{$EMAIL}</label>
                                        </div>
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>

                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="submit" class="ui primary button" value="{$SUBMIT}">
                </form>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}
