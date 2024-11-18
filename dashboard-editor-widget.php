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
            'Nodes 2',
            'dashboard_widget_theme_switch'
        );
    }
});

function dashboard_widget_theme_switch() {

    $current_theme = strtolower(wp_get_theme());

    echo '<div style="display:flex; align-items:center;">
        <div style="flex:1; margin-bottom:10px;">
            <p>La nova versió de Nodes incorpora un disseny més actual i l\'edició per blocs. Per fer el canvi, feu clic al
               botó Activa. Haureu de fer algunes modificacions que podeu preparar seguint
               <a href="https://projectes.xtec.cat/digital/serveis-digitals/nodes/guia-de-nodes/pautes-per-al-canvi/" target="_blank">
               aquestes pautes</a>.
            </p>
        </div>
    </div>';

    if (str_contains($current_theme, 'nodes')) {
        echo '<a class="button button-primary" href="' . admin_url() . '?dashboard-theme-widget=activate_astra">Activa Nodes 2</a>';
    } else {
        echo '<a class="button button-primary" href="' . admin_url() . '?dashboard-theme-widget=activate_nodes">Activa Nodes 1</a>';
    }

    echo '&nbsp;&nbsp;&nbsp;';
    echo '<a class="button button-secondary" href="https://projectes.xtec.cat/digital/serveis-digitals/nodes/guia-de-nodes/"
             target="_blank">Guia de Nodes</a>';

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
