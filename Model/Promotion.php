<?php

namespace Magenest\Promotion\Model;

use Magento\Framework\Model\AbstractModel;

class Promotion extends AbstractModel {
    protected function _construct() {
        /* full resource classname */
        $this->_init('Magenest\Promotion\Model\ResourceModel\Promotion');
    }
}
