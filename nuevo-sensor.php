<?php
/**
 * nuevo-sensor.php
 * Rep les dades del formulari (mètode GET), les valida i insereix
 * el nou sensor a la base de dades.
 */

// ── Recollida de dades de $_GET ──────────────────────────────────────────────
$idMagnitud = isset($_GET['idMagnitud']) ? trim($_GET['idMagnitud']) : '';
$codigo     = isset($_GET['codigo'])     ? trim($_GET['codigo'])     : '';
$modelo     = isset($_GET['modelo'])     ? trim($_GET['modelo'])     : '';
$rango      = isset($_GET['rango'])      ? trim($_GET['rango'])      : '';

// ── Validació: comprova que els camps obligatoris no estiguin buits ──────────
$error = '';

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
    <div class="container">
        <h1>Monitorización de subestaciones eléctricas</h1>
        <h2>Añadir sensores</h2>

        <?php if ($error !== ''): ?>
            <!-- ── Cas 1: falten dades ──────────────────────────────────── -->
            <p class="msg-error"><?= htmlspecialchars($error) ?></p>

        <?php else: ?>
            <?php
            // ── Connexió a la base de dades (mysqli OO) ──────────────────
            $cn = new mysqli('localhost', 'phpmyadmin', '12345', 'IoTMonitorizacion');

            if ($cn->connect_errno) {
                die('Error de connexió: ' . $cn->connect_error);
            }

            // ── Prepared statement per evitar SQL injection ───────────────
            $stmt = $cn->prepare(
                "INSERT INTO Sensor (idMagnitud, codigo, modelo, rango)
                 VALUES (?, ?, ?, ?)"
            );

            if (!$stmt) {
                die('Error preparant la consulta: ' . $cn->error);
            }

            // Vincula els paràmetres: i = integer, s = string
            $stmt->bind_param('isss', $idMagnitud, $codigo, $modelo, $rango);

            // ── Execució i tractament del resultat ────────────────────────
            if ($stmt->execute()) {
                // Cas 2: inserció correcta
                echo '<p class="msg-ok">Operación efectuada correctamente</p>';
            } else {
                // Cas 3: error en la inserció
                if ($cn->errno === 1062) {
                    // Codi duplicat (clau única violada)
                    echo '<p class="msg-error">Duplicate entry \''
                         . htmlspecialchars($codigo)
                         . '\' for key \'codigo\'</p>';
                } else {
                    // Qualsevol altre error de MySQL
                    echo '<p class="msg-error">Error en la inserción: '
                         . htmlspecialchars($cn->error)
                         . '</p>';
                }
            }

            // Allibera els recursos i tanca la connexió
            $stmt->close();
            $cn->close();
            ?>

        <?php endif; ?>

        <a class="back-link" href="index-1.html">Volver al índice</a>
    </div>
</body>
</html>
