<?php
class Hempshades_Material_Block_Adminhtml_Material_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'material';
        $this->_controller = 'adminhtml_material';
        $this->_updateButton('save', 'label', Mage::helper('material')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('material')->__('Delete Item'));   

        if (Mage::registry('material_data') && Mage::registry('material_data')->getId()){
            $this->addButton('createproduct', array(
                'label' => Mage::helper('adminhtml')->__('Create Product'),
                'class' => 'save',
                'onclick' => "setLocation('" . Mage::helper("adminhtml")->getUrl('material/adminhtml_material/createproduct/id/'.Mage::registry('material_data')->getData('material_id')) . "')",
                    ), -100);
        }
    }

    public function getHeaderText()
    {
        if (Mage::registry('material_data') && Mage::registry('material_data')->getId()){
            return Mage::helper('material')->__("Edit Fabric");
        }else{
            return Mage::helper('material')->__('Add Fabric');
        }
    }

}