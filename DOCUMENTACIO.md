# Documentación del proyecto — IoT Monitor (Subestaciones Eléctricas)

> Documento personal de referencia. Explica qué hace la aplicación, con qué tecnologías está hecha, cómo se organizan los archivos y cómo funciona el flujo completo.

---

## 1. ¿Qué es este proyecto?

Es una **aplicación web** para gestionar **sensores IoT** instalados en **subestaciones eléctricas**. Permite:

- Ver un **panel de control** con estadísticas en tiempo real
- **Listar** todos los sensores con su magnitud asociada (temperatura, presión, etc.)
- **Añadir** sensores nuevos
- **Editar** sensores existentes
- **Eliminar** sensores (con protección si tienen datos vinculados)
- **Buscar/filtrar** sensores en la tabla sin recargar la página

La base de datos completa (`IoTMonitorizacion.sql`) incluye también tablas de subestaciones, transformadores y lecturas, pero **la web gestiona principalmente la entidad `Sensor`** y su relación con `Magnitud`.

---

## 2. Stack tecnológico

| Capa | Tecnología | Uso en el proyecto |
|------|------------|-------------------|
| **Frontend** | HTML5 | Estructura de páginas, formularios, tablas, navbar |
| **Estilos** | CSS3 | Tema oscuro industrial, variables CSS, Grid, Flexbox |
| **Tipografías** | Google Fonts | *Inter* (texto general), *JetBrains Mono* (códigos y rangos) |
| **Interactividad** | JavaScript (vanilla) | Validación de formularios, búsqueda en tabla, cuenta atrás de redirección |
| **Backend** | PHP 8.x (nativo) | Lógica de negocio, consultas SQL, generación HTML |
| **Acceso a BD** | MySQLi (orientado a objetos) | Conexión, prepared statements, `bind_param` |
| **Base de datos** | MariaDB 10.6+ | Motor InnoDB, claves foráneas, restricciones UNIQUE |
| **Servidor local** | `php -S localhost:8080` | Servidor integrado de PHP para desarrollo |
| **Producción** | InfinityFree + FTP | Hosting compartido con MariaDB remota |
| **Control de versiones** | Git + GitHub | Rama `millora-ui` |

### Lo que **no** se usa (decisión consciente)

- Sin frameworks PHP (Laravel, Symfony…)
- Sin frameworks JS (React, Vue…)
- Sin npm / Composer
- Sin API REST separada

**Patrón arquitectónico:** aplicación **multi-página clásica**. Cada acción del usuario corresponde a un archivo PHP distinto. La lógica y la vista van juntas en el mismo fichero, lo que facilita entender el flujo en un contexto académico.

---

## 3. Estructura del repositorio

```
Treball_curs_AUTO/
│
├── index.php                 → Panel de control (dashboard)
├── lista.php                 → Listado de sensores (READ)
├── form-sensor.php           → Formulario para añadir (CREATE)
├── nuevo-sensor.php          → Procesa el alta (CREATE handler)
├── editar-sensor.php         → Formulario de edición (UPDATE)
├── guardar-edicion.php       → Procesa la edición (UPDATE handler)
├── eliminar-sensor.php       → Elimina un sensor (DELETE handler)
│
├── config.php                → Configuración central de BD (en GitHub: placeholders)
├── config.local.php          → Credenciales reales (NO subir a GitHub)
├── config.local.php.example  → Plantilla para crear config.local.php
│
├── estilo.css                → Hoja de estilos global (design system)
├── IoTMonitorizacion.sql     → Volcado SQL: esquema + datos de ejemplo
├── README.md                 → Documentación técnica en inglés (GitHub)
├── DOCUMENTACIO.md           → Este documento (explicación personal)
├── memoria.tex               → Memoria del trabajo (LaTeX)
└── .gitignore                → Excluye config.local.php del repositorio
```

---

## 4. Flujo de la aplicación

### Navegación general

