<?php
/**
 * ValidateUser class file
 * 
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-06-09
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
class ValidateUser extends CWebUser
{
	/**
	 * Verificacion de privilegios
	 * @author:  Qbit Mexhico
	 * @revised: Jackfiallos
	 * @date:    2014-06-09
	 * @link:    [link]
	 * @version: [version]
	 * @return   [type]      [description]
	 */
	public function verifyRole($role)
	{
		if (Yii::app()->user->isGuest)
		{
			return false;
		}
		else
		{
			$user = Usuarios::model()->with('permisos')->together()->findByPk(Yii::app()->user->id);

			foreach ($user->permisos as $permiso)
			{
				if ($permiso->id == $role)
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * [verifyOwnership description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function verifyOwnership($id)
	{
		$nota = Notas::model()->findByPk($id);
		if ($nota !== null)
		{
			if ($nota->usuarios_id == Yii::app()->user->id)
			{
				return true;
			}
		}

		return false;
	}

	
	/**
	 * [getEmpresas description]
	 * regresa las empresas
	 * @author nixho
	 * @version [version]
	 * @param   [type]     $id [description]
	 * @return  [type]         [description]
	 */
	public function getEmpresas()
	{
		if (!Yii::app()->user->isGuest)
		{
			$user = Usuarios::model()->with('empresas')->together()->findByPk(Yii::app()->user->id);

			if(!empty($user->empresas))
				return Tools::Implode( $user->empresas , "id" );
		}

		return 0;
	}

	/**
	 * [getGrupo description]
	 * @return [type] [description]
	 */
	public function getGrupo()
	{
		if (!Yii::app()->user->isGuest)
		{
			$user = Usuarios::model()->with('grupo')->together()->findByPk(Yii::app()->user->id);

			if ($user !== null)
			{
				return strtolower($user->grupo->nombre);
			}
		}

		return null;
	}
}
?>