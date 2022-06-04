{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$EDITING_POST}
</h2>

{if isset($ERRORS)}
<div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
        <div class="header">{$ERROR_TITLE}</div>
        <ul class="list">
            {foreach from=$ERRORS item=error}
            <li>{$error}</li>
            {/foreach}
        </ul>
    </div>
</div>
{/if}

<div class="ui padded segment" id="post-edit">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet twelve wide computer column">
                <form class="ui form" action="" method="post" id="form-post-edit">
                    {if isset($EDITING_TOPIC)}
                    <div class="field">
                        <label for="title">{$TOPIC_TITLE}</label>
                        <input type="text" id="title" name="title" value="{$TOPIC_TITLE_VALUE}">
                    </div>
                    {if count($LABELS)}
                    <div class="inline fields labels">
                        {foreach from=$LABELS item=label}
                        <div class="field">
                            <div class="ui checkbox">
                                <input type="checkbox" name="topic_label[]" id="{$label.id}" value="{$label.id}" {if
                                    $label.active} checked="checked" {/if} hidden>
                                <label for="{$label.id}">{$label.html}</label>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                    {/if}
                    {/if}
                    <div class="field">
                        <label for="editor">{$CONTENT_LABEL}</label>
                        <textarea name="content" id="editor"></textarea>
                    </div>
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="submit" class="ui primary button" value="{$SUBMIT}">
                    <a class="ui negative button" href="{$CANCEL_LINK}"
                        onclick="return confirm('{$CONFIRM_CANCEL}')">{$CANCEL}</a>
                </form>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}
