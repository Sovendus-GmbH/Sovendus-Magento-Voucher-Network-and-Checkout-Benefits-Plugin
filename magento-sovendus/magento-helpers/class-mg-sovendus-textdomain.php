<?php

namespace Sovendus\VoucherNetwork\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Translate\Inline\StateInterface;

class Translate extends AbstractHelper
{
    private $inlineTranslation;

    public function __construct(
        Context $context,
        StateInterface $inlineTranslation
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
    }

    public function loadTranslations(): void
    {
        $this->inlineTranslation->suspend();
        // Translations are automatically loaded by Magento
        // from app/code/Sovendus/VoucherNetwork/i18n/*.csv
    }
}