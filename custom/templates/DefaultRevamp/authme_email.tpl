{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$CONNECT_WITH_AUTHME}
</h2>

{if count($ERRORS)}
  <div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
      <div class="header">{$ERROR}</div>
      <ul class="list">
        {foreach from=$ERRORS item=error}
          <li>{$error}</li>
        {/foreach}
      </ul>
    </div>
  </div>
{/if}

<div class="ui padded segment" id="authme-email">
  <div class="ui stackable grid">
    <div class="ui centered row">
      <div class="ui sixteen wide tablet ten wide computer column">
      
        <div class="ui success icon message">
          <i class="check icon"></i>
          <div class="content">
            <div class="header">{$AUTHME_SUCCESS}</div>
            {$AUTHME_INFO}
          </div>
        </div>
      
        <form class="ui form" action="" method="post" id="form-authme-email">
          <div class="field">
            <label for="inputEmail">{$EMAIL}</label>
            <input type="email" id="inputEmail" name="email" placeholder="{$EMAIL}">
          </div>

          {if isset($NICKNAME)}
            <div class="field">
              <label for="inputNickname">{$NICKNAME}</label>
              <input type="text" id="inputNickname" name="nickname" placeholder="{$NICKNAME}">
            </div>
          {/if}

          <div class="field">
            <input type="hidden" name="token" value="{$TOKEN}">
            <input type="submit" class="ui primary button" value="{$SUBMIT}">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{include file='footer.tpl'}

<div class="container">
  <div class="card">
    <div class="card-body">
      <form action="" method="post">
        <h2>{$CONNECT_WITH_AUTHME}</h2>
        <hr />

        {if isset($ERRORS)}
          <div class="alert alert-danger">
            {foreach from=$ERRORS item=error}
              {$error}<br />
            {/foreach}
          </div>
        {/if}
      </form>
    </div>
  </div>
</div>

{include file='footer.tpl'}