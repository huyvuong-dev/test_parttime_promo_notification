<?php
namespace Magenest\Promotion\Block\Promotion\Dashboard;

class Info extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    private $col;
    private $_customerSession;
    private $_customerRepositoryInterface;
    private $promo;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\Promotion\Model\ResourceModel\Promotion\CollectionFactory $col,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        array $data = []
    )
    {
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->_customerSession = $customerSession;
        $this->col = $col;
        parent::__construct($context, $data);
    }
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Notification'));
    }


    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getReceivedId(){
        $id = $this->_customerSession->getCustomer()->getId();
        $customerId = $this->_customerRepositoryInterface->getById($id);
        $attribute = $customerId->getCustomAttribute('notification_received');
        if (isset($customerId) && isset($attribute)){
            $value = $attribute->getValue();
            $arrEntityId = explode('|',$value);
            return $arrEntityId;
        }
    }
    public function getPromotion()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
        if (!$this->promo) {
            $promos = $this->col->create()->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'entity_id',
                ['in' => $this->getReceivedId()]
            );

            return $promos;
        }
    }
    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getPromotion()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'promotion.notification.pager'
            )->setCollection(
                $this->getPromotion()
            );
            $this->setChild('pager', $pager);
            $this->getPromotion()->load();
        }
        return $this;
    }

    public function getDataPromotion()
    {
        $promos = $this->col->create()->getData();
        $id = $this->_customerSession->getCustomer()->getId();
        $customerId = $this->_customerRepositoryInterface->getById($id);
        $attribute = $customerId->getCustomAttribute('notification_received');
        if (isset($customerId) && isset($attribute)){
            $value = $attribute->getValue();
            $arrEntityId = explode('|',$value);
            $arrPromotion = array();
            foreach ($promos as $promo) {
                $entityID = $promo['entity_id'];
                if (in_array($entityID,$arrEntityId))
                    $arrPromotion[] = $promo;
            }
            return $arrPromotion;
        }
    }

    public function getDeleteUrl(){
        return $this->getUrl('promo/notification/delete');
    }

    public function checkMarkAsRead($entity_id){
        $id = $this->_customerSession->getCustomer()->getId();
        $customerId = $this->_customerRepositoryInterface->getById($id);
        $attribute = $customerId->getCustomAttribute('notification_viewed');
        if (isset($customerId) && isset($attribute)){
            $value = $attribute->getValue();
            $arrEntityId = explode('|',$value);
            if (in_array($entity_id,$arrEntityId)){
                return true;
            }
            return false;

        }
    }

    public function getMarkAsReadUrl(){
        return $this->getUrl('promo/notification/markasread');
    }
}