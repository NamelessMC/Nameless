{extends 'user/layout.tpl'}

{block "userContent"}
  <div class="ui segment">
    <h3 class="ui header">
        {$MESSAGE_TITLE}
      <div class="sub header">{$PARTICIPANTS_TEXT}: {$PARTICIPANTS}</div>
    </h3>
  </div>
    {$PAGINATION}
  <div class="res right floated">
    <a class="ui small primary button" href="{$BACK_LINK}">{$BACK}</a>
    <button class="ui small negative button" type="button" data-toggle="modal"
            data-target="#modal-leave">{$LEAVE_CONVERSATION}</button>
  </div>
    {foreach from=$MESSAGES item=message}
      <div class="ui fluid card" id="message">
        <div class="content">
          <img class="ui left floated mini circular image" src="{$message.author_avatar}">
          <div class="header">
            <a href="{$message.author_profile}" data-poload="{$USER_INFO_URL}{$message.author_id}"
               style="{$message.author_style}">{$message.author_username}</a>
          </div>
          <div class="meta">
            <span data-toggle="tooltip" data-content="{$message.message_date_full}">{$message.message_date}</span>
          </div>
          <div class="description forum_post">
              {$message.content}
          </div>
        </div>
      </div>
    {/foreach}
    {$PAGINATION}
  <div class="ui segment">
    <h3 class="ui header">{$NEW_REPLY}</h3>
    <form class="ui form" action="" method="post">
      <div class="field">
        <textarea name="content" id="reply"></textarea>
      </div>
      <input type="hidden" name="token" value="{$TOKEN}">
      <input type="submit" class="ui primary button" name="{$SUBMIT}">
    </form>
  </div>
{/block}

{block "modals" append}
  <div class="ui small modal" id="modal-leave">
    <div class="header">
        {$LEAVE_CONVERSATION}
    </div>
    <div class="content">
        {$CONFIRM_LEAVE}
      <form action="{$LEAVE_CONVERSATION_LINK}" method="post" id="leave-form">
        <input type="hidden" name="token" value="{$TOKEN}">
      </form>
    </div>
    <div class="actions">
      <a class="ui negative button">{$NO}</a>
      <a class="ui positive button" onclick="$('#leave-form').submit();">{$YES}</a>
    </div>
  </div>
{/block}