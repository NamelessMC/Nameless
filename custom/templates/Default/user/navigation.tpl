<div class="card">
  <div class="card-body">
    <ul class="nav nav-pills flex-column">
	  {foreach from=$CC_NAV_LINKS key=name item=item}
	  <li class="nav-item">
		<a class="nav-link{if isset($item.active)} active{/if}" href="{$item.link}" target="{$item.target}">{$item.title}</a>
	  </li>
      {/foreach}
    </ul>
  </div>
</div>