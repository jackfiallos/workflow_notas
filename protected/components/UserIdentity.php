<?php
/**
 * UserIdentity class file
 * 
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-06-09
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate($company = 0)
	{
		$pass_hash = '';
		$this->errorCode = self::ERROR_USERNAME_INVALID;

		$criteria = new CDbCriteria();
        $criteria->condition = 'LOWER(t.username) = :username AND t.estatus = :estatus';
        $criteria->params = array(
            ':username' => strtolower($this->username),
            ':estatus' => Usuarios::ACTIVO
        );
		
		$users = Usuarios::model()->with('permisos')->together()->find($criteria);

		// El usuario no se encuentra porque no existe o porque no selecciono la empresa correcta
		if($users !== null)
		{
			$pass_hash = PassHash::hash($users->clave);

			if (PassHash::check_password($pass_hash, $this->password))
			{
				$this->errorCode = self::ERROR_NONE;
				$this->_id = $users->id;
				Yii::app()->user->setState('username', $users->nombre);

				$date1 = strtotime($users->last_login);
				$date2 = time();
				$subTime = $date2 - $date1;
				$minutos = ($subTime / 60);

				/*var_dump($users->last_login)."<br />";
				var_dump($minutos)."<br />";
				var_dump($users->islogged)."<br />";

				die();*/

				if ((empty($users->last_login)) || ($minutos > 20) || ($users->islogged == 0))
				{
					if(empty($users->permisos))
					{
						$this->errorCode = self::ERROR_USERNAME_INVALID;
					}
					elseif ($users->permisos[0]->id != Usuarios::ADMIN)
					{
						// Buscar la relacion del usuario vs empresa
						$usuarioYempresa = UsuariosHasEmpresas::model()->find(array(
							'condition' => 't.usuarios_id = :usuario_id AND t.empresas_id = :empresa_id',
							'params' => array(
								':usuario_id' => $users->id,
								':empresa_id' => $company
							)
						));
						
						// si existe proceder a verificar el password
						if ($usuarioYempresa === null)
						{
							$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
						}
					}
				}
				else
				{
					$this->errorCode = self::ERROR_USERNAME_INVALID;
				}
			}

			if ($this->errorCode == self::ERROR_NONE)
			{
				$users->islogged = 1; // Logeado y en siteController/actionLogout se marcara nuevamente en 0
				$users->last_login = date("Y-m-d H:i:s");  // establecer la ultima fecha de inicio de sesion
				$users->save(false);
			}
		}

		return !$this->errorCode;
	}

	/**
	 * Return user unique id
	 * @return <integer> user_id
	 */
	public function getId()
	{
		return (int)$this->_id;
	}
}