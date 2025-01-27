<?php

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

function detectLanguage()
{
    $objectManager = ObjectManager::getInstance();
    $scopeConfig = $objectManager->get(ScopeConfigInterface::class);
    $locale = $scopeConfig->getValue('general/locale/code', ScopeInterface::SCOPE_STORE);
    $localeParts = explode('_', $locale);
    return [
        $localeParts[0], // e.g., 'en'
        $localeParts[1]   // e.g., 'US'
    ];
}
