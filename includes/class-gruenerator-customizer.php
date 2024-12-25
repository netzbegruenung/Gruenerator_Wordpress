<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Funktion, um benutzerdefiniertes CSS in den Customizer einzufügen
function gruenerator_custom_css_inject() {
    // Das CSS, das eingefügt werden soll
    $custom_css = '
    .topmenu {
        display: none !important;
    }
    .site-header > .bloginfo.bg-primary {
        background-color: #005437 !important;    
    }
    .navbar.navbar-main.navbar-expand-lg.bg-white {
        background-color: #F5F1E9 !important;
        font-weight: bold;
    }
    .navbar.navbar-main.navbar-expand-lg.bg-white a {
        color: #005437 !important;
        text-transform: none;
    }';

    // CSS in den Customizer einfügen
    if (!get_theme_mod('gruenerator_custom_css')) {
        set_theme_mod('gruenerator_custom_css', $custom_css);
    }
}

// Funktion, um das benutzerdefinierte CSS auf der Website auszugeben
function gruenerator_apply_custom_css() {
    $css_active = (bool)get_option('gruenerator_custom_css_active', false);
    if ($css_active) {
        $custom_css = (string)get_theme_mod('gruenerator_custom_css', '');
        if ($custom_css !== '') {
            echo '<style type="text/css">' . wp_strip_all_tags($custom_css) . '</style>';
        }
    }
}
add_action('wp_head', 'gruenerator_apply_custom_css');

// Seite für das Aktivieren/Deaktivieren von benutzerdefiniertem CSS
function gruenerator_css_page() {
    $css_active = (bool)get_option('gruenerator_custom_css_active', false);

    ?>
    <div class="wrap">
        <h1>Benutzerdefiniertes CSS verwalten</h1>
        <p>Verwende den Schieberegler unten, um das benutzerdefinierte CSS zu aktivieren oder zu deaktivieren. Dieses CSS ändert Header, Menü und Links auf deiner Seite.</p>
        <form id="gruenerator-css-form">
            <?php wp_nonce_field('gruenerator_toggle_css', 'gruenerator_css_nonce'); ?>
            <label class="switch">
                <input type="checkbox" name="toggle_css" id="toggle_css" value="1" <?php checked($css_active); ?>>
                <span class="slider round"></span>
            </label>
            <span id="css-status"><?php echo $css_active ? 'Aktiviert' : 'Deaktiviert'; ?></span>
        </form>
    </div>
    <style>
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: .4s;
    }
    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      transition: .4s;
    }
    input:checked + .slider {
      background-color: #2196F3;
    }
    input:checked + .slider:before {
      transform: translateX(26px);
    }
    .slider.round {
      border-radius: 34px;
    }
    .slider.round:before {
      border-radius: 50%;
    }
    </style>
    <script>
    jQuery(document).ready(function($) {
        $('#toggle_css').on('change', function() {
            var isChecked = $(this).is(':checked');
            var nonce = $('#gruenerator_css_nonce').val();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'gruenerator_toggle_css',
                    toggle_css: isChecked ? 1 : 0,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('#css-status').text(isChecked ? 'Aktiviert' : 'Deaktiviert');
                    } else {
                        alert('Es gab einen Fehler beim Aktualisieren der Einstellung.');
                        $('#toggle_css').prop('checked', !isChecked);
                    }
                }
            });
        });
    });
    </script>
    <?php
}

// AJAX-Handler für das Umschalten des CSS
function gruenerator_ajax_toggle_css() {
    check_ajax_referer('gruenerator_toggle_css', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Nicht autorisiert');
    }

    $css_active = isset($_POST['toggle_css']) ? (bool)$_POST['toggle_css'] : false;
    update_option('gruenerator_custom_css_active', $css_active);
    
    wp_send_json_success();
}
add_action('wp_ajax_gruenerator_toggle_css', 'gruenerator_ajax_toggle_css');

// Initialisierung der CSS-Einstellungen
function gruenerator_init_css_settings() {
    if (get_option('gruenerator_custom_css_active') === false) {
        add_option('gruenerator_custom_css_active', false);
    }
    gruenerator_custom_css_inject();
}
add_action('admin_init', 'gruenerator_init_css_settings');
