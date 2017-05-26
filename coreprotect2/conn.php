<?php
// CoLWI v0.9.3
// Conn JSON application
// Copyright (c) 2015-2016 SimonOrJ

// Request parameters:
// server   = server name (Required)
// a        = actions in array of strings (Defaults to "block" when undefined).
// b        = blocks in array of strings.
// e        = excludes in array of single character (reverse search "b" or "u" request parameter).
// u        = users in array of strings.
// t        = time in integer or string.
// unixtime = is time in unixtime? (will probably be deprecated)
// asendt   = reverse time (asending order) in boolean.
// wid      = world to search in string.
// xyz      = coordinates, either a center or a corner, in array of integers.
// r        = radius in integer.
// xyz2     = second corner coordinates in array of integers.  Turns into integer if only first is defined.
// rollback = rollback flag boolean ("on" or "").
// lim      = limit query integer.
// offset   = query offset integer.
// keyword  = keyword search for chat and commands (and signs in the future) in string.

// When including:
// Read-only variable:
//   $_REQUEST, $c
// Written Variable:
//   $Cc, $Cm, $codb, $filter, $out, $q, $status, $timer, $where

// Testing script
//error_reporting(-1);ini_set('display_errors', 'On');

// Record start time
$timer = microtime(true);

// Code to run right before code terminates
// if used as a web application ($c is not set)
if (!isset($c))
    register_shutdown_function(function () {
        global $out,$timer;
        
        // Set type to application/json
        header('Content-type:application/json;charset=utf-8');
        
        if(!isset($out[0]["status"]))
            $out = array(array(
                'status' => 6,
                'reason' => "Uncaught error has made the script terminate too early."
            ));
        $out[0]["duration"] = microtime(true) - $timer;
        echo json_encode($out);
    });

// Load config if loaded as JSON application (when $c is not defined)
if (!isset($c))
    $c = require "config.php";


// Login check
if (!isset($login)) {
    require "res/php/login.php";
    $login = new Login($c);
}
if (!$login->permission(Login::PERM_LOOKUP)) {
    $out[0]["status"] = 5;
    $out[0]["reason"] = "Insufficient permission.";
    exit();
}

// Check for required variables
if (empty($_REQUEST['server'])) {
    // Required server variable is missing.
    $out[0]["status"] = 5;
    $out[0]["reason"] = "Server variable does not exist.";
    exit();
}

// Check if server exists
if (!file_exists($server = "server/".$_REQUEST['server'].".php")) {
    // Server doesn't exist.
    $out[0]["status"] = 5;
    $out[0]["reason"] = "Server configuration does not exist.";
    exit();
}

// Load Modules
$server = require $server;
require "pdowrapper.php";
require "cachectrl.php";
require "bukkittominecraft.php";

// Create class
$codb = pdoWrapper($server['db']);

// When PDO failed to connect
if (is_a($codb, "PDOException")) {
    $out[0]["status"]=3;$out[0]["reason"]="Database connection failed";$out[1]=$codb->getMessage();
    exit();
}

// Module Classes
$Cc = new CacheCtrl($_REQUEST['server'], $codb, $server);
$Cm = ($c['flag']['bukkitToMc']) ? new BukkitToMinecraft() : new KeepBukkit();

// Special Material list
$signBlocks = ["minecraft:standing_sign","minecraft:wall_sign"];

// Variables
$VARS = array(
    array("a","b","e","u"),                 // Array of strings
    array("xyz", "xyz2"),                   // Array of integers
    array("t","wid"),                       // String
    array("r","rollback","lim","offset"),   // Integer
    array("unixtime","asendt")              // Boolean
);

// Query
$q = array();

// SQL where statements
$filter = array('meta' => array());

// Get all the request into the query array.
foreach ($_REQUEST as $key => $val) {
    if (is_string($val) && $val === "") continue; // String
    if (is_array($val)) {
        // empty() does not support functions for PHP versions under v5.5.0.
        $emptyValTest = array_filter($val,function($k){
            return $k !== "";
        });
        if (empty($emptyValTest)) continue; // Empty String in array
    }
    if (in_array($key, $VARS[0], true)) {
        // Array of strings
        if (is_array($val))
            // It's already written as an array (from the web form)
            $q[$key] = $val;
        elseif (is_string($val) && $val !== "")
            // Comma-separated values (from MC)
            $q[$key] = explode(',',str_replace(' ', '', $val));
    } elseif (in_array($key, $VARS[1], true)) {
        if (is_array($val)) {
            if ($key === "xyz2" && $val[0] !== "" && $val[1] === "" && $val[2] === "")
                // xyz2: second corner or radius
                $q['r'] = intval($val[0]);
            else
                $q[$key] = array_map('intval', $val);
        }
        elseif (is_string($val) && $val !== "")
            $q[$key] = (is_array($val))?$val:explode(',', $val);
    } elseif (in_array($key, $VARS[2], true)) {
        // String
        $q[$key] = $val;
    } elseif (in_array($key, $VARS[3], true)) {
        // Integer only when set
        if ($val !== "")
            $q[$key] = intval($val);
    } elseif (in_array($key, $VARS[4], true)) {
        // Boolean
        if ($val !== "false" || $val !==  "off") $q[$key] = true;
    } elseif ($key === "keyword") {
        // keyword search
        if ($val !== "") $q[$key] = str_getcsv($val);
    }
}

