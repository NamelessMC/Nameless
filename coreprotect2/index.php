<?php
// CoLWI v0.9.3
// Index page
// Copyright (c) 2015-2016 SimonOrJ

// Testing script
//error_reporting(-1);ini_set('display_errors', 'On');

// Get the configuration variable.
$c = require "config.php";

// Check login status.
require "res/php/login.php";
$login = new Login($c);
if (!$login->permission(Login::PERM_LOOKUP)) {
    header("Location: login.php?landing=.%2F");
    exit();
}

/* Psuedocode:
if not logged in
    redirect to 
    exit
*/

// Get the template file and initiate its class.
require "res/php/webtemplate.php";
$template = new WebTemplate($c, $login->getUsername());

// Is the lookup options in the GET request? (Check only via "action")
if ($gr = !empty($_GET['a'])) { // I forgot what "gr" stands for...
    $moreQuery = $_GET;
    if (!empty($moreQuery['lim'])) {
        $moreQuery['offet'] = $_GET['lim'];
        unset($moreQuery['lim']);
    }
    /*
    // Get output from conn.php
    include "conn.php";
    
    // The code from conn.php's shutdown function that never got to run
    if(!isset($out[0]["status"]))
        $out = array(array(
            'status' => 6,
            'reason' => "Uncaught error has made the script terminate too early."
        ));
    $out[0]["duration"] = microtime(true) - $timer;
    
    // TODO: Port JS to PHP
    $tableOutput = $out;
    */
}
// TODO: Automatically get result thingies.
?><!-- CoreProtect LWI by SimonOrJ. All Rights Reserved. -->
<!DOCTYPE html>
<html>
  <?php 
  // Get the head from template.

  $template->head();
  ?>
  <body data-spy="scroll" data-target="#row-pages">

    <?php
    $template->navbar();
    ?>

    <div class="container">
      <?php
// Rejected from setup.php
if (!empty($_GET['from']) && $_GET['from'] === "setup.php" && $c['user'][$login->getUsername()]['perm'] !== 0):
      ?>
      <div class="alert alert-info alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Info:</strong> You were redirected from <code>setup.php</code> because you did not have sufficient permission.  Please consult your administrator.</div>
      <?php
endif;
// If it doesn't have write permission to the ./cache directory
if (!is_writable("./cache/")):
      ?>
      <!-- Write alert box -->
      <div class="alert alert-warning alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Notice:</strong> The directory <code>./cache/</code> is not writable. Lookup may take marginally longer to process, and autocomplete will not have updated data. Please refer to readme.md for setup information.</div>
      <?php
endif;
      ?>

      <!-- Lookup Form -->
      <div class="card">
        <div class="card-header"><span class="h4 card-title">Make a Lookup</span></div>
        <form id="lookupForm" class="card-block" role="form" method="get" action="./">
          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="lServer">Server Name</label>
            <div class="col-sm-10">
              <select class="form-control" id="lServer" name="server">
                <?php
