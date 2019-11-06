<?php
namespace Magenest\Promotion\Controller\Notification;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
class Url extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\View\Result\PageddFactory
    protected $resultPageFactory;
     */
    private $resultPageFactory;
    private $_customerSession;
    public function __construct(\Magento\Customer\Model\Session $customerSession,\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context);
    }
    public function execute()
    {

        $resultPage = $this->resultPageFactory->create();
//        $this->_view->loadLayout();
//        $this->_view->getLayout()->initMessages();
//        $this->_view->renderLayout();
        $id = $this->_customerSession->getCustomer()->getId();
        if (!isset($id)){
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('customer/account');
        }
        return $resultPage;
    }
}