<?php
//compruebo sesión
session_start();
if (!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}

//recupero datos
$name = '';
$weight = '';
$height = '';
$type = '';
$evolution = '';

if (isset($_SESSION['old']['name'])) {
    $name = $_SESSION['old']['name'];
    unset($_SESSION['old']['name']);
}
if (isset($_SESSION['old']['weight'])) {
    $weight = $_SESSION['old']['weight'];
    unset($_SESSION['old']['weight']);
}
if (isset($_SESSION['old']['height'])) {
    $height = $_SESSION['old']['height'];
    unset($_SESSION['old']['height']);
}
if (isset($_SESSION['old']['type'])) {
    $type = $_SESSION['old']['type'];
    unset($_SESSION['old']['type']);
}
if (isset($_SESSION['old']['evolution'])) {
    $evolution = $_SESSION['old']['evolution'];
    unset($_SESSION['old']['evolution']);
}

//establecer conexión bd
try {
    $connection = new \PDO(
        'mysql:host=localhost;dbname=pokemondatabase',
        'pokemonuser2',
        'root',
        array(
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
        )
    );
} catch (PDOException $e) {
    //echo 'no connection';
    header('Location: .'); // habría que dar explicaciones
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $url = '.?op=editpokemon&result=noid';
    header('Location: ' . $url);
    exit;
}

//comprobacion del id
$user = null;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
if (($user === 'even' && $id % 2 != 0) || ($user === 'odd' && $id % 2 == 0)) {
    header('Location: .');
    exit;
}

$sql = 'SELECT * FROM pokemon WHERE id = :id';
$sentence = $connection->prepare($sql);
$parameters = ['id' => $id];
foreach ($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}
if (!$sentence->execute()) {
    echo 'no sql';
    exit;
}
if (!$fila = $sentence->fetch()) {
    echo 'no data';
    exit;
}
$id = $fila['id'];

if ($name == '') {
    $name = $fila['name'];
}
if ($weight == '') {
    $weight = $fila['weight'];
}
if ($height == '') {
    $height = $fila['height'];
}
if ($type == '') {
    $type = $fila['type'];
}
if ($evolution == '') {
    $evolution = $fila['evolution'];
}

$connection = null;
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PokemonApp</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="..">PokemonApp</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="..">home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./">pokemon</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main">
            <div class="jumbotron">
                <div class="container">
                    <h4 class="display-4">Pokémon Edit</h4>
                </div>
            </div>
            <div class="container">
                <?php
                if (isset($_GET['op']) && isset($_GET['result'])) {
                    if ($_GET['result'] > 0) {
                        ?>
                        <div class="alert alert-primary" role="alert">
                            result: <?= htmlspecialchars($_GET['op'] . ' ' . $_GET['result']) ?>
                        </div>
                        <?php 
                    } else {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            result: <?= htmlspecialchars($_GET['op'] . ' ' . $_GET['result']) ?>
                        </div>
                        <?php
                    }
                }
                ?>
                <div>
                    <form action="update.php" method="post">
                        <div class="form-group">
                            <label for="name">Pokémon Name</label>
                            <input value="<?= htmlspecialchars($name) ?>" required type="text" class="form-control" id="name" name="name" placeholder="Pokémon Name">
                        </div>
                        <div class="form-group">
                            <label for="weight">Pokémon Weight</label>
                            <input value="<?= htmlspecialchars($weight) ?>" required type="number" step="0.001" class="form-control" id="weight" name="weight" placeholder="Pokémon Weight">
                        </div>
                        <div class="form-group">
                            <label for="height">Pokémon Height</label>
                            <input value="<?= htmlspecialchars($height) ?>" required type="number" step="0.001" class="form-control" id="height" name="height" placeholder="Pokémon Height">
                        </div>
                        <div class="form-group">
                            <label for="type">Pokémon Type</label>
                            <input value="<?= htmlspecialchars($type) ?>" required type="text" class="form-control" id="type" name="type" placeholder="Pokémon Type">
                        </div>
                        <div class="form-group">
                            <label for="evolution">Pokémon Evolution</label>
                            <input value="<?= htmlspecialchars($evolution) ?>" required type="text" class="form-control" id="evolution" name="evolution" placeholder="Pokémon Evolution">
                        </div>
                        <input type="hidden" name="id" value="<?= $id ?>" />
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </form>
                </div>
                <hr>
            </div>
        </main>
        <footer class="container">
            <p>&copy;  Daniel Fontalva</p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>
