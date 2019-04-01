{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$TITLE}
</h2>

<div class="ui stackable grid" id="alerts">
  <div class="ui centered row">
    <div class="ui six wide tablet four wide computer column">
      {include file='user/navigation.tpl'}
    </div>
    <div class="ui ten wide tablet twelve wide computer column">
      <div class="ui segment">
        <h3 class="ui header">
          {$ALERTS}
          <div class="res right floated">
            <a class="ui mini negative button" href="{$DELETE_ALL_LINK}">{$DELETE_ALL}</a>
          </div>
        </h3>
        <div class="ui middle aligned relaxed selection list">
          {nocache}
            {if count($ALERTS_LIST)}
              {foreach from=$ALERTS_LIST key=name item=alert}
                <a class="item" href="{$alert->view_link}" data-toggle="popup">
                  <i class="angle right icon"></i>
                  <div class="content">
                    <div class="description">
                      {if $alert->read eq 0}
                        <strong>{$alert->content}</strong>
                      {else}
                        {$alert->content}
                      {/if}
                      <br />{$alert->date_nice}
                    </div>
                  </div>
                </a>
                <div class="ui wide popup">
                  <h4>{$alert->content}</h4>
                  {$alert->date}
                </div>
              {/foreach}
            {else}
              <div class="ui info message">
                <div class="content">
                  {$NO_ALERTS}
                </div>
              </div>
            {/if}
          {/nocache}
        </div>
      </div>
    </div>
  </div>
</div>

{include file='footer.tpl'}
