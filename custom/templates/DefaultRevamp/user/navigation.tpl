<div class="ui fluid vertical menu">
    {foreach from=$CC_NAV_LINKS key=name item=item}
    <a class="item{if isset($item.active)} active{/if}" href="{$item.link}" target="{$item.target}">{$item.title}</a>
    {/foreach}
</div>