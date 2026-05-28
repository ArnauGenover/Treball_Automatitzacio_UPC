<?php
/**
 * eliminar-sensor.php
 * Elimina un sensor de la base de dades per ID.
 * Gestiona l'error de clau forana si el sensor té lectures associades.
 */

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}

require_once 'config.php';
if (!$cn) { die(htmlspecialchars($DB_CONNECT_ERROR)); }

// Obté el codi del sensor abans d'esborrar (per mostrar-lo al missatge)
$stmtGet = $cn->prepare("SELECT codigo FROM Sensor WHERE id = ?");
$stmtGet->bind_param('i', $id);
$stmtGet->execute();
$resGet = $stmtGet->get_result();

if ($resGet->num_rows === 0) {
    $stmtGet->close();
    $cn->close();
    header('Location: lista.php');
    exit;
}

$sensor = $resGet->fetch_assoc();
$stmtGet->close();

// Intenta eliminar
$stmt = $cn->prepare("DELETE FROM Sensor WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

$deleted = ($stmt->affected_rows > 0);
$errno   = $cn->errno;
$errmsg  = $cn->error;

$stmt->close();
$cn->close();

// Si s'ha esborrat correctament, redirigeix directament a la llista
if ($deleted) {
    header('Location: lista.php?deleted=' . urlencode($sensor['codigo']));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar sensor — IoT Monitor</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="index.php" class="navbar-brand">
            <div class="brand-icon">⚡</div>
            <div>
                IoT Monitor
                <span class="brand-sub">Subestaciones Eléctricas</span>
            </div>
        </a>
        <ul class="navbar-nav">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="lista.php" class="active">Sensores</a></li>
            <li><a href="form-sensor.php">Añadir</a></li>
        </ul>
    </nav>

    <div class="page">

        <div class="page-header">
            <h1>Error al eliminar</h1>
            <p>No se ha podido eliminar el sensor</p>
        </div>

        <div class="card" style="max-width: 560px;">
            <div class="card-header">
                <h2><span class="icon">⚠</span> Informe</h2>
            </div>
            <div class="card-body">

                <?php if ($errno === 1451): ?>
                    <!-- FK constraint: el sensor té registres associats -->
                    <div class="alert alert-error">
                        <span class="alert-icon">✖</span>
                        <div>
                            No se puede eliminar el sensor <strong><?= htmlspecialchars($sensor['codigo']) ?></strong>
                            porque tiene lecturas o listados asociados en la base de datos.<br>
                            <span style="font-size:0.85rem;color:var(--muted);margin-top:4px;display:block;">
                                Elimina primero los registros vinculados antes de borrar este sensor.
                            </span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-error">
                        <span class="alert-icon">✖</span>
                        <div>Error al eliminar: <?= htmlspecialchars($errmsg) ?></div>
                    </div>
                <?php endif; ?>

                <a href="lista.php" class="btn btn-primary">← Volver a la lista</a>

            </div>
        </div>

        <a class="back-link" href="lista.php">← Volver a la lista</a>

    </div>

</body>
</html>
