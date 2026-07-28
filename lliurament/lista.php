<?php
/**
 * lista.php
 * Mostra la llista de sensors amb la magnitud associada mitjançant INNER JOIN.
 */

// Connexió a MariaDB (mysqli orientat a objectes)
$cn = new mysqli('localhost', 'phpmyadmin', '12345', 'IoTMonitorizacion');

if ($cn->connect_errno) {
    die('Error de connexió: ' . $cn->connect_error);
}

// Consulta amb JOIN entre Sensor i Magnitud (dades de dues taules)
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

    <div class="page">
        <div class="page-header">
            <h1>Monitorización de subestaciones eléctricas</h1>
            <h2 style="font-size:1.1rem;color:var(--muted);border:none;padding:0;margin:0;">Lista de sensores</h2>
        </div>

        <div class="card">
            <div class="table-wrapper">
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
                        <?php if ($resultado->num_rows > 0): ?>
                            <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td class="td-id"><?= htmlspecialchars($fila['id']) ?></td>
                                <td class="td-code"><?= htmlspecialchars($fila['codigo']) ?></td>
                                <td><?= htmlspecialchars($fila['modelo']) ?></td>
                                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                <td class="td-range"><?= htmlspecialchars($fila['rango']) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="td-empty">No hay sensores registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        // Allibera la memòria del resultat i tanca la connexió
        $resultado->free();
        $cn->close();
        ?>

        <a class="back-link" href="index-1.html">← Volver al índice</a>
    </div>

</body>
</html>
