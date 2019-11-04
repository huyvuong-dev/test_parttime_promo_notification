<?php

namespace Magenest\Promotion\Model\ResourceModel\Promotion;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection {
    protected function _construct() {
        $this->_init('Magenest\Promotion\Model\Promotion', 'Magenest\Promotion\Model\ResourceModel\Promotion');
    }
}