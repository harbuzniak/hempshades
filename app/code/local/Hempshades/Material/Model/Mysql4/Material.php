<?php
class Hempshades_Material_Model_Mysql4_Material extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('material/material', 'material_id');
    }
}