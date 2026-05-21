<?php
/**
 * lista.php
 * Mostra la llista de sensors amb la seva magnitud associada.
 */

// ── Connexió a la base de dades (mysqli OO) ──────────────────────────────────
$cn = new mysqli('localhost', 'phpmyadmin', '12345', 'IoTMonitorizacion');

// Comprova que la connexió s'ha establert correctament
if ($cn->connect_errno) {
    die('Error de connexió: ' . $cn->connect_error);
}

// ── Consulta amb JOIN per obtenir sensor + magnitud ──────────────────────────
$sql = "SELECT s.id, s.codigo, s.modelo, m.nombre, s.rango
        FROM Sensor s
        INNER JOIN Magnitud m ON s.idMagnitud = m.id
        ORDER BY s.id ASC";

$resultado = $cn->query($sql);

if (!$resultado) {
    die('Error en la consulta: ' . $cn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de sensores</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>Monitorización de subestaciones eléctricas</h1>
        <h2>Lista de sensores</h2>

        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Código</th>
                    <th>Modelo</th>
                    <th>Magnitud</th>
                    <th>Rango</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Recorre i mostra cada fila del resultat
                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($fila['id'])      . '</td>';
                        echo '<td>' . htmlspecialchars($fila['codigo'])  . '</td>';
                        echo '<td>' . htmlspecialchars($fila['modelo'])  . '</td>';
                        echo '<td>' . htmlspecialchars($fila['nombre'])  . '</td>';
                        echo '<td>' . htmlspecialchars($fila['rango'])   . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">No hay sensores registrados.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <?php
        // Allibera la memòria del resultat i tanca la connexió
        $resultado->free();
        $cn->close();
        ?>

        <a class="back-link" href="index-1.html">Volver al índice</a>
    </div>
</body>
</html>
