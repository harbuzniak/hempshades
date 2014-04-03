<?php
class Hempshades_Material_Block_Adminhtml_Material_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {

        parent::__construct();
        $this->setId('materialGrid');
        $this->setDefaultSort('material_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('material/material')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
//        $this->addColumn('material_id', array(
//            'header' => Mage::helper('material')->__('ID'),
//            'align' => 'right',
//            'width' => '50px',
//            'index' => 'material_id',
//        ));

        $this->addColumn('material_image', array(
            'header' => Mage::helper('material')->__('Image'),
            'align' => 'left',
            'index' => 'material_image',
            'filter' => false,
            'width' => '110px',
            'renderer' => 'Hempshades_Material_Block_Adminhtml_Material_Renderer_Image',
        ));

        $this->addColumn('material_name', array(
            'header' => Mage::helper('material')->__('Name'),
            'align' => 'left',
            'index' => 'material_name',
            'width' => '110px',
        ));
        
        $this->addColumn('material_description', array(
            'header' => Mage::helper('material')->__('Description'),
            'align' => 'left',
            'index' => 'material_description',
        ));
        
        $this->addColumn('material_content', array(
            'header' => Mage::helper('material')->__('Content'),
            'align' => 'left',
            'index' => 'material_content',
        ));
        
        $this->addColumn('material_stitchline_sq_ft_price', array(
            'header' => Mage::helper('material')->__('Price per square foot'),
            'align' => 'left',
            'index' => 'material_stitchline_sq_ft_price',
        ));

        $this->addColumn('material_price_per_yard', array(
            'header' => Mage::helper('material')->__('Price per yard'),
            'align' => 'left',
            'index' => 'material_price_per_yard',
        ));

        $this->addColumn('material_width', array(
            'header' => Mage::helper('material')->__('Width'),
            'align' => 'left',
            'index' => 'material_width',
        ));

        $this->addColumn('keywords', array(
            'header' => Mage::helper('material')->__('Keywords'),
            'align' => 'left',
            'index' => 'keywords',
        ));
        
        $this->addColumn('action', array(
            'header' => Mage::helper('material')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('material')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('material_id');
        $this->getMassactionBlock()->setFormFieldName('material');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('material')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('material')->__('Are you sure?')
        ));
        
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}