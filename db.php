<?php

// Je la met ici pour plus de cohérence, comme ça je ne dévoile pas mes identifiants
function getPDO() {
    $config = parse_ini_file('../../db.ini');
    return new PDO($config['servername'] . ';dbname=' . $config['dbname'] . ';charset=utf8', $config['username'], $config['password']);
}

$db = getPDO();
