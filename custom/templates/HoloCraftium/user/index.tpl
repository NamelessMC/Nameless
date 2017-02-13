{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
  <div class="row">
	<div class="col-md-3">
	  {include file='user/navigation.tpl'}
	</div>
	<div class="col-md-9">
	  <div class="card">
		<div class="card-block">
		  <h2 class="card-title">{$OVERVIEW}</h2>
		  <ul>
			{nocache}
			{foreach from=$USER_DETAILS_VALUES key=name item=value}
			<li>
			  {$name}: <strong>{$value}</strong>
			</li>
			{/foreach}
			{/nocache}
		  </ul>
		</div>
	  </div>
	</div>
  </div>
</div>

{include file='footer.tpl'}