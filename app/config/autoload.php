<?php

$pluginsAutoLoad = [
//    "bootstrap" => [
//        "js" => ["js/bootstrap.min","js/popper.min"],
//        "css" => ["css/bootstrap.min"]
//    ],
    "sweetalert" => [
        "js" => ["sweetalert2.all"],
        "css" => null,
    ],
    "mascara" => [
        "js" => ["mascara"],
        "css" => null,
    ]
];

// Salva como constant
defined("PLGUINS_AUTOLOAD") OR define("PLGUINS_AUTOLOAD", serialize($pluginsAutoLoad));