/*SELECT *, MAX(flujos.nivel_aprobacion)*/
SELECT *
FROM `tblNotas` `t` 
LEFT OUTER JOIN `catRazones` `razones` ON (`t`.`razones_id`=`razones`.`id`) 
LEFT OUTER JOIN `catCaracteristicasTipo` `caracteristicas_tipo` ON (`razones`.`caracteristicasTipo_id`=`caracteristicas_tipo`.`id`) 
LEFT OUTER JOIN `catFlujos` `flujos` ON (`flujos`.`caracteristicasTipo_id`=`caracteristicas_tipo`.`id`) 
WHERE t.estatus = 1 
AND t.revision = 2 
GROUP BY t.id
HAVING MAX(flujos.nivel_aprobacion) <= (SELECT COUNT(*) FROM tblNotas_has_catFlujos WHERE notas_id = t.id);

/**
Usuarios por nivel de aprobacion y caracteristica
*/
SELECT t.username, f.nivel_aprobacion, c.id
FROM tblUsuarios t 
LEFT OUTER JOIN tblUsuarios_has_catPermisos uc ON t.id = uc.usuarios_id
LEFT OUTER JOIN catPermisos p ON uc.permisos_id = t.id
LEFT OUTER JOIN tblUsuarios_has_catFlujos uf ON t.id = uf.usuarios_id
LEFT OUTER JOIN catFlujos f ON uf.flujos_id = f.id 
LEFT OUTER JOIN catCaracteristicasTipo c ON f.caracteristicastipo_id = c.id
WHERE c.id IS NOT NULL;

/**
Notas por nivel de aprobacion y caracteristica
*/
SELECT t.id, f.nivel_aprobacion, c.id
FROM tblNotas t
LEFT OUTER JOIN tblNotas_has_catFlujos nc On t.id = nc.notas_id
LEFT OUTER JOIN catFlujos f ON nc.flujos_id = f.id
LEFT OUTER JOIN catRazones r ON t.razones_id = r.id
LEFT OUTER JOIN catCaracteristicasTipo c ON r.caracteristicasTipo_id = c.id
ORDER BY f.nivel_aprobacion ASC;

SELECT MAX(nivel_aprobacion) as nivel_aprobacion
FROM catFlujos t
WHERE t.caracteristicasTipo_id = 9;

SELECT SUM(t.precio)
FROM tblProductosPrecio t 
WHERE t.productos_id IN (413, 414, 415, 394, 422) 
AND t.anio_id = 1;

SELECT p.* 
FROM tblUsuarios t 
LEFT OUTER JOIN tblUsuarios_has_catPermisos uc ON uc.usuarios_id = t.id
LEFT OUTER JOIN catPermisos p ON uc.permisos_id = t.id 
/*LEFT OUTER JOIN tblUsuarios_has_catFlujos uf ON t.id = uf.usuarios_id
LEFT OUTER JOIN catFlujos f ON uf.flujos_id = f.id 
LEFT OUTER JOIN catCaracteristicasTipo c ON f.caracteristicastipo_id = c.id*/
WHERE p.id = 2;

SELECT * 
FROM tblNotas
WHERE id = 1;

SELECT * 
FROM tblHistorial 
WHERE notas_id = 1;

SELECT * 
FROM tblNotas_has_catFlujos;