// Defaults if the query or required parts of the query is empty:
if (empty($q['a']))         $q['a'] = array("block");
if (!isset($q['asendt']))   $q['asendt'] = false;
if (!isset($q['unixtime'])) $q['unixtime'] = false;

// Integer defaults
if (!isset($q['offset']))   $q['offset'] = 0;
if (!isset($q['lim'])) {
    if (isset($q['offset']) && $q['offset'] !== 0)
        $q['lim'] = $c['form']['loadMoreLimit'];
    else
        $q['lim'] = $c['form']['limit'];
}

// coord xyz, xyz2, r
if ((isset($q['xyz']) && (isset($q['r']) || isset($q['xyz2']))) || isset($q['wid'])) {
    if (isset($q['xyz']) && isset($q['r']))
        $filter['coord'] = "(x BETWEEN "
                .($q['xyz'][0] - $q['r'])
                ." AND "
                .($q['xyz'][0] + $q['r'])
                .") AND (y BETWEEN "
                .($q['xyz'][1] - $q['r'])
                ." AND "
                .($q['xyz'][1] + $q['r'])
                .") AND (z BETWEEN "
                .($q['xyz'][2] - $q['r'])
                ." AND "
                .($q['xyz'][2] + $q['r'])
                .")";
    elseif (isset($q['xyz']) && isset($q['xyz2']))
        $filter['coord'] = "(x BETWEEN "
                .min($q['xyz'][0], $q['xyz2'][0])
                ." AND "
                .max($q['xyz'][0], $q['xyz2'][0])
                .") AND (y BETWEEN "
                .min($q['xyz'][1], $q['xyz2'][1])
                ." AND "
                .max($q['xyz'][1], $q['xyz2'][1])
                .") AND (z BETWEEN "
                .min($q['xyz'][2], $q['xyz2'][2])
                ." AND "
                .max($q['xyz'][2], $q['xyz2'][2])
                .")";
    
    if (isset($q['wid'])) {
        if (isset($coord)) $filter['coord'] .= " AND ";
        else               $filter['coord'] = "";
                           $filter['coord'] .= "wid=".$Cc->getId($q['wid'],"world");
    }
}
else $filter['coord'] = false;

// Time t, unixtime, asendt
if (isset($q['t'])) {
    if ($q['unixtime'])
        // From web form
        $filter['meta']['t'] = $q['t'];
    else {
        // from MC
        $q['t'] = str_replace(",","",$q['t']);
        $q['t'] = preg_split("/(?<=[wdhms])(?=\d)/",$q['t']);
        $filter['time'] = time();
        foreach($q['t'] as $val) {
            $val = preg_split("/(?<=\d)(?=[wdhms])/",$val,2);
            switch($val[1]) {
                case "w": $filter['meta']['t'] -= $val[0]*604800; break;
                case "d": $filter['meta']['t'] -= $val[0]*86400;  break;
                case "h": $filter['meta']['t'] -= $val[0]*3600;   break;
                case "m": $filter['meta']['t'] -= $val[0]*60;     break;
                case "s": $filter['meta']['t'] -= $val[0];        break;
            }
        }
    }
    
    $filter['time'] = "time"
            .(($q['asendt']) ? ">=" : "<=")
            .$filter['meta']['t'];
    unset($filter['meta']['t']);
}
else $filter['time'] = "time<=".(time());

// User u, e
if (isset($q['u'])) {
    // TODO: Make "e" option more dynamic...
    foreach($q['u'] as $key => $us)
        $q['u'][$key] = $Cc->getId($us,"user");
    
    $filter['userid'] = "user"
            .($filter['meta']['uNot'] = isset($q['e']) && in_array("u",$q['e'],true) ? " NOT " : " ")
            ."IN ('"
            .implode("','",$q['u'])
            ."')";
    
    if(in_array("username",$q['a'],true)) {
        foreach($q['u'] as $us)
            $us = $Cc->getValue($us,"user"); // for capitalization purposes
        $filter['username'] = "user".$filter['meta']['uNot']."IN ('".implode("','",$q['u'])."')";
    }
    unset($filter['meta']['uNot']);
}
else $filter['userid'] = false;

