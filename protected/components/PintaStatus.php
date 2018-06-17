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

class PintaStatus extends CGridColumn {
 
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
        switch ($data->estatus) {
            case Notas::ESTATUS_BORRADOR:
                echo "<span class=\"label label-default\">Borrador</span>";
                break;
            case Notas::ESTATUS_PUBLICADO:
                echo "<span class=\"label label-publish\">Enviado</span>";
                break;
            default:
                echo "<span class=\"label\">Borrador</span>";
                break;
        }
    }
}