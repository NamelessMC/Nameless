{foreach from=$TEMPLATE_JS item=script}
    {$script}
{/foreach}

{if isset($NEW_UPDATE)}
{if $NEW_UPDATE_URGENT ne true}
<script type="text/javascript">
    $(document).ready(function() {
        $('#closeUpdate').click(function(event) {
            event.preventDefault();

            let expiry = new Date();
            let length = 3600000;
            expiry.setTime(expiry.getTime() + length);

            $.cookie('update-alert-closed', 'true', { path: '/', expires: expiry });
        });

        if ($.cookie('update-alert-closed') === 'true') {
            $('#updateAlert').hide();
        }
    });
</script>
{/if}
{/if}