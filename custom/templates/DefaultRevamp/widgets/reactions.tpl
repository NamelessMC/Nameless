<div class="ui fluid card">
    <div class="content">
        <div class="header">Reactions</div>
    </div>
    <div class="content">
        <table class="ui celled table">
            <thead>
            <tr>
                <th></th>
                <th>Recieved</th>
                <th>Given</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$ALL_REACTIONS item=reaction}
                <tr>
                    <td data-toggle="tooltip" data-content="{$reaction.name}">{$reaction.html}</td>
                    <td>{$reaction.recieved}</td>
                    <td>{$reaction.given}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    <div class="extra content">
        Karma: {$KARMA}
    </div>
</div>
