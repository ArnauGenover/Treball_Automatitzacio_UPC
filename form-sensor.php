<?php
/**
 * form-sensor.php
 * Formulari per afegir un nou sensor.
 */

require_once 'config.php';
if (!$cn) { die(htmlspecialchars($DB_CONNECT_ERROR)); }

$sqlMagnituds = "SELECT id, nombre FROM Magnitud ORDER BY nombre ASC";
$magnituds    = $cn->query($sqlMagnituds);

if (!$magnituds) {
    die('Error en la consulta: ' . $cn->error);
}

// Còdigs ja registrats (per validar duplicats abans d'enviar el formulari)
$codigosExistentes = [];
$resCodigos = $cn->query("SELECT codigo FROM Sensor ORDER BY codigo ASC");
if ($resCodigos) {
    while ($row = $resCodigos->fetch_assoc()) {
        $codigosExistentes[] = $row['codigo'];
    }
    $resCodigos->free();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir sensor — IoT Monitor</title>
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
            <li><a href="form-sensor.php" class="active">Añadir</a></li>
        </ul>
    </nav>

    <div class="page">

        <!-- Page header -->
        <div class="page-header">
            <h1>Añadir sensor</h1>
            <p>Rellena los campos para registrar un nuevo sensor en el sistema</p>
        </div>

        <!-- Form card -->
        <div class="card" style="max-width: 560px;">
            <div class="card-header">
                <h2><span class="icon">➕</span> Nuevo sensor</h2>
                <span class="badge badge-cyan">Formulario</span>
            </div>
            <div class="card-body">

                <form method="GET" action="nuevo-sensor.php" id="formSensor" novalidate>
                    <div class="form-grid">

                        <div class="form-group">
                            <label for="idMagnitud">Magnitud <span style="color:var(--error)">*</span></label>
                            <select id="idMagnitud" name="idMagnitud">
                                <option value="">— Seleccione una magnitud —</option>
                                <?php
                                while ($mag = $magnituds->fetch_assoc()) {
                                    echo '<option value="' . (int)$mag['id'] . '">'
                                         . htmlspecialchars($mag['nombre'])
                                         . '</option>';
                                }
                                $magnituds->free();
                                $cn->close();
                                ?>
                            </select>
                            <span class="field-error" id="err-magnitud">Selecciona una magnitud.</span>
                        </div>

                        <div class="form-group">
                            <label for="codigo">Código <span style="color:var(--error)">*</span></label>
                            <input type="text" id="codigo" name="codigo"
                                   maxlength="6" placeholder="Máx. 6 caracteres">
                            <span class="field-error" id="err-codigo">El código es obligatorio (máx. 6 caracteres).</span>
                            <span class="field-error" id="err-codigo-duplicado">Este código ya existe. Elige otro.</span>
                        </div>

                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input type="text" id="modelo" name="modelo"
                                   placeholder="Ej. XT-200A">
                        </div>

                        <div class="form-group">
                            <label for="rango">Rango</label>
                            <input type="text" id="rango" name="rango"
                                   placeholder="Ej. 0–100 °C">
                        </div>

                    </div>

                    <hr class="divider">

                    <div class="btn-group">
                        <input type="reset"  value="Borrar"          onclick="clearErrors()">
                        <input type="submit" value="Guardar sensor">
                    </div>
                </form>

            </div>
        </div>

        <a class="back-link" href="index.php">← Volver al inicio</a>

    </div>

    <script>
        const codigosExistentes = <?= json_encode($codigosExistentes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

        function codigoDuplicado(codigo) {
            const normalizado = codigo.trim().toLowerCase();
            return codigosExistentes.some(c => c.toLowerCase() === normalizado);
        }

        document.getElementById('formSensor').addEventListener('submit', function(e) {
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
            const errCodDup = document.getElementById('err-codigo-duplicado');
            errCodDup.classList.remove('visible');

            if (!cod.value.trim()) {
                cod.classList.add('input-error');
                errCod.classList.add('visible');
                valid = false;
            } else if (codigoDuplicado(cod.value)) {
                cod.classList.add('input-error');
                errCodDup.classList.add('visible');
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
                if (id === 'codigo') {
                    document.getElementById('err-codigo-duplicado').classList.remove('visible');
                }
            });
        });

        function clearErrors() {
            document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
            document.querySelectorAll('.field-error').forEach(el => el.classList.remove('visible'));
        }
    </script>

</body>
</html>
