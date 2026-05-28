<?php
/**
 * nuevo-sensor.php
 * Rep les dades del formulari (GET), valida i insereix el sensor a la BD.
 */

// Recollida de dades de $_GET
$idMagnitud = isset($_GET['idMagnitud']) ? trim($_GET['idMagnitud']) : '';
$codigo     = isset($_GET['codigo'])     ? trim($_GET['codigo'])     : '';
$modelo     = isset($_GET['modelo'])     ? trim($_GET['modelo'])     : '';
$rango      = isset($_GET['rango'])      ? trim($_GET['rango'])      : '';

$error   = '';
$success = false;

// Validació: camps obligatoris
if (empty($idMagnitud) || empty($codigo)) {
    $error = 'Faltan datos para añadir el sensor';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir sensor</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <div class="page">
        <div class="page-header">
            <h1>Monitorización de subestaciones eléctricas</h1>
            <h2 style="font-size:1.1rem;color:var(--muted);border:none;padding:0;margin:0;">Añadir sensores</h2>
        </div>

        <div class="card" style="max-width:560px;">
            <div class="card-body">

                <?php if ($error !== ''): ?>
                    <!-- Cas 1: falten dades obligatòries -->
                    <p class="msg-error"><?= htmlspecialchars($error) ?></p>

                <?php else: ?>
                    <?php
                    // Connexió a MariaDB (mysqli orientat a objectes)
                    $cn = new mysqli('localhost', 'phpmyadmin', '12345', 'IoTMonitorizacion');

                    if ($cn->connect_errno) {
                        die('Error de connexió: ' . $cn->connect_error);
                    }

                    // Comprova codi duplicat abans de la inserció
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
                        <!-- Cas 2: codi ja existent -->
                        <p class="msg-error">Ya existe un sensor con el código
                            <strong><?= htmlspecialchars($codigo) ?></strong>.
                            El código debe ser único.</p>

                    <?php else:
                    // Prepared statement per evitar injecció SQL
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
                        <!-- Cas 3: inserció correcta -->
                        <p class="msg-ok">Operación efectuada correctamente</p>

                    <?php else: ?>
                        <!-- Cas 4: altre error d'inserció -->
                        <p class="msg-error">Error en la inserción: <?= htmlspecialchars($cn->error) ?></p>

                    <?php endif; ?>

                    <?php
                    $stmt->close();
                    endif; // duplicado
                    $cn->close();
                    ?>

                <?php endif; ?>

            </div>
        </div>

        <a class="back-link" href="index-1.html">← Volver al índice</a>
    </div>

</body>
</html>
