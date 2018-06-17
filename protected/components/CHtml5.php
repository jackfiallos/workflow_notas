<?php
/**
 * CHtml5 class file
 * 
 * @author      Qbit Mexhico
 * @revised     Jackfiallos
 * @date        2014-06-09
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
class CHtml5 extends CHtml
{
	/**
	 * [activePhoneField description]
	 * @author:  Qbit Mexhico
	 * @revised: Jackfiallos
	 * @date:    2013-07-18
	 * @link:    [link]
	 * @version: [version]
	 * @param    [type]      $model       [description]
	 * @param    [type]      $attribute   [description]
	 * @param    array       $htmlOptions [description]
	 * @return   [type]                   [description]
	 */
    public static function activePhoneField($model, $attribute, $htmlOptions=array())
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        self::clientChange('change', $htmlOptions);
        return self::activeInputField('tel', $model, $attribute, $htmlOptions);
    }

    /**
     * [activeEmailField description]
     * @author:  Qbit Mexhico
     * @revised: Jackfiallos
     * @date:    2013-07-18
     * @link:    [link]
     * @version: [version]
     * @param    [type]      $model       [description]
     * @param    [type]      $attribute   [description]
     * @param    array       $htmlOptions [description]
     * @return   [type]                   [description]
     */
    public static function activeEmailField($model, $attribute, $htmlOptions=array())
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        self::clientChange('change', $htmlOptions);
        return self::activeInputField('email', $model, $attribute, $htmlOptions);
    }

    /**
     * [activeEmailField description]
     * @author:  Qbit Mexhico
     * @revised: Jackfiallos
     * @date:    2013-07-18
     * @link:    [link]
     * @version: [version]
     * @param    [type]      $model       [description]
     * @param    [type]      $attribute   [description]
     * @param    array       $htmlOptions [description]
     * @return   [type]                   [description]
     */
    public static function activeNumberField($model, $attribute, $htmlOptions=array())
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        self::clientChange('change', $htmlOptions);
        return self::activeInputField('number', $model, $attribute, $htmlOptions);
    }
}
?>