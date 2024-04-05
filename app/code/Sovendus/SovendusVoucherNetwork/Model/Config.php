<?php

namespace Sovendus\SovendusVoucherNetwork\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{

    const DE_ENABLE = 'sovendusvouchernetwork/de_settings/de_enable';
    const DE_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/de_settings/de_traffic_source_number';
    const DE_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/de_settings/de_traffic_medium_number';

    const AT_ENABLE = 'sovendusvouchernetwork/at_settings/at_enable';
    const AT_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/at_settings/at_traffic_source_number';
    const AT_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/at_settings/at_traffic_medium_number';

    const NL_ENABLE = 'sovendusvouchernetwork/nl_settings/nl_enable';
    const NL_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/nl_settings/nl_traffic_source_number';
    const NL_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/nl_settings/nl_traffic_medium_number';

    const CH_DE_ENABLE = 'sovendusvouchernetwork/ch_de_settings/ch_de_enable';
    const CH_DE_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/ch_de_settings/ch_de_traffic_source_number';
    const CH_DE_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/ch_de_settings/ch_de_traffic_medium_number';

    const CH_FR_ENABLE = 'sovendusvouchernetwork/ch_fr_settings/ch_fr_enable';
    const CH_FR_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/ch_fr_settings/ch_fr_traffic_source_number';
    const CH_FR_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/ch_fr_settings/ch_fr_traffic_medium_number';

    const FR_ENABLE = 'sovendusvouchernetwork/fr_settings/fr_enable';
    const FR_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/fr_settings/fr_traffic_source_number';
    const FR_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/fr_settings/fr_traffic_medium_number';

    const IT_ENABLE = 'sovendusvouchernetwork/it_settings/it_enable';
    const IT_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/it_settings/it_traffic_source_number';
    const IT_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/it_settings/it_traffic_medium_number';

    const IE_ENABLE = 'sovendusvouchernetwork/ie_settings/ie_enable';
    const IE_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/ie_settings/ie_traffic_source_number';
    const IE_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/ie_settings/ie_traffic_medium_number';

    const UK_ENABLE = 'sovendusvouchernetwork/uk_settings/uk_enable';
    const UK_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/uk_settings/uk_traffic_source_number';
    const UK_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/uk_settings/uk_traffic_medium_number';

    const DK_ENABLE = 'sovendusvouchernetwork/dk_settings/dk_enable';
    const DK_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/dk_settings/dk_traffic_source_number';
    const DK_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/dk_settings/dk_traffic_medium_number';

    const SE_ENABLE = 'sovendusvouchernetwork/se_settings/se_enable';
    const SE_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/se_settings/se_traffic_source_number';
    const SE_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/se_settings/se_traffic_medium_number';

    const ES_ENABLE = 'sovendusvouchernetwork/es_settings/es_enable';
    const ES_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/es_settings/es_traffic_source_number';
    const ES_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/es_settings/es_traffic_medium_number';

    const BE_NL_ENABLE = 'sovendusvouchernetwork/be_nl_settings/be_nl_enable';
    const BE_NL_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/be_nl_settings/be_nl_traffic_source_number';
    const BE_NL_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/be_nl_settings/be_nl_traffic_medium_number';

    const BE_FR_ENABLE = 'sovendusvouchernetwork/be_fr_settings/be_fr_enable';
    const BE_FR_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/be_fr_settings/be_fr_traffic_source_number';
    const BE_FR_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/be_fr_settings/be_fr_traffic_medium_number';

    const PL_ENABLE = 'sovendusvouchernetwork/pl_settings/pl_enable';
    const PL_TRAFFIC_SOURCE_NUMBER = 'sovendusvouchernetwork/pl_settings/pl_traffic_source_number';
    const PL_TRAFFIC_MEDIUM_NUMBER = 'sovendusvouchernetwork/pl_settings/pl_traffic_medium_number';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param $path
     * @param $scopeCode
     * @param $scopeType
     * @return mixed
     */
    public function getConfig($path, $scopeCode, $scopeType = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue($path, $scopeType, $scopeCode);
    }

