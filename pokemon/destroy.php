<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: .');
    exit;
}

try {
    $connection = new \PDO(
        'mysql:host=localhost;dbname=productdatabase',
        'productuser',
        'productpassword',
        array(
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
        )
    );
} catch (PDOException $e) {
    header('Location: index.php?op=errorconnection&result=0');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php?op=deletepokemon&result=0');
    exit;
}

$id = $_GET['id'];

$sql = 'DELETE FROM pokemon WHERE id = :id';
$sentence = $connection->prepare($sql);
$sentence->bindValue(':id', $id);

try {
    $sentence->execute();
    $resultado = $sentence->rowCount();
    $url = 'index.php?op=deletepokemon&result=' . $resultado;
} catch (PDOException $e) {
    $url = 'index.php?op=deletepokemon&result=0';
}

header('Location: ' . $url);

?>
