<?php
/**
 * form-sensor.php
 * Formulari per afegir un nou sensor. Carrega les magnituds des de la BD
 * per poblar el desplegable <select>.
 */

// ── Connexió a la base de dades (mysqli OO) ──────────────────────────────────
$cn = new mysqli('localhost', 'phpmyadmin', '12345', 'IoTMonitorizacion');

if ($cn->connect_errno) {
    die('Error de connexió: ' . $cn->connect_error);
}

// ── Obté la llista de magnituds per al <select> ──────────────────────────────
$sqlMagnituds = "SELECT id, nombre FROM Magnitud ORDER BY nombre ASC";
$magnituds    = $cn->query($sqlMagnituds);

if (!$magnituds) {
    die('Error en la consulta: ' . $cn->error);
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

        <p>Introduzca los datos del nuevo sensor</p>

        <!-- El formulari envia les dades per GET cap a nuevo-sensor.php -->
        <form method="GET" action="nuevo-sensor.php">

            <!-- Desplegable de magnituds carregat des de la BD -->
            <div class="form-group">
                <label for="idMagnitud">Magnitud:</label>
                <select id="idMagnitud" name="idMagnitud" required>
                    <option value="">-- Seleccione una magnitud --</option>
                    <?php
                    while ($mag = $magnituds->fetch_assoc()) {
                        echo '<option value="' . (int)$mag['id'] . '">'
                             . htmlspecialchars($mag['nombre'])
                             . '</option>';
                    }
                    // Allibera el resultat de magnituds
                    $magnituds->free();
                    $cn->close();
                    ?>
                </select>
            </div>

            <!-- Camp Código (màxim 6 caràcters, obligatori) -->
            <div class="form-group">
                <label for="codigo">Código:</label>
                <input type="text" id="codigo" name="codigo"
                       maxlength="6" required placeholder="Máx. 6 caracteres">
            </div>

            <!-- Camp Modelo -->
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" id="modelo" name="modelo"
                       placeholder="Modelo del sensor">
            </div>

            <!-- Camp Rango -->
            <div class="form-group">
                <label for="rango">Rango:</label>
                <input type="text" id="rango" name="rango"
                       placeholder="p.ej. 0-100 °C">
            </div>

            <!-- Botons -->
            <div class="btn-group">
                <input type="reset"  value="Borrar">
                <input type="submit" value="Enviar">
            </div>

        </form>

        <br>
        <a class="back-link" href="index-1.html">Volver al índice</a>
    </div>
</body>
</html>
