<?php
namespace Magenest\Promotion\Block\Promotion;

class Detail extends \Magento\Framework\View\Element\Template
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

    public function getDetailPromotion()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $urlId = $this->_request->getParam('id');
        $promos = $this->col->create()->getData();
        $id = $objectManager->create("Magento\Customer\Model\Session")->getCustomerId();
        $this->_customerData = $this->_customer->getDataModel();
        $this->_customerData->setId($id);
        $customerId = $this->_customerRepositoryInterface->getById($id);

        $attribute = $customerId->getCustomAttribute('notification_viewed');

        if (!isset($attribute)){
            $customerId->setCustomAttribute('notification_viewed',$urlId);
            $this->_customer->updateData($customerId);
            $this->_customer->save();
        }else{
            $value = $attribute->getValue();
            $arrId = explode('|',$value);
            if (!in_array($urlId,$arrId)){
                $listId = $value.'|'.$urlId;
                $customerId->setCustomAttribute('notification_viewed',$listId);
                $this->_customer->updateData($customerId);
                $this->_customer->save();
            }
        }
        foreach ($promos as $promo) {
            $entityID = $promo['entity_id'];
            if ($entityID == $urlId)
                return $promo;
        }
    }

}