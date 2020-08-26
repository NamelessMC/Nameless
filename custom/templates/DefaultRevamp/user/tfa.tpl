{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$TITLE}
</h2>

{if isset($ERROR)}
  <div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
      <div class="header">{$ERROR_TITLE}</div>
      {$ERROR}
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

<div class="ui stackable grid" id="tfa-code">
  <div class="ui centered row">
    <div class="ui six wide tablet four wide computer column">
      {include file='user/navigation.tpl'}
    </div>
    <div class="ui ten wide tablet twelve wide computer column">
      <div class="ui segment">
        <h3 class="ui header">{$TWO_FACTOR_AUTH}</h3>
        {if isset($TFA_SCAN_CODE_TEXT)}
          <div class="ui form">
            <div class="field">
              {$TFA_SCAN_CODE_TEXT}
              <br />
              <img src="{$IMG_SRC}">
            </div>
          </div>
          <div class="ui info message">
            {$TFA_CODE_TEXT} <strong>{$TFA_CODE}</strong>
          </div>
          <a class="ui primary button" href="{$LINK}">{$NEXT}</a>
          <a class="ui red button" href="{$CANCEL_LINK}">{$CANCEL}</a>
        {else}
          <form class="ui form" action="" method="post" id="form-tfa-code">
            <div class="field">
              {$TFA_ENTER_CODE}
              <input type="text" name="tfa_code">
            </div>
            <input type="hidden" name="token" value="{$TOKEN}">
            <input type="submit" class="ui primary button" value="{$SUBMIT}">
            <a class="ui negative button" href="{$CANCEL_LINK}" >{$CANCEL}</a>
          </form>
        {/if}
      </div>
    </div>
  </div>
</div>

{include file='footer.tpl'}