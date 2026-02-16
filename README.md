# Sistema Web + Multimedia

Plataforma educativa: gestión de cursos, contenidos multimedia y evaluaciones (Actividad 3 – Arquitectura, carta de navegación e interfaz).

## Requisitos

- PHP 8+
- MySQL o MariaDB
- Servidor web (XAMPP, WAMP, Laragon) con PHP y MySQL activos

## Instalación

1. Clona o copia el proyecto en la carpeta del servidor (por ejemplo `htdocs` en XAMPP).
2. Crea la base de datos y tablas ejecutando el script SQL:
   - En phpMyAdmin: importar `database/schema.sql`.
   - O por consola: `mysql -u root -p < database/schema.sql`
3. Ajusta `config.php` con tu usuario y contraseña de MySQL (por defecto: `sistema_user` / `password_seguro`; si usas XAMPP típicamente es `root` sin contraseña).
4. Asegura que la carpeta `uploads/` exista y tenga permisos de escritura para subir archivos.

## Usuarios de prueba (tras ejecutar el schema)

| Rol        | Correo              | Contraseña   |
|-----------|---------------------|--------------|
| Admin     | admin@sistema.edu   | admin123     |
| Docente   | docente@sistema.edu | docente123   |
| Estudiante| estudiante@sistema.edu | estudiante123 |

## Carta de navegación

- **Público:** Inicio → Acerca → Instrucciones → Iniciar sesión
- **Estudiante:** Dashboard → Mis cursos / Catálogo → Detalle curso → Contenidos / Evaluaciones → Presentar evaluación → Resultados
- **Docente:** Dashboard → Cursos (CRUD) → Subir contenido → Crear evaluación → Ver resultados del grupo
- **Admin:** Dashboard → Usuarios (CRUD) → Reportes

## Estructura principal de archivos

- `index.php` – Login (validación JS)
- `inicio.php` – Página de inicio pública
- `instrucciones.php` – Instrucciones de uso
- `dashboard.php` – Dashboard por rol
- `cursos.php` – Listado de cursos (búsqueda JS)
- `curso_detalle.php` – Detalle con pestañas Contenidos / Evaluaciones / Info
- `ver_contenido.php` – Reproductor multimedia (video/audio/imagen)
- `evaluaciones.php`, `evaluacion_presentar.php`, `evaluacion_resultados.php`, `resultados.php`
- `admin/usuarios.php`, `admin/reportes.php`
- `database/schema.sql` – Esquema de la base de datos
