<?php
class Hempshades_Customizer_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
       $material_id = (int)Mage::getSingleton('core/session')->getFabricId();
       if($material_id == 0){
          return $this->_redirect('*/index/select'); 
       }else{
          $this->loadLayout();
          $this->renderLayout();
       }       
    }
    
    public function prepareAction() {
        $material_id = (int)$this->getRequest()->getParam('fabricid');
        Mage::getSingleton('core/session')->setFabricId($material_id);
        return $this->_redirect('*/');
    }
    
    public function selectAction() {
       $this->loadLayout();
       $this->renderLayout();
    }    
    
    public function saveAction() {
        $params = $this->getRequest()->getParams();
        $customizer = Mage::getModel('customizer/customizer');
        $data = $params['data'];
        $products = array();
        foreach($data['qty'] as $idx => $qty){
            if(!empty($data['qty'][$idx]) && !empty($data['width'][$idx]) && !empty($data['height'][$idx])){
                $options = array();
                $options['room']    = $data['room'][$idx];
                $options['qty']     = $data['qty'][$idx];
                $options['width']   = $data['width'][$idx];
                $options['height']  = $data['height'][$idx];
                $options['cord']    = $data['cord'][$idx];
                $options['mount']   = $data['mount'][$idx];
                $product = $customizer->createProduct($params['material_id'],$options);
                $products[$product->getId()] = $options;
            }
        }

        if(isset($data['yards']) && !empty($data['yards'])){
            $product = $customizer->createMaterialProduct($params['material_id'],$data['yards']);
            $options = array();
            $options['qty']     = 1;
            $options['yards']   = $data['yards'];
            $products[$product->getId()] = $options;
        }

        $customizer->addToCart($products);

        $this->_redirect('checkout/cart');
    }

}