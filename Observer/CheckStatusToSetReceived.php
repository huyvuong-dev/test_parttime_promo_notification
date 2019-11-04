<?php
namespace Magenest\Promotion\Observer;
use Magento\Customer\Model\Customer;
class CheckStatusToSetReceived implements \Magento\Framework\Event\ObserverInterface
{
    private $layout;
    private $_customer;
    private $_eavConfig;

    public function __construct(
        \Magento\Framework\View\Layout $layout,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->_eavConfig = $eavConfig;
        $this->_customer = $customerRepositoryInterface;
        $this->layout = $layout;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $promotion = $observer->getData('promotion');
        $id = $observer->getData('id');
        if ($promotion->getData('status') == 1){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->getCollection();
            foreach ($customerObj as $customerObjdata) {

                $customermodel  = $objectManager->create('Magento\Customer\Model\Customer');
                $customerData = $customermodel->getDataModel();
                $customerData->setId($customerObjdata->getData('entity_id'));
                $entity_id = $customerObjdata->getData('entity_id');
                $attribute = $this->_customer->getById($entity_id)->getCustomAttribute('notification_received');
                //$arr = explode('|',$attribute->getValue());
                if (!isset($attribute)){
                    $customerData->setCustomAttribute('notification_received', $id);
                    $customermodel->updateData($customerData);

                    $customerResource = $objectManager->create('\Magento\Customer\Model\ResourceModel\CustomerFactory')->create();
                    $customerResource->saveAttribute($customermodel, 'notification_received');
                }else{
                    $value = $attribute->getValue();
                    $listId = $value.'|'.$id;
                    $customerData->setCustomAttribute('notification_received', $listId);
                    $customermodel->updateData($customerData);

                    $customerResource = $objectManager->create('\Magento\Customer\Model\ResourceModel\CustomerFactory')->create();
                    $customerResource->saveAttribute($customermodel, 'notification_received');
                }



            }
        }
        $promotion->save();

    }
}