```
index.php  ──→  lista.php  ──→  editar-sensor.php  ──→  guardar-edicion.php
    │              │
    │              └──→  eliminar-sensor.php
    │
    └──→  form-sensor.php  ──→  nuevo-sensor.php
```

Todas las páginas comparten la **misma navbar** (Inicio / Sensores / Añadir) y el archivo **`estilo.css`**.

### Operaciones CRUD

| Operación | Página de entrada | Página que procesa | Método HTTP |
|-----------|-------------------|--------------------|-------------|
| **Create** | `form-sensor.php` | `nuevo-sensor.php` | GET |
| **Read** | `lista.php` | — | — |
| **Update** | `editar-sensor.php` | `guardar-edicion.php` | GET |
| **Delete** | `lista.php` (botón) | `eliminar-sensor.php` | GET |

> Se usa **GET** en los formularios porque el profesor lo pidió: los parámetros quedan visibles en la URL y facilitan depurar las consultas durante el desarrollo.

### Ejemplo: añadir un sensor

1. El usuario abre `form-sensor.php`
2. PHP carga las magnitudes desde la BD para rellenar el `<select>`
3. JavaScript valida campos obligatorios y **código duplicado** antes de enviar
4. El formulario envía a `nuevo-sensor.php?codigo=...&modelo=...`
5. `nuevo-sensor.php` valida de nuevo en servidor y comprueba duplicados con `SELECT`
6. Si todo es correcto, hace `INSERT` con prepared statement
7. Muestra mensaje de éxito y redirige a `lista.php` tras 3 segundos

---

## 5. Base de datos

### Modelo relacional (simplificado)

```
Magnitud ──< Sensor ──< ListadoSensoresTrafo ──< Lectura
                │
Subestacion ──< Transformador ──< ListadoSensoresTrafo
```

### Tablas que usa la web directamente

**`Magnitud`**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT PK | Identificador |
| `nombre` | VARCHAR(100) | Ej: Presión, Temperatura |

**`Sensor`**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT PK | Identificador autoincremental |
| `idMagnitud` | INT FK | Referencia a `Magnitud.id` |
| `codigo` | VARCHAR(6) UNIQUE | Código corto del sensor (único) |
| `modelo` | VARCHAR(100) | Modelo del fabricante |
| `rango` | VARCHAR(100) | Rango de medida (ej: 0–100 °C) |

### Consulta principal (listado)

```sql
SELECT s.id, s.codigo, s.modelo, m.nombre AS magnitud, s.rango
FROM Sensor s
INNER JOIN Magnitud m ON s.idMagnitud = m.id
ORDER BY s.id ASC;
```

El `INNER JOIN` une cada sensor con el **nombre** de su magnitud para mostrarlo en la tabla sin tener que hacer dos consultas.

---

## 6. Configuración y despliegue

### Archivos de configuración

| Archivo | ¿En GitHub? | Contenido |
|---------|-------------|-----------|
| `config.php` | Sí | Detecta entorno, carga `config.local.php` si existe |
| `config.local.php` | **No** (gitignored) | Credenciales reales de BD |
| `config.local.php.example` | Sí | Plantilla para copiar |

**Detección de entorno:** si `$_SERVER['SERVER_NAME']` es `localhost` o `127.0.0.1` → entorno local. Cualquier otro hostname → producción (InfinityFree).

### Ejecutar en local

```bash
sudo systemctl start mariadb
cd /ruta/al/proyecto
php -S localhost:8080
```

Abrir: **http://localhost:8080/index.php**

### Desplegar en InfinityFree

1. Importar `IoTMonitorizacion.sql` en phpMyAdmin (sin las líneas `CREATE DATABASE` / `USE` si dan error)
2. Crear `config.local.php` en el servidor con credenciales de InfinityFree
3. Subir todos los archivos PHP, CSS y SQL por FTP a `/htdocs/`
4. **No subir** `config.local.php` al repositorio de GitHub, solo al servidor por FTP

---

## 7. Seguridad y validación

### Protección contra SQL Injection

