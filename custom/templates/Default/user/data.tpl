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
		  <h2 class="card-title">{$DATA}</h2>
			{nocache}
			{foreach from=$USER_DATA key=tblname item=value}
			<div class="card mb-3">
				<div class="card-header">{$tblname}</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									{if isset($value[0])}
									{foreach from=$value[0] key=key item=dat}
										<td scope="col">{$key}</td>
									{/foreach}
									{/if}
								</tr>
							</thead>
							<tbody>
								{foreach from=$value key=key item=dat}
									<tr>
									{foreach from=$dat key=col item=data}
										<td>{$data}</td>
									{/foreach}
									</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
				</div>
			</div>
			{/foreach}
			{/nocache}
		  {if isset($FORUM_GRAPH)}
			<div id="chartWrapper">
			  <h3 class="ui header" style="margin-left:20px;">{$FORUM_GRAPH}</h3>
			  <canvas id="dataChart" width="100%" height="40"></canvas>
			</div>
		  {/if}
		</div>
	  </div>
	</div>
  </div>
</div>
{include file='footer.tpl'}