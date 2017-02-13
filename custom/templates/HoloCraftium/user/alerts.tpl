{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
  <div class="row">
	<div class="col-md-3">
	  {include file='user/navigation.tpl'}
	</div>
	<div class="col-md-9">
	  <div class="card">
		<div class="card-block">
		  <h2 class="card-title" style="display: inline;">{$ALERTS}</h2>
		  <span class="pull-right"><a href="{$DELETE_ALL_LINK}" class="btn btn-danger">{$DELETE_ALL}</a></span>
		  
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
				  {$alert->content} 
				  <a href="{$alert->view_link}">{$CLICK_TO_VIEW}</a>
				  <span class="pull-right">
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