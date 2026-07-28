<?php
/**
 * editar-sensor.php
 * Mostra un formulari pre-emplenat amb les dades del sensor a editar.
 */

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: lista.php');
    exit;
}

require_once 'config.php';
if (!$cn) { die(htmlspecialchars($DB_CONNECT_ERROR)); }

// ── Obté les dades del sensor ─────────────────────────────────────────────────
$stmt = $cn->prepare("SELECT id, idMagnitud, codigo, modelo, rango FROM Sensor WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    $stmt->close();
    $cn->close();
    header('Location: lista.php');
    exit;
}

$sensor = $res->fetch_assoc();
$stmt->close();

// ── Obté les magnituds per al desplegable ─────────────────────────────────────
$magnituds = $cn->query("SELECT id, nombre FROM Magnitud ORDER BY nombre ASC");
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
            <h1>Editar sensor</h1>
            <p>Modifica los datos del sensor <span style="color:var(--accent);font-family:'JetBrains Mono',monospace;"><?= htmlspecialchars($sensor['codigo']) ?></span></p>
        </div>

        <div class="card" style="max-width: 560px;">
            <div class="card-header">
                <h2><span class="icon">✏</span> Editar sensor #<?= $sensor['id'] ?></h2>
                <span class="badge badge-blue">ID <?= $sensor['id'] ?></span>
            </div>
            <div class="card-body">

                <form method="GET" action="guardar-edicion.php" id="formEditar" novalidate>

                    <!-- ID oculto -->
                    <input type="hidden" name="id" value="<?= $sensor['id'] ?>">

                    <div class="form-grid">

                        <div class="form-group">
                            <label for="idMagnitud">Magnitud <span style="color:var(--error)">*</span></label>
                            <select id="idMagnitud" name="idMagnitud">
                                <option value="">— Seleccione una magnitud —</option>
                                <?php while ($mag = $magnituds->fetch_assoc()): ?>
                                <option value="<?= (int)$mag['id'] ?>"
                                    <?= $mag['id'] == $sensor['idMagnitud'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($mag['nombre']) ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                            <span class="field-error" id="err-magnitud">Selecciona una magnitud.</span>
                        </div>

                        <div class="form-group">
                            <label for="codigo">Código <span style="color:var(--error)">*</span></label>
                            <input type="text" id="codigo" name="codigo"
                                   maxlength="6"
                                   value="<?= htmlspecialchars($sensor['codigo']) ?>"
                                   placeholder="Máx. 6 caracteres">
                            <span class="field-error" id="err-codigo">El código es obligatorio (máx. 6 caracteres).</span>
                        </div>

                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input type="text" id="modelo" name="modelo"
                                   value="<?= htmlspecialchars($sensor['modelo']) ?>"
                                   placeholder="Ej. XT-200A">
                        </div>

                        <div class="form-group">
                            <label for="rango">Rango</label>
                            <input type="text" id="rango" name="rango"
                                   value="<?= htmlspecialchars($sensor['rango']) ?>"
                                   placeholder="Ej. 0–100 °C">
                        </div>

                    </div>

                    <hr class="divider">

                    <div class="btn-group">
                        <a href="lista.php" class="btn btn-ghost">Cancelar</a>
                        <input type="submit" value="Guardar cambios">
                    </div>
                </form>

            </div>
        </div>

        <a class="back-link" href="lista.php">← Volver a la lista</a>

    </div>

    <?php
    $magnituds->free();
    $cn->close();
    ?>

    <script>
        document.getElementById('formEditar').addEventListener('submit', function(e) {
            let valid = true;

            const mag = document.getElementById('idMagnitud');
            const errMag = document.getElementById('err-magnitud');
            if (!mag.value) {
                mag.classList.add('input-error');
                errMag.classList.add('visible');
                valid = false;
            } else {
                mag.classList.remove('input-error');
                errMag.classList.remove('visible');
            }

            const cod = document.getElementById('codigo');
            const errCod = document.getElementById('err-codigo');
            if (!cod.value.trim()) {
                cod.classList.add('input-error');
                errCod.classList.add('visible');
                valid = false;
            } else {
                cod.classList.remove('input-error');
                errCod.classList.remove('visible');
            }

            if (!valid) e.preventDefault();
        });

        ['idMagnitud', 'codigo'].forEach(id => {
            document.getElementById(id).addEventListener('input', function() {
                this.classList.remove('input-error');
                document.getElementById('err-' + (id === 'idMagnitud' ? 'magnitud' : id))
                        .classList.remove('visible');
            });
        });
    </script>

</body>
</html>
