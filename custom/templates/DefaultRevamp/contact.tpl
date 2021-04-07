{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$CONTACT}
</h2>

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
      <div class="header">Error</div>
      <ul class="list">
        {foreach from=$ERRORS item=error}
          <li>{$error}</li>
        {/foreach}
      </ul>
    </div>
  </div>
{/if}

{if isset($ERROR_EMAIL)}
  <div class="ui negative icon message">
    <i class="x icon"></i>
    <div class="content">
      <div class="header">{$ERROR_TITLE}</div>
      {$ERROR_EMAIL}
    </div>
  </div>
{/if}

{if isset($ERROR_CONTENT)}
  <div class="ui negative icon message">
    <i class="x icon"></i>
    <div class="content">
      <div class="header">{$ERROR_TITLE}</div>
      {$ERROR_CONTENT}
    </div>
  </div>
{/if}

<div class="ui padded segment" id="contact">
  <div class="ui grid">
    <div class="ui centered row">
      <div class="ui sixteen wide tablet ten wide computer column">
        <form class="ui form" action="" method="post" id="form-contact">
          <div class="field">
            <label>{$EMAIL}</label>
            <input type="email" name="email" id="email" placeholder="{$EMAIL}" tabindex="1">
          </div>
          <div class="field">
            <label>{$MESSAGE}</label>
            <textarea id="inputMessage" name="content" placeholder="{$MESSAGE}" tabindex="2"></textarea>
          </div>
          {if $CAPTCHA}
            <div class="field">
              {$CAPTCHA}
            </div>
          {/if}
          <div class="field">
            <input type="hidden" name="token" value="{$TOKEN}">
            <input type="submit" class="ui primary button" value="{$SUBMIT}" tabindex="3">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{include file='footer.tpl'}