// List servers
$sv = new FilesystemIterator("server/");
$svSet = isset($_GET['server']);
foreach ($sv as $fi) {
    if ($fi->getExtension() !== "php") continue;
    
    echo "<option";
    if ($svSet && $_GET['server'] === $fi->getBasename(".php"))
        echo " selected";
    echo ">".$fi->getBasename(".php")."</option>";
}
                ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-2 col-form-label">Actions</div>
            <div class="dtButtons btn-group col-lg-10">
              <label class="btn btn-secondary" for="lABl" data-toggle="tooltip" data-placement="top" title="Block manipulation">
                <input type="checkbox" id="lABl" name="a[]" value="block"<?php if (!$gr || in_array("block",$_GET['a'])) echo " checked";?>>
                Block
              </label>
              <label class="btn btn-secondary" for="lACl" data-toggle="tooltip" data-placement="top" title="Clickable events (e.g. Chest, door, buttons)">
                <input type="checkbox" id="lACl" name="a[]" value="click"<?php if ($gr && in_array("click",$_GET['a'])) echo " checked";?>>
                Click
              </label>
              <label class="btn btn-secondary" for="lACt" data-toggle="tooltip" data-placement="top" title="Item transaction from containers">
                <input type="checkbox" id="lACt" name="a[]" value="container"<?php if ($gr && in_array("container",$_GET['a'])) echo " checked";?>>
                Container
              </label>
              <label class="btn btn-secondary" for="lACh">
                <input type="checkbox" id="lACh" name="a[]" value="chat"<?php if ($gr && in_array("chat",$_GET['a'])) echo " checked";?>>
                Chat
              </label>
              <label class="btn btn-secondary" for="lACm">
                <input type="checkbox" id="lACm" name="a[]" value="command"<?php if ($gr && in_array("command",$_GET['a'])) echo " checked";?>>
                Command
              </label>
              <label class="btn btn-secondary" for="lAKi" data-toggle="tooltip" data-placement="top" title="Mob kills">
                <input type="checkbox" id="lAKi" name="a[]" value="kill"<?php if ($gr && in_array("kill",$_GET['a'])) echo " checked";?>>
                Kill
              </label>
              <label class="btn btn-secondary" for="lASe" data-toggle="tooltip" data-placement="top" title="Player login/logout event">
                <input type="checkbox" id="lASe" name="a[]" value="session"<?php if ($gr && in_array("session",$_GET['a'])) echo " checked";?>>
                Session
              </label>
              <label class="btn btn-secondary" for="lAUs" data-toggle="tooltip" data-placement="top" title="Username change history">
                <input type="checkbox" id="lAUs" name="a[]" value="username"<?php if ($gr && in_array("username",$_GET['a'])) echo " checked";?>>
                Username
              </label>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-2 col-form-label">Toggle</div>
            <div class="col-lg-10">
              <button class="btn btn-secondary" type="button" id="lRCToggle">Radius/Corners</button>
              <span class="dtButtons btn-group">
                <label class="btn btn-outline-success" for="lRY">
                  <input type="radio" id="lRY" name="rollback" value="1"<?php if ($gr && isset($_GET["rollback"]) && $_GET["rollback"] === "1") echo " checked";?>>
                  <span class="glyphicon glyphicon-ok"></span>
                </label>
                <label class="btn btn-secondary" for="lR">
                  <input type="radio" id="lR" name="rollback" value=""<?php if (!$gr || (isset($_GET["rollback"]) && $_GET["rollback"] === "")) echo " checked";?>>
                  Rollback
                </label>
                <label class="btn btn-outline-secondary" for="lRN">
                  <input type="radio" id="lRN" name="rollback" value="0"<?php if ($gr && isset($_GET["rollback"]) && $_GET["rollback"] === "0") echo " checked";?>>
                  <span class="glyphicon glyphicon-minus"></span>
                </label>
              </span>
            </div>
          </div>
          <div class="form-group row">
              <label id="lCorner1" class="col-sm-2 col-form-label" for="lCX">Center / Corner 1</label>
              <div class="col-lg-4 col-sm-10 groups-line">
                <div class="input-group" id="lC1">
                  <input class="form-control" type="number" id="lCX" name="xyz[]" placeholder="x"<?php if ($gr && isset($_GET["xyz"][0])) echo ' value="'.$_GET["xyz"][0].'"';?>>
                    <span class="input-group-btn" style="width:0"></span>
                  <input class="form-control" type="number" id="lCY" name="xyz[]" placeholder="y"<?php if ($gr && isset($_GET["xyz"][1])) echo ' value="'.$_GET["xyz"][1].'"';?>>
                    <span class="input-group-btn" style="width:0"></span>
                  <input class="form-control" type="number" id="lCZ" name="xyz[]" placeholder="z"<?php if ($gr && isset($_GET["xyz"][2])) echo ' value="'.$_GET["xyz"][2].'"';?>>
                </div>
              </div>
              <label id="lCorner2" class="col-sm-2 col-form-label" for="lCX2">Radius / Corner 2</label>
              <div class="col-lg-4 col-sm-10">
                <div class="input-group" id="lC2">
                  <input class="form-control" type="number" id="lCX2" name="xyz2[]" placeholder="Radius or x"<?php if ($gr && (isset($_GET["xyz"][0]) || isset($_GET["r"]))) echo ' value="'.(isset($_GET["xyz"][0]) ? $_GET["xyz2"][0] : $_GET["r"]).'"';?>>
                  <span class="input-group-btn lRadiusHide" style="width:0"></span>
                  <input class="form-control lRadiusHide" type="number" id="lCY2" name="xyz2[]" placeholder="y"<?php if ($gr && isset($_GET["xyz2"][1])) echo' value="'.$_GET["xyz2"][1].'"';?>>
                  <span class="input-group-btn lRadiusHide" style="width:0"></span>
                  <input class="form-control lRadiusHide" type="number" id="lCZ2" name="xyz2[]" placeholder="z"<?php if ($gr && isset($_GET["xyz2"][2])) echo ' value="'.$_GET["xyz2"][2].'"';?>>
                </div>
              </div>
          </div>
          <div class="form-group row">
            <label class="col-xs-2 col-form-label" for="lWorld">World</label>
            <div class="col-xs-10">
              <input class="form-control autocomplete" data-query-table="world" type="text" id="lWorld" name="wid" placeholder="world"<?php if ($gr && isset($_GET["wid"])) echo ' value="'.$_GET["wid"].'"';?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="lU">Users</label>
            <div class="col-lg-10">
              <div class="input-group">
                <span class="dtButtons input-group-btn">
                  <label class="btn btn-secondary" for="lUEx">
                    <input type="checkbox" id="lUEx" name="e[]" value="u"<?php if ($gr && !empty($_GET['e']) && in_array("u",$_GET["e"])) echo " checked";?>>
                    Exclude
                  </label>
                </span>
                <input class="form-control autocomplete" data-query-table="user" type="text" pattern="((#[a-zA-Z_]+)|([a-zA-Z0-9_]{2,16}))(,\s?((#[a-zA-Z_]+)|([a-zA-Z0-9_]{2,16})))*" id="lU" name="u" placeholder="Separate by single comma(,)"<?php if ($gr && isset($_GET["u"])) echo ' value="'.$_GET["u"].'"';?>>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="lB">Blocks</label>
            <div class="col-lg-10">
              <div class="input-group">
                <span class="dtButtons input-group-btn">
                  <label class="btn btn-secondary" for="lBEx">
                    <input type="checkbox" id="lBEx" name="e[]" value="b"<?php if ($gr && !empty($_GET['e']) && in_array("b",$_GET["e"])) echo " checked";?>>
                    Exclude
                  </label>
                </span>
                <input class="form-control autocomplete" data-query-table="material" type="text" pattern="([^:]+:[^:,]+)+" id="lB" name="b" placeholder="minecraft:<block> - Separate by single comma(,)"<?php if ($gr && isset($_GET["b"])) echo ' value="'.$_GET["b"].'"';?>>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="lKeyword">Keyword</label>
            <div class="col-sm-10">
              <input class="form-control" type="text" id="lKeyword" name="keyword"<?php if ($gr && isset($_GET["keyword"])) echo ' value="'.$_GET["keyword"].'"';?> data-toggle="tooltip" data-placement="top" title='Space [&nbsp;] for AND. Comma [,] for OR. Enclose terms in quotes [""] to escape spaces/commas. Only applies to chat and command.'></div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="lT">Date/Time</label>
            <div class="col-lg-4 col-sm-10 groups-line">
              <div class="input-group">
                <span class="dtButtons input-group-btn">
                  <label class="btn btn-secondary" for="lTRv">
                    <input type="checkbox" id="lTRv" name="asendt"<?php if ($gr && isset($_GET["asendt"]) && $_GET["asendt"] === "on") echo " checked";?>>
                    Reverse
                  </label>
                </span>
                <input class="form-control" type="<?php echo ($gr && isset($_GET["t"]) && is_numeric($_GET["t"])) ? "number" : "datetime-local";?>" id="lT" name="t" placeholder="0000-00-00T00:00:00"<?php if ($gr && isset($_GET["t"])) echo ' value="'.$_GET["t"].'"';?>>
              </div>
            </div>
            <input type="hidden" name="unixtime" value="on">
            <label class="col-sm-2 col-form-label" for="lLimit">Limit</label>
            <div class="col-lg-4 col-sm-10">
              <input class="form-control" type="number" id="lLimit" name="lim" min="1" placeholder="<?php echo $c['form']['limit'];?>"<?php if ($gr && isset($_GET['lim'])) echo ' value="'.$_GET['lim'].'"';?>>
            </div>
          </div>
          <div class="row">
            <div class="offset-sm-2 col-sm-10">
              <input class="btn btn-secondary" type="submit" id="lSubmit" value="Make a Lookup">
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Output table -->
    <div class="container-fluid">
      <table id="mainTable" class="table table-sm table-striped">
        <thead class="thead-inverse">
          <tr id="row-0"><th>#</th><th>Time</th><th>User</th><th>Action</th><th>Coordinates / World</th><th>Block/Item:Data</th><th>Amount</th><th>Rollback</th></tr>
        </thead>
        <tbody id="outputTable">
          <?php
