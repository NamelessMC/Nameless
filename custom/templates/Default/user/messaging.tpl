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
		  <h2 class="card-title" style="display:inline;">{$MESSAGING}</h2>
		  {if isset($NEW_MESSAGE)}<span class="float-md-right"><a href="{$NEW_MESSAGE_LINK}" class="btn btn-primary">{$NEW_MESSAGE}</a></span>{/if}
		  <br /><br />
		  
		  {if count($MESSAGES)}
		    <table class="table table-striped">
			  <thead>
				<tr>
				  <th>{$MESSAGE_TITLE}</th>
				  <th>{$PARTICIPANTS}</th>
				  <th>{$LAST_MESSAGE}</th>
				</tr>
			  </thead>
			  <tbody>
		        {foreach from=$MESSAGES item=message}
		        <tr>
				  <td><a href="{$message.link}">{$message.title}</a></td>
				  <td>{$message.participants}</td>
				  <td>
				    <div class="row">
				      <div class="col-md-3">
					    <div class="frame">
					      <a href="{$message.last_message_user_profile}"><img class="img-centre rounded" style="max-height:30px; max-width:30px;" src="{$message.last_message_user_avatar}" alt="{$message.last_message_user}" /></a>
					    </div>
				      </div>
				      <div class="col-md-9">
					    <span data-toggle="tooltip" data-trigger="hover" data-original-title="{$message.last_message_date_full}">{$message.last_message_date}</span><br /> {$BY} <a style="{$message.last_message_user_style}" data-poload="{$USER_INFO_URL}{$message.last_message_user_id}" data-html="true" data-placement="top" href="{$message.last_message_user_profile}">{$message.last_message_user}</a>
				      </div>
				    </div>
				  </td>
				</tr>
		        {/foreach}
			  </tbody>
			</table>
			
			{$PAGINATION}
			
		  {else}
		    {$NO_MESSAGES}
		  {/if}
		  
		</div>
	  </div>
	</div>
  </div>
</div>

{include file='footer.tpl'}