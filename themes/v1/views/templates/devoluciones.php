<?php $this->beginContent('//templates/NotaLayout'); ?>

<p>
	Hola <strong><?php echo $nombre; ?></strong>
</p>

<p>
	El sistema autom&aacute;tico de revisi&oacute;n de solicitudes ha detectado que tienes una solicitud pendiente por revisar, los detalles a continuaci&oacute;n:
</p>

<p>
	Cliente: <?php echo $cliente; ?> [<?php echo $cliente_codigo; ?>]<br />
	Nota: <?php echo $folio; ?><br />
	Creaci&oacute;n: <?php echo $fecha_creacion; ?><br />
	Vencimiento: <?php echo $fecha_vencimiento; ?><br />
	Importe: <?php echo $importe; ?><br />
	Url: <?php echo CHtml::link($url, $url); ?><br />
</p>

<?php $this->endContent(); ?>