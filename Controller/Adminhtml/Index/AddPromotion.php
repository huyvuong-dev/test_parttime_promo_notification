<?php
namespace Magenest\Promotion\Controller\Adminhtml\Index;

use Magenest\Promotion\Model\Promotion as Promotion;
use Magento\Backend\App\Action;


class AddPromotion extends \Magento\Backend\App\Action
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
        $tableName = "promo_notification";
        $id = $this->getNextAutoincrement($tableName);
        $promoDatas = $this->getRequest()->getParam('promotion');
        if(is_array($promoDatas)) {
            $promotion = $this->_objectManager->create(Promotion::class);
            $promotion->setData($promoDatas);
            //$promotion->setData($promoDatas)->save();
            $this->_eventManager->dispatch('check_status', ['promotion' => $promotion,'id'=>$id]);
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index');
        }
    }

    public function getNextAutoincrement($tableName)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $entityStatus = $connection->showTableStatus($tableName);

        if (empty($entityStatus['Auto_increment'])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Cannot get autoincrement value'));
        }
        return $entityStatus['Auto_increment'];

    }

}