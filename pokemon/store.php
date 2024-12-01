<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['user'])) {
    header('Location:.'); 
    exit;
}

try {
    $connection = new \PDO(
      'mysql:host=localhost;dbname=pokemondatabase',
      'pokemonuser',
      'root',
      array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
    );
} catch(PDOException $e) {
    header('Location: create.php?op=errorconnection&result=0');
    exit;
}

if(isset($_POST['name'])) {
    $name = $_POST['name'];
} else {
    header('Location: create.php?op=errorname&result=0');
    exit;
}
if(isset($_POST['weight'])) {
    $weight = $_POST['weight'];
} else {
    header('Location: create.php?op=errorweight&result=0');
    exit;
}
if(isset($_POST['height'])) {
    $height = $_POST['height'];
} else {
    header('Location: create.php?op=errorheight&result=0');
    exit;
}
if(isset($_POST['type'])) {
    $type = $_POST['type'];
} else {
    header('Location: create.php?op=errortype&result=0');
    exit;
}
if(isset($_POST['evolution'])) {
    $evolution = $_POST['evolution'];
} else {
    header('Location: create.php?op=errorevolution&result=0');
    exit;
}

$ok = true;
$name = trim($name); 
if(strlen($name) < 2 || strlen($name) > 100) {
    $ok = false;
}
$weight = trim($_POST['weight']);
if (!is_numeric($weight) || $weight < 0 || $weight > 3000) { 
    $ok = false; 
}


$height = trim($_POST['height']);
if (!is_numeric($height) || $height < 0 || $height > 100) { 
    $ok = false; 
}


$type = trim($_POST['type']);
$valid_types = ['Agua', 'Fuego', 'Planta'];
if (!in_array($type, $valid_types)) {
    $ok = false; 
}

$evolution = trim($_POST['evolution']);
if (!empty($evolution) && (!is_numeric($evolution) || $evolution < 1)) { 
    $ok = false;
}


$_SESSION['old']['name'] = $name;
$_SESSION['old']['weight'] = $weight;
$_SESSION['old']['height'] = $height;
$_SESSION['old']['type'] = $type;
$_SESSION['old']['evolution'] = $evolution;

if($ok === false) {
    
    header('Location: create.php?op=errordata');
    exit;
}

$sql = 'insert into pokemon (name, weight, height, type, evolution) values (:name, :weight, :height, :type, :evolution)';
$sentence = $connection->prepare($sql);

$parameters = ['name' => $name, 'weight' => $weight, 'height' => $height, 'type' => $type, 'evolution' => $evolution,];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}


try {

    $sentence->execute();
    $resultado = $connection->lastInsertId();
    $url = 'index.php?op=insertpokemon&result=' . $resultado;
    unset($_SESSION['old']['name']);
    unset($_SESSION['old']['weight']);
    unset($_SESSION['old']['height']);
    unset($_SESSION['old']['type']);
    unset($_SESSION['old']['evolution']);

} catch(PDOException $e) {
    $resultado = 0;
    $url = 'create.php?op=insertpokemon&result=' . $resultado;
}

header('Location: ' . $url);