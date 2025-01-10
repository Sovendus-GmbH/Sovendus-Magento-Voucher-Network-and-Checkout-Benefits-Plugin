<?php

namespace Sovendus\SovendusVoucherNetwork\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class JsonConfig extends Field
{
    protected function _getElementHtml(AbstractElement $element)
    {
        $html = '<div id="sovendus-config"></div>';
        $html .= '<script type="text/javascript" src="' . $this->getViewFileUrl('Sovendus_SovendusVoucherNetwork::js/config.js') . '"></script>';
        return $html;
    }
}