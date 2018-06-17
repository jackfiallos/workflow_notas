/** 
 * Administrador
 */
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus) VALUES ('aramoz', 'Alberto Ramoz', 'alberto.ramos@pierre-fabre.com.mx', 'aramoz', 1);

/** 
 * Solicitante - SAC
 */
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('lsanchez', 'Luis Sanchez', 'luis.sanchez@pierre-fabre.com.mx', 'lsanchez', 1, 1);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('lgarcia', 'Lidia Garcia', 'lidia.garcia@pierre-fabre.com.mx', 'lgarcia', 1, 1);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('smorales', 'Sandra Morales', 'sandra.morales@pierre-fabre.com.mx', 'smorales', 1, 1);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('malvarado', 'Martha Alvarado', 'martha.alvarado@pierre-fabre.com.mx', 'malvarado', 1, 1);

/**
 * Solicitante - C&C
 */
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('agarcia', 'Aldo Garcia', 'aldo.garcia@pierre-fabre.com.mx', 'agarcia', 1, 2);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('becariocc', 'Becario C&C', 'creditoycobranza@pierre-fabre.com.mx', 'becariocc', 1, 2);

/**
 * Solicitante - Comercial
 */
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('cetrillad', 'Claire Etrillard', 'claire.etrillard@pierre-fabre.com.mx', 'cetrillad', 1, 3);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('emedina', 'Eduardo Medina', 'eduardo.medina@pierre-fabre.com.mx', 'emedina', 1, 3);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('aarzate', 'Alberto Arzate', 'alberto.arzate@pierre-fabre.com.mx', 'aarzate', 1, 3);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('dcamacho', 'Diego Camacho', 'diego.camacho@pierre-fabre.com.mx', 'dcamacho', 1, 3);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('ofuentes', 'Oswaldo Fuentes', 'oswaldo.fuentes@pierre-fabre.com.mx', 'ofuentes', 1, 3);

/**
 * Aprobadores - Comercial
 */
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('azanotti', 'Arnaud Zanotti', 'arnaud.zanotti@pierre-fabre.com.mx', 'azanotti', 1, 3);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('gbrigada', 'Georgina Brigada', 'georgina.brigada@pierre-fabre.com.mx', 'gbrigada', 1, 3);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('ahernandez', 'Alejandro Hernandez', 'alejandro.hernandez@pierre-fabre.com.mx', 'ahernandez', 1, 3);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('jvega', 'Julio Vega', 'julio.vega@pierre-fabre.com.mx', 'jvega', 1, 3);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('ngomez', 'Nancy Gomez', 'nancy.gomez@pierre-fabre.com.mx', 'ngomez', 1, 3);

/**
 * Logistica
 */
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('jarodriguez', 'Jose Antonio Rodriguez', 'antonio.rodriguez@pierre-fabre.com.mx', 'jarodriguez', 1, 5);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('logarcia', 'Lourdes Garcia', 'maria.lourdes.perez@pierre-fabre.com.mx', 'logarcia', 1, 5);

/**
 * Devoluciones
 */
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('armartinez', 'Ariana Martinez', 'almacen_devoluciones@pierre-fabre.com', 'armartinez', 1, 4);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('mxicotencatl', 'Mónica Xicoténcatl', 'almacen_devoluciones@pierre-fabre.com', 'mxicotencatl', 1, 4);

/**
 * Supervisores - Finanzas
 */
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus) VALUES ('bayala', 'Beatriz Ayala', 'beatriz.ayala@pierre-fabre.com.mx', 'bayala', 1);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus) VALUES ('echaulot', 'Emmanuel Chaulot', 'emmanuel.chaulot@pierre-fabre.com.mx', 'echaulot', 1);

/** 
 * Desconocidos
 */ 
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('ggarcia', 'Grisel García', 'grisel.garcia@pierre-fabre.com.mx', 'ggarcia', 1, 1);
INSERT INTO tblUsuarios (username, nombre, correo, clave, estatus, grupos_id) VALUES ('ludovic', 'Ludovic Leo', 'ludovic.leo@pierre-fabre.com.mx', 'ludovic', 1, 1);