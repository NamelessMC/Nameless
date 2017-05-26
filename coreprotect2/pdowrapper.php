<?php
// CoLWI v0.9.3
// pdoWrapper PHP Function
// Copyright (c) 2015-2016 SimonOrJ

// pdoWrapper (array database)
//   returns PDO on success or PDOException on failure.

function pdoWrapper($d) {
    try {
        $p = ($d["type"] === "mysql")
                ?new PDO("mysql:charset=utf8;host="
                    .$d["host"]
                    .";dbname="
                    .$d["data"],
                    $d["user"],
                    $d["pass"]
                )
                :new PDO("sqlite:"
                    .$d["path"]
                );
        return $p;
    } catch(PDOException $e) {
        return $e;
    }
}?>