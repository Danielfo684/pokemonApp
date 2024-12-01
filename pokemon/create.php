<?php

// Control de sesión
session_start();
if (!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}

// Lectura de datos, continuación del hack
$level = '';
$name = '';
$evolution = '';
if (isset($_SESSION['old']['name'])) {
    $name = $_SESSION['old']['name'];
    unset($_SESSION['old']['name']);
}
if (isset($_SESSION['old']['level'])) {
    $level = $_SESSION['old']['level'];
    unset($_SESSION['old']['level']);
}

if (isset($_SESSION['old']['evolution'])) {
    $evolution = $_SESSION['old']['evolution'];
    unset($_SESSION['old']['evolution']);
}
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pokemon</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="..">Pokemon</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
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
                <h4 class="display-4">pokemons</h4>
            </div>
        </div>
        <div class="container">
            <?php
            // isset comprueba si algo llega o no
            if (isset($_GET['op']) && isset($_GET['result'])) {
                $type = 'primary';
                if ($_GET['result'] > 0) {
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
            <div>
                <form action="store.php" method="post">
                    <div class="form-group">
                        <label for="name">pokemon name</label>
                        <input value="<?= $name ?>" required type="text" class="form-control" id="name" name="name"
                            placeholder="pokemon name">
                    </div>
                    <div class="form-group">
                        <label for="level">pokemon level</label>
                        <input value="<?= $level ?>" required type="number" step="0.001" class="form-control" id="level"
                            name="level" placeholder="pokemon level">
                    </div>
                    <div class="form-group">
                        <label for="weight">pokemon weight</label>
                        <input value="<?= $weight ?>" required type="number" step="0.001" class="form-control" id="weight" name="weight" placeholder="pokemon weight">
                    </div>
                    <div class="form-group">
                        <label for="height">pokemon height</label>
                        <input value="<?= $height ?>" required type="number" step="0.001" class="form-control" id="height" name="height" placeholder="pokemon height">
                    </div>
                    <div class="form-group">
                        <label for="ptype">pokemon type</label>
                        <input value="<?= $ptype ?>" required type="text" class="form-control" id="ptype" name="ptype" placeholder="pokemon type">
                    </div>
                    <div class="form-group">
                        <label for="evolution">pokemon evolution</label>
                        <input value="<?= $evolution ?>" required type="number" class="form-control" id="evolution"
                            name="evolution" placeholder="pokemon evolution">
                    </div>
                    <button type="submit" class="btn btn-primary">add</button>
                </form>
            </div>
            <hr>
        </div>
    </main>
    <footer class="container">
        <p>&copy; IZV 2024. Práctica PhP</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>

</html>