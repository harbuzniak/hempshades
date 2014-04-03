<?php
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('material')};
CREATE TABLE {$this->getTable('material')} (
  `material_id` int(11) unsigned NOT NULL auto_increment,
  `material_name` varchar(255) NOT NULL default '',
  `material_description` varchar(255) NOT NULL default '',
  `material_content` varchar(255) NOT NULL default '',
  `material_stitchline_sq_ft_price` decimal(12,2) NOT NULL default 0,
  `material_price_per_yard` decimal(12,2) NOT NULL default 0,
  `material_width` decimal(12,2) NOT NULL default 0,
  `material_image` varchar(255) NOT NULL default '',
  `sku` varchar(255) NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`material_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");


$attributes = array('material');
$labels = array('Material');
$count = 100;
$objCatalogEavSetup = Mage::getResourceModel('catalog/eav_mysql4_setup', 'core_setup');

foreach ($attributes as $key => $code) {    
    $attributeIsset = $objCatalogEavSetup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $code);    
    if ($attributeIsset === false) {
        $objCatalogEavSetup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $code, array(
            'group' => 'General',
            'sort_order' => $count,
            'type' => 'varchar',
            'backend' => '',
            'frontend' => '',
            'label' => $labels[$key],
            'note' => '',
            'input' => 'text',
            'class' => '',
            'source' => '',
            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => '0',
            'visible_on_front' => false,
            'unique' => false,
            'is_configurable' => false,
            'used_for_promo_rules' => true
        ));
        
        $count++;
    }        
}

$installer->endSetup(); 
