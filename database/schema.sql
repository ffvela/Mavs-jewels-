-- Mavs Jewelry · esquema de la base de datos (Tabla 4.3 del informe, modelo relacional)
-- MySQL 8 / MariaDB 10.6 · utf8mb4

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE tecnica (
  id_tecnica  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(60) NOT NULL,
  slug        VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tipo_pieza (
  id_tipo  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre   VARCHAR(60) NOT NULL,
  slug     VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE producto (
  id_producto      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo           VARCHAR(15) NULL UNIQUE,          -- TT-TIP-NNNN, se completa al crear la pieza
  nombre           VARCHAR(120) NOT NULL,
  descripcion      TEXT,
  materiales       VARCHAR(255),
  precio           INT UNSIGNED NOT NULL,
  stock            SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  es_unica         TINYINT(1) NOT NULL DEFAULT 1,
  estado           ENUM('borrador','publicado','oculto','reservado','vendido') NOT NULL DEFAULT 'borrador',
  reservado_hasta  DATETIME NULL,
  creado_en        DATETIME NOT NULL,
  actualizado_en   DATETIME NOT NULL,
  id_tecnica       INT UNSIGNED NOT NULL,
  id_tipo          INT UNSIGNED NOT NULL,
  CONSTRAINT fk_producto_tecnica FOREIGN KEY (id_tecnica) REFERENCES tecnica (id_tecnica),
  CONSTRAINT fk_producto_tipo FOREIGN KEY (id_tipo) REFERENCES tipo_pieza (id_tipo),
  INDEX idx_producto_filtros (estado, id_tecnica, id_tipo),
  INDEX idx_producto_precio (precio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE imagen_producto (
  id_imagen    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_producto  INT UNSIGNED NOT NULL,
  ruta         VARCHAR(255) NOT NULL,
  texto_alt    VARCHAR(150),
  orden        TINYINT NOT NULL DEFAULT 1,
  CONSTRAINT fk_imagen_producto FOREIGN KEY (id_producto) REFERENCES producto (id_producto) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE piedra (
  id_piedra    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre       VARCHAR(60) NOT NULL UNIQUE,
  significado  TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE producto_piedra (
  id_producto  INT UNSIGNED NOT NULL,
  id_piedra    INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_producto, id_piedra),
  CONSTRAINT fk_pp_producto FOREIGN KEY (id_producto) REFERENCES producto (id_producto) ON DELETE CASCADE,
  CONSTRAINT fk_pp_piedra FOREIGN KEY (id_piedra) REFERENCES piedra (id_piedra)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pedido (
  id_pedido          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo             VARCHAR(20) NULL UNIQUE,        -- MJ-AAAA-NNNNNN, se completa al crear el pedido
  fecha_creacion     DATETIME NOT NULL,
  nombre_cliente     VARCHAR(120) NOT NULL,
  rut                VARCHAR(12) NOT NULL,
  correo             VARCHAR(150) NOT NULL,
  telefono           VARCHAR(15) NOT NULL,
  direccion          VARCHAR(200) NOT NULL,
  comuna             VARCHAR(60) NOT NULL,
  region             VARCHAR(60) NOT NULL,
  total              INT UNSIGNED NOT NULL,
  estado             ENUM('pendiente','pagado','rechazado','expirado','en_preparacion','enviado','reembolsado') NOT NULL DEFAULT 'pendiente',
  acepta_politicas   TINYINT(1) NOT NULL,
  acepta_privacidad  TINYINT(1) NOT NULL,
  conoce_ferias      TINYINT(1) NULL,
  fecha_pago         DATETIME NULL,
  INDEX idx_pedido_panel (estado, fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE detalle_pedido (
  id_detalle       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_pedido        INT UNSIGNED NOT NULL,
  id_producto      INT UNSIGNED NOT NULL,
  cantidad         TINYINT UNSIGNED NOT NULL DEFAULT 1,
  precio_unitario  INT UNSIGNED NOT NULL,
  CONSTRAINT fk_detalle_pedido FOREIGN KEY (id_pedido) REFERENCES pedido (id_pedido),
  CONSTRAINT fk_detalle_producto FOREIGN KEY (id_producto) REFERENCES producto (id_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pago (
  id_pago            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_pedido          INT UNSIGNED NOT NULL,
  id_transaccion_mp  VARCHAR(40) NOT NULL UNIQUE,
  estado             VARCHAR(20) NOT NULL,
  monto              INT UNSIGNED NOT NULL,
  fecha              DATETIME NOT NULL,
  CONSTRAINT fk_pago_pedido FOREIGN KEY (id_pedido) REFERENCES pedido (id_pedido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE administradora (
  id_admin           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario            VARCHAR(40) NOT NULL UNIQUE,
  correo             VARCHAR(150) NOT NULL,
  hash_contrasena    VARCHAR(255) NOT NULL,
  intentos_fallidos  TINYINT NOT NULL DEFAULT 0,
  bloqueado_hasta    DATETIME NULL,
  ultimo_acceso      DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE historial_estado (
  id_historial     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_pedido        INT UNSIGNED NOT NULL,
  id_admin         INT UNSIGNED NULL,            -- NULL si lo cambia el sistema
  estado_anterior  VARCHAR(20) NULL,
  estado_nuevo     VARCHAR(20) NOT NULL,
  fecha            DATETIME NOT NULL,
  CONSTRAINT fk_historial_pedido FOREIGN KEY (id_pedido) REFERENCES pedido (id_pedido),
  CONSTRAINT fk_historial_admin FOREIGN KEY (id_admin) REFERENCES administradora (id_admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contenido (
  id_contenido  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  seccion       VARCHAR(40) NOT NULL,
  titulo        VARCHAR(150),
  cuerpo        TEXT,
  imagen        VARCHAR(255) NULL,
  orden         TINYINT NOT NULL DEFAULT 1,
  visible       TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE testimonio (
  id_testimonio        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_mostrado      VARCHAR(80),
  tipo_identificacion  ENUM('nombre','iniciales','anonimo') NOT NULL DEFAULT 'anonimo',
  texto                TEXT NOT NULL,
  visible              TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE punto_venta (
  id_punto      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lugar         VARCHAR(120) NOT NULL,
  direccion     VARCHAR(200),
  fecha_inicio  DATE NOT NULL,
  fecha_fin     DATE NOT NULL,
  horario       VARCHAR(60),
  visible       TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE clic_whatsapp (
  id_clic  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pagina   VARCHAR(120) NOT NULL,
  fecha    DATETIME NOT NULL,
  INDEX idx_clic_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
