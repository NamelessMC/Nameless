{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
  <div class="row">
	<div class="col-md-3">
	  {include file='user/navigation.tpl'}
	</div>
	<div class="col-md-9">
	  <div class="card">
		<div class="card-body">
		  <h2 class="card-title" style="display: inline;">{$ALERTS}</h2>
		  <span class="float-md-right"><a href="{$DELETE_ALL_LINK}" class="btn btn-danger">{$DELETE_ALL}</a></span>
		  
		  <br /><br />

		  <div class="table-responsive">
			<table class="table table-striped">
			  <colgroup>
				<col span="1" style="width: 100%;">
			  </colgroup>
			  {nocache}
			  {if count($ALERTS_LIST)}
			    {foreach from=$ALERTS_LIST item=alert}
			  <tr>
				<td>
				  {if $alert->read eq 0}<strong>{/if}{$alert->content}{if $alert->read eq 0}</strong>{/if}
				  <a href="{$alert->view_link}">{$CLICK_TO_VIEW}</a>
				  <span class="float-md-right">
					<span data-toggle="tooltip" data-trigger="hover" data-original-title="{$alert->date}">{$alert->date_nice}</span>
				  </span>
				</td>
			  </tr>
			    {/foreach}
			  {else}
			    {$NO_ALERTS}
			  {/if}
			  {/nocache}
			</table>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>

{include file='footer.tpl'}