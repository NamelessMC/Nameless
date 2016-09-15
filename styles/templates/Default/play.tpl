<div class="container">
  <div class="alert alert-info"><center>{$CONNECT_WITH}</center></div>
  <div class="row">
    <div class="col-md-3">
	  <div class="well">
	    <table class="table">
		  <tr class="{if $MAIN_ONLINE == 1}success{else}danger{/if}">
			<td><b>{$STATUS}</b></td>
			<td>{if $MAIN_ONLINE == 1}{$ONLINE}{else}{$OFFLINE}{/if}</td>
		  </tr>
		  <tr>
		    <td><b>{$PLAYERS_ONLINE}</b></td>
			<td>{$PLAYER_COUNT}</td>
		  </tr>
		  <tr>
		    <td><b>{$QUERIED_IN}</b></td>
			<td>{$TIMER}</td>
		  </tr>
		</table>
	  </div>
	</div>
	<div class="col-md-9">
	  <div class="well">
	    <h3>{$PLAY_TITLE}</h3>
	    {$SERVER_STATUS}
	  </div>
	</div>
  </div>
</div>