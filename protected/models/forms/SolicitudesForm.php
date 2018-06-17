<?php
/**
 * SolicitudesForm class file
 *
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-06-09
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 *
 */
class SolicitudesForm extends CFormModel
{
	public $tipo_nota;
	public $caracteristica;
	public $razon;
	public $sucursal;
	public $cliente;
	public $anio;
	//public $catalogo_precios;
	public $importe;
	public $product_id;
	public $factura_id;
	public $marca_id;
	public $mes_aplicacion;
	public $fecha_vencimiento;
	public $comentario;
	public $estatus;
	public $cancela_sustituye;
	public $entry;

	public $errors = array();
	public $id;
	public $use_validations = false;

	/**
	 * Declares the validation rules.
	 * The rules state that usermail and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('cliente, tipo_nota, caracteristica, razon, fecha_vencimiento, anio', 'required', 'on'=>'almacen', 'message' => '<strong>{attribute}</strong> es requerido.'),
			array('cliente, tipo_nota, caracteristica, razon, importe, fecha_vencimiento', 'required', 'on'=>'descuento', 'message' => '<strong>{attribute}</strong> es requerido.'),
			array('cliente, tipo_nota, caracteristica, razon, importe, fecha_vencimiento, mes_aplicacion', 'required', 'on'=>'cooperacion', 'message' => '<strong>{attribute}</strong> es requerido.'),
			array('cliente, tipo_nota, caracteristica, razon, fecha_vencimiento, factura_id', 'required', 'on'=>'refacturacion', 'message' => '<strong>{attribute}</strong> es requerido.'),
			array('cliente, tipo_nota, caracteristica, razon, importe, fecha_vencimiento', 'required', 'on'=>'provision', 'message' => '<strong>{attribute}</strong> es requerido.'),
			array('comentario', 'required', 'on'=>'publish'),
			//array('catalogo_precios', 'length', 'max'=>10),
			array('cliente, sucursal, anio, tipo_nota, caracteristica, razon, product_id, marca_id, cancela_sustituye', 'numerical', 'integerOnly'=>true),
			//array('fecha_vencimiento', 'default', 'value'=>date("Y-m-d")),
			array('comentario, factura_id, mes_aplicacion, sucursal', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'cliente' => 'Cliente',
			'sucursal' => 'Sucursal',
			'anio' => 'Cat&aacute;logo de Productos',
			//'catalogo_precios' => 'Cat&aacute;logo de Precios',
			'tipo_nota' => 'Tipo de Nota',
			'caracteristica' => 'Caracter&iacute;stica',
			'razon' => 'Raz&oacute;n',
			'factura_id' => 'No. de Factura',
			'importe' => 'Importe (sin IVA)',
			'product_id' => 'Lista de Productos',
			'cancela_sustituye' => 'Cancelar y Sustituir Factura',
			'marca_id' => 'Lista de Marcas',
			'comentario' => 'Escriba aqu&iacute; sus comentarios',
			'mes_aplicacion' => 'Mes de aplicaci&oacute;n',
			'fecha_vencimiento' => 'Fecha de Finalizaci&oacute;n'
		);
	}

	/**
	 * [save description]
	 * @param  integer $id      [description]
	 * @param  boolean $publish [description]
	 * @return [type]           [description]
	 */
	public function save($id=0, $publish=false)
	{
		$model = new Notas();

		if (!empty($this->factura_id))
		{
			$factura = Facturas::model()->findByAttributes(array(
				'folio' => $this->factura_id,
			));

			if ($factura === null)
			{
				$this->addError('factura_id', 'Folio de factura equivocado');
				return false;
			}
		}

		// Actualizar registro existente
		if ($id > 0)
		{
			$model = Notas::model()->findByPk($id);
			$model->estatus = $this->estatus;
			$model->revision = Notas::REV_PENDIENTE;
			$model->entry = $this->entry;

			if ($publish)
			{
				// Si la nota es refacturacion con RF13 o RF14 para que los supervisores las puedan ver deben de estar como procesadas
				if (($model->razones->codigo == 'RF13') || ($model->razones->codigo == 'RF14'))
				{
					$model->revision = Notas::REV_ACEPTADO;
				}
				$model->comentario = $this->comentario;
			}
			else
			{
				$model->fecha_vencimiento = $this->fecha_vencimiento;
				$model->num_factura = $this->factura_id;
				$model->anio_id = $this->anio;
				//$model->indice = $this->catalogo_precios;
				$model->usuarios_id = Yii::app()->user->id;
				$model->clientes_codigo = $this->cliente;
				$model->razones_id = $this->razon;
				$model->revision = Notas::REV_PENDIENTE; // porque en esta etapa siempre la revision deberia quedar en 0
				$model->cancela_sustituye = $this->cancela_sustituye;
			}

			// Almacenar los cambios en la nota
			if ($model->save())
			{
				// verificar el tipo de nota
				if ($this->tipo_nota == Caracteristicas::DESCUENTOS)
				{
					if (!$publish)
					{
						$descuento = Descuentos::model()->find(array(
							'condition' => 't.notas_id = :notas',
							'params' => array(
								':notas' => $model->id
							)
						));

						if ($descuento !== null)
						{
							$descuento->mes_aplicacion = $this->mes_aplicacion;
							$descuento->importe = $this->importe;
							$descuento->save(false);
						}
						else
						{
							$nuevodescuento = new Descuentos();
							$nuevodescuento->importe = $this->importe;
							$nuevodescuento->mes_aplicacion = $this->mes_aplicacion;
							$nuevodescuento->notas_id = $model->id;
							$nuevodescuento->save(false);
						}
					}
				}
				else if ($this->tipo_nota == Caracteristicas::COOPERACION)
				{
					if (!$publish)
					{
						$cooperacion = Cooperacion::model()->find(array(
							'condition' => 't.notas_id = :notas',
							'params' => array(
								':notas' => $model->id
							)
						));

						if ($cooperacion !== null)
						{
							$cooperacion->mes_aplicacion = $this->mes_aplicacion;
							$cooperacion->importe = $this->importe;
							$cooperacion->save(false);
						}
						else
						{
							$nuevacooperacion = new Cooperacion();
							$nuevacooperacion->importe = $this->importe;
							$nuevacooperacion->mes_aplicacion = $this->mes_aplicacion;
							$nuevacooperacion->notas_id = $model->id;
							$nuevacooperacion->save(false);
						}
					}
				}
				$this->id = $model->id;
				return true;
			}
			else
			{
				$this->errors = $model->getErrors();
			}
		}
		else // Procesar un nuevo registro
		{
 			$model->fecha_vencimiento = $this->fecha_vencimiento;
 			$model->estatus = Notas::ESTATUS_BORRADOR;
 			$model->num_factura = $this->factura_id;
 			$model->anio_id = $this->anio;
 			//$model->indice = $this->catalogo_precios;
 			$model->comentario = $this->comentario;
 			$model->usuarios_id = Yii::app()->user->id;
			$model->clientes_codigo = $this->cliente;
			$model->razones_id = $this->razon;
			$model->cancela_sustituye = $this->cancela_sustituye;
			$model->entry = 'Registro Creado';

			if ($model->save())
			{
				if ($this->tipo_nota == Caracteristicas::DESCUENTOS)
				{
					$descuento = new Descuentos();
					$descuento->importe = $this->importe;
					$descuento->mes_aplicacion = $this->mes_aplicacion;
					$descuento->notas_id = $model->id;
					$descuento->save(false);
				}
				else if ($this->tipo_nota == Caracteristicas::COOPERACION)
				{
					$cooperacion = new Cooperacion();
					$cooperacion->importe = $this->importe;
					$cooperacion->mes_aplicacion = $this->mes_aplicacion;
					$cooperacion->notas_id = $model->id;
					$cooperacion->save(false);
				}

				$this->id = $model->id;
				return true;
			}
			else
			{
				$this->errors = $model->getErrors();
			}
		}

		return false;
	}

