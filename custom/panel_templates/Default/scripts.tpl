{foreach from=$TEMPLATE_JS item=script}
{$script}
{/foreach}

{if isset($NEW_UPDATE)}
{if $NEW_UPDATE_URGENT ne true}
<script type="text/javascript">
    $(document).ready(function () {
        $('#closeUpdate').click(function (event) {
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

{if isset($DEBUGBAR_HTML)}
{$DEBUGBAR_HTML}
{/if}

<script type="text/javascript">
  function toggleDarkLightMode() {
    $.post("{$DARK_LIGHT_MODE_ACTION}", { token: "{$DARK_LIGHT_MODE_TOKEN}" })
      .done(function () {
        window.location.reload();
      });

    return false;
  }
</script>
