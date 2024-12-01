<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: .'); // Redirigir a la página principal si no está autenticado
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
    header('Location: .?op=errorconnection&result=0'); // Redirigir a la página principal si hay un error de conexión
    exit;
}

$resultado = 0;
$url = 'create.php?op=insertpokemon&result=' . $resultado;

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verificar que el método sea POST
    $nombre = trim($_POST['nombre']);
    $peso = $_POST['peso'];
    $altura = $_POST['altura'];
    $tipo = trim($_POST['tipo']);
    $numero = $_POST['numero'];
    
    $ok = true;

    // Validación de los datos
    if (strlen($nombre) < 2 || strlen($nombre) > 100) {
        $ok = false;
    }
    if (!(is_numeric($peso) && $peso >= 0 && $peso <= 1000)) {
        $ok = false;
    }
    if (!(is_numeric($altura) && $altura >= 0 && $altura <= 100)) {
        $ok = false;
    }
    if (strlen($tipo) < 2 || strlen($tipo) > 50) {
        $ok = false;
    }
    if (!(is_numeric($numero) && $numero >= 1 && $numero <= 10000)) {
        $ok = false;
    }

    if ($ok) {
        $sql = 'INSERT INTO pokemon (nombre, peso, altura, tipo, numero) VALUES (:nombre, :peso, :altura, :tipo, :numero)';
        $sentence = $connection->prepare($sql);
        $parameters = [
            'nombre' => $nombre, 
            'peso' => $peso, 
            'altura' => $altura, 
            'tipo' => $tipo, 
            'numero' => $numero
        ];

        foreach ($parameters as $nombreParametro => $valorParametro) {
            $sentence->bindValue($nombreParametro, $valorParametro);
        }

        try {
            $sentence->execute();
            $resultado = $connection->lastInsertId();
            $url = 'index.php?op=insertpokemon&result=' . $resultado; // Redirigir a index.php después de la inserción
        } catch (PDOException $e) {
            // Manejo de errores opcional
            $url = 'create.php?op=insertpokemon&result=0'; // Redirigir con error
        }
    } else {
        $url = 'create.php?op=validation&result=0'; // Redirigir si hay error de validación
    }
    header('Location: ' . $url); // Realizar la redirección
    exit; // Asegúrate de usar exit después de redirigir
}

// Si se llega a este punto, significa que no se envió el formulario, se debe mostrar el formulario
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Crear Pokémon</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="..">dwes</a>
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
                <h4 class="display-4">Crear Pokémon</h4>
            </div>
        </div>
        <div class="container">
            <?php if (isset($_GET['op'])): ?>
                <div class="alert alert-danger" role="alert">
                    Error: <?= htmlspecialchars($_GET['op']) ?> <!-- Muestra errores de manera segura -->
                </div>
            <?php endif; ?>
            <div>
                <form action="create.php" method="post">
                    <div class="form-group">
                        <label for="nombre">Nombre del Pokémon</label>
                        <input required type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del Pokémon">
                    </div>
                    <div class="form-group">
                        <label for="peso">Peso (kg)</label>
                        <input required type="number" step="0.01" class="form-control" id="peso" name="peso" placeholder="Peso">
                    </div>
                    <div class="form-group">
                        <label for="altura">Altura (m)</label>
                        <input required type="number" step="0.01" class="form-control" id="altura" name="altura" placeholder="Altura">
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <input required type="text" class="form-control" id="tipo" name="tipo" placeholder="Tipo">
                    </div>
                    <div class="form-group">
                        <label for="numero">Número del Pokémon</label>
                        <input required type="number" class="form-control" id="numero" name="numero" placeholder="Número del Pokémon">
                    </div>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </form>
            </div>
            <hr>
        </div>
    </main>
    <footer class="container">
        <p>&copy; IZV 2024</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>


