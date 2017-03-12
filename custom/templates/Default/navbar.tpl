<nav class="navbar navbar-fixed-top navbar-dark bg-primary">
  <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#navbar">
	&#9776;
  </button>
  <div class="collapse navbar-toggleable-xs" id="navbar">
	<div class="container">
	  <ul class="nav navbar-nav">
	    <a class="navbar-brand" href="/">{$SITE_NAME}</a>
 	    {foreach from=$NAV_LINKS key=name item=item}
		  {if isset($item.items)}
		    {* Dropdown *}
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.title}</a>
			    <div class="dropdown-menu">
				  {foreach from=$item.items item=dropdown}
				    <a class="dropdown-item" href="{$dropdown.link}" target="{$dropdown.target}">{$dropdown.title}</a>
				  {/foreach}
				</div>
			  </a>
			</li>
		  {else}
		    {* Normal link *}
			<li class="nav-item{if isset($item.active)} active{/if}">
			  <a class="nav-link{if isset($item.active)} white-text{/if}" href="{$item.link}" target="{$item.target}">{$item.title}</a></li>
		  {/if}
		{/foreach}
	  </ul>
	
	  <ul class="nav navbar-nav float-xs-right">
	    {if isset($MESSAGING_LINK)}
	    {* Private messages and alerts *}
		<li class="nav-item dropdown pm-dropdown">
		  <a href="#" class="nav-link dropdown-toggle no-caret" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-envelope"></i> <div style="display: inline;" id="pms"></div></span></a>
		  <div class="dropdown-menu pm-dropdown-menu">
		    <div id="pm_dropdown">{$LOADING}</div>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="{$MESSAGING_LINK}">{$VIEW_MESSAGES}</a>
		  </div>
		</li>
		
		<li class="nav-item dropdown alert-dropdown">
		  <a href="#" class="nav-link dropdown-toggle no-caret" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-flag"></i> <div style="display: inline;" id="alerts"></div></span></a>
		  <div class="dropdown-menu alert-dropdown-menu">
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
			  <div class="dropdown-menu">
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
	  </ul>
	</div>
  </div>
</nav>

<div class="container" style="padding-top: 5rem;">
  {* Global messages *}
  {if isset($MAINTENANCE_ENABLED)}
  <div class="alert alert-danger">
    {$MAINTENANCE_ENABLED}
  </div>
  {/if}
</div>