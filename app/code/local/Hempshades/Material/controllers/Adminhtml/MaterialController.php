<?php
class Hempshades_Material_Adminhtml_MaterialController extends Mage_Adminhtml_Controller_action
{

    protected function _initAction()
    {
        $this->loadLayout()
                ->_setActiveMenu('material/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Fabric Items Manager'), Mage::helper('adminhtml')->__('Fabric Manager'));

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
             ->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('material/material')->load($id);

        if ($model->getId() || $id == 0)
        {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data))
            {
                $model->setData($data);
            }

            Mage::register('material_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('material/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Fabric Manager'), Mage::helper('adminhtml')->__('Fabric Manager'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('material/adminhtml_material_edit'))
                    ->_addLeft($this->getLayout()->createBlock('material/adminhtml_material_edit_tabs'));

            $this->renderLayout();
        } else
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('material')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost())
        {
            $model = Mage::getModel('material/material');
            
            try{
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                          ->setUpdateTime(now());
                }else{
                    $model->setUpdateTime(now());
                }

                if (is_uploaded_file($_FILES['image_filename']['tmp_name'])) {

                    // upload png image
                    $uploader = new Varien_File_Uploader('image_filename');
                    $uploader->setAllowedExtensions(array('png','jpeg','jpg'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $pngPath = Mage::getBaseDir('media') . DS . 'fabric_images' . DS;
                    $pngPath = str_replace('//', '/', $pngPath);
                    if (!file_exists($pngPath)) {
                        mkdir($pngPath, 0777);
                    }

                    $pngName = $_FILES['image_filename']['name'];
                    $uploader->save($pngPath, $pngName);
                    $data['material_image'] = $pngName;
                }
                $model->setData($data)
                      ->setId($this->getRequest()->getParam('id'));               
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('material')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('material')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0)
        {
            try
            {
                $model = Mage::getModel('material/material');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $materialIds = $this->getRequest()->getParam('material');
        if (!is_array($materialIds))
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else
        {
            try
            {
                foreach ($materialIds as $materialId)
                {
                    $material = Mage::getModel('material/material')->load($materialId);
                    $material->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($materialIds)
                        )
                );
            } catch (Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    public function createproductAction(){
        $material_id = $this->getRequest()->getParam('id');
        $customizer = Mage::getModel('customizer/customizer');
        $product = $customizer->createProduct($material_id);
        
        if($product){
           Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('material')->__('Product was successfully created!'));
        }else{
           Mage::getSingleton('adminhtml/session')->addError(Mage::helper('material')->__("Can't save product!")); 
        }

        $this->_redirect('*/*/edit', array('id' => $material_id));
        return;
    }

}