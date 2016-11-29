{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
  <div class="row">
	<div class="col-md-3">
	  {include file='mod/navigation.tpl'}
	</div>
	<div class="col-md-9">
	  <div class="card">
		<div class="card-block">
		  <h2 class="card-title" style="display:inline;">{$REPORTS}</h2>
		  <span class="pull-right">
		    <a href="{$CHANGE_VIEW_LINK}" class="btn btn-info">{$CHANGE_VIEW}</a>
		  </span>
		  
		  <br /><br />
		  <table class="table">
		    <thead>
			  <tr>
			    <th>{$USER_REPORTED}</th>
				<th>{$COMMENTS}</th>
				<th>{$UPDATED_BY}</th>
				<th>{$ACTIONS}</th>
			  </tr>
			</thead>
			<tbody>
			  {foreach from=$ALL_REPORTS item=report}
			  <tr>
			    <td><a href="{$report.user_profile}" style="{$report.user_reported_style}">{$report.user_reported}</a></td>
				<td><i class="fa fa-comments" aria-hidden="true"></i> {$report.comments}</td>
				<td><a href="{$report.updated_by_profile}" style="{$report.updated_by_style}">{$report.updated_by}</a></td>
				<td><a href="{$report.link}" class="btn btn-primary">{$VIEW} &raquo;</a></td>
			  </tr>
			  {/foreach}
			</tbody>
		  </table>
		  
		</div>
	  </div>
	</div>
  </div>
</div>

{include file='footer.tpl'}