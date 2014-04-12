<?php

class Hempshades_Customizer_Model_Customizer extends Mage_Core_Model_Abstract {


    protected function _construct() {
        $this->_init('customizer/customizer');
        $this->_read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $this->_write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $this->_user = (int)Mage::getSingleton('customer/session')->getId();
    }
    
    public function send($data){
        echo json_encode($data);
    }
    
    public function error($message){
        $data = array(
                'error' => true, 
                'message' => $message
            );
        return $this->send($data);
    }
    
    public function generateAddToCartURL($product_id, $data){
        $product = Mage::getModel('catalog/product')->load($product_id);
        $options = $product->getOptions();                            
        foreach ($options as $o) {
            if ($o->getTitle() == 'Material'){
                $materials = $o->getValues();
                $material_id = $o->getId();
                break;
            }
        }
        $selected_material_id = 0;
        $preselected_material_id = (int)$product->getData('preselected_option_id');
        $count = 0;
        foreach ($materials as $idx => $material) {
            if($count == $preselected_material_id){
                $selected_material_id = $material->getOptionTypeId();
                break;
            }
            $count++;
        }        
        
        $qty = (int)$data['qty'];
        if($qty <= 0){
            $qty = 1;
        }        
                
        $url = rtrim(Mage::getUrl('checkout/cart/add'), '/');
        $url .= '?tdtk=1&product=' . $product->getId() . '&qty='.$qty;   
        $url .= '&options['.$material_id.']='.$selected_material_id;
        
        return $url;
    }
    
    public function createProduct($material_id, $params) {
        $material = Mage::getModel('material/material')->load($material_id);
        if(!$material){
            return false;
        }
        $data = $material->getData();
                
        $sku = trim($data['sku']);
        $sku = 'HS_SHADES_' . str_pad($material_id, 3, '0', STR_PAD_LEFT).'_'.$sku;

        $skuExist = Mage::getModel('catalog/product')->getIdBySku($sku);
        if($skuExist){
            $sku .= '_' . time();
        }        
        
        $qty = 10000;        

        $visibility = array(
            'thumbnail',
            'small_image',
            'image'
        );
        $iw = $this->_getDecimalFraction($params['width']);
        $ih = $this->_getDecimalFraction($params['height']);
        $SQ_FT =($iw*$ih/144);
        $price = $material->getMaterialStitchlineSqFtPrice()*$SQ_FT; // price will be set in options
                
        $pngPath = Mage::getBaseDir('media') . DS . 'fabric_images' . DS;
        $pngName = $data['material_image'];
        $filepath = $pngPath . $pngName;
        
        $options = array(
            '0' => array(
                'title' => 'Width',
                'type' => 'field',
                'is_require' => '1',
                'sort_order' => '2',
                'price' => 0,
                'price_type' => 'fixed',
                'sku' => '',
            ),
            '1' => array(
                'title' => 'Room',
                'type' => 'field',
                'is_require' => '0',
                'sort_order' => '1',
                'price' => 0,
                'price_type' => 'fixed',
                'sku' => '',
            ),
            '2' => array(
                'title' => 'Height',
                'type' => 'field',
                'is_require' => '1',
                'sort_order' => '3',
                'price' => 0,
                'price_type' => 'fixed',
                'sku' => '',
            ),
            '3' => array(
                'title' => 'Cord',
                'type' => 'drop_down',
                'is_require' => '1',
                'sort_order' => '4',
                'values' => array(
                    array(
                        'option_type_id' => '-1',
                        'is_delete' => '',
                        'title' => 'Right',
                        'price' => 0,
                        'price_type' => 'fixed',
                        'sku' => '',
                        'sort_order' => 1
                    ),
                    array(
                        'option_type_id' => '-1',
                        'is_delete' => '',
                        'title' => 'Left',
                        'price' => 0,
                        'price_type' => 'fixed',
                        'sku' => '',
                        'sort_order' => 2
                    )
                )
            ),
            '4' => array(
                'title' => 'Mount',
                'type' => 'drop_down',
                'is_require' => '1',
                'sort_order' => '5',
                'values' => array(
                    array(
                        'option_type_id' => '-1',
                        'is_delete' => '',
                        'title' => 'Inside',
                        'price' => 0,
                        'price_type' => 'fixed',
                        'sku' => '',
                        'sort_order' => 1
                    ),
                    array(
                        'option_type_id' => '-1',
                        'is_delete' => '',
                        'title' => 'Outside',
                        'price' => 0,
                        'price_type' => 'fixed',
                        'sku' => '',
                        'sort_order' => 2
                    )
                )
            )
        );

        $product = new Mage_Catalog_Model_Product();
        $product->setSku($sku);
        $product->setAttributeSetId(4);
        $product->setTypeId('simple');
        $product->setName($data['material_name'].' Shades '.$params['width'].'x'.$params['height']);
        $product->setData('material',$material->getId());
        $product->setCategoryIds(array(3));
        $product->setWebsiteIDs(array(1));
        $product->setDescription($data['material_description']);
        $product->setShortDescription($data['material_description']);
        $product->setPrice($price);
        $product->setWeight(1.0000);
        $product->setMetaKeyword($data['keywords']);

        $product->addImageToMediaGallery($filepath, $visibility, false, false);
        $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE);
        $product->setStatus(1);
        $product->setTaxClassId(0);
        $product->setStockData(array(
                                        'use_config_manage_stock' => '1',
                                        'qty' => $qty,
                                        'use_config_min_qty' => '1',
                                        'use_config_min_sale_qty' => '1',
                                        'use_config_max_sale_qty' => '1',
                                        'is_qty_decimal' => '0',
                                        'use_config_backorders' => '1',
                                        'use_config_notify_stock_qty' => '1',
                                        'is_in_stock' => '1',
                                    )
                );
        $product->setCreatedAt(date("Y-m-d H:i:s"));

