<?php
class Hempshades_Material_Block_Material extends Mage_Core_Block_Template
{

    public function _prepareLayout()
    {
        var_dump('test'); exit();
        return parent::_prepareLayout();
    }

    public function getDesign()
    {
        if (!$this->hasData('material'))
        {
            $this->setData('material', Mage::registry('material'));
        }
        return $this->getData('material');
    }

}
