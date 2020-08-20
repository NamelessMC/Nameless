<nav class="navbar navbar-expand-lg fixed-top navbar-{$NAV_STYLE} bg-{$NAV_BG}">
  <div class="container">
   <a class="navbar-brand" href="{$SITE_HOME}">{$SITE_NAME}</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	  <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
	  <ul class="navbar-nav mr-auto">
 	    {foreach from=$NAV_LINKS key=name item=item}
		  {if isset($item.items)}
		    {* Dropdown *}
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.icon} {$item.title}</a>
			  <div class="dropdown-menu">
			    {foreach from=$item.items item=dropdown}
				  <a class="dropdown-item" href="{$dropdown.link}" target="{$dropdown.target}">{$dropdown.icon} {$dropdown.title}</a>
				{/foreach}
			  </div>
			</li>
		  {else}
		    {* Normal link *}
			<li class="nav-item{if isset($item.active)} active{/if}">
			  <a class="nav-link" href="{$item.link}" target="{$item.target}">{$item.icon} {$item.title}</a></li>
		  {/if}
		{/foreach}
	  </ul>
	
	  <ul class="nav navbar-nav">
	    {if isset($MESSAGING_LINK)}
	    {* Private messages and alerts *}
		<li class="nav-item dropdown pm-dropdown">
		  <a href="#" class="nav-link dropdown-toggle no-caret" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-envelope"></i> <span class="mobile_only">{$MESSAGING}</span> <div style="display: inline;" id="pms"></div></span></a>
		  <div class="dropdown-menu pm-dropdown-menu dropdown-menu-right">
		    <div id="pm_dropdown">{$LOADING}</div>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="{$MESSAGING_LINK}">{$VIEW_MESSAGES}</a>
		  </div>
		</li>

		<li class="nav-item dropdown alert-dropdown">
		  <a href="#" class="nav-link dropdown-toggle no-caret" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-flag"></i> <span class="mobile_only">{$ALERTS}</span> <div style="display: inline;" id="alerts"></div></span></a>
		  <div class="dropdown-menu alert-dropdown-menu dropdown-menu-right">
		    <div id="alert_dropdown">{$LOADING}</div>
		    <div class="dropdown-divider"></div>
			<a class="dropdown-item" href="{$ALERTS_LINK}">{$VIEW_ALERTS}</a>
		  </div>
		</li>
		{/if}

		{foreach from=$USER_AREA key=name item=item}
		  {if isset($item.items)}
			{* Dropdown *}
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.title}</a>
			  <div class="dropdown-menu dropdown-menu-right">
				{foreach from=$item.items item=dropdown}
				  {if isset($dropdown.separator)}
				    <div class="dropdown-divider"></div>
				  {else}
				    <a class="dropdown-item" href="{$dropdown.link}" target="{$dropdown.target}">{$dropdown.title}</a>
				  {/if}
				{/foreach}
			  </div>
			</li>
		  {else}
			{* Normal link *}
			<li class="nav-item{if isset($item.active)} active{/if}" style="padding-right:10px;">
			  <a class="nav-link" href="{$item.link}" target="{$item.target}">{$item.title}</a>
			</li>
		  {/if}
		{/foreach}

		{if isset($USER_DROPDOWN)}
            {foreach from=$USER_DROPDOWN key=name item=item}
                {if isset($item.items)}
                    {* Dropdown *}
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            {if isset($LOGGED_IN_USER)}<img src="{$LOGGED_IN_USER.avatar}" alt="{$LOGGED_IN_USER.username}" class="rounded" style="max-height:25px;max-width:25px;"/>{/if} {$item.title}
						</a>
						<div class="dropdown-menu dropdown-menu-right">
                            {foreach from=$item.items item=dropdown}
                                {if isset($dropdown.separator)}
									<div class="dropdown-divider"></div>
                                {else}
									<a class="dropdown-item" href="{$dropdown.link}" target="{$dropdown.target}">{$dropdown.title}</a>
                                {/if}
                            {/foreach}
						</div>
					</li>
                {else}
                    {* Normal link *}
					<li class="nav-item{if isset($item.active)} active{/if}" style="padding-right:10px;">
						<a class="nav-link" href="{$item.link}" target="{$item.target}">{$item.title}</a>
					</li>
                {/if}
            {/foreach}
		{/if}
	  </ul>
    </div>
  </div>
</nav>

<div class="container" style="padding-top: 5rem;">
  {* Global messages *}
  {if isset($NEW_UPDATE)}
	  {if $NEW_UPDATE_URGENT eq true}
		  <div class="alert alert-danger">
	  {else}
		  <div class="alert alert-info alert-dismissible" id="updateAlert">
			  <button type="button" class="close" id="closeUpdate" data-dismiss="alert" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
			  </button>
	  {/if}
	  {$NEW_UPDATE}
	  <br />
	  <a href="{$UPDATE_LINK}" class="btn btn-primary">{$UPDATE}</a>
	  <hr />
	  {$CURRENT_VERSION}<br />
	  {$NEW_VERSION}
	  </div>
  {/if}
  {if isset($MAINTENANCE_ENABLED)}
  <div class="alert alert-danger alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
	  </button>
	  {$MAINTENANCE_ENABLED}
  </div>
  {/if}
  {if isset($MUST_VALIDATE_ACCOUNT)}
  	  <div class="alert alert-info">
  		  {$MUST_VALIDATE_ACCOUNT}
  	  </div>
  {/if}
	{if !empty($ANNOUNCEMENTS)}
		{foreach from=$ANNOUNCEMENTS item=$ANNOUNCEMENT}
		<div class="alert" id="announcement-{$ANNOUNCEMENT->id}" style="background-color:{$ANNOUNCEMENT->background_colour}; color:{$ANNOUNCEMENT->text_colour}">
			{if $ANNOUNCEMENT->closable}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			{/if}
			{if isset($ANNOUNCEMENT->icon)}
				<i class="{$ANNOUNCEMENT->icon}"></i>
			{/if}
			<h5>{$ANNOUNCEMENT->header}</h5>
			<p>{$ANNOUNCEMENT->message}</p>
		</div>
		{/foreach}
	{/if}
</div>
