<?php
$this->pageTitle = Yii::app()->name;
$sumaIVA = 0;
$sumaTotal = 0;
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Detalles de la Solicitud (<?php echo $nota->folio; ?>)</h1>
    
    <span class="pagedesc">Revisi&oacute;n de detalles, revise con detenimiento la solicitud.</span>
    <div class="pull-right" style="margin: 0 10px; float:right">

		<a href="#" class="button-large button-warning pure-button evtPrint"><i class="fa fa-print"></i> Imprimir</a>

		<?php if($nota->estatus == Notas::ESTATUS_BORRADOR): ?>
			<a href="<?php echo Yii::app()->createUrl('solicitudes/update', array('id'=>$id)); ?>" class="button-large button-warning pure-button"><i class="fa fa-edit"></i> Editar</a>
		<?php endif; ?>

		<a href="<?php echo Yii::app()->createUrl('solicitudes'); ?>" class="button-large button-secondary pure-button"><i class="fa fa-home"></i> Regresar</a>
	</div>
</div>
<div id="contentwrapper" class="contentwrapper">

	<p class="printOnly printtitle">
		Manuel Avila Camacho y/o Horacio No. 191 y/o 1855 4o piso Int. 402 <br />
		Col Los Morales Polanco Deleg. Miguel Hidalgo Mexico D.F.
	</p>

	<div class="pure-g noprint" style="margin-bottom: 20px;">
	    <div class="pure-u-1-3">
	    	<strong>Caracter&iacute;stica</strong>:<br />
	        <?php echo $nota->razones->caracteristicas_tipo->caracteristicas->nombre; ?>
	    </div>
	    <div class="pure-u-1-3">
	    	<strong>Tipo de Caracter&iacute;stica</strong>:<br />
	        <?php echo $nota->razones->caracteristicas_tipo->nombre; ?>
	    </div>
	    <div class="pure-u-1-3">
	    	<strong>Raz&oacute;n</strong>:<br />
	        <?php echo $nota->razones->nombre; ?>
	    </div>
	</div>

	<div class="pure-g noprint" style="margin-bottom: 20px;">
	    <div class="pure-u-1-3">
	    	<strong>Usuario que solicita</strong>:<br />
	    	<?php echo $nota->usuarios->nombre; ?>
	    </div>
	    <div class="pure-u-1-3">
	    	<strong>Nombre del Cliente</strong>:<br />
	        <?php echo $nota->clientes->nombre; ?>
	    </div>
	    <div class="pure-u-1-3">
	    	<strong>Factura No.</strong>:<br />
	        <?php echo $nota->num_factura; ?>
	    </div>
	</div>
	
	<div class="pure-g noprint" style="margin-bottom: 20px;">
	    <div class="pure-u-1-3">
	    	<strong>Fecha de Generaci&oacute;n</strong>:<br />
	        <?php echo $nota->fecha_creacion; ?>
	    </div>
	    <div class="pure-u-1-3">
	    	<strong>Fecha de Vencimiento</strong>:<br />
	        <?php echo $nota->fecha_vencimiento; ?>
	    </div>
	    <div class="pure-u-1-3">
	    	<strong>Fecha de Resoluci&oacute;n</strong>:<br />
	        <?php echo $nota->fecha_cierre; ?>
	    </div>
	</div>
	
	<div class="pure-g noprint" style="margin-bottom: 20px;">
	    <div class="pure-u-1-3">
	    	<strong>Estado de la Revisi&oacute;n</strong>:<br />
	        <?php
	        	Tools::pintaRevision($nota->revision);
	        ?>
	    </div>
	    <div class="pure-u-1-3">
	    	<strong>Cat&aacute;logo del A&ntilde;o</strong>:<br />
	       	<?php echo (!empty($nota->anio_id)) ? $nota->anio->anio : '-'; ?>
	    </div>
	    <div class="pure-u-1-3">
	    	<strong>Importe de la Nota</strong>:<br />
	       	<?php
	       		echo Yii::app()->locale->numberFormatter->formatCurrency(Tools::GetTotal($nota), 'MXN');
	       	?>
	    </div>
	</div>

	<!-- Solamente se vera al imprimir la pagina -->
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
					<strong>Caracter&iacute;stica</strong>:<br />
	        		<?php echo $nota->razones->caracteristicas_tipo->caracteristicas->nombre; ?>
				</td>
				<td>
					<strong>Tipo de Caracter&iacute;stica</strong>:<br />
	        		<?php echo $nota->razones->caracteristicas_tipo->nombre; ?>
				</td>
				<td>
					<strong>Raz&oacute;n</strong>:<br />
	        		<?php echo $nota->razones->nombre; ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Usuario que solicita</strong>:<br />
	    			<?php echo $nota->usuarios->nombre; ?>
				</td>
				<td>
					<strong>Nombre del Cliente</strong>:<br />
	        		<?php echo $nota->clientes->nombre; ?>
				</td>
				<td>
		        	<strong>Factura No.</strong>:<br />
	        		<?php echo $nota->num_factura; ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Fecha de Generaci&oacute;n</strong>:<br />
	        		<?php echo $nota->fecha_creacion; ?>
				</td>
				<td>
					<strong>Fecha de Vencimiento</strong>:<br />
	        		<?php echo Yii::app()->dateFormatter->format('dd/MM/yy',strtotime($nota->fecha_vencimiento)); ?>
				</td>
				<td>
		        	<strong>Fecha de Resoluci&oacute;n</strong>:<br />
	        		<?php echo $nota->fecha_cierre; ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>No. de Control</strong><br />
					<?php echo $nota->folio; ?>
				</td>
				<td>
					<strong>Cat&aacute;logo del A&ntilde;o</strong>:<br />
	       			<?php echo (!empty($nota->anio_id)) ? $nota->anio->anio : '-'; ?>
				</td>
				<td>
					<strong>Importe de la Nota</strong>:<br />
			       	<?php
			       		echo Yii::app()->locale->numberFormatter->formatCurrency(Tools::GetTotal($nota), 'MXN');
			       	?>
				</td>
			</tr>
		</tbody>
	</table>
	<!-- Fin pagina impresion -->

	<?php if (($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::ALMACEN) && ($nota->revision == Notas::REV_APROBADO)): ?>
	<hr style="margin-top: 20px;" />
	<div style="margin-bottom: 20px;">
		<div class="contenttitle2">
        	<h3>Detalles de la Nota</h3>
        </div>
		<div class="pure-g" style="margin-bottom: 20px;">
		    <div class="pure-u-1-4">
		    	<strong>Sucursal</strong>:<br />
		        <?php
		        	echo $nota->clientes->sucursal[0]->nombre;
		        ?>
		    </div>
		    <div class="pure-u-1-4">
		    	<strong>Orden de Compra</strong>:<br />
		       	<?php
		       		echo $nota->ordenCompra;
		       	?>
		    </div>
		    <div class="pure-u-1-4">
		    	<strong>Tipo de Orden</strong>:<br />
		       	<?php
		       		if ($nota->tipo_orden == 7)
		       		{
		       			echo 'Sin movimiento de stock';
		       		}
		       		else
		       		{
		       			echo 'Con movimiento de stock';
		       		}
		       	?>
		    </div>
		    <div class="pure-u-1-4">
		    	<strong>Entrada al Almac&eacute;n Cliente</strong>:<br />
		       	<?php
		       		echo $nota->entrada_almacen;
		       	?>
		    </div>
		</div>
	</div>
	<?php endif; ?>

	<hr class="noprint" />

	<div style="margin-bottom: 20px;">
		<?php if ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::ALMACEN): ?>
    		<div class="contenttitle2">
            	<h3>Lista de Productos</h3>
            </div>
            <?php if ($nota->revision == Notas::REV_APROBADO): ?>
    		<table class="stdtable">
				<thead>
					<tr>
						<th class="head1" style="width:5%; text-align:center">C&oacute;digo</th>
						<th class="head1" style="width:15%; text-align:center">Descripci&oacute;n</th>
						<th class="head1" style="width:5%; text-align:center">Cantidad</th>
						<th class="head1" style="width:10%; text-align:center">Precio Unitario</th>
						<th class="head1" style="width:10%; text-align:center">Importe</th>
						<th class="head1" style="width:10%; text-align:center">% Aceptaci&oacute;n</th>
						<th class="head1" style="width:10%; text-align:center">No. de Lote</th>
						<th class="head1" style="width:5%; text-align:center">Almac&eacute;n</th>
						<th class="head1" style="width:10%; text-align:center">Caducidad</th>
						<th class="head1" style="width:10%; text-align:center">Total</th>
						<th class="head1" style="width:10%; text-align:center">I.V.A</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($productos->getData() as $data): ?>
					<?php
						$aplica_descuento = 0;
						$no_iva = false;
						if ($descuento !== null)
						{
							// Determinar para que tipo de productos tiene descuento
							if (in_array($data->codigo, Yii::app()->params['descuentos']['TREV']))
							{
								$aplica_descuento = $descuento->todas_trev;
								$no_iva = true;
							}
							else if (in_array($data->codigo, Yii::app()->params['descuentos']['PFD']))
							{
								$aplica_descuento = $descuento->pfd_notrev;
								$no_iva = true;
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
								$importetotal = (($data->precios[0]->precio - (($aplica_descuento/100) * $data->precios[0]->precio)) * $data->cnotas[0]->cantidad_piezas);
								echo Yii::app()->locale->numberFormatter->formatCurrency($importetotal, "MXN");
							?>
						</td>
						<td style="text-align:center">
							<?php echo $data->cnotas[0]->aceptacion.'%'; ?>
						</td>
						<td style="text-align:center">
							<?php echo $data->cnotas[0]->num_lote; ?>
						</td>
						<td style="text-align:center">
							<?php echo $data->cnotas[0]->almacen; ?>
						</td>
						<td style="text-align:center">
							<?php echo $data->cnotas[0]->caducidad; ?>
						</td>
						<td style="text-align:right">
							<?php
								$total = (($data->precios[0]->precio - (($aplica_descuento/100) * $data->precios[0]->precio)) * $data->cnotas[0]->cantidad_piezas) * ($data->cnotas[0]->aceptacion/100);
								echo Yii::app()->locale->numberFormatter->formatCurrency($total, "MXN");
								$sumaTotal += $total;
							?>
						</td>
						<td style="text-align:right">
							<?php
								if (!$no_iva)
								{
									$iva = ((($data->precios[0]->precio - (($aplica_descuento/100) * $data->precios[0]->precio)) * $data->cnotas[0]->cantidad_piezas) * ($data->cnotas[0]->aceptacion/100)) * ($data->precios[0]->anio->impuesto/100);
								}
								else
								{
									$iva = 0;
								}
								echo Yii::app()->locale->numberFormatter->formatCurrency($iva, "MXN");
								$sumaIVA += $iva;
							?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="10">
							<h4>Total</h4>
						</td>
						<td style="text-align: right;">
							<h4>
								<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaTotal, "MXN"); ?>
							</h4>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="10">
							<h4>IVA</h4>
						</td>
						<td style="text-align: right;">
							<h4>
								<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaIVA, "MXN"); ?>
							</h4>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="10">
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
			<?php endif; ?>

			<?php if ($nota->revision != Notas::REV_APROBADO): ?>
			<table class="stdtable">
				<thead>
					<tr>
						<th class="head1" style="width:5%; text-align:center">C&oacute;digo</th>
						<th class="head1" style="width:15%; text-align:center">Descripci&oacute;n</th>
						<th class="head1" style="width:5%; text-align:center">Cantidad</th>
						<th class="head1" style="width:10%; text-align:center">Precio Unitario</th>
						<th class="head1" style="width:10%; text-align:center">Importe</th>
						<th class="head1" style="width:10%; text-align:center">% Aceptaci&oacute;n</th>
						<th class="head1" style="width:10%; text-align:center">Total</th>
						<th class="head1" style="width:10%; text-align:center">I.V.A</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($productos->getData() as $data): ?>
					<?php
						$aplica_descuento = 0;
						$no_iva = false;
						if ($descuento !== null)
						{
							// Determinar para que tipo de productos tiene descuento
							if (in_array($data->codigo, Yii::app()->params['descuentos']['TREV']))
							{
								$aplica_descuento = $descuento->todas_trev;
								$no_iva = true;
							}
							else if (in_array($data->codigo, Yii::app()->params['descuentos']['PFD']))
							{
								$aplica_descuento = $descuento->pfd_notrev;
								$no_iva = true;
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
								$importetotal = (($data->precios[0]->precio - (($aplica_descuento/100) * $data->precios[0]->precio)) * $data->cnotas[0]->cantidad_piezas);
								echo Yii::app()->locale->numberFormatter->formatCurrency($importetotal, "MXN");
							?>
						</td>
						<td style="text-align:center">
							<?php echo $data->cnotas[0]->aceptacion.'%'; ?>
						</td>
						<td style="text-align:right">
							<?php
								$total = (($data->precios[0]->precio - (($aplica_descuento/100) * $data->precios[0]->precio)) * $data->cnotas[0]->cantidad_piezas) * ($data->cnotas[0]->aceptacion/100);
								echo Yii::app()->locale->numberFormatter->formatCurrency($total, "MXN");
								$sumaTotal += $total;
							?>
						</td>
						<td style="text-align:right">
							<?php
								if (!$no_iva)
								{
									$iva = ((($data->precios[0]->precio - (($aplica_descuento/100) * $data->precios[0]->precio)) * $data->cnotas[0]->cantidad_piezas) * ($data->cnotas[0]->aceptacion/100)) * ($data->precios[0]->anio->impuesto/100);
								}
								else
								{
									$iva = 0;
								}
								echo Yii::app()->locale->numberFormatter->formatCurrency($iva, "MXN");
								$sumaIVA += $iva;
							?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="7">
							<h4>Total</h4>
						</td>
						<td style="text-align: right;">
							<h4>
								<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaTotal, "MXN"); ?>
							</h4>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="7">
							<h4>IVA</h4>
						</td>
						<td style="text-align: right;">
							<h4>
								<?php echo Yii::app()->locale->numberFormatter->formatCurrency($sumaIVA, "MXN"); ?>
							</h4>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="7">
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
			<?php endif; ?>
		<?php elseif ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::DESCUENTOS): ?>
    		<div class="contenttitle2">
            	<h3>Descuentos por Marcas</h3>
            </div>
    		<table class="stdtable evtProductsTable">
				<thead>
					<tr>
						<th class="head1" style="width:15%; text-align:center">C&oacute;digo</th>
						<th class="head1" style="width:15%; text-align:center">Marca</th>
						<th class="head1" style="width:15%; text-align:center">Descuento (%)</th>
						<th class="head1" style="width:15%; text-align:center">I.V.A</th>
						<th class="head1" style="width:15%; text-align:center">Importe</th>
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
		<?php elseif ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::COOPERACION ): ?>
			<div class="contenttitle2">
            	<h3>Cooperaci&oacute;n por Marcas</h3>
            </div>
			<table class="stdtable">
				<thead>
					<tr>
						<th class="head1" style="width:20%; text-align:center">C&oacute;digo</th>
						<th class="head1" style="width:30%; text-align:center">Marca</th>
						<th class="head1" style="width:15%; text-align:center">Descuento (%)</th>
						<th class="head1" style="width:15%; text-align:center">I.V.A</th>
						<th class="head1" style="width:20%; text-align:center">Importe</th>
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
			<div class="contenttitle2">
            	<h3>Facturas</h3>
            </div>
			<?php  $this->widget('zii.widgets.grid.CGridView', array(
			    'id'=>'facturas-grid',
			    'dataProvider'=>$facturas,
			    'itemsCssClass' => 'stdtable',
				'emptyText'=>'No se encontraron facturas.',
				'enableSorting' => false,
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
						'header' => 'C&oacute;digo Producto',
						'value'  => '$data->codigo_producto',
						'htmlOptions' => array(
							'style' => 'width:15%;'
						),
						'headerHtmlOptions'=>array(
							'class' => 'head1',
							'style' => 'text-align:center'
						),
					),
					array(
						'type'   => 'raw',
						'name'   => 'descripcion',
						'header' => 'Descripci&oacute;n',
						'value'  => '$data->descripcion_producto',
						'htmlOptions' => array(
							'style' => 'width:30%;'
						),
						'headerHtmlOptions'=>array(
							'class' => 'head1',
							'style' => 'text-align:center'
						),
					),
					array(
						'type'   => 'raw',
						'name'   => 'cantidad_piezas',
						'header' => 'Cantidad',
						'value'  => '$data->cantidad_piezas',
						'htmlOptions' => array(
							'style' => 'width:10%;text-align:center'
						),
						'headerHtmlOptions'=>array(
							'class' => 'head1',
							'style' => 'text-align:center'
						),
					),
					array(
						'type'   => 'raw',
						'name'   => 'precio_unitario',
						'header' => 'Precio Unitario',
						'value'  => 'Yii::app()->locale->numberFormatter->formatCurrency($data->precio_unitario, "MXN")',
						'htmlOptions' => array(
							'style' => 'width:15%;text-align:right'
						),
						'headerHtmlOptions'=>array(
							'class' => 'head1',
							'style' => 'text-align:center'
						),
					),
					array(
						'type'   => 'raw',
						'header' => 'Importe',
						'value'  => 'Yii::app()->locale->numberFormatter->formatCurrency(($data->precio_unitario * $data->cantidad_piezas), "MXN")',
						'htmlOptions' => array(
							'style' => 'width:15%;text-align:right'
						),
						'headerHtmlOptions'=>array(
							'class' => 'head1',
							'style' => 'text-align:center'
						),
					),
					array(
						'type'   => 'raw',
						'name'   => 'costo_iva',
						'header' => 'I.V.A',
						'value'  => 'Yii::app()->locale->numberFormatter->formatCurrency(($data->costo_iva - ($data->precio_unitario * $data->cantidad_piezas)), "MXN")',
						'htmlOptions' => array(
							'style' => 'width:15%;text-align:right'
						),
						'headerHtmlOptions'=>array(
							'class' => 'head1',
							'style' => 'text-align:center'
						),
					)
			    )
			)); ?>
		<?php endif; ?>
	</div>
	
	<div class="noprint">
	    <div class="contenttitle2">
	    	<h3>Documentos Adjuntos</h3>
	    </div>
		<?php $this->widget('zii.widgets.grid.CGridView', array(
		    'id'=>'documentos-grid',
		    'dataProvider'=>$documentos,
		    'itemsCssClass' => 'stdtable',
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
					'headerHtmlOptions'=>array(
						'class' => 'head1'
					),
				),
				array(
					'type'   => 'raw',
					'name'=>'archivo',
					'header'=>'Nombre del Archivo',
					'value'=>'CHtml::link($data->archivo, Yii::app()->baseUrl."/protected/files/".$data->descripcion, array("target"=>"_blank"))',
					'htmlOptions' => array(
						'style' => 'word-wrap: break-word;'
					),
					'headerHtmlOptions'=>array(
						'class' => 'head1'
					),
				)
		    )
		)); ?>
	</div>
	
	<div class="noprint">
		<div class="contenttitle2">
	    	<h3>&Uacute;ltimo Comentario</h3>
	    </div>
		<div style="overflow-y:auto; height:250px; border: 1px solid #A5A5A5; padding:5px">
			<?php echo nl2br(CHtml::encode($nota->comentario)); ?>
		</div>
	</div>
	    
	<div class="noprint">
	    <div class="contenttitle2">
	    	<h3>Historial de Cambios</h3>
	    </div>
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

	<?php if ($nota->revision == Notas::REV_APROBADO): ?>
    <div>
	    <div class="contenttitle2">
	    	<h3>Aprobadores</h3>
	    </div>
	    <div class="printOnly" style="padding-top:30px">
	    	<h3>Nota autorizada por:</h3>
	    </div>
	    <div style="width:50%;">
	    	<?php
	    		$aprobadores = Tools::getAprobadores($nota->id);
	    		foreach ($aprobadores as $usuario)
	    		{
	    			echo '<p style="padding-bottom:25px; border-top:1px solid #cdcdcd">'.$usuario['nombre'].'</p>';
	    		}
	    	?>
	    </div>
    </div>
	<?php endif; ?>
</div>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerScript('sac.jquery', "
	$('.evtPrint').on('click', function(e){
		e.preventDefault();
		window.print();
	});
", CClientScript::POS_READY);
?>