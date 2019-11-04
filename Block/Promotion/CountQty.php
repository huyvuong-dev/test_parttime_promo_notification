<?php
namespace Magenest\Promotion\Block\Promotion;

class CountQty extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    private $col;
    protected $_customerSession;
    private $_customerRepositoryInterface;
    protected $_request;
    private $_customerFactory;
    private $_customer;
    private $_customerData;
    private $_customerResource;
    private $_customerResourceFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\Promotion\Model\ResourceModel\Promotion\CollectionFactory $col,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Customer\Model\ResourceModel\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Model\Data\Customer $customerData,
        \Magento\Customer\Model\ResourceModel\Customer $customerResource,
        \Magento\Customer\Model\ResourceModel\CustomerFactory $customerResourceFactory,
        array $data = []
    )
    {
        $this->_customerResource = $customerResource;
        $this->_customerResourceFactory = $customerResourceFactory;
        $this->_customerData = $customerData;
        $this->_customer = $customer;
        $this->_customerFactory = $customerFactory;
        $this->_request = $request;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->_customerSession = $customerSession;
        $this->col = $col;
        parent::__construct($context, $data);
    }

    public function getCountQty(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $id = $objectManager->create("Magento\Customer\Model\Session")->getCustomerId();
        $this->_customerData = $this->_customer->getDataModel();
        $this->_customerData->setId($id);
        $customerId = $this->_customerRepositoryInterface->getById($id);

        $attributeViewed = $customerId->getCustomAttribute('notification_viewed');
        $attributeReceived = $customerId->getCustomAttribute('notification_received');
        if (isset($attributeViewed) && isset($attributeReceived)){
            $valueReceived = $attributeReceived->getValue();
            $arrReceived = explode('|',$valueReceived);
            $valueViewed = $attributeViewed->getValue();
            $arrViewed = explode('|',$valueViewed);
            // contain array in array
            $containsSearch = count(array_intersect($arrViewed, $arrReceived));
            $quantity = count($arrReceived) - $containsSearch;
            return $quantity;
        }elseif (!isset($attributeViewed)){
            $valueReceived = $attributeReceived->getValue();
            $arrReceived = explode('|',$valueReceived);
            return count($arrReceived);
        }
    }


}