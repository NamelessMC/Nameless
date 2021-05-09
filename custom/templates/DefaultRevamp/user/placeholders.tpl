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
          {$PLACEHOLDERS}
        </h3>
        <div class="ui middle aligned">
          {nocache}
            {if count($PLACEHOLDERS_LIST)}
            <table class="ui fixed single line selectable unstackable small padded res table" id="subforums-table">
              <thead>
                <tr>
                  <th>Server ID</th>
                  <th>Name</th>
                  <th>Value</th>
                  <th>Last Updated</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              
              {foreach from=$PLACEHOLDERS_LIST key=name item=data}
              <tr>
                <td>
                  {$data.server_id}
                </td>
                <td>
                  {$data.name}
                </td>
                <td>
                  {$data.value}
                </td>
                <td>
                  {$data.last_updated}
                </td>
                <td>
                  <button class="ui yellow icon button"><i class="fas fa-cog icon black"></i></button>
                </td>
              </tr>
              {/foreach}

            </table>
            {else}
              <div class="ui info message">
                <div class="content">
                  {$NO_PLACEHOLDERS}
                </div>
              </div>
            {/if}
          {/nocache}
        </div>
      </div>

      <div class="ui ten wide tablet twelve wide computer column">
        <div class="ui segment">
          <h3 class="ui header">
            {$OPTIONS}
              <div class="sub header">kitpvp_rank</div>
          </h3>
          <div class="ui middle aligned">
            <table class="ui fixed single line selectable unstackable small padded res table" id="subforums-table">
              <thead>
                <tr>
                  <th class="center aligned">Show on Profile</th>
                  <th class="center aligned">Show on Forum Posts</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              
              <tr class="center aligned">
                <td>
                  <div class="ui checkbox">
                    <input type="checkbox" name="example">
                    <label></label>
                  </div>
                </td>
                <td>
                  <div class="ui checkbox">
                    <input type="checkbox" name="example">
                    <label></label>
                  </div>
                </td>
                <td>
                  <button class="ui primary icon button"><i class="fas fa-save icon white"></i></button>
                </td>
              </tr>

            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{include file='footer.tpl'}
