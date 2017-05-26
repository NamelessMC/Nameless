(function () {
// CoLWI v0.9.3
// Lookup JavaScript
// Copyright (c) 2015-2016 SimonOrJ

// this uses jQuery and jQuery UI.

"use strict";

// All the used DOM references in an object
var $form       = $("#lookupForm"),
    $server     = $("#lServer"),
    $coord      = {
        c1Label:    $("#lCorner1"),
        c2Label:    $("#lCorner2"),
        radiusHide: $(".lRadiusHide"),
        c1:         $("#lC1"),
        c2:         $("#lC2"),
        x1:         $("#lCX"),
        y1:         $("#lCY"),
        z1:         $("#lCZ"),
        x2:         $("#lCX2"),
        y2:         $("#lCY2"),
        z2:         $("#lCZ2")
    },
    $world      = $("#lWorld"),
    $user       = $("#lU"),
    $block      = $("#lB"),
    $date       = $("#lT"),
    $reverse    = {
        user: {
            button: $("label[for=lUEx]"),
            box: $("#lUEx")
        },
        block: {
            button: $("label[for=lBEx]"),
            box: $("#lBEx")
        },
        date: {
            button: $("label[for=lTRv]"),
            box: $("#lTRv")
        }
    },
    $limit      = $("#lLimit"),
    $submit     = $("#lSubmit"),
    $moreForm   = $("#loadMoreForm"),
    $moreLimit  = $("#mLimit"),
    $moreOffset = $("#mOffset"),
    $moreSubmit = $("#mSubmit"),
    $moreSubmit = $("#mSubmit"),
    $table      = $("#mainTable"),
    $output     = $("#outputTable"),
    $queryTime  = $("#queryTime"),
    $pages      = $("#row-pages"),
    s           = {server: ""},
    c;

// Get configuration first, then load the configuration-variable-sensetitive things.
$.getJSON("config.json", function(data) {
    c = data;
    
    // Set the locale and format
    moment.locale(c.form.locale);
    moment.updateLocale(c.form.locale, {calendar: {sameElse: "LLLL"}});
    moment.defaultFormat = c.form.dateFormat+" "+c.form.timeFormat;
    
    // Translate the date field
    $date.val(moment($date.val(), ["X", "YYYY-MM-DDTHH:mm", c.form.dateFormat+" "+c.form.timeFormat], true).format());
    
    // Set datetimepicker
    $date.datetimepicker({
        locale: c.form.locale,
        format: c.form.dateFormat+" "+c.form.timeFormat
    });
    
    // Unlock the lookup button
    $submit.prop("disabled", false);
});

$('[data-toggle="tooltip"]').tooltip();

// Radius/Corners toggle
function radius(boolCorner) {
    if(($coord.c1Label.text() === "Center")||boolCorner) {
        $coord.c1Label.text("Corner 1");
        $coord.c2Label.text("Corner 2");
        $coord.c2.addClass("input-group");
        $coord.radiusHide.show();
        $coord.x2.attr("placeholder","x");
    } else {
        $coord.c1Label.text("Center");
        $coord.c2Label.text("Radius");
        $coord.c2.removeClass("input-group");
        $coord.y2.val("");
        $coord.z2.val("");
        $coord.radiusHide.hide();
        $coord.x2.attr("placeholder","Radius");
    }
}
$("#lRCToggle").click(function(){radius();});

// Some CSV Function
var csv = {
    append: function (text, value) {
        return $.inArray(value, text.split(/, ?/)) === -1 ? text + ", " + value : text;
    },
    array: function (value) {
        return value.split(/, ?/);
    },
    join: function (array) {
        return array.join(", ");
    }
}

// Autocomplete
var queryTable;
$(".autocomplete")
    // don't navigate away from the field on tab when selecting an item
    .bind( "keydown", function( e ) {
        queryTable = this.getAttribute("data-query-table");
        if ( e.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
            e.preventDefault();
        }
    })
    .autocomplete({
        source: function( request, response ){
            var a = csv.array(request.term);
            $.ajax("autocomplete.php",{
                data: {
                    s : $server.val(),
                    a : queryTable,
                    b : a.pop(),
                    e : a,
                    l : 6
                },
                dataType: "json",
                method: "POST",
                success: function(data){
                    response(data);
                }
            });
        },
        focus: function( e, ui ) {
            var terms = csv.array(this.value);
            terms.pop();
            terms.push( ui.item.value );
            this.value = csv.join(terms);
            return false;
        },
        select: function( e, ui ) {
            var terms = csv.array(this.value);
            terms.pop();
            terms.push( ui.item.value );
            this.value = csv.join(terms);
            return false;
        }
    });

// Dropdown Menu Listener
// TODO: Fix this
$output.on("click", ".rDrop .cPointer", function(){
    var $this = $(this),
        $par = $this.parent(),
        val,
        nVal;
    if($this.hasClass("t")) {
        nVal = moment($par.parent().attr("data-time"),"X").format();
        if($this.hasClass("Asc")) {
            $reverse.date.box.prop("checked",true);
            $reverse.date.button.addClass("active");
        } else if($this.hasClass("Desc")) {
            $reverse.date.box.prop("checked",false);
            $reverse.date.button.removeClass("active");
        }
        $date.val(nVal);
    } else if($this.hasClass("u")) {
        val = $user.val();
        nVal = $par.prev().text();
        if($this.hasClass("Sch")) {
            if($reverse.user.box.prop("checked")){
                $reverse.user.box.prop("checked",false);
                $reverse.user.button.removeClass("active");
                $user.val(nVal);
            } else if(val === ""){
                $user.val(nVal);
            } else {
                $user.val(csv.append(val,nVal));
            }
        } else if($this.hasClass("ESch")) {
            if(!$reverse.user.box.prop("checked")){
                $reverse.user.box.prop("checked",true);
                $reverse.user.button.addClass("active");
                $user.val(nVal);
            } else if(val === ""){
                $user.val(nVal);
            } else {
                $user.val(csv.append(val,nVal));
            }
        }
    }
    else if($this.hasClass("c")) {
        nVal = $par.prev().text().split(" ");
        if($this.hasClass("Fl1")) {
            $coord.x1.val(nVal[0]);
            $coord.y1.val(nVal[1]);
            $coord.z1.val(nVal[2]);
            $world.val(nVal[3]);
        } else if($this.hasClass("Fl2")) {
            radius(true);
            $coord.x2.val(nVal[0]);
            $coord.y2.val(nVal[1]);
            $coord.z2.val(nVal[2]);
            $world.val(nVal[3]);
        } else if($this.hasClass("DMap")) {
            window.open(s.dynmap.URL+"?worldname="+nVal[3]+"&mapname="+s.dynmap.map+"&zoom="+s.dynmap.zoom+"&x="+nVal[0]+"&y="+nVal[1]+"&z="+nVal[2],"CoLWI-dmap");
        }
    }
    else if($this.hasClass("b")) {
        val = $block.val();
        nVal = $par.parent().attr("data-block");
        if($this.hasClass("Sch")) {
            if($reverse.block.box.prop("checked")){
                $reverse.block.box.prop("checked",false);
                $reverse.block.button.removeClass("active");
                $block.val(nVal);
            } else if (val === "") {
                $block.val(nVal);
            } else {
                $block.val(csv.append(val,nVal));
            }
        }
        else if($this.hasClass("ESch")) {
            if(!$reverse.block.box.prop("checked")){
                $reverse.block.box.prop("checked",true);
                $reverse.block.button.addClass("active");
                $block.val(nVal);
            } else if (val === "") {
                $block.val(nVal);
            } else {
                $block.val(csv.append(val,nVal));
            }
        }
    }
});

// Purge server cache button
$("#purgeServerCache").click(function() {
    var server = $("#lServer").val();
    if(confirm("Do you want to purge \""+server+"\" server's cache? You won't lose any permanent data.")) {
        $.ajax("purge.php?server="+server,{
            dataType:"json",
            success:function(data){
                if(data[0]) {alert("The \""+server+"\" server's cache was cleared successfully.");}
                else {alert("Unfortunately, the purge of \""+server+"\" server's cache was unsuccessful."+(data[1]?"\nReason: "+data[1]:""));}
            }
        });
    }
});

// Purge all cache button
$("#purgeAllCache").click(function() {
    if(confirm("Do you want to purge all of the cache? You won't lose any permanent data.")) {
        $.ajax("purge.php?all=on",{
            dataType:"json",
            success:function(data){
                if(data[0]) {alert("The ./cache/ directory was cleared successfully.");}
                else {alert("Unfortunately, the purge was unsuccessful."+(data[1]?"\nMessage"+data[1]:""));}
            }
        });
    }
});

// Variables
var formData = window.location.href.split('?',2)[1],
    resCt, intCt;

// Load dynmap information
function loadDynmapConfig (server) {
    if (server !== s.server) {
        $.getJSON("server/"+server+".json", function (data) {
            s = data;
            s.server = server;
        }).error(function () {
            s = {server: server};
        });
    }
}

// On lookup form submit
$form.submit(function(e) {
    e.preventDefault();
    $.ajax("conn.php",{
        beforeSend: function(xhr,s){
            // Upon submit
            $submit.prop("disabled",true);
            $moreSubmit.prop("disabled",true);
            
            // Get the time in UNIX
            var time = "&t=";
            if ($date.val() !== "") {
                time += moment($date.val(),c.form.dateFormat+" "+c.form.timeFormat).unix();
            } else if ($reverse.date.box.prop("checked")){
                // Reverse is clicked and date field is empty.  Don't account for time.
                time = "";
            } else {
                time += moment(Date.now()).unix();
            }
            
            // Set the URL and data
            window.history.replaceState(null, "", "?" + (s.data += time))
            
            // Set offset
            $moreOffset.val($limit.val() !== "" ? parseInt($limit.val()) : 30);
            
            // Set the Load More action
            var lim = "lim";
            formData = $.param($.grep($form.serializeArray(),function(e){return e.name !== lim})) + time;
        },
        data:       $form.serialize(),
        dataType:   "json",
        method:     "POST",
        complete:   function() {
            // Upon submit
            $submit.prop("disabled",false);
            $moreSubmit.prop("disabled",false);
            loadDynmapConfig($server.val());
        },
        success:function(data) {
            // Start the page count on bottom bar
            $pages.html('<li class="nav-item"><a class="nav-link active" href="#top">Top</a></li><li class="nav-item"><a class="nav-link" href="#rRow-0">0</a></li>');
            reachedLimit(false);
            
            // Revise "action" to the loadMore form
            $moreForm[0].setAttribute("action","./?"+formData)
            
            lastDataTime = Date.now()/1000;
            resCt=1;
            intCt=c.form.pageInterval;
            phraseReturn(data);
        },
        error: function(){
            $pages.html('<li class="nav-item"><a class="nav-link active" href="#top">Top</a></li>');
            phraseReturn([{
                status:7,
                reason:"The lookup script was unable to send a proper response."
            }]);
        }
    });
});

// on Load More submit
$moreForm.submit(function(e) {
    e.preventDefault();
    $.ajax("conn.php?" + $moreForm.attr("action").split("?",2)[1],{
        beforeSend: function() {
            // Disable submit button
            $submit.prop("disabled",true);
            $moreSubmit.prop("disabled",true);
            
            // Add Offset
            $moreOffset.val(parseInt($moreOffset.val()) + ($moreLimit.val() !== "" ? parseInt($moreLimit.val()) : 10));
        },
        data:$moreForm.serialize(),
        dataType:"json",
        method:"POST",
        complete:function(){
            $submit.prop("disabled",false);
            $moreSubmit.prop("disabled",false);
        },
        success:function(data) {
            phraseReturn(data,1);
        },
    });
});

// Limit function
function reachedLimit(toggle) {
  $moreSubmit.prop("disabled",toggle);
  if(toggle) {
      return '<i>No more results</i>';
  }
}

// Simple exist function
function if_exist(value,if_not) {
    if(value==="") {return if_not;}
    return value;
}

// Dropdown menu creation function
$table.on("show.bs.dropdown",".rDrop",function(){
    var $this = $(this);
    if(!$this.hasClass("dropdown")) {
        $this.addClass("dropdown");
        if ($this.hasClass("t")) {
            // Time
            $this.append('<div class="dropdown-menu"><span class="dropdown-header">Date/Time</span><span class="dropdown-item cPointer t Asc">Search ascending</span><span class="dropdown-item cPointer t Desc">Search descending</span></div>');
        } else if($this.hasClass("u")) {
            // Username
            $this.append('<div class="dropdown-menu"><span class="dropdown-header">User</span><span class="dropdown-item cPointer u Sch">Search user</span><span class="dropdown-item cPointer u ESch">Exclusive Search</span></div>');
        } else if($this.hasClass("c")) {
            // Coordinates
            $this.append('<div class="dropdown-menu"><span class="dropdown-header">Coordinates</span><span class="dropdown-item cPointer c Fl1">Center/Corner 1</span><span class="dropdown-item cPointer c Fl2">Corner 2</span>'+(s.dynmap.URL ? '<span class="dropdown-item cPointer c DMap">Open in Dynmap</span>' : "")+'</div>');
        } else if($this.hasClass("b")) {
            // Block
            $this.append('<div class="dropdown-menu"><span class="dropdown-header">Block</span><span class="dropdown-item cPointer b Sch">Search block</span><span class="dropdown-item cPointer b ESch">Exclusive Search</span></div>');
        } else {
            // should not reach this point...
            $this.append('<div class="dropdown-menu"><span class="dropdown-header">derp</span></div>');
        }
    }
});

// Displaying sign data function
$table.on("click.collapse-next.data-api",".collapse-toggle",function(){
    $(this).next().collapse("toggle");
});

// Returns data in table format
var spanSign = '<span class="collapse-toggle" data-toggle="collapse-next" aria-expanded="false">',
    divSignData = function(Lines) {
        return '<div class="mcSign">&nbsp;'+Lines.join("&nbsp;\n&nbsp;")+"&nbsp;</div>";
    },
    spanDToggle =  '<span class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">',
    lastDataTime;
function phraseReturn(obj,more) {
    $queryTime.text("Request generated in "+Math.round(obj[0].duration*1000)+"ms");
    var o;
    if (obj[0].status !== 0) { // If an error exists
        o = '<tr class="text-'+(obj[0].status===1?"info":"danger")+'"><th scope="row">'+(obj[0].status===1?"--":"E")+'</th><td colspan="7"';
        
        // Error type
        switch(obj[0].status) {
            case 1:
                // End of result
                o += ' class="text-xs-center">'+reachedLimit(true);
            break;
            case 2:
                // SQL unsuccessful
                o += '><b>The request did not go through properly.</b></td></tr><tr><th scope="row">-</th><td>'+obj[1][0]+"</td><td>"+obj[1][1]+'</td><td colspan="7">Error '+obj[1][2];
                reachedLimit(true);
            break;
            case 3:
                // Database Connection failed (PDOException)
                o += '><b>The webserver could not establish a connection to the database.</b> Please check your configuration.</td></tr><tr><th scope="row">-</th><td colspan="7">PDOException: '+obj[1];
                
            break;
            case 4:
                // Some searches by blocks and usernames does not exist
                o += "><b>The following value does not exist in the database:</b>";
                for(var j=0; j<obj[1].length;j++) {
                    o += '</td></tr><tr><th scope="row">-</th><td>';
                    switch(obj[1][j][0]) {
                        // [material,id or value, thing that has weird stuff]
                        case "material":
                            o += 'Block';
                            break;
                        case "user":
                            o += 'Username';
                            break;
                        default:
                            o += obj[1][j][0];
                    }
                    o += '</td><td colspan="6">'+obj[1][j][2];
                }
                reachedLimit(true);
                break;
            case 5:
                // Configuration error
                break;
            case 6:
                // Something else.
            default:
                o += '><b>Unexpected Error '+obj[0].status+":</b> "+obj[0].reason;
                reachedLimit(true);
                break;
        }
        o += '</td></tr>';
    } else {
        // Success
        var r = obj[1],
            i, jsTime;
        o = "";
        for (i = 0; i<r.length; i++) {
            // UNIX to JS Date
            if(c.form.timeDividor < Math.abs(lastDataTime-r[i].time) || !moment(lastDataTime,"X").isSame(r[i].time*1000, "day")) {
                o += '<tr class="table-section"><th scope="row">-</th><th colspan="7">'
                    + moment(r[i].time, "X").calendar()
                    + "</th></tr>";
            }
            o += '<tr id="rRow-'+resCt+'"';
            if (r[i].rolled_back === "1"){o += ' class="table-success"';}

            // Time, Username, Action
            o += '><th scope="row">' + resCt
                + '</th><td class="rDrop t" title="'
                + moment(r[i].time, "X").format(c.form.dateFormat)
                + '" data-time="' + r[i].time + '">'
                + spanDToggle+moment(r[i].time, "X").format(c.form.timeFormat)
                + '</span></td><td class="rDrop u">'
                + spanDToggle + r[i].user
                + '</span></td><td>' + r[i].table + '</td><td';
            lastDataTime = r[i].time;
            switch(r[i].table) {
                case "click":
                case "session":
                    r[i].rolled_back = "";
                case "container":
                case "block":
                case "kill":
                    // rolled_back translation
                    if(r[i].rolled_back) {
                        if(r[i].rolled_back === "0"){r[i].rolled_back = "Not rolled.";}
                        else if(r[i].rolled_back === "1"){r[i].rolled_back = "Rolled.";}
                    }
                    // Coordinates, Type:Data, Amount, Rollback
                    o += ' class="rDrop c">'+spanDToggle+r[i].x+' '+r[i].y+' '+r[i].z+' '+r[i].wid+"</span></td><td"+(r[i].table === "session"?">"
                    :(r[i].signdata?' class="rColl">'+spanSign
                    :' class="rDrop b" data-block="'+r[i].type+'">'+spanDToggle)+r[i].type+':'+r[i].data+"</span>"+(r[i].signdata? '<div class="rDrop b collapse" data-block="'+r[i].type+'">'+divSignData(r[i].signdata)+"<br>"+spanDToggle+r[i].type+':'+r[i].data+"</span></div>"
                    :""))+'</td><td'+(r[i].action === "0"?' class="table-warning">-'
                    :(r[i].action === "1"?' class="table-info">+'
                    :'>'))+(r[i].table === "container" ? r[i].amount
                    : '')+'</td><td>'+r[i].rolled_back;
                    break;
                case "chat":
                case "command":
                case "username_log":
                    // Message/UUID
                    o += ' colspan="4">'+r[i].data;
                    break;
            }
            o +='</td></tr>';
            resCt++;
        }
    }
    if (more) {
        $output.append(o);
    }
    else {
        $output.html(o);
    }
    for (intCt; intCt < resCt; intCt = intCt + c.form.pageInterval) {
        $pages.append('<li class="nav-item"><a class="nav-link" href="#rRow-'+intCt+'">'+intCt+'</a></li>');
    }
}

/* Smooth scrolling by mattsince87 from http://codepen.io/mattsince87/pen/exByn
// ------------------------------
// http://twitter.com/mattsince87
// ------------------------------
*/
$('.nav').on("click", "a", function(){  
    $('html,body').stop().animate({scrollTop:$($(this).attr('href')).offset().top},380);
    return false;
});

}());