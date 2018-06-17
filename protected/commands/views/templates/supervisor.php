<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>

    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        
        <table width="600" cellpadding="0" border="1" cellspacing="0" style="">
            <tbody>
                <tr>
                    <td style="">
                                        
                    </td>
                </tr>
                <tr>
                    <td style="color:#2e2e2e; font-size:16px; font-weight:lighter; line-height:22px; font-family:'Helvetica Neue', Helvetica, Arial, sans-serif; border-spacing:0; padding-top:30px; padding-bottom:20px; padding-left:20px; min-width:150px; width:100%">
                        <p>
							Hola <strong><?php echo $nombre; ?></strong>
						</p>
						<p>
							Hemos detectado que tienes una solicitud pendiente para revisar, 
							por favor dir&iacute;gete hacia tu panel de administraci&oacute;n para 
							revisar los detalles y darle soluci&oacute;n.
						</p>
						<p>
							Los detalles de la nota son los siguientes:<br />
							<ul>
								<li>Identificador: <?php echo $id; ?></li>
								<li>Importe: <?php echo $importe; ?></li>
								<li>Cliente: <?php echo $cliente; ?></li>
							</ul>
						</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>