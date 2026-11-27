# Mavs Jewelry · sitio web

Proyecto semestral de Ingeniería de Software (UTEM): sitio web de comercio electrónico para el emprendimiento de joyería artesanal **Mavs Jewelry**, de María Angélica Venegas.

**Equipo:** Felipe Velásquez, Eduardo Velásquez, Ernesto Baltierra, Ignacio Casas y Benjamín Segura.
**Profesora:** Carolina Martínez Valenzuela.

## Estado actual

Este repositorio contiene el **prototipo navegable de la propuesta de diseño aprobada** por la clienta (diagramación del "diseño propio" con los colores pastel de la marca). Está hecho en HTML, CSS y JavaScript, con datos de ejemplo, y sirve de base para las vistas de la versión en PHP.

| Página | Archivo |
| --- | --- |
| Inicio | `index.html` |
| Catálogo | `catalogo.html` |
| Ficha de producto | `producto.html?id=1` |
| Personalizadas | `especialidad.html` |
| Nosotros | `nosotros.html` |
| Contacto y políticas | `contacto.html` |
| Carrito | `carrito.html` |
| Datos de despacho | `despacho.html` |
| Panel: ingreso | `panel-ingreso.html` |
| Panel: productos, pedidos y contenidos | `panel.html` |

Para verlo, abre `index.html` en el navegador. No necesita servidor.

El pago con Mercado Pago, la reserva de piezas por 15 minutos, la base de datos MySQL y el panel con sesión se implementan en la versión PHP 8 (MVC), según los capítulos 4 y 5 del informe.

## Estructura

```
css/        estilos del sitio (mavs.css) y del panel (panel.css)
js/         productos.js (catálogo de ejemplo), mavs.js (cabecera, pie, carrito, catálogo),
            paginas.js (ficha, carrito, despacho), panel.js (panel de administración)
img/        fotografías en WebP (640 y 1200 px) y logotipos
video/      testimonios de clientas
```

## Flujo de trabajo

- `main`: versión estable; solo recibe cambios desde `develop` mediante pull request.
- `develop`: integración de las funciones terminadas.
- `feature/<nombre>` y `fix/<nombre>`: se crean desde `develop` y se integran por pull request.
- Commits en español, en imperativo y con prefijo de tipo (`feat:`, `fix:`, `docs:`).
- Las credenciales nunca se versionan: van en `.env`, que está en `.gitignore`.
