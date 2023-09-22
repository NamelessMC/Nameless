<form action="" method="post">
    <div class="form-group custom-control custom-switch">
        <input type="hidden" name="custom_avatars" value="0">
        <input id="inputCustomAvatars" name="custom_avatars" type="checkbox"
               class="custom-control-input" value="1" {if $CUSTOM_AVATARS_VALUE eq 1}
            checked{/if} />
        <label class="custom-control-label" for="inputCustomAvatars">{$CUSTOM_AVATARS}</label>
    </div>
    <div class="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
    </div>
</form>

<hr />

<strong>{$DEFAULT_AVATAR}</strong>

<br /><br />

<button class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">{$UPLOAD_NEW_IMAGE}</button>

<br />

{if count($IMAGES)}
    <form action="" method="post">
        <div class="form-group">
            <label for="selectDefaultAvatar">{$SELECT_DEFAULT_AVATAR}</label>
            <select class="image-picker show-html" id="selectDefaultAvatar" name="avatar">
                {foreach from=$IMAGES key=key item=item}
                    <option data-img-src="{$key}" value="{$item}" {if $DEFAULT_AVATAR_IMAGE eq $item} selected{/if}>{$item}</option>
                {/foreach}
            </select>
        </div>
        <div class="form-group">
            <input type="hidden" name="token" value="{$TOKEN}">
            <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
        </div>
    </form>
{else}
    {$NO_AVATARS}
{/if}
