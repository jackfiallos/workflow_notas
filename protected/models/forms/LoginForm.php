<?php
/**
 * LoginForm class file
 * 
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-06-09
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;
	public $company;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('username, password, company', 'required', 'message'=>'<strong>{attribute}</strong> es requerido.'),
			array('company', 'numerical', 'integerOnly'=>true),
			array('rememberMe', 'boolean'),
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Recordarme',
			'username' => 'Nombre del usuario',
			'password' => 'Clave de acceso',
			'company' => 'Empresa'
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute, $params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity = new UserIdentity($this->username, $this->password);

			if(!$this->_identity->authenticate($this->company))
			{
				if ($this->_identity->errorCode == UserIdentity::ERROR_UNKNOWN_IDENTITY)
				{
					$this->addError('company', 'Usuario no permitido en esa empresa');
				}
				else
				{
					$this->addError('password', 'Email o clave de acceso incorrectos.');
				}
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity === null)
		{
			$this->_identity = new UserIdentity($this->username, $this->password);
			$this->_identity->authenticate($this->company);
		}

		if($this->_identity->errorCode === UserIdentity::ERROR_NONE)
		{
			$duration = ($this->rememberMe) ? 3600*24*30 : 1200; // 30 dias o 20 min
			Yii::app()->user->login($this->_identity, $duration);
			return true;
		}
		else
		{
			return false;
		}
	}
}
