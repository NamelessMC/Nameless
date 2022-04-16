{extends 'layouts/default.tpl'}

{block 'body'}
  <div class="ui container">
    <h2 class="ui header">
        {$PRIVACY_POLICY}
    </h2>

    <div class="ui padded segment" id="privacy-policy">
      <p>{$POLICY}</p>
    </div>
  </div>
{/block}