{if isset($NEW_UPDATE)}
    {if $NEW_UPDATE_URGENT eq true}
        <div class="alert alert-danger">
        {else}
            <div class="alert alert-primary alert-dismissible" id="updateAlert">
                <button type="button" class="close" id="closeUpdate" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            {/if}
            {$NEW_UPDATE}<br />
            <a href="{$UPDATE_LINK}" class="btn btn-primary" style="text-decoration:none">{$UPDATE}</a>
            <hr /> {$CURRENT_VERSION}
            <br />{$NEW_VERSION}
        </div>
    {/if}