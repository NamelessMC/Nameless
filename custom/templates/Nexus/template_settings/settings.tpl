{*
 *  Nexus · Admin template settings panel
 *  Rendered inside the StaffCP → Layout → Templates → "Settings" view.
 *  Uses the panel template's own chrome (so we DON'T include header/footer).
*}
{if isset($TEMPLATE_ERRORS)}
  <div class="ui error message">
    <ul class="list">
      {foreach from=$TEMPLATE_ERRORS item=error}<li>{$error}</li>{/foreach}
    </ul>
  </div>
{/if}

<form action="" method="post" class="ui form">
  <p style="color: #555;">{$NEXUS_NOTE}</p>

  <div class="field">
    <label for="nexusAccent">{$NEXUS_ACCENT_LABEL}</label>
    <input type="text" id="nexusAccent" name="nexusAccent" value="{$NEXUS_ACCENT}"
           placeholder="#7C5CFF" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" required>
    <small>Hex colour, e.g. <code>#7C5CFF</code>. Theme rebuild needed after change.</small>
  </div>

  <input type="hidden" name="token" value="{$TOKEN}">
  <button type="submit" class="ui primary button">{$SUBMIT}</button>
</form>
