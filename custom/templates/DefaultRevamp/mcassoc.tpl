{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$VERIFY_ACCOUNT}
  <div class="sub header">{$VERIFY_ACCOUNT_HELP}</div>
</h2>

{if !isset($STEP)}
  <div class="ui padded segment" id="mcassoc-body">
    {$MCASSOC}
  </div>
{else}
  {if isset($ERROR)}
    <div class="ui error icon message">
      <i class="x icon"></i>
      <div class="content">
        <div class="header">Error</div>
        {$ERROR}
        <br />
        <b><a href="{$RETRY_LINK}">{$RETRY_TEXT}</a></b>
      </div>
    </div>
  {else if isset($SUCCESS)}
    <div class="ui success icon message">
      <i class="check icon"></i>
      <div class="content">
        <div class="header">Success</div>
        {$SUCCESS}
        <br />
        <b><a href="{$LOGIN_LINK}">{$LOGIN_TEXT}</a></b>
      </div>
    </div>
  {/if}
{/if}

{include file='footer.tpl'}