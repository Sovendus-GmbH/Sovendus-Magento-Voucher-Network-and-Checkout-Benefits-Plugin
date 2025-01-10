<?php
namespace Sovendus\VoucherNetwork\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Sovendus\VoucherNetwork\Helper\Settings as SettingsHelper;

class Settings extends Template
{
    private $settingsHelper;

    public function __construct(
        Template\Context $context,
        SettingsHelper $settingsHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->settingsHelper = $settingsHelper;
    }

    public function getSettingsJson(): string
    {
        return json_encode($this->settingsHelper->getSettings());
    }

    public function getSaveUrl(): string
    {
        return $this->getUrl('sovendus/settings/save');
    }
}