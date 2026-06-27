<?php
namespace Wilchers\FormBuilder\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallDefaultForms implements DataPatchInterface
{
    public function __construct(private ModuleDataSetupInterface $setup) {}

    public function apply()
    {
        $this->setup->getConnection()->startSetup();
        $t = $this->setup->getTable('wilchers_form');

        $this->setup->getConnection()->insert($t, [
            'title'           => 'Herroeping & Retour',
            'identifier'      => 'herroeping',
            'recipient_email' => 'wilma@wilcherswaanzinnigewereld.nl',
            'allow_copy'      => 1,
            'success_message' => 'Uw herroepingsverklaring is ontvangen. U ontvangt direct een bevestiging per e-mail met het tijdstip van ontvangst.',
            'is_active'       => 1,
            'fields_config'   => json_encode([
                ['name'=>'name',       'label'=>'Volledige naam',                          'type'=>'text',     'required'=>true],
                ['name'=>'email',      'label'=>'E-mailadres',                             'type'=>'email',    'required'=>true],
                ['name'=>'order_id',   'label'=>'Bestelnummer',                            'type'=>'text',     'required'=>true],
                ['name'=>'order_date', 'label'=>'Besteldatum',                             'type'=>'date',     'required'=>true],
                ['name'=>'items',      'label'=>'Te herroepen artikel(en)',                'type'=>'textarea', 'required'=>true],
                ['name'=>'reason',     'label'=>'Reden voor herroeping (optioneel)',       'type'=>'textarea', 'required'=>false],
                ['name'=>'send_copy',  'label'=>'Stuur mij een bevestiging per e-mail',   'type'=>'checkbox', 'required'=>false],
            ], JSON_UNESCAPED_UNICODE),
        ]);

        $this->setup->getConnection()->insert($t, [
            'title'           => 'Contactformulier',
            'identifier'      => 'contact',
            'recipient_email' => 'wilma@wilcherswaanzinnigewereld.nl',
            'allow_copy'      => 1,
            'success_message' => 'Uw bericht is ontvangen. We nemen zo snel mogelijk contact met u op.',
            'is_active'       => 1,
            'fields_config'   => json_encode([
                ['name'=>'name',      'label'=>'Uw naam',                                'type'=>'text',     'required'=>true],
                ['name'=>'email',     'label'=>'E-mailadres',                            'type'=>'email',    'required'=>true],
                ['name'=>'phone',     'label'=>'Telefoonnummer',                         'type'=>'tel',      'required'=>false],
                ['name'=>'subject',   'label'=>'Onderwerp',                              'type'=>'text',     'required'=>true],
                ['name'=>'message',   'label'=>'Uw bericht',                             'type'=>'textarea', 'required'=>true],
                ['name'=>'send_copy', 'label'=>'Stuur mij een kopie van dit bericht',   'type'=>'checkbox', 'required'=>false],
            ], JSON_UNESCAPED_UNICODE),
        ]);

        $this->setup->getConnection()->endSetup();
        return $this;
    }

    public static function getDependencies() { return []; }
    public function getAliases() { return []; }
}