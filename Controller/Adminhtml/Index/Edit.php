<?php
namespace Magenest\Promotion\Controller\Adminhtml\Index;

use Magenest\Promotion\Model\Promotion as Promotion;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Edit A Contact Page
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();

        $promoDatas = $this->getRequest()->getParam('promotion');

        if(is_array($promoDatas)) {
            $promotion = $this->_objectManager->create(Promotion::class);
            $promotion->setData($promoDatas);
            $data = $promotion->getData();
            $id = $data['entity_id'];
            //$promotion->setData($movieDatas)->save();
            $this->_eventManager->dispatch('check_status', ['promotion' => $promotion,'id'=>$id]);
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index');
        }
    }
}