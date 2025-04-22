<?php

/**
 * Plugin Name: Nodes: Theme Switch Widget
 * Plugin URI: https://agora.xtec.cat/nodes/
 * Description: Widget to switch between Nodes and Astra themes from the control panel.
 *
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

        // Retrieve and apply the "Nodes 1" theme (if it exists).
        $reactor_theme = get_option('stylesheet_nodes_1');
        if ($reactor_theme) {
            switch_theme($reactor_theme);
        }

        // Restore the content of "GTranslate" from "GTranslate-nodes-1"
        restore_gtranslate_backup();

    } elseif ($url_param === 'activate_astra') {

        // Save the current content of "GTranslate" into "GTranslate-nodes-1"
        save_gtranslate_backup();

        // Activate the "Astra" theme.
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

function dashboard_widget_theme_switch(): void {

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

function first_time(): void {

    $stylesheet_nodes_1 = get_option('stylesheet_nodes_1');

    if (!$stylesheet_nodes_1) {
        $stylesheet = get_option('stylesheet');
        if (!update_option('stylesheet_nodes_1', $stylesheet)) {
            error_log('Failed to update stylesheet_nodes_1 option');
        }
    }

}

function save_gtranslate_backup(): void {

    $record_original = 'GTranslate';
    $record_backup = 'GTranslate_nodes_1';
    $value = unserialize(
        'a:39:{s:11:"pro_version";s:0:"";s:18:"enterprise_version";s:0:"";s:16:"wrapper_selector";s:19:".gtranslate_wrapper";s:14:"custom_domains";s:0:"";s:19:"custom_domains_data";s:0:"";s:15:"url_translation";s:0:"";s:17:"add_hreflang_tags";s:0:"";s:17:"email_translation";s:0:"";s:23:"email_translation_debug";s:0:"";s:12:"show_in_menu";s:0:"";s:26:"floating_language_selector";s:9:"top_right";s:21:"native_language_names";i:1;s:10:"enable_cdn";s:0:"";s:23:"detect_browser_language";s:0:"";s:12:"add_new_line";s:0:"";s:21:"select_language_label";s:8:"Tradueix";s:10:"custom_css";s:0:"";s:16:"default_language";s:2:"ca";s:11:"widget_look";s:8:"dropdown";s:9:"flag_size";i:24;s:10:"flag_style";s:2:"2d";s:10:"globe_size";i:60;s:11:"globe_color";s:7:"#66aaff";s:10:"incl_langs";a:8:{i:0;s:2:"ca";i:1;s:2:"es";i:2;s:2:"en";i:3;s:2:"ar";i:4;s:2:"de";i:5;s:2:"fr";i:6;s:5:"zh-CN";i:7;s:2:"ru";}s:11:"fincl_langs";a:4:{i:0;s:2:"es";i:1;s:2:"ar";i:2;s:2:"ca";i:3;s:2:"en";}s:9:"alt_flags";a:0:{}s:19:"switcher_text_color";s:4:"#666";s:20:"switcher_arrow_color";s:4:"#666";s:21:"switcher_border_color";s:4:"#ccc";s:25:"switcher_background_color";s:4:"#fff";s:32:"switcher_background_shadow_color";s:7:"#efefef";s:31:"switcher_background_hover_color";s:4:"#fff";s:19:"dropdown_text_color";s:4:"#000";s:20:"dropdown_hover_color";s:4:"#fff";s:25:"dropdown_background_color";s:4:"#eee";s:29:"float_switcher_open_direction";s:3:"top";s:23:"switcher_open_direction";s:3:"top";s:14:"language_codes";s:320:"af,sq,am,es,ar,hy,az,eu,be,bn,bs,bg,ca,ceb,ny,zh-CN,zh-TW,co,hr,cs,da,nl,en,eo,et,tl,fi,fr,fy,gl,ka,de,el,gu,ht,ha,haw,iw,hi,hmn,hu,is,ig,id,ga,it,ja,jw,kn,kk,km,ko,ku,ky,lo,la,lv,lt,lb,mk,mg,ms,ml,mt,mi,mr,mn,my,ne,no,ps,fa,pl,pt,pa,ro,ru,sm,gd,sr,st,sn,sd,si,sk,sl,so,su,sw,sv,tg,ta,te,th,tr,uk,ur,uz,vi,cy,xh,yi,yo,zu";s:15:"language_codes2";s:320:"af,sq,am,ca,es,en,ar,de,fr,it,pt,hy,az,eu,be,bn,bs,bg,ceb,ny,zh-CN,zh-TW,co,hr,cs,da,nl,eo,et,tl,fi,fy,gl,ka,el,gu,ht,ha,haw,iw,hi,hmn,hu,is,ig,id,ga,ja,jw,kn,kk,km,ko,ku,ky,lo,la,lv,lt,lb,mk,mg,ms,ml,mt,mi,mr,mn,my,ne,no,ps,fa,pl,pa,ro,ru,sm,gd,sr,st,sn,sd,si,sk,sl,so,su,sw,sv,tg,ta,te,th,tr,uk,ur,uz,vi,cy,xh,yi,yo,zu";}',
        ['allowed_classes' => true]
    );

    // Get the value of the original record from wp_options.
    $value_original = get_option($record_original);

    // If exists, save the original value to the backup record.
    if ($value_original !== false) {
        update_option($record_backup, $value_original);
    }

    // Update the original record with the new value.
    update_option($record_original, $value);

}

function restore_gtranslate_backup(): void {

    $record_original = 'GTranslate';
    $record_backup = 'GTranslate_nodes_1';

    // Recover the backup value.
    $value_backup = get_option($record_backup);

    if ($value_backup !== false) {
        // If the backup exists, restore its value to the original record.
        update_option($record_original, $value_backup);
    }

}
