{include file='navbar.tpl'}

<div class="container">
    <div class="row">
        <div class="col-md-3">
            {include file='mod/navigation.tpl'}
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title" style="display:inline;">{$PUNISHMENTS}</h2>
                    <span class="pull-right">
                        <a href="{$BACK_LINK}" class="btn btn-info">{$BACK}</a>
                    </span>
                    <hr />
                    <h4>{$VIEWING_USER}</h4>
                    {if isset($ERROR)}
                        <div class="alert alert-danger">{$ERROR}</div>
                    {/if}
                    {if isset($SUCCESS)}
                        <div class="alert alert-success">{$SUCCESS}</div>
                    {/if}
                    <div class="row">
                        {if isset($WARN)}
                        <div class="col-md-4" style="text-align: center">
                            <a href="#" data-toggle="modal" data-target="#warnModal" class="btn btn-warning">{$WARN}</a>
                        </div>
                        {/if}
                        {if isset($BAN)}
                        <div class="col-md-4" style="text-align: center">
                            <a href="#" data-toggle="modal" data-target="#banModal" class="btn btn-danger">{$BAN}</a>
                        </div>
                        {/if}
                        {if isset($BAN_IP)}
                        <div class="col-md-4" style="text-align: center">
                            <a href="#" data-toggle="modal" data-target="#banIPModal" class="btn btn-danger">{$BAN_IP}</a>
                        </div>
                        {/if}
                    </div>
                    <hr />
                    <h4>{$PREVIOUS_PUNISHMENTS}</h4>
                    {if count($PREVIOUS_PUNISHMENTS_LIST)}
                        {foreach from=$PREVIOUS_PUNISHMENTS_LIST item=punishment name=punishments}
                            <div class="card">
                                <div class="card-header">
                                    {if $punishment.type_numeric == 1}
                                        <span class="badge badge-danger">{$punishment.type}</span>
                                    {elseif $punishment.type_numeric == 2}
                                        <span class="badge badge-warning">{$punishment.type}</span>
                                    {elseif $punishment.type_numeric == 3}
                                        <span class="badge badge-danger">{$punishment.type}</span>
                                    {/if}
                                    {if $punishment.revoked == 1}
                                        <span class="badge badge-info">{$REVOKED}</span>
                                    {/if}
                                    {if $punishment.acknowledged == 1}
                                        <span class="badge badge-success">{$ACKNOWLEDGED}</span>
                                    {/if}
                                    <a href="{$punishment.issued_by_profile}" style="{$punishment.issued_by_style}">{$punishment.issued_by_nickname}</a>
                                    <span class="pull-right"><span data-toggle="tooltip" data-original-title="{$punishment.date_full}">{$punishment.date_friendly}</span></span>
                                </div>
                                <div class="card-body">
                                    {$punishment.reason}
                                    {if $punishment.revoked == 0 && $punishment.revoke_link != 'none'}
                                        <hr />
                                        <a href="{$punishment.revoke_link}" class="btn btn-warning" onclick="return confirm('{$punishment.confirm_revoke_punishment}');">{$REVOKE}</a>
                                    {/if}
                                </div>
                            </div>
                            {if not $smarty.foreach.punishments.last}<br />{/if}
                        {/foreach}
                    {else}
                        <div class="alert alert-info">
                            {$NO_PREVIOUS_PUNISHMENTS}
                        </div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>

{if isset($WARN)}
<div class="modal fade" id="warnModal" tabindex="-1" role="dialog" aria-labelledby="warnModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="warnModalLabel">{$WARN_USER}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="InputReason">{$REASON}</label>
                        <textarea class="form-control" id="InputReason" name="reason"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">{$CANCEL}</button>
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="hidden" name="type" value="warn">
                    <input type="submit" class="btn btn-danger" value="{$SUBMIT}">
                </div>
            </form>
        </div>
    </div>
</div>
{/if}
{if isset($BAN)}
<div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-labelledby="banModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="banModalLabel">{$BAN_USER}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="InputReason">{$REASON}</label>
                        <textarea class="form-control" id="InputReason" name="reason"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">{$CANCEL}</button>
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="hidden" name="type" value="ban">
                    <input type="submit" class="btn btn-danger" value="{$SUBMIT}">
                </div>
            </form>
        </div>
    </div>
</div>
{/if}
{if isset($BAN_IP)}
<div class="modal fade" id="banIPModal" tabindex="-1" role="dialog" aria-labelledby="banIPModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="banIPModalLabel">{$BAN_IP}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="InputReason">{$REASON}</label>
                        <textarea class="form-control" id="InputReason" name="reason"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">{$CANCEL}</button>
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="hidden" name="type" value="ban_ip">
                    <input type="submit" class="btn btn-danger" value="{$SUBMIT}">
                </div>
            </form>
        </div>
    </div>
</div>
{/if}

{include file='footer.tpl'}