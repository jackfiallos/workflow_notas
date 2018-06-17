<?php
$this->pageTitle = Yii::app()->name;
$sumaIVA = 0;
$sumaTotal = 0;
?>

<article class="data-block">
	<?php $form=$this->beginWidget('CActiveForm', array(
	    'id'=>'solicitud-form',
	    'action' => CController::createUrl('sac/update', array('id'=>Yii::app()->request->getParam('id',0))),
	    'enableClientValidation'=>false
	)); ?>
	<div class="pull-right">
		<?php if ($nota->revision == Notas::REV_PROCESADO): ?>
		<?php echo $form->hiddenField($model,'estatus', array('value'=>Notas::REV_DEVOLUCIONES)); ?>
		<button class="btn btn-primary" type="submit">Marcar como "Enviado a Devoluciones"</button>
		<?php endif; ?>
		<a href="<?php echo Yii::app()->createUrl('sac'); ?>" class="btn btn-info">Regresar</a>
	</div>
	<h2 class="noprint">
		Detalles de la Solicitud (<?php echo $nota->folio; ?>)
	</h2>
	<p class="printOnly printtitle">
		Manuel Avila Camacho y/o Horacio No. 191 y/o 1855 4o piso Int. 402 <br />
		Col Los Morales Polanco Deleg. Miguel Hidalgo Mexico D.F.
	</p>
	<?php $this->endWidget(); ?>

	<?php if(Yii::app()->user->hasFlash('Sac.Error')):?>
		<div class="evtMessage alert alert-danger">
			<a class="close" data-dismiss="alert" href="#">&times;</a>
			<h4>Errores de captura</h4>
			<?php echo Yii::app()->user->getFlash('Sac.Error'); ?>
		</div>
	<?php endif; ?>

	<hr />
	<section style="padding-bottom: 20px;">
		<div class="row-fluid noprint">
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
		<hr class="noprint" />
		<div class="row-fluid noprint">
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
		<hr class="noprint" />
		<div class="row-fluid noprint">
		    <div class="span4">
		    	<strong>Fecha de Generaci&oacute;n</strong>:<br />
		        <?php echo $nota->fecha_creacion; ?>
		    </div>
		    <div class="span4">
		    	<strong>Fecha de Vencimiento</strong>:<br />
		        <?php echo $nota->fecha_vencimiento; ?>
		    </div>
		    <div class="span4">
		    	<strong>Fecha de Finalizaci&oacute;n</strong>:<br />
		        <?php echo $nota->fecha_cierre; ?>
		    </div>
		</div>
		<hr class="noprint" />
		<div class="row-fluid noprint">
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

		<hr class="noprint" />

		<!-- Solamente se vera al imprimir la pagina -->
		<?php if ($nota->revision == Notas::REV_PROCESADO): ?>
		<div class="row-fluid">
	    	<div class="span12">
				<table cellpadding="5px" class="printOnly" style="padding-bottom:20px">
					<thead>
						<tr>
							<th style="width:10%; text-align:center"></th>
							<th style="width:10%; text-align:center"></th>
							<th style="width:10%; text-align:center"></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
					        	<?php echo $nota->razones->caracteristicas_tipo->nombre; ?>
							</td>
							<td></td>
							<td>
					        	<?php echo $nota->razones->nombre; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $nota->clientes->nombre; ?>
							</td>
							<td></td>
							<td>
					        	Factura No. <?php echo $nota->num_factura; ?>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold">
								No. de Control <?php echo $nota->folio; ?>
							</td>
							<td></td>
							<td>
					        	Vence el <?php echo Yii::app()->dateFormatter->format('dd/MM/yy',strtotime($nota->fecha_vencimiento)); ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php endif; ?>
		<!-- Fin pagina impresa -->

		<div class="row-fluid">
	    	<div class="span12">
			<?php if ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::ALMACEN): ?>
	    		<h3 class="noprint">Lista de Productos</h3>
	    		<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th style="width:10%; text-align:center">C&oacute;digo</th>
							<th style="width:15%; text-align:center">Descripci&oacute;n</th>
							<th style="width:10%; text-align:center">No.de Lote</th>
							<th style="width:10%; text-align:center">Almacen</th>
							<th style="width:10%; text-align:center">Caducidad</th>
							<th style="width:10%; text-align:center">Cantidad</th>
							<th style="width:10%; text-align:center">Precio Unitario</th>
							<th style="width:10%; text-align:center">I.V.A</th>
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
							<td style="text-align:center;"><?php echo $data->cnotas[0]->num_lote; ?></td>
							<td style="text-align:center;"><?php echo $data->cnotas[0]->almacen; ?></td>
							<td style="text-align:center;"><?php echo $data->cnotas[0]->caducidad; ?></td>
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
							<td style="text-align:right; font-weight:bold;" colspan="8">
								<h4>Total</h4>
							</td>
							<td style="text-align: right;">
								<h4>
									<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaTotal, "MXN"); ?>
								</h4>
							</td>
						</tr>
						<tr>
							<td style="text-align:right; font-weight:bold;" colspan="8">
								<h4>IVA</h4>
							</td>
							<td style="text-align: right;">
								<h4>
									<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaIVA, "MXN"); ?>
								</h4>
							</td>
						</tr>
						<tr>
							<td style="text-align:right; font-weight:bold;" colspan="8">
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
							<td style="text-align:right"><?php echo $data->importe."%"; ?></td>
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
			<?php elseif ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::COOPERACION ): ?>
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
			<?php elseif ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::REFACTURACION ): ?>
				<h3> Facturas </h3>
				<?php  $this->widget('zii.widgets.grid.CGridView', array(
				    'id'=>'facturas-grid',
				    'dataProvider'=>$facturas,
				    'itemsCssClass' => 'table table-striped table-hover table-bordered',
					'emptyText'=>'No se encontraron facturas.',
					'enableSorting' => true,
					'summaryText'=>'Mostrando {start} al {end} de {count} factura(s)',
					'pager'=>array(
						'header'         => '',
						 'nextPageLabel' => 'Next',
						 'prevPageLabel' => 'Prev',
						 'selectedPageCssClass' => 'active',
						 'hiddenPageCssClass' => 'disabled',
					),
				    'columns'=>array(
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
								'style' => 'width:20%;text-align:right'
							),
						)
				    )
				)); ?>
			<?php endif; ?>
			</div>
		</div>
		<hr class="noprint" />
 		<div class="row-fluid noprint">
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
		    	<h3>Comentarios</h3>
		    	<div style="overflow-y:auto; height:250px; background-color: #E7E7E7; border: 1px solid #A5A5A5; padding:5px">
		    		<?php echo nl2br(CHtml::encode($nota->comentario)); ?>
		    	</div>
		    </div>
		    <div class="span4">
	            <h3>Historial de Cambios</h3>
		        <div style="overflow-y:auto; height:250px; background-color:#fff; padding: 5px 6px 5px 2px;">
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
	</section>
