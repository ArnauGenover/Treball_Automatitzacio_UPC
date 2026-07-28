# ⚡ IoT Power Grid Monitor — Substation Sensor Management

> A full-stack web application for the real-time registration, listing, editing, and deletion of IoT sensors deployed across electrical substations. Built with native PHP, MariaDB, and a custom dark-theme UI — no frameworks, no dependencies.

---

## Table of Contents

1. [Key Features](#key-features)
2. [Tech Stack & Architecture](#tech-stack--architecture)
3. [Database Integration](#database-integration)
4. [File Architecture & Role](#file-architecture--role)
5. [Security & Data Validation](#security--data-validation)
6. [Quality Assurance / Test Cases](#quality-assurance--test-cases)
7. [Deployment Guide](#deployment-guide)

---

## Key Features

- **Dark industrial UI** — Custom CSS design system with CSS custom properties, Inter + JetBrains Mono fonts (via Google Fonts), radial gradient backgrounds, and smooth hover transitions across all interactive elements.
- **Persistent sticky navbar** — Shared navigation bar across all pages with active-page highlighting, brand icon, and responsive collapse on mobile.
- **Live dashboard** — `index-1.php` queries the database on every load and displays real-time sensor and magnitude counts, plus a live DB connection health indicator.
- **Full CRUD** — Create, Read, Update, and Delete sensors through a consistent, user-friendly interface.
- **Edit with pre-filled form** — `editar-sensor.php` loads all existing sensor data from the database into the form fields, enabling precise in-place modification.
- **Safe delete with FK guard** — `eliminar-sensor.php` catches MariaDB error `1451` (foreign key violation) and renders a human-readable explanation instead of a raw DB error, preventing data integrity issues.
- **Client-side validation** — Required fields (`codigo`, `idMagnitud`) are validated in the browser before submission, with red border highlights and inline error messages — no page reload needed.
- **Live search / filter** — The sensor table in `lista.php` can be filtered in real time by any column text using a JavaScript search bar. A "no results" row appears dynamically when the query matches nothing.
- **Magnitude statistics** — `lista.php` shows a summary of how many sensors are registered per magnitude using a `GROUP BY` SQL query and styled chip components.
- **Auto-redirect on success** — After a successful insert or update, a 3-second countdown redirects the user to the full sensor list automatically.
- **Duplicate key interception** — MariaDB error `1062` (unique constraint on `codigo`) is caught explicitly and presented as a clear, styled error message.
- **XSS protection** — All user-supplied data rendered in HTML is passed through `htmlspecialchars()`.
- **SQL Injection prevention** — All write operations use PHP MySQLi prepared statements with `bind_param`.

---

## Tech Stack & Architecture

| Layer | Technology | Details |
|---|---|---|
| Markup | **HTML5** | Semantic structure, `<nav>`, `<table>`, `<form>` |
| Styling | **CSS3** | Custom properties (`--var`), Grid, Flexbox, transitions |
| Fonts | **Google Fonts** | Inter (UI), JetBrains Mono (codes & ranges) |
| Backend | **PHP 8.x** | OOP MySQLi, prepared statements, `htmlspecialchars` |
| Database | **MariaDB 10.6+** | InnoDB engine, foreign keys, AUTO_INCREMENT |
| Server | **PHP built-in / Apache** | `php -S` for development, Apache for production |
| Form method | **GET** | Parameters visible in URL — facilitates academic debugging |

> **Architecture pattern:** The application follows a classic multi-page PHP architecture. Each user action maps to a dedicated script. There is no front-end framework or back-end routing layer — all logic is co-located with the view it serves, keeping the codebase readable and auditable.

---

## Database Integration

### Entity-Relationship Summary

```
Magnitud ──< Sensor ──< ListadoSensoresTrafo ──< Lectura
```

The project manages two primary entities, `Magnitud` and `Sensor`, with two additional supporting tables (`ListadoSensoresTrafo`, `Transformador`, `Subestacion`) that form a wider IoT data pipeline.

### Table: `Magnitud`

| Column | Type | Notes |
|---|---|---|
| `id` | `INT` AUTO_INCREMENT PK | Surrogate primary key |
| `nombre` | `VARCHAR(100)` NOT NULL | Descriptive name (e.g. *Temperatura*, *Presión*) |

Seeded values: **Presión** (id=1), **Temperatura** (id=2).

### Table: `Sensor`

| Column | Type | Notes |
|---|---|---|
| `id` | `INT` AUTO_INCREMENT PK | Surrogate primary key |
| `idMagnitud` | `INT` NOT NULL FK | References `Magnitud(id)` |
| `codigo` | `VARCHAR(6)` NOT NULL UNIQUE | Short sensor identifier — uniqueness enforced at DB level |
| `modelo` | `VARCHAR(100)` NOT NULL | Manufacturer model name |
| `rango` | `VARCHAR(100)` NOT NULL | Operating range (e.g. `-50 a 100º C`) |

### Foreign Key Constraints

```sql
-- Sensor → Magnitud
ALTER TABLE Sensor
  ADD CONSTRAINT FK_Sensor_01
  FOREIGN KEY (idMagnitud) REFERENCES Magnitud(id)
  ON DELETE NO ACTION ON UPDATE CASCADE;

-- ListadoSensoresTrafo → Sensor
ALTER TABLE ListadoSensoresTrafo
  ADD CONSTRAINT FK_ListadoSensoresTrafo_02
  FOREIGN KEY (idSensor) REFERENCES Sensor(id)
  ON DELETE NO ACTION ON UPDATE CASCADE;
```

- `ON DELETE NO ACTION` on `Sensor` prevents accidental deletion of sensors that are already linked to transformer readings — the application intercepts this with a user-friendly error message.
- `ON UPDATE CASCADE` ensures that if a magnitude's primary key ever changes, all linked sensors update automatically.

### JOIN Query (used in `lista.php`)

```sql
SELECT s.id, s.codigo, s.modelo, m.nombre AS magnitud, s.rango
FROM Sensor s
INNER JOIN Magnitud m ON s.idMagnitud = m.id
ORDER BY s.id ASC;
```

### Magnitude Statistics Query (used in `lista.php`)

```sql
SELECT m.nombre, COUNT(s.id) AS total
FROM Magnitud m
LEFT JOIN Sensor s ON s.idMagnitud = m.id
GROUP BY m.id, m.nombre
ORDER BY m.nombre ASC;
```

The `LEFT JOIN` ensures that magnitudes with zero associated sensors are still listed in the statistics row.

---

## File Architecture & Role

```
Treball_curs_AUTO/
│
├── index-1.php            # Dashboard — live DB stats, navigation hub
├── lista.php              # READ — sensor table with search, edit & delete
├── form-sensor.php        # CREATE form — loads magnitudes from DB, client-side validation
├── nuevo-sensor.php       # CREATE handler — validates GET params, INSERT, redirect
├── editar-sensor.php      # UPDATE form — loads existing sensor data, pre-fills fields
├── guardar-edicion.php    # UPDATE handler — validates GET params, UPDATE, redirect
├── eliminar-sensor.php    # DELETE handler — deletes sensor, catches FK violation
│
├── estilo.css             # Global design system (dark theme, CSS variables, components)
├── IoTMonitorizacion.sql  # Full DB dump — schema + seed data
└── README.md              # This file
```

### File Responsibilities

| File | HTTP Method | DB Operation | Redirects to |
|---|---|---|---|
| `index-1.php` | — | `SELECT COUNT` × 2 | — |
| `lista.php` | — | `SELECT` + `JOIN` + `GROUP BY` | — |
| `form-sensor.php` | — | `SELECT` (magnitudes) | `nuevo-sensor.php` |
| `nuevo-sensor.php` | GET | `INSERT` (prepared) | `lista.php` (auto, 3 s) |
| `editar-sensor.php` | GET | `SELECT` (1 sensor) + `SELECT` (magnitudes) | `guardar-edicion.php` |
| `guardar-edicion.php` | GET | `UPDATE` (prepared) | `lista.php` (auto, 3 s) |
| `eliminar-sensor.php` | GET | `DELETE` (prepared) | `lista.php` (immediate) |

---

## Security & Data Validation

### 1 — SQL Injection Prevention via Prepared Statements

All queries that accept user input use MySQLi's prepared statement API. The user-supplied values are **never interpolated** into the SQL string.

```php
// INSERT example (nuevo-sensor.php)
$stmt = $cn->prepare(
    "INSERT INTO Sensor (idMagnitud, codigo, modelo, rango) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param('isss', $idMagnitud, $codigo, $modelo, $rango);
$stmt->execute();

// UPDATE example (guardar-edicion.php)
$stmt = $cn->prepare(
    "UPDATE Sensor SET idMagnitud = ?, codigo = ?, modelo = ?, rango = ? WHERE id = ?"
);
$stmt->bind_param('isssi', $idMagnitud, $codigo, $modelo, $rango, $id);
$stmt->execute();

// DELETE example (eliminar-sensor.php)
$stmt = $cn->prepare("DELETE FROM Sensor WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
```

The `bind_param` type string (`i` = integer, `s` = string) enforces type safety before the query reaches the database engine.

### 2 — XSS Prevention via Output Sanitization

Every piece of user-originated data rendered inside HTML is wrapped in `htmlspecialchars()`, converting dangerous characters (`<`, `>`, `"`, `'`, `&`) into their safe HTML entity equivalents.

```php
echo '<td>' . htmlspecialchars($fila['codigo']) . '</td>';
```

This prevents a stored or reflected Cross-Site Scripting attack even if malicious content has been inserted into the database.

### 3 — Server-Side Input Validation

Before any database operation, required fields are validated in PHP:

```php
if (empty($idMagnitud) || empty($codigo)) {
    $error = 'Faltan datos obligatorios para añadir el sensor.';
}
```

If validation fails, the database connection is never opened and no query is executed.

### 4 — Client-Side Validation (UX layer)

A JavaScript event listener on the form's `submit` event inspects required fields before the request is dispatched. Fields that fail validation receive the `.input-error` CSS class (red border + glow) and an inline `.field-error` message is revealed. This is a UX enhancement only — server-side validation remains the authoritative layer.

### 5 — Duplicate Key Interception (MariaDB Error 1062)

The `codigo` column has a `UNIQUE` index at the database level. When a duplicate value is submitted, MariaDB returns `errno 1062`. The application catches this explicitly:

```php
if ($cn->errno === 1062) {
    // Render a human-readable duplicate key error
} else {
    // Render a generic DB error
}
```

### 6 — Foreign Key Violation Interception (MariaDB Error 1451)

When a user attempts to delete a sensor that is referenced by `ListadoSensoresTrafo`, MariaDB returns `errno 1451`. `eliminar-sensor.php` catches this and renders a clear explanation rather than a raw database error:

```php
if ($errno === 1451) {
    // "Cannot delete: sensor has linked readings"
}
```

---

## Quality Assurance / Test Cases

The following three test cases are formally required by the course supervisor and can be reproduced exactly as described.

---

### Test Case 1 — Empty Form Submission

**Objective:** Verify that the system rejects a form submitted without the mandatory fields.

**Steps:**
1. Navigate to `http://localhost:8080/form-sensor.php`
2. Leave all fields empty and click **Guardar sensor**

**Expected Result (client-side):**
- The *Magnitud* dropdown and *Código* input are highlighted with a red border
- Inline error messages appear below each invalid field
- The form is **not** submitted — no network request is made

**Expected Result (server-side, if JS is disabled):**
- `nuevo-sensor.php` receives empty `idMagnitud` and `codigo`
- The PHP validation block fires: `$error = 'Faltan datos obligatorios...'`
- A styled red alert box is rendered; the database is never contacted

---

### Test Case 2 — Successful Sensor Insertion

**Objective:** Verify that a valid sensor is correctly persisted to the database.

**Steps:**
1. Navigate to `http://localhost:8080/form-sensor.php`
2. Select a magnitude from the dropdown (e.g. *Temperatura*)
3. Enter a unique 6-character code (e.g. `TMP099`)
4. Enter a model (e.g. `Sensor T09`) and range (e.g. `0 a 150 °C`)
5. Click **Guardar sensor**

**Expected Result:**
- `nuevo-sensor.php` renders a green success alert: *"Sensor TMP099 añadido correctamente"*
- A summary table displays the inserted values
- A 3-second countdown appears, then the browser automatically redirects to `lista.php`
- The new sensor row is visible at the bottom of the sensor table

**Verification query:**
```sql
SELECT * FROM Sensor WHERE codigo = 'TMP099';
```

---

### Test Case 3 — Duplicate Key Exception

**Objective:** Verify that attempting to insert a sensor with an already-existing `codigo` is rejected gracefully.

**Steps:**
1. Note an existing `codigo` in `lista.php` (e.g. `PSN001`)
2. Navigate to `http://localhost:8080/form-sensor.php`
3. Select any magnitude, enter `PSN001` as the code, and submit

**Expected Result:**
- `nuevo-sensor.php` renders a red error alert
- The message explicitly states: *"Ya existe un sensor con el código PSN001. El código debe ser único."*
- No duplicate row is created in the `Sensor` table
- MariaDB errno `1062` has been caught and translated into a user-facing message

---

## Deployment Guide

### Prerequisites

| Requirement | Minimum Version |
|---|---|
| PHP | 8.0+ (CLI or Apache module) |
| MariaDB | 10.6+ |
| Apache *(optional)* | 2.4+ |
| OS | Linux (Debian / Ubuntu) |

---

### Step 1 — Install the stack

```bash
sudo apt update && sudo apt install -y \
    php \
    php-mysqli \
    mariadb-server \
    apache2          # optional, only needed for production
```

---

### Step 2 — Start MariaDB and secure it

```bash
sudo systemctl start mariadb
sudo systemctl enable mariadb        # auto-start on boot
sudo mysql_secure_installation       # optional but recommended
```

---

### Step 3 — Create the database and user

```bash
sudo mysql -u root -p
```

Inside the MariaDB shell:

```sql
CREATE DATABASE IF NOT EXISTS IoTMonitorizacion
    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE USER 'phpmyadmin'@'localhost' IDENTIFIED BY '12345';
GRANT ALL PRIVILEGES ON IoTMonitorizacion.* TO 'phpmyadmin'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

### Step 4 — Import the database dump

```bash
mysql -u phpmyadmin -p IoTMonitorizacion < IoTMonitorizacion.sql
```

Verify the import:

```bash
mysql -u phpmyadmin -p -e "USE IoTMonitorizacion; SHOW TABLES; SELECT * FROM Sensor;"
```

---

### Step 5 — Run the development server

```bash
cd /path/to/Treball_curs_AUTO
php -S localhost:8080
```

Open your browser at:

```
http://localhost:8080/index-1.php
```

Stop the server at any time with `Ctrl+C`.

---

### Step 6 — Production deployment with Apache *(optional)*

```bash
sudo cp -r /path/to/Treball_curs_AUTO/* /var/www/html/
sudo systemctl restart apache2
```

Then navigate to `http://localhost/index-1.php`.

---

### Connection parameters reference

All PHP files share the same hardcoded connection string. To change credentials, update the `mysqli` constructor in each file:

```php
$cn = new mysqli('localhost', 'phpmyadmin', '12345', 'IoTMonitorizacion');
```

| Parameter | Value |
|---|---|
| Host | `localhost` |
| User | `phpmyadmin` |
| Password | `12345` |
| Database | `IoTMonitorizacion` |

---

*Project developed for the Automatització course — Escola Tècnica Superior d'Enginyeria Industrial de Barcelona (ETSEIB), Universitat Politècnica de Catalunya (UPC).*