// Rollback flag block, kill, container
if(isset($q['rollback'])) {
    $filter['meta']['rbflag'] = "rolled_back=".(($q['rollback']) ? "1" : "0");
}
else $filter['meta']['rbflag'] = false;

// actions, separate click, separate kill
$filter['meta']['a'] = array(array(),false,false);

// Block b, e; container action for block translation
if(in_array("block",$q['a'],true) || in_array("click",$q['a'],true) || in_array("container",$q['a'],true)) {
    // TODO: Make "e" option more dynamic...
    // Blocks
    if(in_array("block",$q['a'],true)) {
        $filter['meta']['a'][0][] = 0; // destroy
        $filter['meta']['a'][0][] = 1; // place
    }
    // clicks
    if(in_array("click",$q['a'],true)) {
        if ($filter['meta']['rbflag'])
            $filter['meta']['a'][1] = true;
        else
            $filter['meta']['a'][0][] = 2;
    }
    
    // block search translation
    if(isset($q['b'])) {
        foreach($q['b'] as $key => $bk) {
            $bk = $Cm->getBk($bk);
            $q['b'][$key] = $bk;
            // TODO: Get a better solution.
            // TODO: Fix non-existant block lookup.
            if($server['legacy']) if($bk !== ($bk2=preg_replace("/^minecraft:/","",$bk))) $q['b'][] = $bk2;
        }
        foreach($q['b'] as $key => $bk) $q['b'][$key] = $Cc->getId($bk,"material");
        $filter['block'] = "type"
                .(isset($q['e']) && in_array("b",$q['e'],true) ? " NOT " : " ")
                ."IN ('"
                .implode("','",$q['b'])
                ."')";
    } else $filter['block'] = false;
} else $filter['block'] = false;

// Error checking
if(!empty($cc->error)) {
    $out[0]["status"] = 4;
    $out[0]["reason"] = "Username/Block Input not found";
    $out[1] = $Cc->error;
    exit();
}

// kill
if(in_array("kill",$q['a'],true)) {
    if(isset($q['b'])) $filter['meta']['a'][2] = true;
    else $filter['meta']['a'][0][] = 3;
}

// keyword
if(isset($q['keyword'])) {
    foreach($q['keyword'] as $word) {
        $terms = [];
        $words = str_getcsv($word,' ');
        foreach($words as $val) $terms[] = "message LIKE '%".$val."%'";
        $filter['keyword'][] = "(".implode(" AND ",$terms).")";
    }
    $filter['keyword'] = "(".implode(" OR ",$filter['keyword']).")";
    /*if(in_array("block",$a,true)) {
        foreach($keywords as $val) $serachSign[] = "(line_1 LIKE '%".$val."%' OR line_2 LIKE '%".$val."%' OR line_3 LIKE '%".$val."%' OR line_4 LIKE '%".$val."%')";
        $searchSign = "(".implode(" AND ",$searchSign).")";
    }*/
}
else $filter['keyword'] = false;

// Function for heading
function sel($as,$cl) {
    if($as === NULL) return ",NULL AS ".$cl;
    return ",".$as." AS ".$cl;
}

// Make query heading
function sqlreq($table) {
    global $server, $filter;
    $where[0] = $filter['time'];
    if($filter['userid'])
        $where[] = ($table == "username") ? $filter['username'] : $filter['userid'];
    switch ($table) {
        case "block":
        case "session":
        case "container":
            $select = ",wid,x,y,z";
            if($filter['coord']) $where[] = $filter['coord'];
            if($table == "session") $select .= sel(0,"type").sel(0,"data").sel(0,"amount").",action".sel(0,"rolled_back");
            else {
                $select .= ",type,data";
                if($table == ("block")) {
                    $select .= sel(0,"amount");
                    if($filter['meta']['a'][0])
                        $whereB[] = "action IN ("
                            . implode(",",$filter['meta']['a'][0])
                            . ")"
                            . ($filter['block']
                                ? " AND " . $filter['block']
                                : "")
                            . (($filter['meta']['rbflag'])
                                ? " AND " . $filter['meta']['rbflag']
                                : ""
                            );
                    if($filter['meta']['a'][1])
                        $whereB[] = "action=2".(($filter['block']) ? " AND ".$filter['block'] : "");
                    if($filter['meta']['a'][2])
                        $whereB[] = "action=3".(($filter['meta']['rbflag']) ? " AND ".$filter['meta']['rbflag'] : "");
                    if(!empty($whereB)) $where[] = "(".implode(") OR (",$whereB).")";
                }
                else {
                    $select .= ",amount";
                    if($filter['block']) $where[] = $filter['block'];
                    if($filter['meta']['rbflag']) $where[] = $filter['meta']['rbflag'];
                }
                $select .= ",action,rolled_back";
            }
            break;
        case "chat":
        case "command":
            if($filter['keyword']) $where[] = $filter['keyword'];
        case "username_log":
            $select = sel(0,"wid").sel(0,"x").sel(0,"y").sel(0,"z").sel(0,"type");
            $select .= ($table == "username_log")? sel("uuid","data") : sel("message","data");
            $select .= sel(0,"amount").sel(0,"action").sel(0,"rolled_back");
    }
    return "SELECT time,'".$table."' AS 'table',user".$select." FROM ".$server['co'].$table.((empty($where)) ? "" : " where ".implode(" AND ",$where));
}

