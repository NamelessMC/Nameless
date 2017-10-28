{include file='navbar.tpl'}

<div class="container">
  <div class="row">
	<div class="col-md-3">
	  {include file='mod/navigation.tpl'}
	</div>
	<div class="col-md-9">
	  <div class="card">
		<div class="card-body">
		  <h2 class="card-title">{$OVERVIEW}</h2>
		  {$OPEN_REPORTS}
		</div>
	  </div>
	</div>
  </div>
</div>

{include file='footer.tpl'}