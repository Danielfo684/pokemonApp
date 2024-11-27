<?php

// 1º Habilito la visualización de errores (solo en desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Compruebo si el usuario está logueado
session_start();
if(!isset($_SESSION['user'])) {
    header('Location:.'); // redireccion
    exit; //detengo la ejecución
}

// Conexión a la base de datos
try {
    $connection = new \PDO(
      'mysql:host=localhost;dbname=pokemondatabase',
      'pokemontrainer',
      'pokemonpassword',
      array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
    );
} catch(PDOException $e) {
    header('Location: create.php?op=errorconnection&result=0');
    exit;
}

$resultado = 0;
$url = 'create.php?op=insertpokemon&result=' . $resultado;

// Compruebo que los datos obligatorios: nombre y nivel, estén presentes
if(isset($_POST['name']) && isset($_POST['level']) && isset($_POST['evolution'])) {
    $name = $_POST['name'];
    $level = $_POST['level'];
    $evolution = $_POST['evolution'];	
    $ok = true;
    $name = trim($name);
    // Verifica que el nombre tenga entre 2 y 100 caracteres
    if(strlen($name) < 2 || strlen($name) > 100) { 
        $ok = false;
    }
    // Verifica que el nivel sea un número entre 0 y 100
    if(!(is_numeric($level) && $level >= 0 && $level <= 100)) { 
        $ok = false;
    }
    // Verifica que la evolución sea un número entre 0 y 3
    if(!(is_numeric($evolution) && $evolution >= 0&& $evolution <= 3)) {
        $ok = false;
    }

    if($ok) {
        // Prepara la consulta SQL para insertar un pokémon
        $sql = 'insert into pokemon (name, level, evolution) values (:name, :level, :evolution)'; 
        $sentence = $connection->prepare($sql); 
        // Define los parámetros para la consulta
        $parameters = ['name' => $name, 'level' => $level, 'evolution' => $evolution]; 
        foreach($parameters as $nombreParametro => $valorParametro) { 
            $sentence->bindValue($nombreParametro, $valorParametro); 
        }

        try {
            $sentence->execute(); 
            $resultado = $connection->lastInsertId(); 
            $url = 'index.php?op=insertpokemon&result=' . $resultado; 
        } catch(PDOException $e) {
            
        }
    }
}
if($resultado == 0) {
    $_SESSION['old']['name'] = $name; // Guarda el nombre en la sesión en caso de error
    $_SESSION['old']['level'] = $level; // Guarda el nivel en la sesión en caso de error
    $_SESSION['old']['evolution'] = $evolution; // Guarda la evolución en la sesión en caso de error
}

// El método header() redirecciona a la URL indicada
header('Location: ' . $url);