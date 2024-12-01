<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}
try {
    $connection = new \PDO(
        'mysql:host=localhost;dbname=pokemondatabase',
        'pokemontrainer',
        'pokemonpassword',
        array(
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
        )
    );
} catch (PDOException $e) {
    //echo 'no connection';
    header('Location: ..');
    exit;
}
if (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    //echo 'no id';
    header('Location: ..');
    exit;
}

$user = null;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

if (
    ($user == 'even' && $id % 2 != 0) ||
    ($user == 'odd' && $id % 2 == 0)
) {
    header('Location: .');
    exit;
}

if (isset($_POST['name'])) {
    $name = trim($_POST['name']);
} else {
    header('Location: .');
    //echo 'no name';
    exit;
}
if (isset($_POST['level'])) {
    $level = $_POST['level'];
} else {
    header('Location: .');
    exit;
}
if (isset($_POST['weight'])) {
    $weight = $_POST['weight'];
} else {
    header('Location: .');
    exit;
}
if (isset($_POST['height'])) {
    $height = $_POST['height'];
} else {
    header('Location: .');
    exit;
}
if (isset($_POST['ptype'])) {
    $ptype = $_POST['ptype'];
} else {
    header('Location: .');
    exit;
}
if (isset($_POST['evolution'])) {
    $evolution = $_POST['evolution'];
} else {
    header('Location: .');
    exit;
}


$sql = 'update pokemon set name = :name, level = :level, weight = :weight, height = :height, ptype = :ptype, evolution = :evolution where id = :id';
$parameters = ['name' => $name, 'level' => $level, 'weight' => $weight, 'height' => $height, 'ptype' => $ptype, 'evolution' => $evolution, 'id' => $id];
//debería meter la misma validación que antes en store.php
$sentence = $connection->prepare($sql);
foreach ($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}
try {
    $sentence->execute();
    $resultado = $sentence->rowCount();
    $url = '.?op=editpokemon&result=' . $resultado;
} catch (PDOException $e) {
    $resultado = 0;
    $_SESSION['old']['name'] = $name;
    $_SESSION['old']['level'] = $level;
    $_SESSION['old']['weight'] = $weight;
    $_SESSION['old']['height'] = $height;
    $_SESSION['old']['ptype'] = $ptype;
    $_SESSION['old']['evolution'] = $evolution;
    $url = 'edit.php?id=' . $id . '&op=editpokemon&result=' . $resultado;
}
header('Location: ' . $url);