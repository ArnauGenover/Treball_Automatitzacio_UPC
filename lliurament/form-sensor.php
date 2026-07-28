<?php
/**
 * form-sensor.php
 * Formulari per afegir un nou sensor. Carrega les magnituds des de la BD.
 */

// Connexió a MariaDB (mysqli orientat a objectes)
$cn = new mysqli('localhost', 'phpmyadmin', '12345', 'IoTMonitorizacion');

if ($cn->connect_errno) {
    die('Error de connexió: ' . $cn->connect_error);
}

// Obté la llista de magnituds per al desplegable <select>
$sqlMagnituds = "SELECT id, nombre FROM Magnitud ORDER BY nombre ASC";
$magnituds    = $cn->query($sqlMagnituds);

if (!$magnituds) {
    die('Error en la consulta: ' . $cn->error);
}

// Còdigs ja registrats (validació de duplicats abans d'enviar el formulari)
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
                <p style="color:var(--muted);margin-bottom:20px;">Introduzca los datos del nuevo sensor</p>

                <!-- Formulari enviat per GET cap a nuevo-sensor.php -->
                <form method="GET" action="nuevo-sensor.php" id="formSensor" novalidate>
                    <div class="form-grid">

                        <div class="form-group">
                            <label for="idMagnitud">Magnitud:</label>
                            <select id="idMagnitud" name="idMagnitud">
                                <option value="">-- Seleccione una magnitud --</option>
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
                            <label for="codigo">Código:</label>
                            <input type="text" id="codigo" name="codigo"
                                   maxlength="6" placeholder="Máx. 6 caracteres">
                            <span class="field-error" id="err-codigo">El código es obligatorio (máx. 6 caracteres).</span>
                            <span class="field-error" id="err-codigo-duplicado">Este código ya existe. Elige otro.</span>
                        </div>

                        <div class="form-group">
                            <label for="modelo">Modelo:</label>
                            <input type="text" id="modelo" name="modelo" placeholder="Modelo del sensor">
                        </div>

                        <div class="form-group">
                            <label for="rango">Rango:</label>
                            <input type="text" id="rango" name="rango" placeholder="p.ej. 0-100 °C">
                        </div>

                    </div>

                    <div class="btn-group">
                        <input type="reset" value="Borrar" onclick="clearErrors()">
                        <input type="submit" value="Enviar">
                    </div>
                </form>
            </div>
        </div>

        <a class="back-link" href="index-1.html">← Volver al índice</a>
    </div>

    <script>
        // Llista de codis existents (procedent de la BD)
        const codigosExistentes = <?= json_encode($codigosExistentes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

        // Comprova si el codi ja existeix
        function codigoDuplicado(codigo) {
            const normalizado = codigo.trim().toLowerCase();
            return codigosExistentes.some(c => c.toLowerCase() === normalizado);
        }

        // Validació abans d'enviar el formulari
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

        // Esborra els estils d'error en escriure
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
