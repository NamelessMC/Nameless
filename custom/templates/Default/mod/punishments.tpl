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
                        <a href="{$VIEW_PUNISHMENTS_LINK}" class="btn btn-info">{$VIEW_PUNISHMENTS}</a>
                    </span>
                    <hr />
                    <table class="table table-bordered dataTables-users">
                        <colgroup>
                            <col span="1" style="width: 30%;">
                            <col span="1" style="width: 30%;">
                            <col span="1" style="width: 20%;">
                            <col span="1" style="width: 20%;">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>{$USERNAME}</th>
                            <th>{$GROUPS}</th>
                            <th>{$BANNED}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$USERS item=user}
                            <tr>
                                <td><a href="{$user.profile}" style="{$user.style}">{$user.username}</a></td>
                                <td>{foreach from=$user.groups item=group}{$group} {/foreach}</td>
                                <td>{if $user.banned eq 1}<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i>{else}<i class="fa fa-times-circle fa-2x" aria-hidden="true"></i>{/if}</td>
                                <td><a href="{$user.punish_link}" class="btn btn-info btn-sm">{$PUNISH}</a></td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}