echo isset($tableOutput) ? $tableOutput : '<tr><th scope="row">-</th><td colspan="7">Please submit a lookup.</td></tr>';
          ?>
        </tbody>
        <caption id="queryTime"></caption>
      </table>
    </div>

    <!-- Load More form -->
    <form class="container" id="loadMoreForm" method="post" action="./<?php if (!empty($moreQuery)) echo "?".http_build_query($moreQuery);?>">
      <input id="mOffset" type="hidden" name="offset" value="0">
      <div class="row">
        <div class="col-sm-offset-2 col-sm-8 form-group input-group">
          <label class="input-group-addon" for="mLimit">load next </label>
          <input id="mLimit" class="form-control" type="number" name="lim" min="1" placeholder="<?php echo $c['form']['loadMoreLimit'];?>">
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-offset-2 col-sm-8">
          <input class="btn btn-secondary" id="mSubmit" type="submit" value="Load more"<?php if (empty($moreQuery)) echo " disabled";?>>
        </div>
      </div>
    </form>


    <div class="container">
      <?php if ($login->permission(Login::PERM_PURGE)):?>
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Advanced
        </button>
        <div class="dropdown-menu" aria-labelledby="Advanced">
          <?php if ($login->permission(Login::PERM_SETUP)):?>
          <a class="dropdown-item" href="setup.php">Setup</a>
          <?php endif;?>
          <button id="purgeServerCache" class="dropdown-item list-group-item-danger">Purge server cache</button>
          <button id="purgeAllCache" class="dropdown-item list-group-item-danger">Purge all cache</button>
        </div>
      </div>
      <?php endif;?>
	  <p><a href="https://github.com/SimonOrJ/CoreProtect-Lookup-Web-Interface">GitHub project page</a></p>
    </div>

    <!-- Copyright Message -->
    <div class="container-fluid">
      <p>&copy; <?php echo str_replace("%year%", date("Y"),$c["copyright"]);?>. <span class="">CoreProtect LWI v0.9.3-beta &ndash; Created by <a href="http://simonorj.com/">SimonOrJ</a>, ported to <a href="https://www.spigotmc.org/resources/namelessmc.11434/">NamelessMC</a> by <a href="http://muhsinunc.ml/">MuhsinunC</a>.</span></p>
    </div>

    <!-- All the scripting needs -->
    <?php
    // Unset sensetitive information before sending it to the JS.
    unset($c['login']);
    unset($c['user']);
    ?>
    <script>
    // Quick Styling for JS-enabled browser
    
    // Corner/Radius Reset
    document.getElementById("lCorner1").innerHTML = "Center";
    document.getElementById("lCorner2").innerHTML = "Radius";
    document.getElementById("lC2").className = "";
    a = document.getElementsByClassName("lRadiusHide");
    for (var i = 0; i < a.length; i++) a[i].style.display = "none";
    document.getElementById("lCX2").setAttribute("placeholder","Radius");
    
    // Add data-toggle attribute to checkboxes (and radio buttons) with dtButtons class
    a = document.getElementsByClassName("dtButtons");
    for (var i = 0; i < a.length; i++) a[i].setAttribute("data-toggle","buttons");
    document.getElementById("lT").setAttribute("placeholder","")
    document.getElementById("lT").setAttribute("type","text");
    document.getElementById("lT").removeAttribute("name");
    
    document.getElementById("lSubmit").disabled = true;
    document.getElementById("mSubmit").disabled = true;
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js">// JQuery</script>
    <script src="res/js/buttons.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js">// Dropdown</script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/tether/1.1.1/js/tether.min.js">// Bootstrap dependency</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous">// Bootstrap (Alpha!)</script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment-with-locales.min.js">// datetime-picker dependency</script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js">// Datetime Picker</script>
    <script src="res/js/lookup.js"></script>
  </body>
</html>