$filter['meta']['blockFlag'] = true;

foreach($q['a'] as $pa) {
    switch($pa) {
        case "block":
        case "click":
        case "kill":
            if($filter['meta']['blockFlag']) {
                $filter['meta']['blockFlag'] = false;
                $sql[] = sqlreq("block");
            }
            break;
        case "username":
            $sql[] = sqlreq("username_log");
            break;
        default:
            $sql[] = sqlreq($pa);
            break;
    }
}
// SELECT time,'block' AS 'table',user,wid,x,y,z,type,data,NULL AS amount,action,rolled_back FROM co_block where type LIKE ($signBlocks) AND x= AND y= AND z= AND time<=1454881758 ORDER BY rowid DESC LIMIT 1;
// SELECT time,user,x,y,z,wid from co_sign where (line like '%stuff%') ORDER BY rowid DESC LIMIT 1;
$tables = "";
if(($out[0]["SQLqs"]=count($sql)) > 1) foreach($sql as $key => $val) {
    if($key) $tables .= " UNION ALL ";
    $tables .= "SELECT * FROM (".$val." ORDER BY rowid ".(($q['asendt'])?"ASC":"DESC")." LIMIT ?) AS T".$key;
}

$lookup = $codb->prepare($sql = (($out[0]["SQLqs"] > 1)?$tables:$sql[0])
    ." ORDER BY "
    .(($out[0]["SQLqs"] > 1)?"time":"rowid")." "
    .(($q['asendt'])?"ASC":"DESC")
    ." LIMIT ?,?;");
$out[0]["SQL"] = $sql;

if ($lookup === false) {
    // Lookup query is invalid.
    $out[0]["status"] = 2;
    $out[0]["reason"] = "The script returned an invalid SQL statement.";
    exit();
}

if($out[0]["SQLqs"] > 1) for($i = 1; $i <= $out[0]["SQLqs"]; $i++) {
    $lookup->bindValue($i, (isset($q["offset"]) ? $q["offset"] : 0) + $q["lim"], PDO::PARAM_INT);
}
else $out[0]["SQLqs"] = 0;

$lookup->bindValue($out[0]["SQLqs"]+1, $q["offset"], PDO::PARAM_INT);
$lookup->bindValue($out[0]["SQLqs"]+2, $q["lim"], PDO::PARAM_INT);

if ($lookup->execute()) {
    $out[0]["status"] = 0;
    $out[0]["reason"] = "Request successful";
//    $status["rows"] = $numrows;
    // Code Sanitaizer
    while($r = $lookup->fetch(PDO::FETCH_ASSOC)) {
        if ($r["table"] !== "username_log") $r["user"] = $Cc->getValue($r["user"],"user");
        if ($r["table"] == "block" || $r["table"] == "container") {
            if ($r["action"] == 3) {
                $r["type"] = $Cc->getValue($r["type"],"entity");
                $r["table"] = "kill";
                }
            else {
                if ($r["action"] == 2) $r["table"] = "click";
                $r["type"] = $Cm->getMc($Cc->getValue($r["type"],"material"));
                if(in_array($r["type"],$signBlocks,true)) {
                    $sign = $codb->query("SELECT line_1,line_2,line_3,line_4 FROM ".$server['co']."sign WHERE x=".$r["x"]." AND y=".$r["y"]." AND z=".$r["z"]." AND time".(($r["action"]=="0")?"<":">=").$r["time"]." ORDER BY rowid ".(($r["action"]=="0")?"DESC":"ASC")." LIMIT 1;")->fetch(PDO::FETCH_NUM);
                    if($sign!=NULL)foreach($sign as $val) {
                        if(trim($val)!="") {
                            $r["signdata"]=$sign;
                            break;
                        }
                    }
                }
            }
        }
        if ($r["wid"]) $r["wid"] = $Cc->getValue($r["wid"],"world");
        $out[1][] = $r;
    }
    if(empty($out[1])) {
        $out[0]["status"] = 1;
        $out[0]["reason"] = "No results";
    }
}
else {
    $out[0]["status"] = 2;
    $out[0]["reason"] = "SQL Execution Unsuccessful.";
    $out[1] = $lookup->errorInfo();
}
?>
