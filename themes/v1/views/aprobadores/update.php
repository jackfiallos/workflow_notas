<?php
$this->pageTitle = Yii::app()->name;
$sumaIVA = 0;
$sumaTotal = 0;
?>

<article class="data-block">
	<div class="pull-right">
		<a href="<?php echo Yii::app()->createUrl('aprobadores/index'); ?>" class="btn btn-info">Regresar</a>
	</div>
	<h2>
		Detalles de la Solicitud (<?php echo $nota->folio; ?>)
	</h2>
	<hr />
	<section style="padding-bottom: 20px;">
		<div class="row-fluid">
		    <div class="span4">
		    	<strong>Caracter&iacute;stica</strong>:<br />
		        <?php echo $nota->razones->caracteristicas_tipo->caracteristicas->nombre; ?>
		    </div>
		    <div class="span4">
		    	<strong>Tipo de Caracter&iacute;stica</strong>:<br />
		        <?php echo $nota->razones->caracteristicas_tipo->nombre; ?>
		    </div>
		    <div class="span4">
		    	<strong>Raz&oacute;n</strong>:<br />
		        <?php echo $nota->razones->nombre; ?>
		    </div>
		</div>
		<hr />
		<div class="row-fluid">
		    <div class="span4">
		    	<strong>Usuario que solicita</strong>:<br />
		    	<?php echo $nota->usuarios->nombre; ?>
		    </div>
		    <div class="span4">
		    	<strong>Nombre del Cliente</strong>:<br />
		        <?php echo $nota->clientes->nombre; ?>
		    </div>
		    <div class="span4">
		    	<strong>Factura No.</strong>:<br />
		        <?php echo $nota->num_factura; ?>
		    </div>
		</div>
		<hr />
		<div class="row-fluid">
		    <div class="span4">
		    	<strong>Fecha de Generaci&oacute;n</strong>:<br />
		        <?php echo $nota->fecha_creacion; ?>
		    </div>
		    <div class="span4">
		    	<strong>Fecha de Vencimiento</strong>:<br />
		        <?php echo $nota->fecha_vencimiento; ?>
		    </div>
		    <div class="span4">
		    	<strong>Fecha de Resoluci&oacute;n</strong>:<br />
		        <?php echo $nota->fecha_cierre; ?>
		    </div>
		</div>
		<hr />
		<div class="row-fluid">
		    <div class="span4">
		    	<strong>Estado de la Revisi&oacute;n</strong>:<br />
		        <?php
		        	Tools::pintaRevision($nota->revision);
		        ?>
		    </div>
		    <div class="span4">
		    	<strong>Cat&aacute;logo del A&ntilde;o</strong>:<br />
		       	<?php echo (!empty($nota->anio_id)) ? $nota->anio->anio : '-'; ?>
		    </div>
		    <div class="span4">
		    	<strong>Importe de la Nota</strong>:<br />
		       	<?php
		       	if (($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::REFACTURACION) || ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::COOPERACION) || ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::ALMACEN))
		       	{
		       		echo Yii::app()->locale->numberFormatter->formatCurrency(Tools::GetTotal($nota), 'MXN');
		       	}
		       	else
		       	{
		       		echo (!empty($nota->descuentos->importe)) ? Yii::app()->locale->numberFormatter->formatCurrency($nota->descuentos->importe, 'MXN') : '-';
		       	}
		       	?>
		    </div>
		</div>

		<hr />

		<div class="row-fluid">
	    	<div class="span12">
			<?php if ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::ALMACEN): ?>
	    		<h3>Lista de Productos</h3>
	    		<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th style="width:15%; text-align:center">C&oacute;digo</th>
							<th style="width:30%; text-align:center">Descripci&oacute;n</th>
							<th style="width:10%; text-align:center">Cantidad</th>
							<th style="width:15%; text-align:center">Precio Unitario</th>
							<th style="width:15%; text-align:center">I.V.A</th>
							<th style="width:15%; text-align:center">Importe</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($productos->getData() as $data): ?>
						<?php
							$aplica_descuento = 0;
							if ($descuento !== null)
							{
								// Determinar para que tipo de productos tiene descuento
								if (in_array($data->codigo, Yii::app()->params['descuentos']['TREV']))
								{
									$aplica_descuento = $descuento->todas_trev;
								}
								else if (in_array($data->codigo, Yii::app()->params['descuentos']['PFD']))
								{
									$aplica_descuento = $descuento->pfd_notrev;
								}
								else
								{
									$aplica_descuento = $descuento->todas_nopfd;
								}
							}
						?>
						<tr>
							<td><?php echo $data->codigo; ?></td>
							<td><?php echo $data->descripcion; ?></td>
							<td style="text-align:center;"><?php echo $data->cnotas[0]->cantidad_piezas; ?></td>
							<td style="text-align:right">
								<?php
									$precio = $data->precios[0]->precio - (($aplica_descuento/100) * $data->precios[0]->precio);
									echo Yii::app()->locale->numberFormatter->formatCurrency($precio, "MXN");
								?>
							</td>
							<td style="text-align:right">
								<?php
									$iva = (($data->precios[0]->precio - (($aplica_descuento/100) * $data->precios[0]->precio)) * $data->cnotas[0]->cantidad_piezas) * ($data->precios[0]->anio->impuesto/100);
									echo Yii::app()->locale->numberFormatter->formatCurrency($iva, "MXN");
									$sumaIVA += $iva;
								?>
							</td>
							<td style="text-align:right">
								<?php
									$total = (($data->precios[0]->precio - (($aplica_descuento/100) * $data->precios[0]->precio)) * $data->cnotas[0]->cantidad_piezas);
									echo Yii::app()->locale->numberFormatter->formatCurrency($total, "MXN");
									$sumaTotal += $total;
								?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<td style="text-align:right; font-weight:bold;" colspan="5">
								<h4>Total</h4>
							</td>
							<td style="text-align: right;">
								<h4>
									<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaTotal, "MXN"); ?>
								</h4>
							</td>
						</tr>
						<tr>
							<td style="text-align:right; font-weight:bold;" colspan="5">
								<h4>IVA</h4>
							</td>
							<td style="text-align: right;">
								<h4>
									<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaIVA, "MXN"); ?>
								</h4>
							</td>
						</tr>
						<tr>
							<td style="text-align:right; font-weight:bold;" colspan="5">
								<h4>Gran Total</h4>
							</td>
							<td style="text-align: right;">
								<h4>
									<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaIVA+$sumaTotal, "MXN"); ?>
								</h4>
							</td>
						</tr>
					</tfoot>
				</table>
			<?php elseif ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::DESCUENTOS): ?>
	    		<h3>Descuentos por Marcas</h3>
				<table class="table evtProductsTable">
					<thead>
						<tr>
							<th style="width:15%; text-align:center">C&oacute;digo</th>
							<th style="width:15%; text-align:center">Marca</th>
							<th style="width:15%; text-align:center">Descuento (%)</th>
							<th style="width:15%; text-align:center">I.V.A</th>
							<th style="width:15%; text-align:center">Importe</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($descuentos->getData() as $data): ?>
						<tr>
							<td><?php echo $data->marcas->codigo; ?></td>
							<td><?php echo $data->marcas->marca; ?></td>
							<td style="text-align:center"><?php echo $data->importe."%"; ?></td>
							<td style="text-align:right">
							<?php
								$iva = floatval($data->descuentos->importe)*($data->importe/100) * (Yii::app()->params['iva']/100);
								echo Yii::app()->locale->numberFormatter->formatCurrency($iva, 'MXN');
								$sumaIVA += $iva;
							?>
							</td>
							<td style="text-align:right">
							<?php
								$total = floatval($data->descuentos->importe)*($data->importe/100);
								echo Yii::app()->locale->numberFormatter->formatCurrency($total, 'MXN');
								$sumaTotal += $total;
							?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<td style="text-align:right; font-weight:bold;" colspan="4">
								<h4>Total</h4>
							</td>
							<td style="text-align: right;">
								<h4>
									<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaTotal, "MXN"); ?>
								</h4>
							</td>
						</tr>
						<tr>
							<td style="text-align:right; font-weight:bold;" colspan="4">
								<h4>I.V.A</h4>
							</td>
							<td style="text-align: right;">
								<h4>
									<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaIVA, "MXN"); ?>
								</h4>
							</td>
						</tr>
						<tr>
							<td style="text-align:right; font-weight:bold;" colspan="4">
								<h4>Gran Total</h4>
							</td>
							<td style="text-align: right;">
								<h4>
									<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaTotal+$sumaIVA, "MXN"); ?>
								</h4>
							</td>
						</tr>
					</tfoot>
				</table>
			<?php elseif  ( $nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::COOPERACION ): ?>
				<h3>Cooperaci&oacute;n por Marcas</h3>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th style="width:20%; text-align:center">C&oacute;digo</th>
							<th style="width:30%; text-align:center">Marca</th>
							<th style="width:15%; text-align:center">Descuento (%)</th>
							<th style="width:15%; text-align:center">I.V.A</th>
							<th style="width:20%; text-align:center">Importe</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($cooperacion->getData() as $data): ?>
						<tr>
							<td><?php echo $data->marcas->codigo; ?></td>
							<td><?php echo $data->marcas->marca; ?></td>
							<td style="text-align:center;"><?php echo $data->importe."%"; ?></td>
							<td style="text-align:right">
							<?php
								$iva = ((floatval($data->cooperacion->importe)*($data->importe/100))*(Yii::app()->params['iva']/100));
								echo Yii::app()->locale->numberFormatter->formatCurrency($iva, 'MXN');
								$sumaIVA += $iva;
							?>
							</td>
							<td style="text-align:right">
							<?php
								$total = (floatval($data->cooperacion->importe) * ($data->importe/100));
								echo Yii::app()->locale->numberFormatter->formatCurrency($total, 'MXN');
								$sumaTotal += $total;
							?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<td style="text-align:right; font-weight:bold;" colspan="4">
								<h4>Total</h4>
							</td>
							<td style="text-align: right;">
								<h4>
									<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaTotal, "MXN"); ?>
								</h4>
							</td>
						</tr>
						<tr>
							<td style="text-align:right; font-weight:bold;" colspan="4">
								<h4>I.V.A</h4>
							</td>
							<td style="text-align: right;">
								<h4>
									<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaIVA, "MXN"); ?>
								</h4>
							</td>
						</tr>
						<tr>
							<td style="text-align:right; font-weight:bold;" colspan="4">
								<h4>Gran Total</h4>
							</td>
							<td style="text-align: right;">
								<h4>
									<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaIVA+$sumaTotal, "MXN"); ?>
								</h4>
							</td>
						</tr>
					</tfoot>
				</table>
			<?php elseif  ( $nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::REFACTURACION ): ?>
				<h3> Facturas </h3>
				<?php  $this->widget('zii.widgets.grid.CGridView', array(
				    'id'=>'facturas-grid',
				    'dataProvider'=>$facturas,
				    'itemsCssClass' => 'table table-striped table-hover table-bordered',
					'emptyText'=>'No se encontraron facturas.',
					'enableSorting' => true,
					'summaryText'=>'Mostrando {start} al {end} de {count} factura(s)',
				    'columns'=>array(
				    	array(
							'type'   => 'raw',
							'name'   => 'folio',
							'header' => 'Folio',
							'value'  => '$data->folio',
							'htmlOptions' => array(
								'style' => 'width:20%;'
							),
						),
						array(
							'type'   => 'raw',
							'name'   => 'codigo_producto',
							'header' => 'Codigo Producto',
							'value'  => '$data->codigo_producto',
							'htmlOptions' => array(
								'style' => 'width:20%;'
							),
						),
						array(
							'type'   => 'raw',
							'name'   => 'cantidad_piezas',
							'header' => 'Cantidad Piezas',
							'value'  => '$data->cantidad_piezas',
							'htmlOptions' => array(
								'style' => 'width:20%;'
							),
						),
						array(
							'type'   => 'raw',
							'name'   => 'costo_iva',
							'header' => 'Costo IVA',
							'value'  => 'Yii::app()->locale->numberFormatter->formatCurrency($data->costo_iva, "MXN")',
							'htmlOptions' => array(
								'style' => 'width:20%; text-align:right'
							),
						)
				    )
				)); ?>
			<?php endif; ?>
			</div>
		</div>
		<hr />
		
		<style>
			table {
				table-layout: fixed;
			}
		</style>
 		<div class="row-fluid">
 			<div class="span4">
	            <h3>Documentos Adjuntos</h3>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
				    'id'=>'documentos-grid',
				    'dataProvider'=>$documentos,
				    'itemsCssClass' => 'table table-striped table-hover table-bordered',
					'emptyText'=>'No se encontraron documentos.',
					'enableSorting' => false,
					'summaryText'=>'',
				    'columns'=>array(
				    	array(
							'type'   => 'raw',
							'name'=>'fecha_creacion',
							'header'=>'Fecha',
							'value'=>'$data->fecha_creacion',
							'htmlOptions' => array(
								'style' => 'width:25%;word-wrap: break-word;'
							),
						),
						array(
							'type'   => 'raw',
							'name'=>'archivo',
							'header'=>'Nombre del Archivo',
							'value'=>'CHtml::link($data->archivo, Yii::app()->baseUrl."/protected/files/".$data->descripcion , array("target"=>"_blank"))',
							'htmlOptions' => array(
								'style' => 'word-wrap: break-word;'
							),
						)
				    )
				)); ?>
		    </div>
		    <div class="span4">
		    	<h3>&Uacute;ltimo Comentario</h3>
		    	<div style="overflow-y:auto; height:250px; background-color: #E7E7E7; border: 1px solid #A5A5A5; padding:5px">
		    		<?php echo nl2br(CHtml::encode($nota->comentario)); ?>
		    	</div>
		    </div>
		    <div class="span4">
	            <h3>Historial de Cambios</h3>
		        <div style="overflow-y:auto; height:250px; background-color:#fff; padding:5px">
		        	<ul>
						<?php while ( list($key,$value) = each($historial) ) { ?>
							<li>
								Usuario: <?php echo $value->usuarios->nombre; ?><br />
								Descripci&oacute;n: <?php echo nl2br(CHtml::encode($value->descripcion)); ?><br />
								Fecha: <?php echo $value->fecha_creacion; ?><br />
								<hr />
							</li>
						<?php }  ?>
					</ul>
		        </div>
		    </div>
		</div>
		<hr />
		
		<?php $form=$this->beginWidget('CActiveForm', array(
		    'id'=>'solicitud-form',
		    'focus' => array($model, 'estatus'),
		    'htmlOptions' => array(
		        'class' => 'well',
		    ),
		    'enableClientValidation'=>false
		)); ?>
		<div class="row-fluid">
	    	<div class="span12">
	    		<h3>Modificaci&oacute;n de estatus</h3>
				<div class="control-group <?php echo (!is_null($model->getError('estatus')) ? 'error' : ''); ?>">
		            <div class="controls">
		                <?php  echo $form->radioButtonList($model,'estatus',
							array( 
			                	Notas::REV_ACEPTADO=> 'Aceptado' , 
			                	Notas::REV_RECHAZADO  => 'Rechazado' 
		                	),
		                	array(
		                		'labelOptions'=>array('style'=>'display:inline;margin-right: 5px;'), 
    							'separator'=>' ',
    							'class' => 'validar'
		                	)
		                ); ?>
		                 <span class="help-inline">
                        	<?php echo $form->error($model,'estatus'); ?>
                    	</span>
		            </div>
		        </div>
		    </div>
		</div>
		<div class="row-fluid">
		    <div class="span12">
		        <div class="control-group <?php echo (!is_null($model->getError('comentario')) ? 'error' : ''); ?>">
					<label for="<?php echo CHtml::activeId($model,'comentario'); ?>" class="control-label">Escriba un comentario, acerca del cambio de estatus realizado.</label>
		            <div class="controls">
						<?php echo $form->textArea($model, 'comentario', 
								array('class'=>'span12 validar','rows' => 6, 'cols' => 50, 'value'=>'')); 
						?>
						 <span class="help-inline">
                        	<?php echo $form->error($model,'comentario'); ?>
                    	</span>
		            </div>
		        </div>
		        <div class="form-actions">
		    		<button id="boton_form"class="btn btn-primary" type="submit" name="yt0" value="Guardar" disabled="disabled">Guardar</button>
		    		<a href="<?php echo Yii::app()->createUrl('aprobadores/index')?>" class="btn">Regresar</a>
				</div>
		    </div>
		</div>
		<?php $this->endWidget(); ?>

	</section>
</article>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerScript('solicitudesAprobar.jquery.main', "
	$(document).on('change', '.validar[type=radio]', function(e){
		$('#boton_form').attr('disabled','disabled');
		if( $('.validar[type=radio]').is(':checked') && $('#SolicitudesAprobarForm_comentario').val() !== '' ) {
			$('#boton_form').removeAttr('disabled');
		}
    });

	$(document).on('keyup', '.validar', function(e){
		$('#boton_form').attr('disabled','disabled');
		if( $('.validar[type=radio]').is(':checked') && $('#SolicitudesAprobarForm_comentario').val() !== '' ) {
			$('#boton_form').removeAttr('disabled');
		}
    });
", CClientScript::POS_END );