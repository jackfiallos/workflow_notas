== INSTALACION ==
- Copiar la carpeta workflow_notas/trunk en el servidor
- Permisos de lectura y ejecucion a todo trunk
- Permisos de escritura en trunk/assets trunk/protected/runtime trunk/protected/files
- Descargar el framework de yii (1.14) y ubicar localmente dentro de workflow_notas/trunk
- Modificar el archivo trunk/index.php apuntando hacia el path del framework
- Modificar el archivo trunk/cron.php apuntando hacia el path del framework
- Modificar archivos de configuracion trunk/protected/config/*
- Recordar desplazar el autoincrement de la tabla notas para que inicie en 7000000
- Limpiar bd (data/clean.sql)
- Ejecutar script de creacion de BD (data/schema.sql) - mysql -h localhost -u root -p workflownotas < protected/data/schema.sql
- Subir catálogos para tablas (data/catalogos.sql) - mysql -h localhost -u root -p workflownotas < catalogos.sql
- Subir datos de flujos (data/flujos.sql) mysql -h localhost -u root -p workflownotas  < flujos.sql
- Importar usuarios (data/usuarios.sql) - mysql -h localhost -u root -p workflownotas  < usuarios.sql
- Importar el archivo de clientes vs usuarios (data/usuarios_has_clientes.sql) - mysql -h localhost -u root -p workflownotas  < usuarios_has_clientes.sql
- Se crear de forma automatica median script un primer usuario (admin - jack)
- Importar clientes (data/clientes_dermo.csv) desde la aplicacion
- Se deben de importar las facturas (x_importar/ordenes20140717.txt)
- Importar marcas (data/marcas_dermo.csv) desde la aplicacion
- Importar productos (http://workflow.local/index.php?r=site/ImportarProductos)
- Importar precios (http://workflow.local/index.php?r=site/ImportarPrecios)
[- Importar productos (data/precios_dermo.csv)] // se deja de utilizar porque existe un cron de importar precios

DEPRECADO - Se debe de importar el archivo de descuentos para clientes (data/DescuentosCliente2014.csv)
- Revisar el archivo php.ini (maximo consumo de memoria y maximo subida de archivos)

== CRONTAB ==
sudo nano /etc/crontab
# (1 vez al dia a las 8am)
0 8 * * * root curl -X GET /index.php?r=site/DarSeguimientoAprobadores &> /dev/null
# (1 vez al dia a las 10am)
0 10 * * * root curl -X GET /index.php?r=site/DarSeguimientoOtros &> /dev/null

== PACKAGES ==
sudo apt-get update
sudo apt-get install build-essential
sudo apt-get install mysql-server php5-mysql php5 php5-memcached memcached

# Hacer un Backup
mysqldump -h localhost -u root -p workflownotas > workflownotas.clean.21-07-2014.sql

# Restaurar un Backup
mysql workflownotas < workflownotas.clean.21-07-2014.sql

== PRUEBAS ==
RE - solo el grupo sac puede ver (d1,d2,d3,d4,rf1)
OK - ocurrio un error al subir archivos (se suben pero marca un error IE)
OK - poner el no. de folio al grid de sac
OK - subir archivos (100 MB - 3600 seg en php.ini)
OK - en los rechazos marca un error de smtp (tools 165)
OK - solicitudes/_form 427 disable
OK - solicitudes /view los documentos no se abren en pestaña nueva 397 el atributo se esta poniendo a la celda y no al link
OK - los grids deberian de mostrar el cargador (porque en paginacion esta tardando un poco)
OK - el grid de clientes, deberia mostrar a que empresa pertenecen
OK - hace falta un nuevo menu en los supervisores, ahora solo para que muestre las notas que se deben de aprobar y un menu con todas las notas (solo vista de dealles)
OK - las fechas de caducidad provocan un error (no se permiten elegir) por devoluciones solo en IE
OK - debe de funcionar minimamente para IE 8.0.76
OK - Se permite la adicion de porcentajes utilizando 2 decimales
OK - Pagina de impresion de notas para los usuarios de sac
OK - Finalizar SAC la nota escribiendo campos nuevos
OK - Datos a considerar en el formulario de devoluciones (edicion)
	- almacen numerico 2 digit
	- lote alfanumerico
OK - en lo reportes graficos no se puede utilizar fx()[] PAYMTN error PHP
OK - Implementación del layout a exportar
OK - Reorganizar usuarios en app de acuerdo a WF
OK - Incluir descuentos por usuarios
OK - Manuales de usuario

CC1
las gamas son solo para codigo de campañas en descuento comercial
d1 - d5 hay que agregar un cuadrito de texto para aplicar descuentos a cada producto

== TODO #1 ==
OK - el usuario de logistica no aprueba cuando la empresa seleccionada es farma (farma no se hace)
OK - Descuentos comerciales para farma, el codigo df2 en lugar de ser marcas seran productos (no se hace)

== TODO #2 ==
OK - 1d - porcentaje de aceptado por producto (D1 - D5) aparte de la cantidad
OK - 4h - productos de PFD y TREV no llevan iva porque son medicamentos (entonces el precio de lista es el mismo precio final.. el iva se omite)
OK - 3d - tema de diseño, deberia verse visualmente similar a web notas de credito
OK - 2h - La dimension contable (cuenta) de cooperacion y descuento comercial son diferentes (son marcas y no productos)
OK - 1h - nancy gomez y ludovic leo (copia para daniela.loera@pierre-fabre.com.mx) modificar el envio de correos porque ahora irán separados por punto y coma .. tomar en cuenta ese cambio
OK - 1h - Siempre debe de haber un archivo adjunto si se ha utilizado una factura
OK - 2h - solamente a scala (exportar a layout con lista de precios original) se subiran los precios sin descuentos (esto es la ultima actualizacion), para efectos visuales se mantiene igual
OK - 2h - Falta hacer la separacion de los tipos de cuentas en la exportacion del archivo (se refiere a descuentos y cooperacion - revisar Codigos de Razón 240714.xlsx)
OK - 2h - el cooperacion comercial solamente se cargan marcas y no gamas (codigosdeerazon110714.xls hoja #2)
OK - 2h - Por concepto de corrección de manual
OK - 3d - pueden existir sub-categorias en las listas de precio (es decir, pueden existir x num. de actualizaciones a los precios en determinado año)

== TODO #3 ==
OK - 3h - los usuarios deben de estar firmados solamente 1 vez (guardar la sesion en BD)
OK - 2h - Para los aprobadores el importe de la nota de crédito debe ser considerada con IVA
OK - furterer se va (ya no existe en el archivo marcas_dermo.csv)
OK - glytone se va (ya no existe en el archivo marcas_dermo.csv)
OK - el codigo de los clientes deberia ser alfanumerico y no numerico
OK - ordenar la lista de clientes en orden ascendente
OK - a los solicitantes no les aparecen los clientes
OK - Ocultar en el menu 2012 y 2013 (listas de productos), lo que significa que se cargará solamente 2014. (hecho desde el script)
OK - DC1 se cargan gamas y productos y en el resto las gamas no aparecen ([D1 a D5 no se utilizan] [CC1 y RFP1 tampoco] [solo en descuento comercial])
OK - si se quita el no. de factura, tambien se quita la lista de sus productos (y la relacion tambien)
OK - Jack nos ayudara a deshabilitar el back space cuando no este en un campo.
OK - El monto de la NCR no lo toma en pesos sino en Euros.
OK - Para los lotes se debe considerar que reconozca un patron alfa numérico.
OK - la nota 7000029 le aparece a nancy gomez, pero los flujos dicen que debe de ser arnutt
OK - Se pone el total en el grid de solicitantes (se quitó la columna razon para que alcanzara)
OK - Modificar la vista de productos en _formAlmacen usando (lineas y marcas)
OK - verificar que los cambios en los precios no impacten Total::getTotal (solo para notas de almacen)
OK - no se debe permitir que se escriba otra sucursal, si el cliente ya posee una anterior, si esto se permite, se registraran multimples direcciones y exportar no sabra cual es la buena
OK - Cuando se elimina un producto de la lista, se deberia regresar a la normalidad el producto de la tabla de las marcas
OK - Cuando se utiliza el no. de factura, ahora sera necesario hacer un reload de la pagina y dejar de usar ajax
OK - DEPRECATED - Revisar RecordatoriosCommand porque puede que no se esten enviando los correos a los aprobadores/supervisores correctos
OK - Probar siteController/actionDarSeguimientoOtros y siteController/actionDarSeguimientoAprobadores

== TODO #4 ==
OK - Las pestañas de las marcas no funcionan en IE (notas de credito de almacen)
OK - Los puntos decimales (notas de descuento) no funcionan en IE .. no se permite la escritura
OK - Desde la pestaña de los totales se deben de poder modificar los productos
OK - Cuando se selecciona una factura, se bloquea la seleccion de productos, solo se permite modificar la cantidad o remover lineas de los productos
OK - La seleccion de los periodos para los precios se eligen a un lado del año (solo notas de almacen)
OK - filtrar por no. de solicitud (solicitantes, logistica, sac, aprobadores y supervisores)
OK - Los datos para antes de cerrarse (almacen, cliente, sucursal, etc) deben mostrarse en la solicitud, una vez que esta se ha cerrado
OK - En las lista de impresion, se deben de poder ver el solicitante, los aprobadores y el financiero que autorizo la nota (se debe de incluir su grupo) (imprimir antes de cerrar -- antes y despues)
	- SAC antes de cerrarla se debe de poder imprimir
	- Solicitudes al estar cerrada
	- SAC debe de poder consultar notas cerradas
	- Supervisores deben poder imprimir
	- Antonio Rodriguez debe poder tener consulta de las solicitudes cerradas (aparte de todos los SAC)
OK - Revisar que tipos de notas quedan bloqueadas para que grupos (el ultimo archivo de codigos de razon tiene esa informacion 260814.xlsx)
OK - Se resuelve que 2 usuarios que estaban como desconocidos, ya pertenecen a un grupo (ludovic y grisel)
OK - Las graficas no funcionaron en el servidor (dashboards)
- Nancy no revisa todas las solicitudes del primer nivel, revisar porque esa regla ya cambio (para efecto de pruebas/ejemplo 700008)
- Graficos por cliente y por codigo de razon (más fondos blancos y letras similares a la aplicación)

== TODO #5 ==
OK - 24h - Importar nueva lista de precios
	- No se subiran las listas de otros años, solo a partir de este año en adelante
	- Se identificaran por meses, ejemplo (Octubre, A, B, C)
	- Envio complemento de lista de precios con las Marcas, Códigos,  Segmentos/Líneas.
	- No se reemplazaran, a partir de la primera Lista de Precios, el sistema generará el historico de datos.
	- Los códigos que no tengan identificado marca o línea quedaran fuera de la aplicación.
	- La lista de precios se actualizara diariamente, sumando los registros nuevos a la lista original, en caso que detecte un precio distinto sobre el mismo código generara la lista B.
OK - Ya no se utilizará el archivo/vista "Importar de descuentos"
OK - Modificar el autoincrement de la tabla de tblNotas (debe de iniciar en 7000000001 ya que se deben de cubrir 10 espacios)
OK - 2h - Se agrega un nuevo código de razon llamado "Provisiones", el cual es una copia de "Descuento Financiero", aunque con un flujo diferente
OK - 2h - Importar desde una ubicacion las facturas generadas al dia (cronjob de ejecucion diaria)
OK - Modificar la exportacion del layout (no deben de utilizarse ; sino que solamente espacios)
OK - 2h - Revisar nuevamente Codigos de razon, WF Aprobadores para determinar los flujos
OK - Modifique el archivo marcas_dermo.csv para agregar a furterer porque la nueva lista de productos lo incluye
- El usuario de SAC antes de cerrar la nota debe poder modificar la dirección fiscal del cliente
- Alberto indicara una carpeta en la cual se pondrán las notas generadas ya finalizadas (layouts) diariamente
- Revisar que el servidor de Pierre tenga correctamente instalado los modulos necesarios para el despliegue de la aplicación

== COMENTARIOS ==
- descubri que la orden de compra y entrada almacen cliente no son necesarios a menos que el cliente sea liverpool (verificar)
OK - la trama para descuentos y cooperacion (dividida en %) se necesita el % o el monto que es el costo del % equivalente al importe total ? (monto)
- la trama en descuentos, cooperacion y refacturaccion nunca tendra almacen entrada pf y lote del proveedor
- El archivo de aprobadores no esta correctamente formado ya que los codigos de clientes se toman como numericos lo que claramente hace que no se relacionen con los existentes en la BD
- El archivo de precios, lineas y marcas (si no se tiene un campo numerico en precio, este no se agregara)
- El archivo de precios, lineas y marcas (deben de coincidir los nombres de las marcas, el archivo tiene ADERMA y la marca es A-DERMA .. como no coincide, esta no se agrega) [modifique marcas_dermo.csv] y el archivo tiene PFD1 cuando debe ser PFD

== CONSIDERACIONES ==
- No he podido importar otro archivo de precios
- El grupo ya no se llama "comercial", ahora se llama "cuentas clave"

== REVISION ==
- No se tienen productos para la marca Galenic ? [Si se tienen, omitir la pestaña si no se tienen]
- La columna de código de barras no la utilizaré, por lo que el archivo tampoco debería tenerla. [omitirlo solamente, no lo quitaran]
OK - Al momento de capturar el precio, el solicitante eligira de la lista el precio o se pre eligira un precio de la columna ? (a la par del año)
- codigos de razon.310714.xlsx es el ultimo archivo y ahi definen que perfil tiene acceso a que tipos de notas

Cliente fiscal
sucursal de entrega

Descuento Financiero es lo mismo que Descuento Comercial ?

Almacen
Descuentos comerciales
Copperacion al cliente
Refacturaciones
Provisión == Descuentos Comerciales

Que pasara con la importacion de facturas mediante la subida de archivos, yo la quitaria y solo dejaria una sola forma de cargar facturas
Que hacer con la lista de precios que no tiene cliente, pero tiene su codigo de cliente
La lista de clientes anterior la seguire utilizando (se importaba), porque esa lista es mucho mas extensa que la actual q contiene precios
La lista de clientes no coincide con la lista de clientes y precios de productos, por lo que los clientes no existentes no se toman en cuenta


Un usuario nuevo
Varios usuarios ya no se utilizan
Muchos nuevos clientes
Un nuevo flujo en los DC y CC
Encontre codigos de clientes que llevan letras al final o simbolos
