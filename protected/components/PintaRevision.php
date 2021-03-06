<?php
 
/**
 * Tools class file
 * 
 * @author      Qbit Mexhico
 * @revised     nixho
 * @date        2014-06-09
 * @link:       [link]
 * @version:    [version]
 * @copyright   Copyright (c) 2011 Qbit Mexhico
 * 
 */
Yii::import('zii.widgets.grid.CGridColumn');

class PintaRevision extends CGridColumn {
 
    private $_attr  = null;
    public $name;

    public function getAttribute()
    {
        return $this->_attr;
    }

    public function setAttribute($value)
    {
        $this->_attr = $value;
    }
    
    /**
     * [renderDataCellContent description]
     * This is a cool function
     * @author nixho
     * @version [version]
     * @date    2014-06-23
     * @param   [type]     $row  [description]
     * @param   [type]     $data [description]
     * @return  [type]           [description]
     */
    public function renderDataCellContent($row, $data) {
        Tools::pintaRevision($data->revision);
    }
}