        Mage::getSingleton('catalog/product_option')->unsetOptions();
        $product->setProductOptions($options);
        $product->setCanSaveCustomOptions(true);
        $product->save();
        return $product;
    }

    public function createMaterialProduct($material_id, $size) {
        $material = Mage::getModel('material/material')->load($material_id);
        if(!$material){
            return false;
        }
        $data = $material->getData();

        $sku = trim($data['sku']);
        $sku = 'HS_FABRIC_' . str_pad($material_id, 3, '0', STR_PAD_LEFT).'_'.$sku;

        $skuExist = Mage::getModel('catalog/product')->getIdBySku($sku);
        if($skuExist){
            $sku .= '_' . time();
        }

        $qty = 10000;

        $visibility = array(
            'thumbnail',
            'small_image',
            'image'
        );
        $yards = $this->_getDecimalFraction($size);
        $price = $material->getMaterialPricePerYard()*$yards; // price will be set in options

        $pngPath = Mage::getBaseDir('media') . DS . 'fabric_images' . DS;
        $pngName = $data['material_image'];
        $filepath = $pngPath . $pngName;

        $options = array(
            '0' => array(
                'title' => 'Yards',
                'type' => 'field',
                'is_require' => '1',
                'sort_order' => '1',
                'price' => 0,
                'price_type' => 'fixed',
                'sku' => '',
            )
        );

        $product = new Mage_Catalog_Model_Product();
        $product->setSku($sku);
        $product->setAttributeSetId(4);
        $product->setTypeId('simple');
        $product->setName($data['material_name'].' Fabric '.$size.' yards');
        $product->setData('material',$material->getId());
        $product->setCategoryIds(array(3));
        $product->setWebsiteIDs(array(1));
        $product->setDescription($data['material_description']);
        $product->setShortDescription($data['material_description']);
        $product->setPrice($price);
        $product->setWeight(1.0000);
        $product->setMetaKeyword($data['keywords']);

        $product->addImageToMediaGallery($filepath, $visibility, false, false);
        $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE);
        $product->setStatus(1);
        $product->setTaxClassId(0);
        $product->setStockData(array(
                'use_config_manage_stock' => '1',
                'qty' => $qty,
                'use_config_min_qty' => '1',
                'use_config_min_sale_qty' => '1',
                'use_config_max_sale_qty' => '1',
                'is_qty_decimal' => '0',
                'use_config_backorders' => '1',
                'use_config_notify_stock_qty' => '1',
                'is_in_stock' => '1',
            )
        );
        $product->setCreatedAt(date("Y-m-d H:i:s"));

        Mage::getSingleton('catalog/product_option')->unsetOptions();
        $product->setProductOptions($options);
        $product->setCanSaveCustomOptions(true);
        $product->save();
        return $product;
    }

    private function _getDecimalFraction($w)
    {
        if($w =="") return 0;
        $w =str_replace(',','.',trim($w));
        $line = explode(' ', $w);
        $digit_w = $line[0];
        $line_for_drob = explode($digit_w, $w);
        $drob = $line_for_drob[1] ;
        $drob = trim($drob);
        if(isset($drob) && $drob != ' ' && $drob != "" )
        {
            $drob2 = explode("/", $drob);
            $chislitel =trim($drob2[0]);
            $znamenatel = trim($drob2[1]);
            $digit_w = $digit_w + $chislitel/$znamenatel;
        }
        return $digit_w;
    }

    /**
     * Retrieve shopping cart model object
     *
     * @return Mage_Checkout_Model_Cart
     */
    private function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    /**
     * Get checkout session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    private function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current active quote instance
     *
     * @return Mage_Sales_Model_Quote
     */
    private function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }

    public function addToCart($products){
        $cart   = $this->_getCart();
        foreach($products as $product_id => $data){
            $product = Mage::getModel('catalog/product')->load($product_id);
            $options = $product->getOptions();
            $params = array();

            $params['qty'] = $data['qty'];
            $params['options'] = array();
            $params['tdtk'] = 1;

            foreach ($options as $o) {
                switch($o->getTitle()){
                    case 'Yards':
                        $params['options'][$o->getId()] = $data['yards'];
                        break;
                    case 'Room':
                        $params['options'][$o->getId()] = $data['room'];
                        break;
                    case 'Width':
                        $params['options'][$o->getId()] = $data['width'];
                        break;
                    case 'Height':
                        $params['options'][$o->getId()] = $data['height'];
                        break;
                    case 'Cord':
                        $vals = $o->getValues();
                        $count = 0;
                        foreach ($vals as $idx => $val) {
                            if($count == $data['cord']){
                                $params['options'][$o->getId()] = $val->getOptionTypeId();
                                break;
                            }
                            $count++;
                        }
                        break;
                    case 'Mount':
                        $vals = $o->getValues();
                        $count = 0;
                        foreach ($vals as $idx => $val) {
                            if($count == $data['mount']){
                                $params['options'][$o->getId()] = $val->getOptionTypeId();
                                break;
                            }
                            $count++;
                        }
                        break;
                }
            }
            $cart->addProduct($product, $params);
        }
        $res = $cart->save();
        $this->_getSession()->setCartWasUpdated(true);
        return $res;
    }
}