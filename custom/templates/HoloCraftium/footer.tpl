<footer>
  <div class="container">
    <div class="card">
      <div class="card-block">
	    {*Social media*}
		{if !empty($SOCIAL_MEDIA_ICONS)}
		  {foreach from=$SOCIAL_MEDIA_ICONS item=icon}
		    <a href="{$icon.link}" target="_blank"><i id="social-{$icon.short}" class="fa fa-{$icon.long}-square fa-3x social"></i></a>
		  {/foreach}
		{/if}
        <span class="pull-right">
		  <ul class="nav nav-inline dropup">
		    {if $PAGE_LOAD_TIME}
		    <li class="nav-item">
			  <a class="nav-link" href="#" onClick="return false;" data-toggle="tooltip" id="page_load_tooltip" title="Page loading.."><i class="fa fa-tachometer fa-fw"></i></a>
			</li>
		    {/if}

			{foreach from=$FOOTER_NAVIGATION key=name item=item}
			  {if isset($item.items)}
				{* Dropup *}
				<li class="nav-item">
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
				<li class="nav-item">
				  <a class="nav-link{if isset($item.active)} active{/if}" href="{$item.link}" target="{$item.target}">{$item.title}</a></li>
			  {/if}
			{/foreach}
			
			<li class="nav-item">
			  <a class="nav-link"href="#">
				&copy; {$SITE_NAME} {'Y'|date}
			  </a>
			</li>
		  </ul>
        </span>
      </div>
    </div>
  </div>
</footer>