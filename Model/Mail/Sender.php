<?php
namespace Wilchers\FormBuilder\Model\Mail;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Sender
{
    private const SHOP_EMAIL = 'wilma@wilcherswaanzinnigewereld.nl';
    private const SHOP_NAME  = 'Wilchers Waanzinnige Wereld';

    public function __construct(
        private TransportBuilder      $transportBuilder,
        private StateInterface        $inlineTranslation,
        private StoreManagerInterface $storeManager,
        private LoggerInterface       $logger
    ) {}

    public function send(
        string $recipientEmail,
        string $formTitle,
        array  $labeledData,
        string $submitterName,
        string $submitterEmail,
        bool   $sendCopy,
        string $successMessage,
        string $timestamp
    ): void {
        $bodyHtml = $this->buildTable($labeledData);
        $storeId  = $this->storeManager->getStore()->getId();

        $this->dispatch('wilchers_formbuilder_notification', $recipientEmail, $submitterEmail ?: self::SHOP_EMAIL, $storeId, [
            'subject'         => 'Nieuwe inzending: ' . $formTitle,
            'form_title'      => $formTitle,
            'submitter_name'  => $submitterName,
            'submitter_email' => $submitterEmail,
            'body_html'       => $bodyHtml,
            'timestamp'       => $timestamp,
        ]);

        if ($sendCopy && filter_var($submitterEmail, FILTER_VALIDATE_EMAIL)) {
            $this->dispatch('wilchers_formbuilder_confirmation', $submitterEmail, self::SHOP_EMAIL, $storeId, [
                'subject'         => 'Bevestiging: ' . $formTitle,
                'form_title'      => $formTitle,
                'submitter_name'  => $submitterName,
                'body_html'       => $bodyHtml,
                'timestamp'       => $timestamp,
                'success_message' => $successMessage,
            ]);
        }
    }

    private function dispatch(string $tplId, string $to, string $replyTo, int $storeId, array $vars): void
    {
        try {
            $this->inlineTranslation->suspend();
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($tplId)
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId])
                ->setTemplateVars($vars)
                ->setFromByScope(['email' => self::SHOP_EMAIL, 'name' => self::SHOP_NAME])
                ->addTo($to)
                ->setReplyTo($replyTo)
                ->getTransport();
            $transport->sendMessage();
        } catch (\Exception $e) {
            $this->logger->error('[Wilchers_FormBuilder] ' . $e->getMessage());
        } finally {
            $this->inlineTranslation->resume();
        }
    }

    private function buildTable(array $data): string
    {
        $rows = '';
        foreach ($data as $label => $value) {
            $rows .= '<tr>'
                . '<th style="text-align:left;padding:7px 12px;background:#f5f0fa;width:35%;font-weight:600;border-bottom:1px solid #e8dff5">' . htmlspecialchars($label) . '</th>'
                . '<td style="padding:7px 12px;border-bottom:1px solid #f0ebf8">' . nl2br(htmlspecialchars((string)$value)) . '</td>'
                . '</tr>';
        }
        return '<table style="width:100%;border-collapse:collapse;font-size:14px;font-family:Arial,sans-serif">' . $rows . '</table>';
    }
}