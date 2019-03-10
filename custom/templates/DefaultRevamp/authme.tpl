{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$CONNECT_WITH_AUTHME}
  <div class="sub header">{$AUTHME_INFO}</div>
</h2>

{if count($ERRORS)}
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

{if isset($AUTHME_SUCCESS)}
  <div class="ui success icon message">
    <i class="check icon"></i>
    <div class="content">
      <div class="header">Success</div>
      {$AUTHME_SUCCESS}
    </div>
  </div>
{/if}

<div class="ui padded segment" id="authme">
  <div class="ui stackable grid">
    <div class="ui centered row">
      <div class="ui sixteen wide tablet ten wide computer column">
        <form class="ui form" action="" method="post" id="form-authme">
          <div class="field">
            <label for="inputEmail">{$EMAIL}</label>
            <input type="email" id="inputEmail" name="email" placeholder="{$EMAIL}" tabindex="1">
          </div>
          {if isset($NICKNAME)}
            <div class="field">
              <label for="inputNickname">{$NICKNAME}</label>
              <input type="text" id="inputNickname" name="nickname" placeholder="{$NICKNAME}" tabindex="2">
            </div>
          {/if}
          <input type="hidden" name="token" value="{$TOKEN}">
          <input type="submit" class="ui primary button" value="{$SUBMIT}" tabindex="3">
        </form>
      </div>
    </div>
  </div>
</div>

{include file='footer.tpl'}