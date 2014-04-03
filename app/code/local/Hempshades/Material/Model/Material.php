<?php

class Hempshades_Material_Model_Material extends Mage_Core_Model_Abstract {

    protected $_table;

    public function _construct() {
        parent::_construct();
        $this->_init('material/material');
        $this->_read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $this->_write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $this->_table = "material";
    }    

}