    /**
     * @param $countryCode
     * @param $storeCode
     * @return array
     */
    public function getSovConfig($countryCode, $storeCode = null)
    {
        switch ($countryCode) {
            case "DE":
                return array(
                    $this->getConfig(self::DE_ENABLE, $storeCode),
                    (int) $this->getConfig(self::DE_TRAFFIC_SOURCE_NUMBER, $storeCode),
                    (int) $this->getConfig(self::DE_TRAFFIC_MEDIUM_NUMBER, $storeCode),

                );
            case "AT":
                return array(
                    $this->getConfig(self::AT_ENABLE, $storeCode),
                    (int) $this->getConfig(self::AT_TRAFFIC_SOURCE_NUMBER, $storeCode),
                    (int) $this->getConfig(self::AT_TRAFFIC_MEDIUM_NUMBER, $storeCode),

                );
            case "NL":
                return array(
                    $this->getConfig(self::NL_ENABLE, $storeCode),
                    (int) $this->getConfig(self::NL_TRAFFIC_SOURCE_NUMBER, $storeCode),
                    (int) $this->getConfig(self::NL_TRAFFIC_MEDIUM_NUMBER, $storeCode),

                );
            case "CH":
                return array(
                    array(
                        "de" => $this->getConfig(self::CH_DE_ENABLE, $storeCode),
                        "fr" => $this->getConfig(self::CH_FR_ENABLE, $storeCode)
                    ),
                    array(
                        "de" => (int) $this->getConfig(self::CH_DE_TRAFFIC_SOURCE_NUMBER, $storeCode),
                        "fr" => (int) $this->getConfig(self::CH_FR_TRAFFIC_SOURCE_NUMBER, $storeCode)
                    ),
                    array(
                        "de" => (int) $this->getConfig(self::CH_DE_TRAFFIC_MEDIUM_NUMBER, $storeCode),
                        "fr" => (int) $this->getConfig(self::CH_FR_TRAFFIC_MEDIUM_NUMBER, $storeCode)
                    ),

                );
            case "FR":
                return array(
                    $this->getConfig(self::FR_ENABLE, $storeCode),
                    (int) $this->getConfig(self::FR_TRAFFIC_SOURCE_NUMBER, $storeCode),
                    (int) $this->getConfig(self::FR_TRAFFIC_MEDIUM_NUMBER, $storeCode),

                );
            case "IT":
                return array(
                    $this->getConfig(self::IT_ENABLE, $storeCode),
                    (int) $this->getConfig(self::IT_TRAFFIC_SOURCE_NUMBER, $storeCode),
                    (int) $this->getConfig(self::IT_TRAFFIC_MEDIUM_NUMBER, $storeCode),

                );
            case "IE":
                return array(
                    $this->getConfig(self::IE_ENABLE, $storeCode),
                    (int) $this->getConfig(self::IE_TRAFFIC_SOURCE_NUMBER, $storeCode),
                    (int) $this->getConfig(self::IE_TRAFFIC_MEDIUM_NUMBER, $storeCode),

                );
            case "GB":
                return array(
                    $this->getConfig(self::UK_ENABLE, $storeCode),
                    (int) $this->getConfig(self::UK_TRAFFIC_SOURCE_NUMBER, $storeCode),
                    (int) $this->getConfig(self::UK_TRAFFIC_MEDIUM_NUMBER, $storeCode),

                );
            case "DK":
                return array(
                    $this->getConfig(self::DK_ENABLE, $storeCode),
                    (int) $this->getConfig(self::DK_TRAFFIC_SOURCE_NUMBER, $storeCode),
                    (int) $this->getConfig(self::DK_TRAFFIC_MEDIUM_NUMBER, $storeCode),

                );
            case "SE":
                return array(
                    $this->getConfig(self::SE_ENABLE, $storeCode),
                    (int) $this->getConfig(self::SE_TRAFFIC_SOURCE_NUMBER, $storeCode),
                    (int) $this->getConfig(self::SE_TRAFFIC_MEDIUM_NUMBER, $storeCode),

                );
            case "ES":
                return array(
                    $this->getConfig(self::ES_ENABLE, $storeCode),
                    (int) $this->getConfig(self::ES_TRAFFIC_SOURCE_NUMBER, $storeCode),
                    (int) $this->getConfig(self::ES_TRAFFIC_MEDIUM_NUMBER, $storeCode),

                );
            case "BE":
                return array(
                    array(
                        "nl" => $this->getConfig(self::BE_NL_ENABLE, $storeCode),
                        "fr" => $this->getConfig(self::BE_FR_ENABLE, $storeCode)
                    ),
                    array(
                        "nl" => (int) $this->getConfig(self::BE_NL_TRAFFIC_SOURCE_NUMBER, $storeCode),
                        "fr" => (int) $this->getConfig(self::BE_FR_TRAFFIC_SOURCE_NUMBER, $storeCode)
                    ),
                    array(
                        "nl" => (int) $this->getConfig(self::BE_NL_TRAFFIC_MEDIUM_NUMBER, $storeCode),
                        "fr" => (int) $this->getConfig(self::BE_FR_TRAFFIC_MEDIUM_NUMBER, $storeCode)
                    ),

                );
            case "PL":
                return array(
                    $this->getConfig(self::PL_ENABLE, $storeCode),
                    (int) $this->getConfig(self::PL_TRAFFIC_SOURCE_NUMBER, $storeCode),
                    (int) $this->getConfig(self::PL_TRAFFIC_MEDIUM_NUMBER, $storeCode),

                );
            default:
                return array(
                    0,
                    0,
                    0,
                );
        }
    }
}