<?php
//compruebo sesión
session_start();
if(!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}
//recupero nivel, nombre, peso, altura, tipo y evolucion
$name = '';
$level = '';
$weight = '';
$height = '';
$ptype = '';
$evolution = '';

if(isset($_SESSION['old']['name'])) {
    $name = $_SESSION['old']['name'];
    unset($_SESSION['old']['name']);
}
if(isset($_SESSION['old']['level'])) {
    $level = $_SESSION['old']['level'];
    unset($_SESSION['old']['level']);
}
if(isset($_SESSION['old']['weight'])) {
    $weight = $_SESSION['old']['weight'];
    unset($_SESSION['old']['weight']);
}
if(isset($_SESSION['old']['height'])) {
    $height = $_SESSION['old']['height'];
    unset($_SESSION['old']['height']);
}
if(isset($_SESSION['old']['ptype'])) {
    $ptype = $_SESSION['old']['ptype'];
    unset($_SESSION['old']['ptype']);
}
if(isset($_SESSION['old']['evolution'])) {
    $evolution = $_SESSION['old']['evolution'];
    unset($_SESSION['old']['evolution']);
}
//establecer conexión bd
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
    //echo 'no connection';
    header('Location: .'); // habría que dar explicaciones
    exit;
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $url = '.?op=editpokemon&result=noid';
    header('Location: ' . $url);
    exit;
}
// Control
$user = null;
if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

if (($user == 'even' && $id % 2 != 0) || 
    ($user == 'odd' && $id % 2 == 0)) {
        header('Location: .');
}

$sql = 'select * from pokemon where id = :id';
$sentence = $connection->prepare($sql);
$parameters = ['id' => $id];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}
if(!$sentence->execute()){
    echo 'no sql';
    exit;
}
if(!$fila = $sentence->fetch()) {
    echo 'no data';
    exit;
}
$id = $fila['id'];
if($name == '') {
    $name = $fila['name'];
}
if($level == '') {
    $level = $fila['level'];
}
if($weight == '') {
    $weight = $fila['weight'];
}
if($height == '') {
    $height = $fila['height'];
}
if($ptype == '') {
    $ptype = $fila['ptype'];
}
if($evolution == '') {
    $evolution = $fila['evolution'];
}
$connection = null;
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Pokemon</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="..">Pokemon</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="..">home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./">Pokemons</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main">
            <div class="jumbotron">
                <div class="container">
                    <h4 class="display-4">Pokemons</h4>
                </div>
            </div>
            <div class="container">
            <?php
                if(isset($_GET['op']) && isset($_GET['result'])) {
                    if($_GET['result'] > 0) {
                        ?>
                        <div class="alert alert-primary" role="alert">
                            result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php 
                    } else {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php
                        }
                }
                ?>
                <div0>
                    <form action="update.php" method="post">
                        <div class="form-group">
                            <label for="name">pokemon name</label>
                            <input value="<?= $name ?>" required type="text" class="form-control" id="name" name="name" placeholder="pokemon name">
                        </div>
                        <div class="form-group">
                            <label for="level">pokemon level</label>
                            <input value="<?= $level ?>" required type="number" step="0.001" class="form-control" id="level" name="level" placeholder="pokemon level">
                        </div>
                        <div class="form-group">
                            <label for="evolution">pokemon evolution</label>
                            <input value="<?= $evolution ?>" required type="text" class="form-control" id="evolution" name="evolution" placeholder="pokemon evolution">
                        </div>
                        <input type="hidden" name="id" value="<?= $id ?>" />
                        <button type="submit" class="btn btn-primary">edit</button>
                    </form>
                </div>
                <hr>
            </div>
        </main>
        <footer class="container">
            <p>&copy; IZV 2024</p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>