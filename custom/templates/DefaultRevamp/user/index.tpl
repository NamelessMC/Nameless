{extends 'user/layout.tpl'}

{block "userContent"}
  <div class="ui segment">
    <h3 class="ui header">{$OVERVIEW}</h3>
    <div class="ui relaxed list">
        {nocache}
            {foreach from=$USER_DETAILS_VALUES key=name item=value}
              <div class="item">
                <i class="angle right icon"></i>
                <div class="middle aligned content">
                  <span class="header">{$name}</span>
                  <div class="description">{$value}</div>
                </div>
              </div>
            {/foreach}
        {/nocache}
    </div>
  </div>
    {if isset($FORUM_GRAPH)}
      <div class="ui segment">
        <h3 class="ui header">{$FORUM_GRAPH}</h3>
        <div id="chartWrapper">
          <canvas id="dataChart" width="100%" height="40"></canvas>
        </div>
      </div>
    {/if}
{/block}