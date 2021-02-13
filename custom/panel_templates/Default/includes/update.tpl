{if isset($NEW_UPDATE)}
    {if $NEW_UPDATE_URGENT eq true}
        <div class="alert bg-danger text-white">
    {else}
        <div class="alert bg-primary text-white alert-dismissible" id="updateAlert">
        <button type="button" class="close" id="closeUpdate" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    {/if}
    {$NEW_UPDATE}<br />
    <a href="{$UPDATE_LINK}" class="btn btn-info">{$UPDATE}</a>
    <hr style="border-color: rgba(0,0,0,.1)" /> {$CURRENT_VERSION}
    <br />{$NEW_VERSION}
    </div>
{/if}
