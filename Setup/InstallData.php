<?php
namespace Magenest\Promotion\Setup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
class InstallData implements InstallDataInterface
{
    private $customerSetupFactory;
    public function __construct(\Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory)
    {
        $this->customerSetupFactory = $customerSetupFactory;
    }
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $setup->startSetup();
        $attributeCode = 'notification_received';
        $customerSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            $attributeCode, [
                            'type' => 'text',
                            'label' => 'Notification Received',
                            'input' => 'text',
                            'source' => '',
                            'required' => false,
                            'visible' => true,
                            'position' => 900,
                            'system' => false,
                            'backend' => ''
                            ]);
        $attribute = $customerSetup->getEavConfig()->getAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            $attributeCode
        );
        $attribute->setData('used_in_forms', ['adminhtml_customer']);
        $attribute->save();
        $setup->endSetup();
    }
}