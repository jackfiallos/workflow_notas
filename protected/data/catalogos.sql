/**
 * Empresas
 */
INSERT INTO catEmpresas (nombre) VALUES('Pierre Fabre México');
INSERT INTO catEmpresas (nombre) VALUES('Pierre Fabre Farma de México');

/**
 * Años
 */
INSERT INTO catAnio (anio, impuesto) VALUES (2014,16);
INSERT INTO catAnio (anio, impuesto) VALUES (2015,16);

/**
 * Permisos
 */
INSERT INTO catPermisos (permiso) VALUES ('administrador');
INSERT INTO catPermisos (permiso) VALUES ('supervisor');
INSERT INTO catPermisos (permiso) VALUES ('aprobador');
INSERT INTO catPermisos (permiso) VALUES ('solicitud');
INSERT INTO catPermisos (permiso) VALUES ('logistica');
INSERT INTO catPermisos (permiso) VALUES ('sac');

/**
 * Grupos
 */
INSERT INTO catGrupos (nombre) VALUES ('SAC');
INSERT INTO catGrupos (nombre) VALUES ('C&C');
INSERT INTO catGrupos (nombre) VALUES ('Comercial');
INSERT INTO catGrupos (nombre) VALUES ('Devoluciones');
INSERT INTO catGrupos (nombre) VALUES ('Logística');
INSERT INTO catGrupos (nombre) VALUES ('Finanzas');

/**
 * Caracteristicas
 */
INSERT INTO catCaracteristicas (nombre) VALUES ('Notas de Crédito de Almacén');
INSERT INTO catCaracteristicas (nombre) VALUES ('Descuento Comercial');
INSERT INTO catCaracteristicas (nombre) VALUES ('Cooperación al Cliente');
INSERT INTO catCaracteristicas (nombre) VALUES ('Refacturaciones');
INSERT INTO catCaracteristicas (nombre) VALUES ('Provisiones');

/**
 * Tipos de Caracteristicas
 */
INSERT INTO catCaracteristicasTipo (nombre, codigo, caracteristicas_id) VALUES ('Rechazo de Entrega', 'D1', 1);
INSERT INTO catCaracteristicasTipo (nombre, codigo, caracteristicas_id) VALUES ('Faltante de Origen', 'D2', 1);
INSERT INTO catCaracteristicasTipo (nombre, codigo, caracteristicas_id) VALUES ('Producto Dañado', 'D3', 1);
INSERT INTO catCaracteristicasTipo (nombre, codigo, caracteristicas_id) VALUES ('Siniestro', 'D4', 1);
INSERT INTO catCaracteristicasTipo (nombre, codigo, caracteristicas_id) VALUES ('Devoluciones', 'D5', 1);
INSERT INTO catCaracteristicasTipo (nombre, codigo, caracteristicas_id) VALUES ('Campañas', 'DC1', 2);
INSERT INTO catCaracteristicasTipo (nombre, codigo, caracteristicas_id) VALUES ('Descuento Comercial', 'DC2', 2);
INSERT INTO catCaracteristicasTipo (nombre, codigo, caracteristicas_id) VALUES ('Cooperación al Cliente', 'CC1', 3);
INSERT INTO catCaracteristicasTipo (nombre, codigo, caracteristicas_id) VALUES ('Refacturaciones', 'RF1', 4);
INSERT INTO catCaracteristicasTipo (nombre, codigo, caracteristicas_id) VALUES ('Provisiones', 'PR1', 5);

/**
 * Razones
 */
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Duplicidad en el pedido', 'D11', 'D002', 1);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Producto no solicitado', 'D12', 'D002', 1);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Fuera de Tiempo', 'D13', 'D002', 1);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Corta Caducidad', 'D14', 'D003', 1);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Rechazo por Daño', 'D15', 'D001', 1);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Por llegar tarde a la cita para la entrega', 'D16', 'D002', 1);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Falta de Documentación', 'D17', 'D002', 1);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Por error en la orden de compra del cliente', 'D18', 'D002', 1);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Por cancelación del pedido', 'D19', 'D002', 1);

INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Error de Surtido', 'D21', 'D002', 2);

INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Daño por el transporte', 'D31', 'D001', 3);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Daño en el surtido', 'D32', 'D001', 3);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Dañado por el cliente', 'D33', 'D001', 3);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Dañado de Origen', 'D34', 'D001', 3);

INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Por robo al transportista', 'D41', 'B006', 4);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Por accidente del transporte', 'D42', 'B006', 4);

INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Producto dañado (Punto de Venta)', 'D51', 'D001', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Corta Caducidad de Origen', 'D52', 'D003', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Promocionales por vencimiento de campaña', 'D53', 'D004', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Dev. Producto de Lanzamiento con bajo desplazamiento', 'D54', 'D004', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Canje', 'D55', 'D003', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Próximo a caducar (Punto de Venta)', 'D56', 'D003', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Producto no solicitado', 'D57', 'D002', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Duplicidad en el pedido', 'D58', 'D002', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Orden de compra incorrecta', 'D59', 'D002', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Corta Caducidad', 'D510', 'D003', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Producto Caducado', 'D511', 'D003', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Recall', 'D512', 'D001', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Presentación del empaque de producto', 'D513', 'D001', 5);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Por Excedentes', 'D514', 'D002', 5);

INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Solares', 'DC11', 'B004', 6);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Padres', 'DC12', 'B004', 6);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Madres', 'DC13', 'B004', 6);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Navidad', 'DC14', 'B004', 6);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Anti edad', 'DC15', 'B004', 6);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Anti celulitis', 'DC16', 'B004', 6);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Cuidado Total', 'DC17', 'B004', 6);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Anti Acné (Cleanance)', 'DC18', 'B004', 6);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Otros', 'DC19', 'B004', 6);

INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Descuento por Venta', 'DC21', 'B004', 7);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Promociones', 'DC22', 'B004', 7);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Precio', 'DC23', 'B002', 7);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Sanciones', 'DC24', 'B004', 7);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Pronto Pago', 'DC25', 'B004', 7);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Fill Rate', 'DC26', 'B004', 7);

INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Muebles', 'CC11', 'B001', 8);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Talones de Embarque', 'CC12', 'B001', 8);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Servicio de Distribución', 'CC13', 'B001', 8);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Consejeras', 'CC14', 'B001', 8);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Volantes', 'CC15', 'B001', 8);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Publicidad', 'CC16', 'B001', 8);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Uso de Portal', 'CC17', 'B001', 8);

INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Por fecha actual', 'RF11', 'F008', 9);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Error en el Domicilio Fiscal', 'RF12', 'F008', 9);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Error de Captura', 'RF13', 'F008', 9);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Domicilio de Entrega Erroneo', 'RF14', 'F008', 9);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Por precio Incorrecto', 'RF15', 'F008', 9);

INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Provisión Devoluciones', 'PR11', 'F010', 10);
INSERT INTO catRazones (nombre, codigo, cuenta, caracteristicasTipo_id) VALUES ('Provisión Descuentos', 'PR12', 'F010', 10);

/**
 * Usuario administrador
 * y sus permisos
 */

INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus) VALUES ('admin', 'administrador', 'admin@test.com', 'jack', 1);
INSERT INTO tblUsuarios_has_catPermisos (usuarios_id, permisos_id) VALUES (1, 1);
