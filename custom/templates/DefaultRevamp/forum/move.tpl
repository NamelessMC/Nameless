{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$MOVE_TOPIC}
</h2>

<div class="ui padded segment" id="move-topic">
  <div class="ui stackable grid">
    <div class="ui centered row">
      <div class="ui sixteen wide tablet ten wide computer column">
        <form class="ui form" action="" method="post" id="form-move-topic">
          <div class="field">
            <label for="name">{$MOVE_TO}</label>
            <select name="forum" id="InputForum">
              {foreach from=$FORUMS item=forum}
                <option value="{$forum->id}">{$forum->forum_title|escape}</option>
              {/foreach}
            </select>
          </div>
          <input type="hidden" name="token" value="{$TOKEN}">
          <input type="submit" class="ui primary button" value="{$SUBMIT}">
          <a class="ui negative button" href="{$CANCEL_LINK}" onclick="return confirm('{$CONFIRM_CANCEL}')">{$CANCEL}</a>
        </form>
      </div>
    </div>
  </div>
</div>

{include file='footer.tpl'}