	/**
	 * [afterValidate description]
	 * @return [type] [description]
	 */
	public function afterValidate()
	{
		$sumaImportes = 0;

		if ($this->use_validations)
		{
			if ((int)$this->tipo_nota == Caracteristicas::DESCUENTOS)
			{
				// Encontrar todos los descuentos
				$descuentos = Descuentos::model()->with('cmarcas')->together()->findAll(array(
					'condition' => 't.notas_id = :id',
					'params' => array(
						':id' => $this->id
					)
				));

				// Si existen descuentos
				if (($descuentos !== null) && (count($descuentos) > 0 ))
				{
					// Recorrer cada descuento y sumar los porcentajes asignados
					foreach ($descuentos[0]->cmarcas as $marcas)
					{
						// Verificar que ya tiene alguna marca y un porcentaje asignado
						$sumaImportes += $marcas->importe;
					}

					// Solamente si la sumatoria es igual a 100 se podra enviar a revision
					if ($sumaImportes != 100)
					{
						$this->addError('importe', 'Para enviar una nota se debe de asignar el 100% de su importe entre las diferentes marcas.');
					}
				}
				else
				{
					$this->addError('importe', 'Para enviar una nota se debe de asignar el 100% de su importe entre las diferentes marcas.');
				}
			}
			else if ((int)$this->tipo_nota == Caracteristicas::COOPERACION)
			{
				// Encontrar todos las cooperacion
				$cooperaciones = Cooperacion::model()->with('cmarcas')->together()->findAll(array(
					'condition' => 't.notas_id = :id',
					'params' => array(
						':id' => $this->id
					)
				));

				// Si existen cooperaciones
				if (($cooperaciones !== null) && (count($cooperaciones) > 0 ))
				{
					// Recorrer cada cooperacion y sumar los porcentajes asignados
					foreach ($cooperaciones[0]->cmarcas as $marcas)
					{
						$sumaImportes += $marcas->importe;
					}

					// Solamente si la sumatoria es igual a 100 se podra enviar a revision
					if ($sumaImportes != 100)
					{
						$this->addError('importe', 'Para enviar una nota se debe de asignar el 100% de su importe entre las diferentes marcas.');
					}
				}
				else
				{
					$this->addError('importe', 'Para enviar una nota se debe de asignar el 100% de su importe entre las diferentes marcas.');
				}
			}
		}

		return parent::afterValidate();
	}
}
