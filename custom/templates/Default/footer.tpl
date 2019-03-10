{* Punishment modal if necessary *}
{if isset($GLOBAL_WARNING_TITLE)}
  <div class="modal fade show-punishment" data-keyboard="false" data-backdrop="static" id="acknowledgeModal" tabindex="-1" role="dialog" aria-labelledby="acknowledgeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="acknowledgeModalLabel">{$GLOBAL_WARNING_TITLE}</h4>
        </div>
        <div class="modal-body">
          {$GLOBAL_WARNING_REASON}
        </div>
        <div class="modal-footer">
          <a href="{$GLOBAL_WARNING_ACKNOWLEDGE_LINK}" class="btn btn-warning">{$GLOBAL_WARNING_ACKNOWLEDGE}</a>
        </div>
      </div>
    </div>
  </div>
{/if}

<br />
<footer>
  <div class="container">
    <div class="card">
      <div class="card-body">
	    {*Social media*}
		{if !empty($SOCIAL_MEDIA_ICONS)}
		  {foreach from=$SOCIAL_MEDIA_ICONS item=icon}
			<a href="{$icon.link}" target="_blank"><i id="social-{$icon.short}" class="{if $icon.long neq 'envelope'}fab{else}fas{/if} fa-{$icon.long}-square fa-3x social"></i></a>
		  {/foreach}
		{/if}
        <span class="float-md-right">
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
				  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.icon} {$item.title}</a>
					<div class="dropdown-menu">
					  {foreach from=$item.items item=dropdown}
						<a class="dropdown-item" href="{$dropdown.link}" target="{$dropdown.target}">{$dropdown.icon} {$dropdown.title}</a>
					  {/foreach}
					</div>
				  </a>
				</li>
			  {else}
				{* Normal link *}
				<li class="nav-item">
				  <a class="nav-link{if isset($item.active)} active{/if}" href="{$item.link}" target="{$item.target}">{$item.icon} {$item.title}</a></li>
			  {/if}
			{/foreach}
			
			<li class="nav-item">
			  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
				&copy; {$SITE_NAME} {'Y'|date}
			  </a>
			  <div class="dropdown-menu" aria-labelledby="Preview">
				<a class="dropdown-item" target="_blank" href="https://namelessmc.com/">Powered by NamelessMC</a>
				<a class="dropdown-item" href="{$TERMS_LINK}">{$TERMS_TEXT}</a>
				<a class="dropdown-item" href="{$PRIVACY_LINK}">{$PRIVACY_TEXT}</a>
			  </div>
			</li>
		  </ul>
        </span>
      </div>
    </div>
  </div>
</footer>
<br />

{foreach from=$TEMPLATE_JS item=script}
	{$script}
{/foreach}

{if isset($NEW_UPDATE)}
	{if $NEW_UPDATE_URGENT ne true}
		<script type="text/javascript">
			$(document).ready(function(){
			    $('#closeUpdate').click(function(event){
			        event.preventDefault();

			        let expiry = new Date();
			        let length = 3600000;
			        expiry.setTime(expiry.getTime() + length);

			        $.cookie('update-alert-closed', 'true', { path: '/', expires: expiry });
                });

			    if($.cookie('update-alert-closed') === 'true'){
			        $('#updateAlert').hide();
                }
            });
		</script>
	{/if}
{/if}

</body>
</html>