<?php
/**
 * guardar-edicion.php
 * Rep les dades del formulari d'edició (GET), les valida
 * i actualitza el sensor a la base de dades.
 */

$id         = isset($_GET['id'])         ? (int)trim($_GET['id'])         : 0;
$idMagnitud = isset($_GET['idMagnitud']) ? (int)trim($_GET['idMagnitud']) : 0;
$codigo     = isset($_GET['codigo'])     ? trim($_GET['codigo'])          : '';
$modelo     = isset($_GET['modelo'])     ? trim($_GET['modelo'])          : '';
$rango      = isset($_GET['rango'])      ? trim($_GET['rango'])           : '';

$error   = '';
$success = false;

if ($id <= 0 || $idMagnitud <= 0 || empty($codigo)) {
    $error = 'Faltan datos obligatorios para actualizar el sensor.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar sensor — IoT Monitor</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="index-1.php" class="navbar-brand">
            <div class="brand-icon">⚡</div>
            <div>
                IoT Monitor
                <span class="brand-sub">Subestaciones Eléctricas</span>
            </div>
        </a>
        <ul class="navbar-nav">
            <li><a href="index-1.php">Inicio</a></li>
            <li><a href="lista.php" class="active">Sensores</a></li>
            <li><a href="form-sensor.php">Añadir</a></li>
        </ul>
    </nav>

    <div class="page">

        <div class="page-header">
            <h1>Resultado de la edición</h1>
            <p>Estado de la actualización del sensor</p>
        </div>

        <div class="card" style="max-width: 560px;">
            <div class="card-header">
                <h2><span class="icon">📋</span> Informe</h2>
            </div>
            <div class="card-body">

                <?php if ($error !== ''): ?>
                    <div class="alert alert-error">
                        <span class="alert-icon">✖</span>
                        <div><?= htmlspecialchars($error) ?></div>
                    </div>
                    <a href="lista.php" class="btn btn-primary">← Volver a la lista</a>

                <?php else: ?>
                    <?php
                    $cn = new mysqli('localhost', 'phpmyadmin', '12345', 'IoTMonitorizacion');

                    if ($cn->connect_errno) {
                        die('Error de connexió: ' . $cn->connect_error);
                    }

                    $stmt = $cn->prepare(
                        "UPDATE Sensor
                         SET idMagnitud = ?, codigo = ?, modelo = ?, rango = ?
                         WHERE id = ?"
                    );

                    if (!$stmt) {
                        die('Error preparant la consulta: ' . $cn->error);
                    }

                    $stmt->bind_param('isssi', $idMagnitud, $codigo, $modelo, $rango, $id);

                    if ($stmt->execute()):
                        $success = true;
                    ?>
                        <div class="alert alert-success">
                            <span class="alert-icon">✔</span>
                            <div>Sensor <strong><?= htmlspecialchars($codigo) ?></strong> actualizado correctamente.</div>
                        </div>

                        <table style="margin-bottom:20px;">
                            <tbody>
                                <tr>
                                    <td style="color:var(--muted);width:120px;font-size:0.8rem;text-transform:uppercase;letter-spacing:.05em;">Código</td>
                                    <td class="td-code"><?= htmlspecialchars($codigo) ?></td>
                                </tr>
                                <tr>
                                    <td style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:.05em;">Modelo</td>
                                    <td><?= htmlspecialchars($modelo ?: '—') ?></td>
                                </tr>
                                <tr>
                                    <td style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:.05em;">Rango</td>
                                    <td class="td-range"><?= htmlspecialchars($rango ?: '—') ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="btn-group">
                            <a href="lista.php"                       class="btn btn-primary">Ver lista de sensores</a>
                            <a href="editar-sensor.php?id=<?= $id ?>" class="btn btn-ghost">Seguir editando</a>
                        </div>
                        <p class="redirect-note">Redirigiendo a la lista en <span id="countdown">3</span>s…</p>

                    <?php else: ?>
                        <?php
                        if ($cn->errno === 1062) {
                            $msg = 'Ya existe un sensor con el código <strong>' . htmlspecialchars($codigo) . '</strong>. El código debe ser único.';
                        } else {
                            $msg = 'Error al actualizar: ' . htmlspecialchars($cn->error);
                        }
                        ?>
                        <div class="alert alert-error">
                            <span class="alert-icon">✖</span>
                            <div><?= $msg ?></div>
                        </div>
                        <a href="editar-sensor.php?id=<?= $id ?>" class="btn btn-primary">← Volver al formulario</a>

                    <?php endif; ?>

                    <?php
                    $stmt->close();
                    $cn->close();
                    ?>

                <?php endif; ?>

            </div>
        </div>

        <a class="back-link" href="lista.php">← Volver a la lista</a>

    </div>

    <?php if ($success): ?>
    <script>
        let secs = 3;
        const el = document.getElementById('countdown');
        const interval = setInterval(() => {
            secs--;
            if (el) el.textContent = secs;
            if (secs <= 0) {
                clearInterval(interval);
                window.location.href = 'lista.php';
            }
        }, 1000);
    </script>
    <?php endif; ?>

</body>
</html>
