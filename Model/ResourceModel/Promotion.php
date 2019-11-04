<?php
namespace Magenest\Promotion\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Promotion extends AbstractDb {
    protected function _construct() {
        /* tablename, primarykey*/
        $this->_init('promo_notification', 'entity_id');
    }
}
