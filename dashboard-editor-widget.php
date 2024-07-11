<?php

/**
 * Plugin Name: Nodes: Theme Switch Widget
 * Plugin URI: https://agora.xtec.cat/nodes/
 * Description: Widget to switch between Nodes and Astra themes from the control panel.
 * @package Nodes_Editor_Widget
 * @author: Jose Alejandro Escobar Mosqueda
 * @version: 1.0.0
 * @license: GPLv3
 */
const ASTRA = 'astra';

/**
 * If there is the specific GET parameter, activate or deactivate the editor.
 */
$url_param = $_GET['dashboard-theme-widget'] ?? '';

if (!empty($url_param)) {
    if ($url_param === 'activate_nodes') {
        $reactor_theme = get_option('stylesheet_nodes_1');
        if ($reactor_theme) {
            switch_theme($reactor_theme);
        }
    } elseif ($url_param === 'activate_astra') {
        first_time();
        add_action('wp_loaded', function () {
            switch_theme(ASTRA);
        });
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

function dashboard_widget_theme_switch() {

    $current_theme = strtolower(wp_get_theme());

    echo '<div style="display:flex; align-items:center;">
        <div style="flex:1; margin-bottom:10px;">
            <p>Des d\'aquí podeu canviar el tema del vostre Nodes. La primera vegada que activeu el tema
               del Nodes 2, s\'importaran els paràmetres de configuració del tema del Nodes 1. Podeu
               tornar enrere i fer el canvi tantes vegades com vulgueu, però la importació només es
               farà una vegada.</p>
        </div>
    </div>';

    if (str_contains($current_theme, 'nodes')) {
        echo '<a class="button button-primary" href="' . admin_url() . '?dashboard-theme-widget=activate_astra">Activa el tema del Nodes 2</a>';
    } else {
        echo '<a class="button button-primary" href="' . admin_url() . '?dashboard-theme-widget=activate_nodes">Activa el tema del Nodes 1</a>';
    }

}

function first_time() {

    $stylesheet_nodes_1 = get_option('stylesheet_nodes_1');

    if (!$stylesheet_nodes_1) {
        $stylesheet = get_option('stylesheet');
        if (!update_option('stylesheet_nodes_1', $stylesheet)) {
            error_log('Failed to update stylesheet_nodes_1 option');
        }
    }

}
