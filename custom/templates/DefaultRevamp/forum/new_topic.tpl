{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$CREATING_TOPIC_IN}
</h2>

{if count($ERROR)}
  <div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
      <div class="header">Error</div>
      <ul class="list">
        {foreach from=$ERROR item=error}
          <li>{$error}</li>
        {/foreach}
      </ul>
    </div>
  </div>
{/if}

<div class="ui padded segment" id="new-topic">
  <div class="ui stackable grid">
    <div class="ui centered row">
      <div class="ui sixteen wide tablet twelve wide computer column">
        <form class="ui form" action="" method="post" id="form-new-topic">
          <div class="field">
            <label for="name">{$TOPIC_TITLE}:</label>
            <input type="text" name="title" placeholder="{$TOPIC_TITLE}" maxlength="64">
          </div>
          {if count($LABELS)}
            <div class="inline fields">
              <label>Label:</label>
              {foreach from=$LABELS item=label}
                <div class="field">
                  <div class="ui radio checkbox">
                    <input type="radio" name="topic_label" id="{$label.id}" value="{$label.id}" hidden>
                    <label for="{$label.id}">{$label.html}</label>
                  </div>
                </div>
              {/foreach}
            </div>
          {/if}
          {if isset($MARKDOWN)}
            <div class="field">
              <label for="markdown">Content:</label>
              <textarea name="content" id="markdown"></textarea>
            </div>
          {else}
            <div class="field">
              <label for="editor">Content:</label>
              <textarea name="content" id="reply"></textarea>
            </div>
          {/if}
          {$TOKEN}
          <input type="submit" class="ui primary button" value="{$SUBMIT}">
          <a class="ui negative button" href="#" data-toggle="modal" data-target="#modal-cancel">{$CANCEL}</a>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="ui small modal" id="modal-cancel">
  <div class="header">
    {$CANCEL}
  </div>
  <div class="content">
    {$CONFIRM_CANCEL}
  </div>
  <div class="actions">
    <button type="button" class="ui negative button">{$CLOSE}</button>
    <a class="ui positive button" href="{$FORUM_LINK}">{$CANCEL}</a>
  </div>
</div>

{include file='footer.tpl'}