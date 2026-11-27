# Mavs Jewelry · sitio web

Proyecto semestral de Ingeniería de Software (UTEM): sitio web de comercio electrónico para el emprendimiento de joyería artesanal **Mavs Jewelry**, de María Angélica Venegas.

**Equipo:** Felipe Velásquez, Eduardo Velásquez, Ernesto Baltierra, Ignacio Casas y Benjamín Segura.
**Profesora:** Carolina Martínez Valenzuela.

## Contenido del repositorio

| Carpeta | Qué contiene |
| --- | --- |
| `app/` | Aplicación PHP 8 con patrón MVC: `Controllers`, `Models`, `Services` y `Views` |
| `config/` | `bootstrap.php` (sesión segura, CSRF y ayudas de vistas) y la guía de piedras |
| `public/` | Raíz pública del sitio: `index.php` (controlador frontal), `.htaccess`, CSS, JS, imágenes y videos |
| `database/` | `schema.sql` (modelo relacional, Tabla 4.3 del informe), `datos.sql` (datos iniciales) y `crear_admin.php` |
| `cron/` | `liberar_reservas.php`: libera las reservas vencidas cada 5 minutos (RF-23) |
| `prototipo/` | Prototipo navegable en HTML, CSS y JavaScript de la propuesta de diseño aprobada |

## Puesta en marcha local (Docker)

```bash
cp .env.example .env                 # completar las variables
docker compose up -d                 # Apache + PHP 8.2 y MariaDB con el esquema y los datos iniciales
docker compose exec web composer install
docker compose exec web php database/crear_admin.php admin correo@ejemplo.cl
```

Con el `.env` de ejemplo, usar `DB_HOST=db`, `DB_USER=mavs` y `DB_PASS=mavs_local`. El sitio queda en http://localhost:8080 y el panel en http://localhost:8080/admin/login.

Mercado Pago no puede enviar notificaciones (webhook) a `localhost`: el pago completo se prueba en el hosting con las credenciales de prueba.

## Publicación en el hosting (cPanel)

1. Subir el proyecto fuera de `public_html` y apuntar el dominio a la carpeta `public/`.
2. Crear la base de datos y cargar `database/schema.sql` y `database/datos.sql` desde phpMyAdmin.
3. Crear el `.env` con las credenciales reales, ejecutar `composer install --no-dev` y crear la cuenta con `database/crear_admin.php`.
4. Programar el cron cada 5 minutos: `php /home/USUARIO/mavs/cron/liberar_reservas.php`.
5. En Mercado Pago: configurar el webhook en `https://DOMINIO/pago/webhook` y copiar la clave secreta en `MP_WEBHOOK_SECRET`.

## Estado

| Parte | Estado |
| --- | --- |
| Catálogo, ficha, carrito, datos de despacho y páginas de contenido | Hecho |
| Registro del pedido y reserva de piezas por 15 minutos | Hecho |
| Pago con Mercado Pago (preferencia, webhook con firma, pago tardío y reembolso) | Hecho; falta probarlo con credenciales de prueba |
| Liberación de reservas (cron) | Hecho |
| Panel: ingreso, productos (con fotos) y pedidos | Hecho |
| Panel: edición de contenidos (punto de venta, textos, testimonios) | Pendiente; hoy se editan en la base de datos |
| Pruebas con base de datos y pago real | Pendiente (semana 4) |

## Flujo de trabajo

- `main`: versión estable; solo recibe cambios desde `develop` mediante pull request.
- `develop`: integración de las funciones terminadas.
- `feature/<nombre>` y `fix/<nombre>`: se crean desde `develop` y se integran por pull request.
- Commits en español, en imperativo y con prefijo de tipo (`feat:`, `fix:`, `docs:`).
- Las credenciales nunca se versionan: van en `.env`, que está en `.gitignore`.
