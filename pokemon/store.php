<?php

//1º habilito la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['user'])) {
    header('Location:.'); //redireccion
    exit;
}

//realizo la conexion con la base de datos
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

//compruebo que me llegan los datos obligatorios
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

//limpieza con el trim para los espacios finales y iniciales, y validacion de datos
$ok = true;
$name = trim($name); //en realidad si al final es la cadena vacia, y el campo no es obligatorio, se asigna null
if(strlen($name) < 2 || strlen($name) > 100) {
    $ok = false;
}
// Limpiar y validar el campo 'weight'
$weight = trim($_POST['weight']);
if (!is_numeric($weight) || $weight < 0 || $weight > 1000) { // Ajusta el rango según sea necesario
    $ok = false; // El peso debe ser un número positivo y razonable
}

// Limpiar y validar el campo 'height'
$height = trim($_POST['height']);
if (!is_numeric($height) || $height < 0 || $height > 10) { // Ajusta el rango según sea necesario
    $ok = false; // La altura debe ser un número positivo y razonable
}

// Limpiar y validar el campo 'type'
$type = trim($_POST['type']);
$valid_types = ['water', 'fire', 'grass'];
if (!in_array($type, $valid_types)) {
    $ok = false; // El tipo debe ser uno de los tipos válidos
}

// Limpiar y validar el campo 'evolution'
$evolution = trim($_POST['evolution']);
if (!empty($evolution) && (!is_numeric($evolution) || $evolution < 1)) { // Asegúrate de que sea un número positivo
    $ok = false; // La evolución debe ser un número positivo o vacío
}


//HACK, uso la sesion para guardar datos 'temporalmente'
$_SESSION['old']['name'] = $name;
$_SESSION['old']['weight'] = $weight;
$_SESSION['old']['height'] = $height;
$_SESSION['old']['type'] = $type;
$_SESSION['old']['evolution'] = $evolution;

//si falla la validacion, redireccion
if($ok === false) {
    
    header('Location: create.php?op=errordata');
    exit;
}

//insert con sentencia preparada
$sql = 'insert into pokemon (name, weight, height, type, evolution) values (:name, :weight, :height, :type, :evolution)';
$sentence = $connection->prepare($sql);

//array asociativo con los parametros + bucle para asignar los parametros
$parameters = ['name' => $name, 'weight' => $weight, 'height' => $height, 'type' => $type, 'evolution' => $evolution,];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}

//sentencia preparada y despues su ejecucion
//2º capturo el error
try {
    /*if(!$sentence->execute()){
        echo 'no sql';
        exit;
    }*/
    $sentence->execute();
    //esta es la UNICA forma para obtener el ID
    $resultado = $connection->lastInsertId();
    $url = 'index.php?op=insertpokemon&result=' . $resultado;
    unset($_SESSION['old']['name']);
    unset($_SESSION['old']['weight']);
    unset($_SESSION['old']['height']);
    unset($_SESSION['old']['type']);
    unset($_SESSION['old']['evolution']);

} catch(PDOException $e) {
    //echo '<pre>' . var_export($e, true) . '</pre>';
    $resultado = 0;
    $url = 'create.php?op=insertpokemon&result=' . $resultado;
    //exit;
}

header('Location: ' . $url);//redireccion