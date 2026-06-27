<?php
namespace Wilchers\FormBuilder\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $formTable = $setup->getConnection()->newTable($setup->getTable('wilchers_form'))
            ->addColumn('form_id',         Table::TYPE_INTEGER,   null,   ['identity'=>true,'unsigned'=>true,'nullable'=>false,'primary'=>true], 'ID')
            ->addColumn('title',           Table::TYPE_TEXT,      255,    ['nullable'=>false], 'Naam formulier')
            ->addColumn('identifier',      Table::TYPE_TEXT,      100,    ['nullable'=>false], 'URL-sleutel')
            ->addColumn('recipient_email', Table::TYPE_TEXT,      255,    ['nullable'=>false], 'Ontvanger')
            ->addColumn('allow_copy',      Table::TYPE_SMALLINT,  null,   ['default'=>1],      'Kopie aan invuller')
            ->addColumn('success_message', Table::TYPE_TEXT,      '64k',  ['nullable'=>true],  'Succesbericht')
            ->addColumn('is_active',       Table::TYPE_SMALLINT,  null,   ['default'=>1],      'Actief')
            ->addColumn('fields_config',   Table::TYPE_TEXT,      '64k',  ['nullable'=>true],  'Velden JSON')
            ->addColumn('created_at',      Table::TYPE_TIMESTAMP, null,   ['nullable'=>false,'default'=>Table::TIMESTAMP_INIT])
            ->addColumn('updated_at',      Table::TYPE_TIMESTAMP, null,   ['nullable'=>false,'default'=>Table::TIMESTAMP_INIT_UPDATE])
            ->addIndex($setup->getIdxName('wilchers_form', ['identifier']), ['identifier'])
            ->setComment('Wilchers FormBuilder - Formulieren');
        $setup->getConnection()->createTable($formTable);

        $subTable = $setup->getConnection()->newTable($setup->getTable('wilchers_form_submission'))
            ->addColumn('submission_id',   Table::TYPE_INTEGER,   null,   ['identity'=>true,'unsigned'=>true,'nullable'=>false,'primary'=>true], 'ID')
            ->addColumn('form_id',         Table::TYPE_INTEGER,   null,   ['unsigned'=>true,'nullable'=>false], 'Formulier ID')
            ->addColumn('form_title',      Table::TYPE_TEXT,      255,    ['nullable'=>true],  'Formuliernaam')
            ->addColumn('submitter_name',  Table::TYPE_TEXT,      255,    ['nullable'=>true],  'Naam invuller')
            ->addColumn('submitter_email', Table::TYPE_TEXT,      255,    ['nullable'=>true],  'E-mail invuller')
            ->addColumn('data',            Table::TYPE_TEXT,      '64k',  ['nullable'=>true],  'Gegevens JSON')
            ->addColumn('ip_address',      Table::TYPE_TEXT,      50,     ['nullable'=>true],  'IP-adres')
            ->addColumn('submitted_at',    Table::TYPE_TIMESTAMP, null,   ['nullable'=>false,'default'=>Table::TIMESTAMP_INIT])
            ->setComment('Wilchers FormBuilder - Inzendingen');
        $setup->getConnection()->createTable($subTable);

        $setup->endSetup();
    }
}