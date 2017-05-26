(function () {
// CoLWI v0.9.3
// Setup JavaScript
// Copyright (c) 2015-2016 SimonOrJ

// this uses jQuery.

"use strict";

var $dbRadio = {
        mysql: {
            checkbox:   $("#dbMySQL"),
            button:     $("label[for=dbMySQL]")
        },
        sqlite: {
            checkbox:   $("#dbSQLite"),
            button:     $("label[for=dbSQLite]")
        }
    },
    $dbRows        = {
        mysql: {
            row:    $("div.dbCheckMySQL"),
            input:  $("div.dbCheckMySQL>div input"),
            button:    $("div.dbCheckMySQL>div label")
        },
        sqlite: {
            row:    $("#dbCheckSQLite"),
            input:  $("#dbCheckSQLite>div input"),
            button:    $("#dbCheckSQLite>div label")
        }
    },
    $dbPrefix       = $("#dbPrefix"),
    $dbDynmapURL    = $("#dbDmURL"),
    $dbDynmapZoom   = $("#dbDmZoom"),
    $dbDynmapMap    = $("#dbDmMap"),
    $updateButton   = $(".updateButton"),
    $serverSelect   = $("#dbSelect"),
    $newServerInput = $("#dbName"),
    $skipDbButton   = $("label[for=dbNodb]"),
    $skipDbCheckbox = $("#dbNodb"),
    $dbForm         = $("#setupDb"),
    $cfForm         = $("#setupCf"),
    $dbSubmit       = $("#dbSubmit"),
    $cfSubmit       = $("#cfSubmit");

$("input.jsCheck").val("enabled");

// Button Styling
$skipDbButton.click(function() {
    // Because checked state updates after this function runs through.
    setTimeout(function() {
        if ($skipDbCheckbox.prop("checked")) {
            $dbRows.mysql.row.css("display", "none");
            $dbRows.sqlite.row.css("display", "none");
            $dbRows.mysql.button.addClass("disabled");
            $dbRows.sqlite.button.addClass("disabled");
        } else {
            if ($dbRadio.mysql.checkbox.prop("checked")) {
                $dbRows.mysql.row.css("display", "");
                $dbRows.mysql.button.removeClass("disabled");
            } else if ($dbRadio.sqlite.checkbox.prop("checked")) {
                $dbRows.sqlite.row.css("display", "");
                $dbRows.sqlite.button.removeClass("disabled");
            }
        }
    }, 1)
});

$dbRadio.mysql.button.click(function() {
    $dbRows.sqlite.row.css("display", "none");
    $dbRows.sqlite.button.addClass("disabled");
    if (!$skipDbCheckbox.prop("checked")) {
        $dbRows.mysql.row.css("display", "");
        $dbRows.mysql.button.removeClass("disabled");
    }
});

$dbRadio.sqlite.button.click(function() {
    $dbRows.mysql.row.css("display", "none");
    $dbRows.mysql.button.addClass("disabled");
    if (!$skipDbCheckbox.prop("checked")) {
        $dbRows.sqlite.row.css("display", "");
        $dbRows.sqlite.button.removeClass("disabled");
    }
});

$serverSelect.change(function() {
    if ($serverSelect.val() === "") {
        $updateButton.css("display", "none");
    } else {
        // Get server configuration values
        $.ajax("setup.php", {
            dataType: "json",
            data: "server="+$serverSelect.val(),
            method: "POST",
            success: function(d) {
                if (d.dbtype === "mysql") {
                    $dbRadio.mysql.button.addClass("active");
                    $dbRadio.mysql.checkbox.addClass("checked", true);
                    $dbRadio.sqlite.button.removeClass("active");
                    $dbRadio.sqlite.checkbox.addClass("checked", false);
                    $dbRadio.mysql.button.trigger("click");
                } else if (d.dbtype === "sqlite") {
                    $dbRadio.sqlite.button.addClass("active");
                    $dbRadio.sqlite.checkbox.addClass("checked", true);
                    $dbRadio.mysql.button.removeClass("active");
                    $dbRadio.mysql.checkbox.addClass("checked", false);
                    $dbRadio.sqlite.button.trigger("click");
                    
                }
                $dbPrefix.val(d.co);
                $dbDynmapURL.val(d.dynmap.URL);
                $dbDynmapZoom.val(d.dynmap.zoom);
                $dbDynmapMap.val(d.dynmap.map);
            }
        })
        $updateButton.css("display", "");
    }
});

// Submit Handler
var newServer;
function errorOnSubmit(obj) {
    if (obj[0] === 3) {
        alert("Error 3:\n"+(obj[1] === null ? "The server wasn't able to connect to the configured database. Please correct the Server Configuration and try again." : "PDOException: "+obj[1]));
    } else {
        alert("Error "+obj[0]+":\n"+obj[1]);
    }
}

$dbForm.submit(function(e) {
    e.preventDefault();
    $.ajax("setup.php",{
        beforeSend: function() {
            $dbSubmit.prop("disabled",true);
            newServer = $serverSelect.val() ? false : $newServerInput.val();
            window.newServer = newServer;
        },
        data: $dbForm.serialize(),
        dataType: "json",
        method: "POST",
        complete: function(){
            $dbSubmit.prop("disabled",false);
        },
        success: function(data) {
            if (data[0] === 0) {
                alert("The server has been successfully "+(newServer ? "added" : "updated")+"!");
                if (newServer) {
                    $serverSelect.append("<option>"+newServer+"</option>");
                    $serverSelect.val(newServer);
                    $newServerInput.val("");
                }
            } else {
                errorOnSubmit(data);
            }
        },
    });
});

$cfForm.submit(function(e) {
    e.preventDefault();
    $.ajax("setup.php",{
        beforeSend: function() {
            $cfSubmit.prop("disabled",true);
        },
        data: $cfForm.serialize(),
        dataType: "json",
        method: "POST",
        complete: function(){
            $cfSubmit.prop("disabled",false);
        },
        success: function(data) {
            if (data[0] === 0) {
                alert("The configuration has been successfully updated.");
            } else {
                errorOnSubmit(data);
            }
        },
    });
});
}())