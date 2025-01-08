<?php

require_once plugin_dir_path(file: __FILE__) . '../settings/get-settings.php';

class Magento_Sovendus_Settings
{
    public static function add_section($sections)
    {
        $sections['magentosovendus'] = __('Sovendus App', 'magento-sovendus');
        return $sections;
    }

    public static function settings($settings, $current_section)
    {
        if ($current_section === 'wcsovendus') {
            echo '<div id="sovendus-settings-container"></div>';
            return [];
        } else {
            return $settings;
        }
    }
}

function enqueue_sovendus_react_scripts($hook)
{
    if ($hook !== 'woocommerce_page_wc-settings') {
        return;
    }
    wp_enqueue_script('frontend_react_loader', plugins_url('../dist/frontend_react_loader.js', __FILE__), ['react', 'react-dom'], null, true);

    wp_localize_script('frontend_react_loader', 'sovendusSettings', [
        'settings' => WC_Sovendus_Helper::get_settings(countryCode: null),
        'ajaxurl' => admin_url('admin-ajax.php'),
    ]);
}
