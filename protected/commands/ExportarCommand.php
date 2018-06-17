<?php
//php cron.php exportar > file.cvs
class ExportarCommand extends CConsoleCommand
{
	/**
	 * [run description]
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
    public function run($args)
	{
		$response = array();
		$gamas = array('RAVSOL', 'RAVAAG', 'RAVCLE', 'RELCEL');

		try
		{
			$criteria = new CDbCriteria();
			//$criteria->condition = 't.id = 7000001';
			$criteria->condition = 't.revision = :revision AND t.procesado = :procesado';
			$criteria->params = array(
				':revision' => Notas::REV_APROBADO,
				':procesado' => 0
			);

			$notas = Notas::model()->with('usuarios','razones.caracteristicas_tipo.caracteristicas','clientes.sucursal','anio','descuentos')->together()->findAll($criteria);;

			foreach ($notas as $nota)
			{
				if ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::ALMACEN)
				{
					$productos = Productos::model()->with('precios','cnotas')->together()->findAll(array(
						'condition' => 'cnotas.notas_id = :id AND precios.anio_id=:agnio ',
						'params' => array(
			                ':id' =>  $nota->id,
			                ':agnio' =>  $nota->anio_id
			            )
					));

					foreach ($productos as $producto)
					{
						echo implode(';', array(
							'orden_venta' => str_pad(substr($nota->id, 0, 10), 10, " ", STR_PAD_LEFT),										// #1 id nota
							'tipo_orden' => $nota->tipo_orden,																				// #2 tipo de orden (con o sin movimiento de stock)
							'cliente_factura' => str_pad(substr($nota->clientes->codigo, 0, 10), 10, " ", STR_PAD_LEFT),					// #3
							'cliente_entrega' => str_pad(substr($nota->clientes->sucursal[0]->nombre, 0 , 10), 10, " ", STR_PAD_LEFT),		// #4
							'orden_compra' => str_pad(substr($nota->ordenCompra, 0, 20), 20, " ", STR_PAD_LEFT),							// #5
							'entrada_almacen' => str_pad(substr($nota->entrada_almacen, 0, 20), 20, " ", STR_PAD_LEFT),						// #6
							'comentario_factura' => str_pad(substr($nota->descripcion, 0, 25), 25, " ", STR_PAD_LEFT),						// #7
							'centro_costo' => str_pad('CLIENT', 6, " ", STR_PAD_LEFT),														// #8
							'producto' => str_pad('VTAS', 6, " ", STR_PAD_LEFT),															// #9
							'proyecto' => str_pad('00', 6, " ", STR_PAD_LEFT),																// #10
							'auxiliar_facturacion' => str_pad($nota->razones->cuenta, 4, " ", STR_PAD_LEFT),								// #11
							'codigo_stock' => str_pad(substr($producto->codigo, 0, 10), 10, " ", STR_PAD_LEFT),							// #12
							'precio_unitario' => str_pad(substr($producto->precios[0]->precio, 0, 10), 10, " ", STR_PAD_LEFT),				// #13
							'descuento' => str_pad('0', 10, " ", STR_PAD_LEFT),																// #14
							'almacen_entrada_pf' => str_pad(substr($producto->cnotas[0]->almacen, 0, 10), 10, " ", STR_PAD_LEFT),				// #15
							'lote_vendedor' => str_pad(substr($producto->cnotas[0]->num_lote, 0, 12), 12, " ", STR_PAD_LEFT),					// #16
						))."\n";
					}

					//$nota->procesado = 1;
					//$nota->save(false);
				}
				else if ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::DESCUENTOS)
				{
					$modelMarcas = MarcasHasDescuentos::model()->with('marcas')->together()->findAll(array(
						'condition' => 't.descuentos_id = :id',
						'order' => 't.id ASC',
						'params' => array(
							':id' => !empty($nota->descuentos->id) ? $nota->descuentos->id: 0
						)
					));

					foreach($modelMarcas as $brand)
					{
						$cuenta = 'B004';

						echo implode(';', array(
							'orden_venta' => str_pad(substr($nota->id, 0, 10), 10, " ", STR_PAD_LEFT),										// #1 id nota
							'tipo_orden' => $nota->tipo_orden,																				// #2 tipo de orden (con o sin movimiento de stock)
							'cliente_factura' => str_pad(substr($nota->clientes->codigo, 0, 10), 10, " ", STR_PAD_LEFT),					// #3
							'cliente_entrega' => str_pad(substr($nota->clientes->sucursal[0]->nombre, 0 , 10), 10, " ", STR_PAD_LEFT),		// #4
							'orden_compra' => str_pad(substr($nota->ordenCompra, 0, 20), 20, " ", STR_PAD_LEFT),							// #5
							'entrada_almacen' => str_pad(substr($nota->entrada_almacen, 0, 20), 20, " ", STR_PAD_LEFT),						// #6
							'comentario_factura' => str_pad(substr($nota->descripcion, 0, 25), 25, " ", STR_PAD_LEFT),						// #7
							'centro_costo' => str_pad('CLIENT', 6, " ", STR_PAD_LEFT),														// #8
							'producto' => str_pad('VTAS', 6, " ", STR_PAD_LEFT),															// #9
							'proyecto' => str_pad('00', 6, " ", STR_PAD_LEFT),																// #10
							'auxiliar_facturacion' => str_pad($cuenta, 4, " ", STR_PAD_LEFT),												// #11
							'codigo_stock' => str_pad(substr($brand->marcas->codigo, 0 , 10), 10, " ", STR_PAD_LEFT),						// #12
							'precio_unitario' => str_pad(substr($brand->importe, 0 , 10), 10, " ", STR_PAD_LEFT),							// #13
							'descuento' => str_pad('0', 10, " ", STR_PAD_LEFT),																// #14
							'almacen_entrada_pf' => str_pad(substr('', 0, 10), 10, " ", STR_PAD_LEFT),										// #15
							'lote_vendedor' => str_pad(substr('', 0, 12), 12, " ", STR_PAD_LEFT),											// #16
						))."\n";
					}

					// $nota->procesado = 1;
					// $nota->save(false);
				}
				else if ($nota->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::COOPERACION)
				{
					$cooperacion = Cooperacion::model()->find(array(
						'condition' => 't.notas_id = :notas',
						'params' => array(
							':notas' => $nota->id
						)
					));

					$modelMarcas = MarcasHasCooperacion::model()->with('marcas')->together()->findAll(array(
						'condition' => 't.cooperacion_id = :id',
						'params' => array(
							':id' => $cooperacion->id
						)
					));

					foreach($modelMarcas as $brand)
					{
						$cuenta = 'B001';
						if (in_array($brand->marcas->codigo, $gamas))
						{
							$cuenta = 'B004';
						}

						echo implode(';', array(
							'orden_venta' => str_pad(substr($nota->id, 0, 10), 10, " ", STR_PAD_LEFT),										// #1 id nota
							'tipo_orden' => $nota->tipo_orden,																				// #2 tipo de orden (con o sin movimiento de stock)
							'cliente_factura' => str_pad(substr($nota->clientes->codigo, 0, 10), 10, " ", STR_PAD_LEFT),					// #3
							'cliente_entrega' => str_pad(substr($nota->clientes->sucursal[0]->nombre, 0 , 10), 10, " ", STR_PAD_LEFT),		// #4
							'orden_compra' => str_pad(substr($nota->ordenCompra, 0, 20), 20, " ", STR_PAD_LEFT),							// #5
							'entrada_almacen' => str_pad(substr($nota->entrada_almacen, 0, 20), 20, " ", STR_PAD_LEFT),						// #6
							'comentario_factura' => str_pad(substr($nota->descripcion, 0, 25), 25, " ", STR_PAD_LEFT),						// #7
							'centro_costo' => str_pad('CLIENT', 6, " ", STR_PAD_LEFT),														// #8
							'producto' => str_pad('VTAS', 6, " ", STR_PAD_LEFT),															// #9
							'proyecto' => str_pad('00', 6, " ", STR_PAD_LEFT),																// #10
							'auxiliar_facturacion' => str_pad($cuenta, 4, " ", STR_PAD_LEFT),												// #11
							'codigo_stock' => str_pad(substr($brand->marcas->codigo, 0 , 10), 10, " ", STR_PAD_LEFT),						// #12
							'precio_unitario' => str_pad(substr($brand->importe, 0 , 10), 10, " ", STR_PAD_LEFT),							// #13
							'descuento' => str_pad('0', 10, " ", STR_PAD_LEFT),																// #14
							'almacen_entrada_pf' => str_pad(substr('', 0, 10), 10, " ", STR_PAD_LEFT),										// #15
							'lote_vendedor' => str_pad(substr('', 0, 12), 12, " ", STR_PAD_LEFT),											// #16
						))."\n";
					}

					// $nota->procesado = 1;
					// $nota->save(false);
				}
				else
				{
					$facturas = Facturas::model()->with('clientes')->together()->findAll(array(
						'condition' => 't.folio = :folio',
						'params' => array(
							':folio' => $nota->num_factura
						)
					));

					foreach($facturas as $producto)
					{
						$leyendaCancelar = (($nota->cancela_sustituye == 1) ? 'Cancela y Sustituye' : $nota->descripcion);

						echo implode(';', array(
							'orden_venta' => str_pad(substr($nota->id, 0, 10), 10, " ", STR_PAD_LEFT),										// #1 id nota
							'tipo_orden' => $nota->tipo_orden,																				// #2 tipo de orden (con o sin movimiento de stock)
							'cliente_factura' => str_pad(substr($nota->clientes->codigo, 0, 10), 10, " ", STR_PAD_LEFT),					// #3
							'cliente_entrega' => str_pad(substr($nota->clientes->sucursal[0]->nombre, 0 , 10), 10, " ", STR_PAD_LEFT),		// #4
							'orden_compra' => str_pad(substr($nota->ordenCompra, 0, 20), 20, " ", STR_PAD_LEFT),							// #5
							'entrada_almacen' => str_pad(substr($nota->entrada_almacen, 0, 20), 20, " ", STR_PAD_LEFT),						// #6
							'comentario_factura' => str_pad(substr($leyendaCancelar, 0, 25), 25, " ", STR_PAD_LEFT),						// #7
							'centro_costo' => str_pad('CLIENT', 6, " ", STR_PAD_LEFT),														// #8
							'producto' => str_pad('VTAS', 6, " ", STR_PAD_LEFT),															// #9
							'proyecto' => str_pad('00', 6, " ", STR_PAD_LEFT),																// #10
							'auxiliar_facturacion' => str_pad($nota->razones->cuenta, 4, " ", STR_PAD_LEFT),								// #11
							'codigo_stock' => str_pad(substr($producto->codigo_producto, 0 , 10), 10, " ", STR_PAD_LEFT),						// #12
							'precio_unitario' => str_pad(substr($producto->precio_unitario, 0 , 10), 10, " ", STR_PAD_LEFT),				// #13
							'descuento' => str_pad('0', 10, " ", STR_PAD_LEFT),																// #14
							'almacen_entrada_pf' => str_pad(substr('', 0, 10), 10, " ", STR_PAD_LEFT),				// #15
							'lote_vendedor' => str_pad(substr('', 0, 12), 12, " ", STR_PAD_LEFT),					// #16
						))."\n";
					}

					// $nota->procesado = 1;
					// $nota->save(false);
				}
			}
		}
		catch (Exception $exc)
		{
			$message = $exc->getMessage();
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}
    }
}
