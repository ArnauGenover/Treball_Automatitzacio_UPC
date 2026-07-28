<?php
/**
 * nuevo-sensor.php
 * Rep les dades del formulari (mètode GET), les valida i insereix
 * el nou sensor a la base de dades.
 */

require_once 'config.php';

$idMagnitud = isset($_GET['idMagnitud']) ? trim($_GET['idMagnitud']) : '';
$codigo     = isset($_GET['codigo'])     ? trim($_GET['codigo'])     : '';
$modelo     = isset($_GET['modelo'])     ? trim($_GET['modelo'])     : '';
$rango      = isset($_GET['rango'])      ? trim($_GET['rango'])      : '';

$error   = '';
$success = false;

if (empty($idMagnitud) || empty($codigo)) {
    $error = 'Faltan datos obligatorios para añadir el sensor (magnitud y código son requeridos).';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado — IoT Monitor</title>
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
            <li><a href="lista.php">Sensores</a></li>
            <li><a href="form-sensor.php">Añadir</a></li>
        </ul>
    </nav>

    <div class="page">

        <div class="page-header">
            <h1>Resultado de la operación</h1>
            <p>Estado de la inserción del nuevo sensor</p>
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
                    <a href="form-sensor.php" class="btn btn-primary">← Volver al formulario</a>

                <?php else: ?>
                    <?php
                    if (!$cn) { die(htmlspecialchars($DB_CONNECT_ERROR)); }

                    // Comprova duplicat abans d'intentar la inserció
                    $duplicado = false;
                    $stmtCheck = $cn->prepare("SELECT id FROM Sensor WHERE codigo = ? LIMIT 1");
                    if ($stmtCheck) {
                        $stmtCheck->bind_param('s', $codigo);
                        $stmtCheck->execute();
                        $resCheck = $stmtCheck->get_result();
                        $duplicado = ($resCheck->num_rows > 0);
                        $stmtCheck->close();
                    }

                    if ($duplicado):
                    ?>
                        <div class="alert alert-error">
                            <span class="alert-icon">✖</span>
                            <div>Ya existe un sensor con el código <strong><?= htmlspecialchars($codigo) ?></strong>. El código debe ser único.</div>
                        </div>
                        <a href="form-sensor.php" class="btn btn-primary">← Volver al formulario</a>

                    <?php else:
                    $stmt = $cn->prepare(
                        "INSERT INTO Sensor (idMagnitud, codigo, modelo, rango)
                         VALUES (?, ?, ?, ?)"
                    );

                    if (!$stmt) {
                        die('Error preparant la consulta: ' . $cn->error);
                    }

                    $stmt->bind_param('isss', $idMagnitud, $codigo, $modelo, $rango);

                    if ($stmt->execute()):
                        $success = true;
                    ?>
                        <div class="alert alert-success">
                            <span class="alert-icon">✔</span>
                            <div>Sensor <strong><?= htmlspecialchars($codigo) ?></strong> añadido correctamente.</div>
                        </div>

                        <table style="margin-bottom: 20px;">
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
                            <a href="lista.php"       class="btn btn-primary">Ver lista de sensores</a>
                            <a href="form-sensor.php" class="btn btn-ghost">Añadir otro</a>
                        </div>
                        <p class="redirect-note" id="redirectNote">Redirigiendo a la lista en <span id="countdown">3</span>s…</p>

                    <?php else: ?>
                        <div class="alert alert-error">
                            <span class="alert-icon">✖</span>
                            <div>Error en la inserción: <?= htmlspecialchars($cn->error) ?></div>
                        </div>
                        <a href="form-sensor.php" class="btn btn-primary">← Volver al formulario</a>

                    <?php endif; ?>

                    <?php
                    $stmt->close();
                    endif; // duplicado
                    $cn->close();
                    ?>

                <?php endif; ?>

            </div>
        </div>

        <a class="back-link" href="index.php">← Volver al inicio</a>

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
