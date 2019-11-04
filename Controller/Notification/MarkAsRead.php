<?php
namespace Magenest\Promotion\Controller\Notification;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
class MarkAsRead extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\View\Result\PageddFactory
    protected $resultPageFactory;
     */
    private $resultPageFactory;
    private $_customerRepositoryInterface;
    private $_customerSession;
    public function __construct(\Magento\Customer\Model\Session $customerSession,
                                \Magento\Framework\App\Action\Context $context,
                                \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->_customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $id = $this->_customerSession->getCustomer()->getId();
        if (!isset($id)){
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('customer/account');
        }
        $urlId = $this->_request->getParam('id');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerRepositoryInterface = $objectManager->create(\Magento\Customer\Api\CustomerRepositoryInterface::class);
        $customerData = $objectManager->create(\Magento\Customer\Model\Data\Customer::class);
        $customer = $objectManager->create(\Magento\Customer\Model\Customer::class);
        $id = $objectManager->create("Magento\Customer\Model\Session")->getCustomerId();
        $customerData = $customer->getDataModel();
        $customerData->setId($id);
        $customerId = $customerRepositoryInterface->getById($id);
        $attribute = $customerId->getCustomAttribute('notification_viewed');
        if (!isset($attribute)){
            $customerId->setCustomAttribute('notification_viewed',$urlId);
            $customer->updateData($customerId);
            $customer->save();
        }else{
            $value = $attribute->getValue();
            $arrId = explode('|',$value);
            if (!in_array($urlId,$arrId)){
                $listId = $value.'|'.$urlId;
                $customerId->setCustomAttribute('notification_viewed',$listId);
                $customer->updateData($customerId);
                $customer->save();
            }
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index');
    }
}