</article>

<?php if ($nota->revision == Notas::REV_SAC): ?>
	<?php $form=$this->beginWidget('CActiveForm', array(
	    'id'=>'sac-form',
	    'focus' => array($model, 'estatus'),
	    'action' => CController::createUrl('sac/view', array('id'=>Yii::app()->request->getParam('id',0))),
	    'htmlOptions' => array(
	        'class' => 'well noprint',
	    ),
	    'enableClientValidation'=>false
	)); ?>
	<h3>Complete la siguiente informaci&oacute;n antes de Cerrar la Solicitud</h3>
	<hr />
	<div class="row-fluid">
		<div class="span4">
			<div class="control-group">
				<div style="padding-bottom: 10px;">
					Modificaci&oacute;n de Estatus
				</div>
				<label class="radio" style="float: left;width: 100px;">
				  <input class="validar" data-status="1" type="radio" name="<?php echo CHtml::activeName($model, 'estatus'); ?>" value="<?php echo Notas::REV_APROBADO; ?>" />
				  Aceptado
				</label>
				<label class="radio">
				  <input class="validar" data-status="0" type="radio" name="<?php echo CHtml::activeName($model, 'estatus'); ?>" value="<?php echo Notas::REV_RECHAZADO; ?>" />
				  Rechazado
				</label>
			</div>
	    </div>
	    <div class="span4">
	    	<div class="control-group <?php echo (!is_null($model->getError('sucursal')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model, 'sucursal', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->textField($model,'sucursal'); ?>
				</div>
			</div>
	    </div>
	    <div class="span4">
	    	<div class="control-group <?php echo (!is_null($model->getError('entrada_almacen')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model, 'entrada_almacen', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->textField($model,'entrada_almacen'); ?>
				</div>
			</div>
	    </div>
	</div>
	<div class="row-fluid">
	    <div class="span4">
	        <div class="control-group <?php echo (!is_null($model->getError('descripcion')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model,'descripcion', array('class'=>'control-label')); ?>
	            <div class="controls">
					<?php echo $form->textField($model,'descripcion', array('maxlength'=>25)); ?>
	            </div>
	        </div>
	    </div>
	    <div class="span4">
	        <div class="control-group <?php echo (!is_null($model->getError('ordenCompra')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model,'ordenCompra', array('class'=>'control-label')); ?>
	            <div class="controls">
	            	<?php if(is_null($ordenCompra)): ?>
					<?php echo $form->textField($model, 'ordenCompra', array('maxlength'=>25, 'value'=>$ordenCompra)); ?>
					<?php else: ?>
					<div style="color: #808080; border: 1px solid #ddd; background: #eee; padding: 7px 12px; width: 206px; border-radius: 3px;">
						<?php echo $ordenCompra; ?>
					</div>
					<?php echo $form->hiddenField($model, 'ordenCompra', array('value'=>$ordenCompra)); ?>
					<?php endif; ?>
	            </div>
	        </div>
	    </div>
	    <div class="span4">
	    	<div class="control-group <?php echo (!is_null($model->getError('tipoOrden')) ? 'error' : ''); ?>">
				<?php echo $form->labelEx($model, 'tipoOrden', array('class'=>'control-label')); ?>
				<div class="controls">
					<?php echo $form->dropDownList($model, 'tipoOrden', array(
						7 => 'Sin movimiento de stock',
						8 => 'Con movimiento de stock'
					), array(
						'empty'=>'Seleccionar'
					)); ?>
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
							array('class'=>'span12','rows' => 6, 'cols' => 50, 'value'=>'')); 
					?>
	            </div>
	        </div>
	        <div class="form-actions">
	    		<button class="btn btn-primary" type="submit">Guardar</button>
	    		<a href="<?php echo Yii::app()->createUrl('sac/index')?>" class="btn">Regresar</a>
			</div>
	    </div>
	</div>
	<?php $this->endWidget(); ?>
<?php endif; ?>

<?php
$cs = Yii::app()->getClientScript();
if ($nota->revision == Notas::REV_PROCESADO) 
{
	$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/print.css', 'print');
}
$cs->registerScript('sac.jquery', "
	$('.validar').on('click', function(e){
		var el = $(this);
		if (el.attr('data-status') == 0) {
			$('#sac-form *').filter(':input[type=\"text\"]').each(function() {
				$(this).attr('disabled', true);
            });
			$('#".CHtml::activeId($model, 'tipoOrden')."').attr('disabled', true);
		}
		else {
			$('#sac-form *').filter(':input[type=\"text\"]').each(function() {
				$(this).removeAttr('disabled');
            });
			$('#".CHtml::activeId($model, 'tipoOrden')."').removeAttr('disabled');
		}
	})
", CClientScript::POS_READY);
?>