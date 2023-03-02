<div class="ui fluid card">
    <div class="content">
        <h4 class="ui header">Reactions</h4>
        <div class="description">
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
    </div>
</div>

