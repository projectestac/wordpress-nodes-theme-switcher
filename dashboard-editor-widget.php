<?php

/**
 * @package Nodes_Editor_Widget
 * @version 1.0.0
 */
/**
 * Plugin Name: Nodes: Theme Switch Widget
 * Plugin URI: https://agora.xtec.cat/nodes/
 * Description: Widget to switch between Nodes and Astra themes from the control panel.
 * Author: Jose Alejandro Escobar Mosqueda
 * Version: 1.0.0
 * License: GPLv3
 */

const ASTRA = 'astra';

/**
 * If there is the specific GET parameter, activate or deactivate the editor.
 */
$url_param = $_GET['dashboard-theme-widget'] ?? '';

if (!empty($url_param)) {
    if ($url_param === 'activate_nodes') {
        $reactorTheme = get_option('stylesheet_nodes_1');
        if ($reactorTheme) {
            switch_theme($reactorTheme);
        }
    } elseif ($url_param === 'activate_astra') {
        firstTime();
        switch_theme(ASTRA);
    }
}

/**
 * Add the widget to the dashboard.
 */
add_action('wp_dashboard_setup', function () {
    if (current_user_can('switch_themes')) {
        wp_add_dashboard_widget(
            'dashboard_widget_theme_switch',
            'Canvi de tema',
            'dashboard_widget_theme_switch'
        );
    }
});

function dashboard_widget_theme_switch()
{
    $current_theme = strtolower(wp_get_theme());

    echo '<div style="display:flex; align-items:center;">
        <div style="flex:1; margin-bottom:10px;">
            <p>This widget allows you to switch between the Nodes and Astra themes. Choose the desired theme and click the button to activate it.</p>
        </div>
    </div>';

    if (str_contains($current_theme, 'nodes')) {
        echo '<a class="button button-primary" href="' . admin_url() . '?dashboard-theme-widget=activate_astra">Activate Astra</a>';
    } else {
        echo '<a class="button button-primary" href="' . admin_url() . '?dashboard-theme-widget=activate_nodes">Activate Nodes</a>';
    }
}

function firstTime()
{
    $stylesheet_nodes_1 = get_option('stylesheet_nodes_1');
    if (!$stylesheet_nodes_1) {
        $stylesheet = get_option('stylesheet');
        if (!update_option('stylesheet_nodes_1', $stylesheet)) {
            error_log('Failed to update stylesheet_nodes_1 option');
        }
    }
}
