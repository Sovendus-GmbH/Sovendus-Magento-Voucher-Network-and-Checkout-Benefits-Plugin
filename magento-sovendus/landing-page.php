<?php
namespace Sovendus\VoucherNetwork\Block;

use Magento\Framework\View\Element\Template;
use Sovendus\VoucherNetwork\Helper\Settings;

class LandingPage extends Template
{
    private $settings;
    
    public function __construct(
        Template\Context $context,
        Settings $settings,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->settings = $settings;
    }

    public function getSettings()
    {
        return $this->settings->getSettings();
    }

    public function getPluginName()
    {
        return 'Magento2';
    }

    public function getPluginVersion()
    {
        return '1.0.0'; // Get from composer.json
    }
}