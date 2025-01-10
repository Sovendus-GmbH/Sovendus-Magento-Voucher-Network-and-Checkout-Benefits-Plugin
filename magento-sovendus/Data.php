<?php
namespace Sovendus\SovendusVoucherNetwork\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\ModuleListInterface;

class Data extends AbstractHelper 
{
    private $moduleList;

    public function __construct(
        Context $context,
        ModuleListInterface $moduleList
    ) {
        parent::__construct($context);
        $this->moduleList = $moduleList;
    }

    public function isModuleEnabled(): bool
    {
        return $this->moduleList->has('Sovendus_SovendusVoucherNetwork');
    }

    public function getModuleVersion(): string
    {
        return '1.3.0';
    }
}