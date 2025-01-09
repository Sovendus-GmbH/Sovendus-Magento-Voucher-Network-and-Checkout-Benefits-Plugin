<?php

namespace Sovendus\VoucherNetwork\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\ModuleListInterface;

class ModuleCheck extends AbstractHelper
{
    private $moduleList;

    public function __construct(
        Context $context,
        ModuleListInterface $moduleList
    ) {
        parent::__construct($context);
        $this->moduleList = $moduleList;
    }

    public function isMagentoActive(): bool
    {
        return $this->moduleList->has('Magento_Store') 
            && $this->moduleList->has('Magento_Checkout')
            && $this->moduleList->has('Magento_Sales');
    }
}