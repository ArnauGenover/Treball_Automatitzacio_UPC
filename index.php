<?php
/**
 * index-1.php
 * Pàgina principal del panell de control. Consulta les estadístiques
 * de sensors i magnituds per mostrar-les en temps real.
 */

require_once 'config.php';

$totalSensors   = '—';
$totalMagnituds = '—';
$dbOk           = ($cn !== null);   // config.php sets $cn to null on failure

if ($dbOk) {

    $r = $cn->query("SELECT COUNT(*) AS total FROM Sensor");
    if ($r) { $totalSensors = (int)$r->fetch_assoc()['total']; }

    $r = $cn->query("SELECT COUNT(*) AS total FROM Magnitud");
    if ($r) { $totalMagnituds = (int)$r->fetch_assoc()['total']; }

    $cn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IoT Monitoring — Subestaciones Eléctricas</title>
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
            <li><a href="index.php" class="active">Inicio</a></li>
            <li><a href="lista.php">Sensores</a></li>
            <li><a href="form-sensor.php">Añadir</a></li>
        </ul>
    </nav>

    <div class="page">

        <!-- Page header -->
        <div class="page-header">
            <h1>Panel de control</h1>
            <p>Gestión y monitorización de sensores en subestaciones eléctricas</p>
        </div>

        <!-- Stat cards -->
        <div class="dashboard-grid" style="margin-bottom: 32px;">
            <div class="stat-card">
                <div class="stat-icon">🔌</div>
                <div class="stat-value"><?= $totalSensors ?></div>
                <div class="stat-label">Sensores registrados</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📡</div>
                <div class="stat-value"><?= $totalMagnituds ?></div>
                <div class="stat-label">Magnitudes activas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⚡</div>
                <?php if ($dbOk): ?>
                    <div class="stat-value" style="color:var(--success);font-size:1.1rem;">EN LÍNEA</div>
                    <div class="stat-label" style="display:flex;align-items:center;gap:6px;margin-top:6px;">
                        <span class="dot dot-green"></span> Sistema operativo
                    </div>
                <?php else: ?>
                    <div class="stat-value" style="color:var(--error);font-size:1.1rem;">ERROR</div>
                    <div class="stat-label" style="display:flex;align-items:center;gap:6px;margin-top:6px;">
                        <span class="dot" style="background:var(--error);"></span> Sin conexión a BD
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Navigation card -->
        <div class="card">
            <div class="card-header">
                <h2><span class="icon">🗂</span> Acciones disponibles</h2>
                <span class="badge badge-cyan">v1.0</span>
            </div>
            <div class="card-body">
                <ul class="nav-links">
                    <li>
                        <a href="lista.php">
                            <div class="link-icon link-icon-blue">📋</div>
                            <div class="link-text">
                                Ver lista de sensores
                                <small>Consultar todos los sensores registrados</small>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="form-sensor.php">
                            <div class="link-icon link-icon-cyan">➕</div>
                            <div class="link-text">
                                Añadir un sensor
                                <small>Registrar un nuevo sensor en el sistema</small>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>

</body>
</html>
