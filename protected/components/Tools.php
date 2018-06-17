<?php
/**
 * Tools class file
 *
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-06-09
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 *
 */
class Tools
{
	private function Tools() { /* Evita que se instancie */ }

	/**
	 * [ellipsis description]
	 * @param  [type]  $text   [description]
	 * @param  integer $max    [description]
	 * @param  string  $append [description]
	 * @return [type]          [description]
	 */
	public static function ellipsis($text, $max=100, $append='&hellip;')
	{
		if (strlen($text) <= $max) return $text;
		$out = substr($text,0,$max);
		if (strpos($text,' ') === FALSE) return $out.$append;
		return preg_replace('/\w+$/','',$out).$append;
	}

	/**
	 * [print_array description]
	 * @author:  Qbit Mexhico
	 * @revised: Jackfiallos
	 * @date:    2013-07-14
	 * @link:    [link]
	 * @version: [version]
	 * @param    [type]      $array  [description]
	 * @return   [type]              [description]
	 */
	public static function print_array($array)
	{
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

 	/**
 	 * [print_model description]
 	 * @author:  Qbit Mexhico
 	 * @revised: Jackfiallos
 	 * @date:    2013-07-14
 	 * @link:    [link]
 	 * @version: [version]
 	 * @param    [type]      $model_or_array [description]
 	 * @return   [type]                      [description]
 	 */
    public static function print_model($model_or_array)
    {
        if(is_object($model_or_array))
        {
            Tools::print_array($model_or_array->getAttributes());
        }
        else
        {
            $array = array();
            foreach($model_or_array AS $model)
            {
                $array[] = $model->getAttributes();
            }
            Tools::print_array($array);
        }
    }

    /**
     * [Implode description]
     * oiplode array in object
     * @author nixho
     * @version [version]
     * @date    2014-06-25
     * @param   [type]     $array [description]
     * @param   [type]     $param [description]
     */
    public static function Implode($array, $param)
	{
		if( is_null($array) || empty($array) )
			return '0';

		$arrayImplode = array();

		while ( list($index,$value) = each($array)) {
			array_push( $arrayImplode , $value->$param );
		}

		return implode(',', $arrayImplode);
	}

    /**
     * [pintaRevision description]
     * @param  [type] $revision [description]
     * @return [type]           [description]
     */
    public static function pintaRevision($revision)
    {
        switch ($revision) {
            case Notas::REV_PENDIENTE:
                echo "<span class=\"label label-info\">Pendiente de aprobar</span>";    // 1. estatus inicial
                break;
            case Notas::REV_ACEPTADO:
                echo "<span class=\"label label-warning\">En aprobaci&oacute;n</span>";       // 2. de inicial a aceptado (aprobadores)
                break;
            case Notas::REV_APROBADO:
                echo "<span class=\"label label-success\">Finalizado</span>";           // 6.estatus final (sac stage 2)
                break;
            case Notas::REV_RECHAZADO:
                echo "<span class=\"label label-important\">Rechazado</span>";          // estatus de rechazo
                break;
            case Notas::REV_PROCESADO:
                echo "<span class=\"label label-inverse\">Procesado</span>";            // 3. de aceptado a procesado (supervisores)
                break;
            case Notas::REV_DEVOLUCIONES:
                echo "<span class=\"label label-inverse\">En Devoluciones</span>";      // 4. de procesado a devoluciones (sac stage 1)
                break;
            case Notas::REV_SAC:
                echo "<span class=\"label label-inverse\">En SAC</span>";               // 5. de devoluciones a sac (sac stage 2)
                break;
            default:
                echo "<span class=\"label label-info\">En aprobaci&oacute;n</span>";
                break;
        }
    }

    /**
     * [GetTotal description]
     * Obtienen el total de una nota
     * @author nixho
     * @version [version]
     * @date    2014-06-27
     * @param   [type]     $data [description]
     */
	public static function GetTotal($data)
	{
		$total = 0;
        $iva = 0;

		//  De acuerdo al tipo de caracteristica se ontiene el total
        //  Total tipo caracteristica por medio de prodcitos
        if ($data->razones->caracteristicas_tipo->caracteristicas->id == Caracteristicas::ALMACEN)
        {
			// Obtener los id de los precios de los productos


            // Seleccion de porductos relacion con año de la nota
            /*$lstProductos = Productos::model()->with('precios')->together()->findAll(array(
                'condition' => 'precios.anio_id = :agnio',
                'params' => array(
                    ':agnio' =>  $data->anio_id
                )
            ));*/

			$lstProductos = Productos::model()->with('cnotas','precios.cproductos')->together()->findAll(array(
				'condition' => 'cnotas.notas_id = :id AND cproductos.catClientes_codigo = :codigo AND precios.anio_id = :agnio',
				'params' => array(
					':id' => $data->id,
					':codigo' => $data->clientes_codigo,
					':agnio' => $data->anio_id
				)
			));

            // Calcular el total de  acuerdo al año
            while (list($index, $producto) = each($lstProductos))
            {
                $aplica_descuento = 0;

                //$total += (($producto->precios[0]->precio - (($aplica_descuento/100) * $producto->precios[0]->precio)) * $producto->cnotas[0]->cantidad_piezas) * ($producto->cnotas[0]->aceptacion/100);
                $iva = ((($producto->precios[0]->precio - (($aplica_descuento/100) * $producto->precios[0]->precio)) * $producto->cnotas[0]->cantidad_piezas) * ($producto->cnotas[0]->aceptacion/100)) * ($producto->precios[0]->anio->impuesto/100);
                $total += ((($producto->precios[0]->precio - (($aplica_descuento/100) * $producto->precios[0]->precio)) * $producto->cnotas[0]->cantidad_piezas) * ($producto->cnotas[0]->aceptacion/100)) + ($iva);
            }
        }
        elseif ($data->razones->caracteristicas_tipo->caracteristicas->id ==  Caracteristicas::DESCUENTOS)
        {
            $modelMarcas = MarcasHasDescuentos::model()->with('marcas')->together()->findAll(array(
                'condition' => 't.descuentos_id = :id',
                'order' => 't.id ASC',
                'params' => array(
                    ':id' => !empty($data->descuentos->id) ? $data->descuentos->id: 0
                )
            ));

            foreach($modelMarcas as $brand)
            {
                $iva += ((($data->descuentos->importe * $brand->importe) / 100) * (Yii::app()->params['iva']/100));
            }

            $total = $data->descuentos->importe + $iva;
        }
        elseif ($data->razones->caracteristicas_tipo->caracteristicas->id ==  Caracteristicas::COOPERACION)
        {
            $cooperacion = Cooperacion::model()->find(array(
                'condition' => 't.notas_id = :notas',
                'params' => array(
                    ':notas' => $data->id
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
                $iva += ((($brand->importe * $cooperacion->importe) / 100) * (Yii::app()->params['iva']/100));
            }

            $total = $data->cooperacion->importe + $iva;
        }
        elseif ($data->razones->caracteristicas_tipo->caracteristicas->id ==  Caracteristicas::REFACTURACION)
        {
            $lstFacturas =  Facturas::model()->findAll(array(
                'condition' => 't.folio=:folioFactura',
                'params' => array(
                    'folioFactura' => $data->num_factura
                )
            ));

            while (list($index, $factura) = each($lstFacturas))
            {
                //$total += $factura->costo_iva;
                $total += ($factura->precio_unitario * $factura->cantidad_piezas);
            }

            $total += ( $total * (Yii::app()->params['iva']/100));
        }

        return $total;
	}

    /**
     * [SaveNota description]
     * Guradar la nota
     * @author nixho
     * @version [version]
     * @date    2014-06-27
     * @param   [type]     $descripcion [description]
     * @param   [type]     $notas_id    [description]
     */
    public static function SaveNota($nota, $model)
    {
        // Obtner el nivel  de la nota y el último novel que puede tener la nota
        $nivel = Tools::GetNivelNota($nota->id);
        $flujoId = Tools::GetFlujoId($nota->id);
        $ultimoNivel = Tools::GetUltimoNivel($nota->id);

        // Variables para mandar el correo
        $usuarios = "";
        $subject = "Nota Aceptada";

        // Crear el nuevo flujo de la nota
        $notasHasFlujos = new NotasHasFlujos();
        $notasHasFlujos->notas_id = $nota->id;
        $notasHasFlujos->flujos_id = $flujoId;

        if (!empty($flujoId))
        {
            $notasHasFlujos->save(false);
        }
        else
        {
            Yii::log('El flujo id = '.$flujoId.' no existe.', CLogger::LEVEL_ERROR, 'application');
        }

        // La variable entry guardar el historial de la nota
        $comentarioAux = (($model->estatus == Notas::REV_ACEPTADO) ? 'Nota Aceptada' : 'Nota Rechazada');
        $nota->comentario = $comentarioAux." - ".$model->comentario;
        $nota->entry = $nota->comentario;

        // cambiar el estatus a procesado cuando se ha llegado al ultimo nivel
        if (($model->estatus == Notas::REV_ACEPTADO) && ($nivel == $ultimoNivel))
        {
            // Los supervisores son quienes cambiar a este estado solamente
            if (in_array($nota->razones->caracteristicas_tipo->id, array(1,2,3,4,5))) // D1 D2 D3 D4 D5
            {
                $nota->revision = Notas::REV_PROCESADO;
            }
            else
            {
                $nota->revision = Notas::REV_SAC;
            }
        }
        else
        {
            $nota->revision = $model->estatus;
        }

        // Si la nota es rechazada reiniciar el flujo  cambiar el estatus a borrador
        if ($model->estatus == Notas::REV_RECHAZADO)
        {
            $nota->estatus = Notas::ESTATUS_BORRADOR;

            // Eliminar los flujos  para poder reiniciar el flujo da la nota
            NotasHasFlujos::model()->deleteAll(array( 'condition' => 'notas_id=:id ',
                'params' => array(
                    'id' => $nota->id
                )
            ));

            // Mandar correo al usuario que genero la nota
            $usuario = Usuarios::model()->findByPk($nota->usuarios_id);

            if(!empty($usuario))
            {
                try
                {
                    // Mandar el correo  al  usuario que genmero la nota
                    $str = Yii::app()->controller->renderPartial('//templates/Nota', array('nota' => $nota), true);
                    Yii::import('application.extensions.phpMailer.yiiPhpMailer');
                    $mailer = new yiiPhpMailer;
                    $mailer->Ready("Nota Rechazada" , $str,
                        array(
                            'name'=>$usuario->nombre,
                            'email'=>$usuario->correo
                        )
                    );
                }
                catch(Excepcion $ex)
                {
                    $message = $ex->getMessage();
                    Yii::log($message, CLogger::LEVEL_ERROR, 'application');
                }
            }
        }

        $nota->save(false);
    }

    /**
     * [GetNivelNota description]
     * Regresa el nivel de la nota
     * @author nixho
     * @version [version]
     * @date    2014-06-27
     * @param   [type]     $notas_id [description]
     */
    public static function GetNivelNota( $notas_id )
    {
        return  ( 1 + NotasHasFlujos::model()->count(array(
                        'condition' => 'notas_id=:id_nota',
                        'params' =>  array( ':id_nota' => $notas_id )
                    )) );
    }


    /**
     * [GetFlujoId description]
     * Getlfujo id de la nota compramdo el nivel de la nota, total , caracteristicaTipo , caracteristica
     * tambien si tiene cantidad si es mayor o menor
     * @author nixho
     * @version [version]
     * @date    2014-06-27
     * @param   [type]     $notas_id [description]
     */
    public static function GetFlujoId( $notas_id )
    {
        $flujoId = 0;
        $nota = Notas::model()->findByPk($notas_id);
        $total = Tools::GetTotal( $nota );
        $nivel = Tools::GetNivelNota( $notas_id );

        $flujos  = Flujos::model()->with('caracteristicas_tipo')->findAll(array(
                        'condition' => ' t.nivel_aprobacion=:nivel AND t.caracteristicasTipo_id = :caracteristicasTipoId '.
                                        ' AND caracteristicas_tipo.caracteristicas_id = :caracteristicasId ',

                        'params' => array(
                            'nivel' => $nivel,
                            'caracteristicasTipoId' => $nota->razones->caracteristicas_tipo->id,
                            'caracteristicasId' => $nota->razones->caracteristicas_tipo->caracteristicas->id,
                        )
                    ));

        if( count($flujos) == 1 )
        {
            return $flujos[0]->id;
        }
        else if( count($flujos) > 1 )
        {
            while ( list($key,$value) = each($flujos)  ) {

                $codicion = trim( preg_replace("/[0-9]/", '',$value->expresion) );
                $catidad = preg_replace("/[^0-9]/", "", $value->expresion );

                if(  $codicion == ">" && $total > floatval($catidad))
                {
                    $flujoId = $value->id;
                    break;
                }
                else if( $total <=  floatval($catidad) ){
                    $flujoId = $value->id;
                    break;
                }
            }
        }

        return $flujoId;
    }

    /**
     * [GetUltimoNivel description]
     * Obtienen el ultimo nivel que puede tener un nota
     * @author nixho
     * @version [version]
     * @date    2014-06-27
     * @param   [type]     $notas_id [description]
     */
    public static function GetUltimoNivel( $notas_id )
    {
        $nota = Notas::model()->findByPk($notas_id);

        $flujos  = Flujos::model()->with('caracteristicas_tipo')->findAll(array(
                        'condition' => '  t.caracteristicasTipo_id = :caracteristicasTipoId '.
                                        ' AND caracteristicas_tipo.caracteristicas_id = :caracteristicasId ',
                        'params' => array(
                            'caracteristicasTipoId' => $nota->razones->caracteristicas_tipo->id,
                            'caracteristicasId' => $nota->razones->caracteristicas_tipo->caracteristicas->id,
                        ),
                        'order' => 'nivel_aprobacion desc'
                    ));

        return $flujos[0]->nivel_aprobacion;
    }


    /**
     * [GetUltimosNivele description]
     * This is a cool function
     * @author nixho
     * @version [version]
     * @date    2014-06-27
     */
    public static function GetUltimosNivele()
    {
        $sql =  " SELECT table1.idflujo FROM  ".
                " ( ".
                "    SELECT  ".
                "    catf.id as idflujo,  ".
                "    catct.caracteristicas_id as caracteristicaTipo,  ".
                "    catct.id as caracteristica,  ".
                "    catf.nivel_aprobacion as aprobacion ".
                "    FROM  catFlujos as catf ".
                "    left join catCaracteristicasTipo as catct on catf.caracteristicasTipo_id = catct.id ".
                " )as table1 Inner join ".
                " ( ".
                "  SELECT  ".
                "    catf.id as idflujo, ".
                "    catct.caracteristicas_id as caracteristicaTipo, ".
                "    catct.id as caracteristica,  ".
                "    max(catf.nivel_aprobacion) as aprobacion ".
                "    FROM  catFlujos as catf ".
                "    left join catCaracteristicasTipo as catct on catf.caracteristicasTipo_id = catct.id ".
                "    group by  catct.caracteristicas_id , catct.id ".
                " )as table2 ".
                " on  ".
                "   table1.caracteristicaTipo =  table2.caracteristicaTipo and ".
                "   table1.caracteristica =  table2.caracteristica and ".
                "   table1.aprobacion =  table2.aprobacion ";

        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $flujosIds = $command->queryAll();

        return  array_keys( CHtml::listData( $flujosIds ,'idflujo','idflujo') ) ;
    }

    /**
     * [UsuarioConNotaValida description]
     * Validar que el usuario pueda editar o ver la nota regresa falso
     * so el usuario logueado puede ver la nota y true si la puede editar
     * @author nixho
     * @version [version]
     * @date    2014-06-30
     * @param   [type]     $notaId [description]
     */
    public static function UsuarioConNotaValida($notaId)
    {
        // $criteria = new CDbCriteria;
        // $reglas =   UsuariosHasFlujos::model()->findAll(array(
        //                 'condition' => 'usuarios_id=:id',
        //                 'params' => array(
        //                     ':id' => Yii::app()->user->id
        //                 )
        //             ));
        // $having = "";
        // $count = count($reglas) - 1;

        // while ( list($key,$value) = each($reglas) ) {
        //     $having .= " ( caracteristicas_tipo.id = ".$value->cflujo->caracteristicasTipo_id;
        //     $having .= " AND nivel = ".$value->cflujo->nivel_aprobacion;
        //     $having .=  empty($value->cflujo->expresion) ? "" : " AND montoTotal ".$value->cflujo->expresion;
        //     $having .= ( $count != $key ) ? " ) OR" : " ) ";
        // }

        // $criteria->select = " * , ".
        //     " ( CASE caracteristicas_tipo.caracteristicas_id ".
        //     "       WHEN 1 THEN  ".
        //     "           ( Select sum(tblnhp.cantidad_piezas * ( tblpp.precio + ( tblpp.precio * (cata.impuesto/100) ))) ".
        //     "           From ".Productos::model()->tableName()." as catp ".
        //     "           left join ".ProductosPrecio::model()->tableName()." as tblpp on catp.id = tblpp.productos_id ".
        //     "           left join ".NotasHasProductos::model()->tableName()." as tblnhp on catp.id = tblnhp.productos_id ".
        //     "           left join ".Anio::model()->tableName()." as cata on cata.id  = tblpp.anio_id ".
        //     "           where tblpp.anio_id  = t.anio_id and  tblnhp.notas_id = t.id ".
        //     "           ) ".
        //     "       WHEN 2 THEN  ".
        //     "           (select des.importe From ".Descuentos::model()->tableName()." as des where des.notas_id = t.id) ".
        //     "       WHEN 3 THEN ".
        //     "           (select cop.importe From ".Cooperacion::model()->tableName()." as cop where cop.notas_id = t.id) ".
        //     "       ELSE  ".
        //     "           (select sum(catf.costo_iva) From ".Facturas::model()->tableName()." as catf where catf.folio = t.num_factura ) ".
        //     "   END  ".
        //     " ) as  montoTotal , ".
        //     " ( SELECT count(*) + 1 From ".NotasHasFlujos::model()->tableName()." as nhf where nhf.notas_id = t.id ) as nivel ";
        // $criteria->together = true;
        // $criteria->with  = array( 'usuarios' , 'clientes.empresas' , 'razones' , 'anio' , 'razones.caracteristicas_tipo' );
        // $criteria->condition = " t.estatus = :estatus AND t.id=:notaId  AND  ".
        //                        " empresas_empresas.empresas_id IN (".Yii::app()->user->getEmpresas().")";
        // $criteria->params = array(
        //     ':estatus' => Notas::ESTATUS_PUBLICADO,
        //     ':notaId' => $notaId
        // );
        // $criteria->having = $having;

        // return (Notas::model()->count($criteria) != 0);
        // Obtener todas las solicitudes

        $f = array();
        $bloqueadas = array(Notas::REV_APROBADO, Notas::REV_RECHAZADO, Notas::REV_PROCESADO, Notas::REV_DEVOLUCIONES);

        $solicitudes = Notas::model()->with('razones.caracteristicas_tipo')->together()->findAll(array(
            'condition' => 't.estatus = :estatus AND t.revision NOT IN('.implode(",", $bloqueadas).') AND t.id = :id',
            'params' => array(
                ':estatus' => Notas::ESTATUS_PUBLICADO,
                ':id' => $notaId
            )
        ));

        // Por cada solicitud encontrada, tomar su caracteristica tipo y determinar el flujo que corresponde
        foreach ($solicitudes as $solicitud)
        {

            // Esto es una excepcion ya que no es a nivel caracteristica sino a nivel razon
            if (( ($solicitud->razones->codigo == 'RF11') || ($solicitud->razones->codigo == 'RF12')) && !Yii::app()->user->verifyRole(Usuarios::SUPERVISOR) )
            {
                // Se tomara el primer usuario que se tenga para revision de D1 en nivel 1
                $nivel_aprobacion = 1;
                $UsuarioLogistica = Usuarios::model()->with('flujos.caracteristicas_tipo')->together()->find(array(
                    'condition' => 'caracteristicas_tipo.codigo = :codigo AND flujos.nivel_aprobacion = :nivel',
                    'params' => array(
                        ':codigo' => 'D1',
                        ':nivel' => '1',
                    )
                ));

                if ($UsuarioLogistica != null)
                {
                    array_push($f, $solicitud->id);
                }
            }

            $flujos = Flujos::model()->with('usuarios')->together()->findAll(array(
                'condition' => 't.caracteristicasTipo_id = :tipo AND usuarios.id = :id',
                'params' => array(
                    ':tipo' => $solicitud->razones->caracteristicas_tipo->id,
                    ':id' => Yii::app()->user->id
                )
            ));

            // Recorrer cada flujo
            foreach ($flujos as $flujo)
            {
                // Determinar el usuario de cada flujo para cada solicitud y crear el arreglo
                foreach ($flujo->usuarios as $usuario)
                {
                    $cumple = false;

                    // Si existe una expresion que validar, hacerlo para determinar que usuario debe atenderlo
                    if (!is_null($flujo->expresion))
                    {
                        if (eval('return (Tools::GetTotal($solicitud) '.$flujo->expresion.');'))
                        {
                            $cumple = true;
                        }
                    }
                    else
                    {
                        $cumple = true;
                    }

                    if ($cumple)
                    {
                        //array_push($f, $solicitud->id);

                        // Obtener el no. de flujos que ya revisaron la nota
                        $notaFlujo = NotasHasFlujos::model()->count(array(
                             'condition' => 't.notas_id = :notaId',
                             'params' => array(
                                ':notaId' => $solicitud->id
                             )
                        ));

                        // Si el no. de flujo + 1 = al nivel de aprobacion del usuario, entonces permitir mostrar la nota
                        if (((int)$notaFlujo + 1) == $flujo->nivel_aprobacion)
                        {
                            array_push($f, $solicitud->id);
                        }
                    }
                }
            }
        }

        return (in_array($notaId, $f)) ? true : false;
    }

    /**
     * [getAprobadores description]
     * Encuentra y retorna en un arreglo todos los
     * aprobadores de una nota determinada
     * @author Jackfiallos
     * @version [version]
     * @date    2014-09-02
     * @param   [type]     $id [description]
     */
    public static function getAprobadores($id)
    {
        $f = array();

        // Obtener la solicitud
        $solicitud = Notas::model()->with('razones.caracteristicas_tipo')->together()->findByPk($id);

        // Verificar que tenga datos
        if ($solicitud !== null)
        {
            $flujos = Flujos::model()->with('usuarios')->together()->findAll(array(
                'condition' => 't.caracteristicasTipo_id = :tipo',
                'params' => array(
                    ':tipo' => $solicitud->razones->caracteristicas_tipo->id
                )
            ));

            // Recorrer cada flujo
            foreach ($flujos as $flujo)
            {
                // Determinar el usuario de cada flujo para cada solicitud y crear el arreglo
                foreach ($flujo->usuarios as $usuario)
                {
                    $cumple = false;

                    // Si existe una expresion que validar, hacerlo para determinar que usuario debe atenderlo
                    if (!is_null($flujo->expresion))
                    {
                        if (eval('return (Tools::GetTotal($solicitud) '.$flujo->expresion.');'))
                        {
                            $cumple = true;
                        }
                    }
                    else
                    {
                        $cumple = true;
                    }

                    $solicitantes = AprobadoresHasSolicitantes::model()->findAll(array(
                        'condition' => 't.aprobador = :id',
                        'params' => array(
                            ':id' => $usuario->id
                        )
                    ));

                    $usuarios_solicitantes = array();
                    if ($solicitantes !== null)
                    {
                        foreach ($solicitantes as $solicitante)
                        {
                            array_push($usuarios_solicitantes, $solicitante->solicitante);
                        }
                    }

                    if (($cumple) && (in_array($solicitud->usuarios_id, $usuarios_solicitantes)))
                    {
                        if (!isset($f[$solicitud->id][$flujo->nivel_aprobacion]))
                        {
                            // Esto es una excepcion ya que no es a nivel caracteristica sino a nivel razon
                            if (($solicitud->razones->codigo == 'RF11') || ($solicitud->razones->codigo == 'RF12'))
                            {
                                // Se tomara el primer usuario que se tenga para revision de D1 en nivel 1
                                $nivel_aprobacion = 1;
                                $UsuarioLogistica = Usuarios::model()->with('flujos.caracteristicas_tipo')->together()->find(array(
                                    'condition' => 'caracteristicas_tipo.codigo = :codigo AND flujos.nivel_aprobacion = :nivel',
                                    'params' => array(
                                        ':codigo' => 'D1',
                                        ':nivel' => '1'
                                    )
                                ));

                                if ($UsuarioLogistica != null)
                                {
                                    $f[] = array(
                                        'id' => $UsuarioLogistica->id,
                                        'nombre' => $UsuarioLogistica->nombre
                                    );
                                }
                            }

                            $f[] = array(
                                'id' => $usuario->id,
                                'nombre' => $usuario->nombre
                            );
                        }
                    }
                }
            }
        }

        return $f;
    }
}
