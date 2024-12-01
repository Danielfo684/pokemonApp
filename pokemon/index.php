<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

$user = null;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

try {
    $connection = new PDO(
        'mysql:host=localhost;dbname=productdatabase',
        'productuser',
        'productpassword',
        array(
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
        )
    );
} catch (PDOException $e) {
    header('Location:..');
    exit;
}

$sql = 'SELECT * FROM pokemon ORDER BY nombre, id';
try {
    $sentence = $connection->prepare($sql);
    $sentence->execute();
} catch (PDOException $e) {
    header('Location:..');
    exit;
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pokemon List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="..">Pokémon</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="..">Home</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="./">Pokémon</a>
            </li>
        </ul>
    </div>
</nav>
<main role="main">
    <div class="jumbotron">
        <div class="container">
            <h4 class="display-4">Pokémon</h4>
        </div>
    </div>
    <div class="container">
        <?php if (isset($_GET['op']) && isset($_GET['result'])): ?>
            <?php if ($_GET['result'] > 0): ?>
                <div class="alert alert-primary">
                    result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="row">
            <h3>Pokémon List</h3>
        </div>
        <table class="table table-striped table-hover" id="tablaPokemon">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Peso</th>
                    <th>Altura</th>
                    <th>Tipo</th>
                    <th>Número</th>
                    <?php if (isset($_SESSION['user'])): ?>
                        <th>Delete</th>
                        <th>Edit</th>
                    <?php endif; ?>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $sentence->fetch()): ?>
                    <tr>
                        <td><?= $fila['id'] ?></td>
                        <td><?= $fila['nombre'] ?></td>
                        <td><?= $fila['peso'] ?></td>
                        <td><?= $fila['altura'] ?></td>
                        <td><?= $fila['tipo'] ?></td>
                        <td><?= $fila['numero'] ?></td>
                        <?php if (($user === 'even' && $fila['id'] % 2 == 0) || 
                                  ($user === 'odd' && $fila['id'] % 2 != 0)): ?>
                            <td><a href="destroy.php?id=<?= $fila['id'] ?>" class="borrar">Delete</a></td>
                            <td><a href="edit.php?id=<?= $fila['id'] ?>">Edit</a></td>
                        <?php elseif ($user != null): ?>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        <?php endif; ?>
                        <td><a href="show.php?id=<?= $fila['id'] ?>">View</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="row">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="create.php" class="btn btn-success">Add Pokémon</a>
            <?php endif; ?>
        </div>
        <hr>
    </div>
</main>
<footer class="container">
    <p>&copy; Pokémon 2024</p>
</footer>
</body>
</html>

<?php
$connection = null;
?>
