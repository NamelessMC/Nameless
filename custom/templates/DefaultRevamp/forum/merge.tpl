{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$MERGE_TOPICS}
</h2>

<div class="ui padded segment" id="merge-topic">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet ten wide computer column">
                <form class="ui form" action="" method="post" id="form-merge-topic">
                    <div class="field">
                        <label for="name">{$MERGE_INSTRUCTIONS}</label>
                        <select name="merge" id="InputTopic">
                            {foreach from=$TOPICS item=topic}
                            <option value="{$topic->id}">{$topic->topic_title|escape}</option>
                            {/foreach}
                        </select>
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