Todas las operaciones de escritura usan **prepared statements**:

```php
$stmt = $cn->prepare("INSERT INTO Sensor (idMagnitud, codigo, modelo, rango) VALUES (?, ?, ?, ?)");
$stmt->bind_param('isss', $idMagnitud, $codigo, $modelo, $rango);
$stmt->execute();
```

Los valores del usuario **nunca** se concatenan directamente en la cadena SQL.

### Protección contra XSS

Todo dato que viene del usuario o de la BD y se muestra en HTML pasa por:

```php
htmlspecialchars($valor)
```

### Validación de datos

| Nivel | Qué valida |
|-------|------------|
| **Cliente (JS)** | Campos obligatorios, código duplicado antes de enviar |
| **Servidor (PHP)** | Campos vacíos, código duplicado antes de INSERT |
| **Base de datos** | UNIQUE en `codigo`, FK en `idMagnitud`, NO ACTION al borrar sensores vinculados |

### Errores gestionados

- **Código duplicado** → mensaje claro, sin error SQL visible
- **Sensor con lecturas vinculadas** → error 1451 interceptado al eliminar
- **Conexión BD fallida** → `$DB_CONNECT_ERROR` o panel en rojo en `index.php`

---

## 8. Interfaz de usuario

### Design system (`estilo.css`)

- **Tema oscuro** con fondo `#080d1a` y acentos azul/cian
- **Variables CSS** (`--primary`, `--accent`, `--error`…) para mantener coherencia
- **Componentes reutilizables:** `.card`, `.navbar`, `.btn`, `.alert`, `.badge`, `.stat-card`
- **Responsive:** navbar simplificada en móvil, grid adaptable

### Páginas principales

| Página | Contenido destacado |
|--------|---------------------|
| `index.php` | Contadores en vivo, estado EN LÍNEA / ERROR, accesos rápidos |
| `lista.php` | Tabla con búsqueda, chips de estadísticas por magnitud, botones editar/eliminar |
| `form-sensor.php` | Formulario con validación inline |
| `nuevo-sensor.php` / `guardar-edicion.php` | Informe de resultado + redirección automática |

---

## 9. Casos de prueba (para la evaluación)

| # | Caso | Resultado esperado |
|---|------|-------------------|
| 1 | Enviar formulario vacío | Error de validación, no se contacta la BD |
| 2 | Insertar sensor con datos válidos y código único | Mensaje de éxito + redirección a lista |
| 3 | Insertar sensor con código ya existente (ej: `PSN001`) | Bloqueo en formulario o mensaje amigable en servidor |
| 4 | Eliminar sensor sin lecturas asociadas | Desaparece de la lista con banner de confirmación |
| 5 | Eliminar sensor con lecturas vinculadas | Mensaje explicando que no se puede borrar |

---

## 10. Historial de mejoras (evolución del proyecto)

1. **Versión inicial** — CRUD básico con estilos simples y conexión hardcodeada
2. **Rediseño UI** — Tema oscuro, navbar, cards, tipografías modernas
3. **CRUD completo** — Editar, eliminar, búsqueda, estadísticas por magnitud
4. **Dashboard dinámico** — `index.php` con consultas COUNT en tiempo real
5. **Centralización BD** — `config.php` + detección local/producción
6. **Seguridad en Git** — `config.local.php` gitignored, placeholders en repo
7. **Validación duplicados** — Comprobación en cliente y servidor antes del INSERT

---

## 11. Comandos Git útiles

```bash
# Ver estado
git status

# Commit
git add .
git commit -m "Descripción del cambio"

# Subir a GitHub
git push origin millora-ui
```

---

## 12. Resumen en una frase

> Aplicación web PHP + MariaDB sin frameworks, con CRUD completo de sensores IoT, interfaz oscura profesional, prepared statements, validación en cliente y servidor, y configuración separada para desarrollo local y despliegue en InfinityFree.

---

*Proyecto del curso Automatització — ETSEIB, UPC.*
