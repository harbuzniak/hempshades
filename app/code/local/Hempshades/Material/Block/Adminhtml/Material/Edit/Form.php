<?php
class Hempshades_Material_Block_Adminhtml_Material_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {   
        parent::__construct();     
        $this->setId('hempshades_material_material_form');
        $this->setTitle($this->__('Fabric Information'));
    }   
     
    protected function _prepareForm()
    {   
        $model = Mage::registry('material_data');
        $data = $model->getData();
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        )); 
     
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('material')->__('Fabric Information'),
            'class'     => 'fieldset',
        )); 
     
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            )); 
        }   
        $note = '';

        $fieldset->addField('image_filename', 'file', array(
            'name'      => 'image_filename',
            'label'     => Mage::helper('material')->__('Image File (*.png;*.jpeg;*.jpg)'),
            'title'     => Mage::helper('material')->__('Image File (*.png;*.jpeg;*.jpg)'),
            'disabled' => false,
            'after_element_html' => '<br /><small>Upload your Image file here</small>',
        ));

        $fieldset->addField('material_name', 'text', array(
            'name'      => 'material_name',
            'label'     => Mage::helper('material')->__('Name'),
            'title'     => Mage::helper('material')->__('Name'),
            'required'  => false,
        ));
        
        $fieldset->addField('sku', 'text', array(
            'name'      => 'sku',
            'label'     => Mage::helper('material')->__('SKU'),
            'title'     => Mage::helper('material')->__('SKU'),
            'required'  => false,
        ));
        
        $fieldset->addField('material_description', 'textarea', array(
            'name'      => 'material_description',
            'label'     => Mage::helper('material')->__('Description'),
            'title'     => Mage::helper('material')->__('Description'),
            'required'  => false,
        ));
        
        $fieldset->addField('material_content', 'text', array(
            'name'      => 'material_content',
            'label'     => Mage::helper('material')->__('Content'),
            'title'     => Mage::helper('material')->__('Content'),
            'required'  => false,
        ));
        
        $fieldset->addField('material_stitchline_sq_ft_price', 'text', array(
            'name'      => 'material_stitchline_sq_ft_price',
            'label'     => Mage::helper('material')->__('Price per square foot'),
            'title'     => Mage::helper('material')->__('Price per square foot'),
            'required'  => false,
        ));

        $fieldset->addField('material_price_per_yard', 'text', array(
            'name'      => 'material_price_per_yard',
            'label'     => Mage::helper('material')->__('Price per yard'),
            'title'     => Mage::helper('material')->__('Price per yard'),
            'required'  => false,
        ));

        $fieldset->addField('material_width', 'text', array(
            'name'      => 'material_width',
            'label'     => Mage::helper('material')->__('Fabric width in inches'),
            'title'     => Mage::helper('material')->__('Fabric width in inches'),
            'required'  => false,
        ));

        $fieldset->addField('keywords', 'text', array(
            'name'      => 'keywords',
            'label'     => Mage::helper('material')->__('Keywords'),
            'title'     => Mage::helper('material')->__('Keywords'),
            'required'  => false,
            'after_element_html' => '<br /><small>Comma separated</small>',
        ));

        $form->setValues($data);
        $form->setUseContainer(true);
        $this->setForm($form);
     
        return parent::_prepareForm();
    }   
}