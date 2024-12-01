<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}
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
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Daniel</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="..">Daniel</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="..">home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./">Pokemon</a>
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
                if(isset($_GET['op'])) {
                    ?>
                    <div class="alert alert-primary" role="alert">
                        result: <?= $_GET['op'] ?>
                    </div>
                    <?php
                }
                ?>
                <div>
                <form action="store.php" method="post">
                <div class="form-group">
                    <label for="name">Pokemon Name</label>
                    <input value="<?= $name ?>" required type="text" class="form-control" id="name" name="name" placeholder="Pokemon name">
                </div>
                <div class="form-group">
                    <label for="weight">Pokemon Weight</label>
                    <input value="<?= $weight ?>" required type="number" step="0.001" class="form-control" id="weight" name="weight" placeholder="Pokemon weight">
                </div>
                <div class="form-group">
                    <label for="height">Pokemon Height</label>
                    <input value="<?= $height ?>" required type="number" step="0.001" class="form-control" id="height" name="height" placeholder="Pokemon height">
                </div>
                <div class="form-group">
                    <label for="type">Pokemon Type</label>
                    <select required class="form-control" id="type" name="type">
                        <option value="agua">water</option>
                        <option value="fuego">fire</option>
                        <option value="planta">grass</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="evolution">Evolution (ID)</label>
                    <input value="<?= $evolution ?>" type="number" class="form-control" id="evolution" name="evolution" placeholder="Evolution ID">
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
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