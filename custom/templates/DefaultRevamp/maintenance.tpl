{include file='header.tpl'}

    <div class="ui container" id="error-maintenance">
      <div class="ui segment">
        <h2 class="ui header">{$MAINTENANCE_TITLE}</h2>
        <div class="ui divider"></div>
        <p>{$MAINTENANCE_MESSAGE}</p>
        <div class="ui buttons">
          <button class="ui primary button" onclick="javascript:history.go(-1)">{$BACK}</button>
          <div class="or"></div>
          <button class="ui positive button" onclick="window.location.reload()">{$RETRY}</button>
        </div>
      </div>
    </div>
  
  </body>
</html>