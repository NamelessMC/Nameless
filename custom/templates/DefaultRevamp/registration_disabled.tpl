{extends 'layouts/default.tpl'}

{block 'body'}
  <div class="ui container">
    <h2 class="ui header">
        {$CREATE_AN_ACCOUNT}
    </h2>

    <div class="ui info message">
      <div class="content">
          {$REGISTRATION_DISABLED}
      </div>
    </div>
  </div>
{/block}