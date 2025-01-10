<?php

namespace Sovendus\SovendusApp\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class JsonConfig extends Field
{
    protected function _getElementHtml(AbstractElement $element)
    {
        $html = '<div id="sovendus-settings-container"></div>';
        $html .= '<script type="text/javascript" src="' . $this->getViewFileUrl('Sovendus_SovendusApp::js/frontend_react_loader.js') . '"></script>';
        return $html;
    }
}
