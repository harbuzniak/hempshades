<?php
class Hempshades_Material_Block_Adminhtml_Material_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        $value = trim($row->getData($this->getColumn()->getIndex()));
        if($value != ''){
            $pngPath = Mage::getBaseURL('media') . 'fabric_images' . DS;
            $value = '<img src="' . $pngPath . $value . '" style="width:100px;height:100px;"/>';
        }
        
        return $value;
    }
    
}