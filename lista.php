<?php
/**
 * lista.php
 * Mostra la llista de sensors amb la seva magnitud associada.
 */

$cn = new mysqli('localhost', 'phpmyadmin', '12345', 'IoTMonitorizacion');

if ($cn->connect_errno) {
    die('Error de connexió: ' . $cn->connect_error);
}

// ── Sensors amb JOIN a Magnitud ───────────────────────────────────────────────
$sql = "SELECT s.id, s.codigo, s.modelo, m.nombre AS magnitud, s.rango
        FROM Sensor s
        INNER JOIN Magnitud m ON s.idMagnitud = m.id
        ORDER BY s.id ASC";

$resultado = $cn->query($sql);
if (!$resultado) { die('Error en la consulta: ' . $cn->error); }
$total = $resultado->num_rows;

// ── Sensors per magnitud (per a les estadístiques) ────────────────────────────
$sqlStats = "SELECT m.nombre, COUNT(s.id) AS total
             FROM Magnitud m
             LEFT JOIN Sensor s ON s.idMagnitud = m.id
             GROUP BY m.id, m.nombre
             ORDER BY m.nombre ASC";

$stats = $cn->query($sqlStats);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensores — IoT Monitor</title>
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

        <!-- Page header -->
        <div class="page-header">
            <h1>Lista de sensores</h1>
            <p>Registro completo de sensores y sus magnitudes asociadas</p>
        </div>

        <!-- Delete success banner -->
        <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success" style="margin-bottom:20px;">
            <span class="alert-icon">✔</span>
            <div>Sensor <strong><?= htmlspecialchars($_GET['deleted']) ?></strong> eliminado correctamente.</div>
        </div>
        <?php endif; ?>

        <!-- Magnitude stats -->
        <?php if ($stats && $stats->num_rows > 0): ?>
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h2><span class="icon">📊</span> Sensores por magnitud</h2>
            </div>
            <div class="card-body" style="padding: 16px 24px;">
                <div class="mag-grid">
                    <?php while ($s = $stats->fetch_assoc()): ?>
                    <div class="mag-chip">
                        <span class="mag-name"><?= htmlspecialchars($s['nombre']) ?></span>
                        <span class="mag-count"><?= (int)$s['total'] ?></span>
                    </div>
                    <?php endwhile; ?>
                    <div class="mag-chip">
                        <span class="mag-name">Total</span>
                        <span class="mag-count" style="color:var(--text);"><?= $total ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Table card -->
        <div class="card">
            <div class="card-header">
                <h2><span class="icon">🔌</span> Sensores registrados</h2>
                <div style="display:flex;align-items:center;gap:12px;">
                    <!-- Search filter -->
                    <div class="search-bar">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchInput"
                               placeholder="Buscar por código o modelo…"
                               oninput="filterTable()">
                    </div>
                    <a href="form-sensor.php" class="btn btn-primary btn-sm">+ Añadir</a>
                </div>
            </div>
            <div class="table-wrapper">
                <table id="sensorTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Modelo</th>
                            <th>Magnitud</th>
                            <th>Rango</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php if ($total > 0): ?>
                            <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td class="td-id">#<?= htmlspecialchars($fila['id']) ?></td>
                                <td class="td-code"><?= htmlspecialchars($fila['codigo']) ?></td>
                                <td><?= htmlspecialchars($fila['modelo']) ?></td>
                                <td>
                                    <span class="badge badge-blue"><?= htmlspecialchars($fila['magnitud']) ?></span>
                                </td>
                                <td class="td-range"><?= htmlspecialchars($fila['rango']) ?></td>
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <a href="editar-sensor.php?id=<?= (int)$fila['id'] ?>"
                                           class="btn btn-edit btn-sm">✏ Editar</a>
                                        <a href="eliminar-sensor.php?id=<?= (int)$fila['id'] ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('¿Seguro que quieres eliminar el sensor <?= htmlspecialchars($fila['codigo'], ENT_QUOTES) ?>?')">
                                           🗑 Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="td-empty">
                                    No hay sensores registrados.
                                    <a href="form-sensor.php" style="color:var(--accent);margin-left:6px;">Añadir uno →</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        $resultado->free();
        $cn->close();
        ?>

        <a class="back-link" href="index-1.php">← Volver al inicio</a>

    </div>

    <script>
        function filterTable() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const rows  = document.querySelectorAll('#tableBody tr');
            let visible = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const show = text.includes(query);
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            // Show "no results" row if everything is hidden
            let noResultRow = document.getElementById('noResultRow');
            if (visible === 0 && query !== '') {
                if (!noResultRow) {
                    noResultRow = document.createElement('tr');
                    noResultRow.id = 'noResultRow';
                    noResultRow.innerHTML = '<td colspan="6" class="td-empty">No hay sensores que coincidan con "<strong>' + query + '</strong>".</td>';
                    document.getElementById('tableBody').appendChild(noResultRow);
                }
                noResultRow.style.display = '';
            } else if (noResultRow) {
                noResultRow.style.display = 'none';
            }
        }
    </script>

</body>
</html>
