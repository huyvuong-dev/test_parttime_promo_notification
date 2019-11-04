<?php

namespace Magenest\Promotion\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface {
    /*
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return null
    */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '2.0.3') < 0) {
            $conn = $setup->getConnection();
            $tableName = $setup->getTable('promo_notification');
            if ($conn->isTableExists($tableName) != true) {
                $table = $conn->newTable($tableName)
                    ->addColumn(
                        'entity_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity'=>true, 'unsigned'=>true, 'nullable'=>false, 'primary' => true],
                        'Entity ID'
                    )
                    ->addColumn(
                        'name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Name'
                    )
                    ->addColumn(
                        'status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        [],
                        'Status'
                    )
                    ->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,[
                        'nullable' => false,
                        'default' =>
                            \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
                    ],
                        'Created at'
                    )
                    ->addColumn(
                        'short_description',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Short Description'
                    )
                    ->addColumn(
                        'redirect_url',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Redirect Url'
                    )
                    ->setComment('Promotion Table');

                $conn->createTable($table);
            }

        }
        $setup->endSetup();

    }
}