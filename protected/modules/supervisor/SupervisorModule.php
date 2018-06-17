<?php
/**
 * SupervisorModule class file
 * 
 * @autor		Jackfiallos
 * @link		http://qbit.com.mx
 * @copyright	Copyright (c) 2011 Qbit Mexhico
 * @description
 * 
 **/
class SupervisorModule extends CWebModule
{
	/**
	 * [beforeControllerAction description]
	 * @author:  Qbit Mexhico
	 * @revised: Jackfiallos
	 * @date:    2014-06-10
	 * @link:    [link]
	 * @version: [version]
	 * @param: 	 [type] $controller
	 * @param: 	 [type] $action
	 */
	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
