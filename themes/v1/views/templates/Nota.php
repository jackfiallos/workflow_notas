<?php $this->beginContent('//templates/NotaLayout'); ?>
	
	<strong>Nota </strong>:&nbsp;
	<?php echo $nota->id ?>
	
	<br /><br />
	<strong>Estado de la Revisi&oacute;n</strong>:&nbsp;
    <?php
    	switch ($nota->revision) {
    		case Notas::REV_PENDIENTE:
    			echo "<span class=\"label label-warning\">Pendiente</span>";
    			break;
    		case Notas::REV_ACEPTADO:
    			echo "<span class=\"label label-info\">Aceptado parcialmente</span>";
    			break;
    		case Notas::REV_APROBADO:
    			echo "<span class=\"label label-success\">Aprobado</span>";
    			break;
    		case Notas::REV_RECHAZADO:
    			echo "<span class=\"label label-important\">Rechazado</span>";
    			break;
    		default:
    			echo "<span class=\"label label-warning\">Pendiente</span>";
    			break;
    	}
    ?>
    <br /><br />

    <strong>Comentario </strong>:&nbsp;
    <?php echo $nota->comentario ?>
<?php $this->endContent(); ?>