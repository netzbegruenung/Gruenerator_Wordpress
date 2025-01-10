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
    $expert_mode = (bool)get_option('gruenerator_expert_mode', false);
    $css_active = (bool)get_option('gruenerator_custom_css_active', false);
    
    if ($expert_mode) {
        // Im Expertenmodus individuelle Einstellungen anwenden
        $hide_topbar = (bool)get_option('gruenerator_hide_topbar', false);
        $header_color = get_option('gruenerator_header_color', 'original');
        $navbar_color = get_option('gruenerator_navbar_color', 'sand');
        $title_color = get_option('gruenerator_title_color', 'black');
        $navbar_text_color = get_option('gruenerator_navbar_text_color', 'tanne');
        
        $css = '';
        
        if ($hide_topbar) {
            $css .= '.topmenu { display: none !important; }';
        }
        
        if ($header_color !== 'original') {
            if ($header_color === 'tanne') {
                $css .= '.site-header > .bloginfo.bg-primary { background-color: var(--gruenerator-tanne, #005437) !important; }';
            } elseif ($header_color === 'white') {
                $css .= '.site-header > .bloginfo.bg-primary { background-color: #ffffff !important; }';
            }
        }

        // Navbar-Farbe
        if ($navbar_color === 'sand') {
            $css .= '.navbar.navbar-main.navbar-expand-lg.bg-white { background-color: #F5F1E9 !important; }';
        } elseif ($navbar_color === 'white') {
            $css .= '.navbar.navbar-main.navbar-expand-lg.bg-white { background-color: #ffffff !important; }';
        } elseif ($navbar_color === 'tanne') {
            $css .= '.navbar.navbar-main.navbar-expand-lg.bg-white { background-color: var(--gruenerator-tanne, #005437) !important; }';
        }

        // Navbar-Schriftfarbe
        switch ($navbar_text_color) {
            case 'white':
                $css .= '.navbar-light .navbar-nav .nav-link { color: #ffffff !important; }';
                break;
            case 'sand':
                $css .= '.navbar-light .navbar-nav .nav-link { color: #F5F1E9 !important; }';
                break;
            case 'tanne':
                $css .= '.navbar-light .navbar-nav .nav-link { color: var(--gruenerator-tanne, #005437) !important; }';
                break;
            case 'black':
                $css .= '.navbar-light .navbar-nav .nav-link { color: #000000 !important; }';
                break;
        }

        // Titel-Farbe
        switch ($title_color) {
            case 'white':
                $css .= '.theme--default .bloginfo-name { color: #ffffff !important; }';
                break;
            case 'sand':
                $css .= '.theme--default .bloginfo-name { color: #F5F1E9 !important; }';
                break;
            case 'tanne':
                $css .= '.theme--default .bloginfo-name { color: var(--gruenerator-tanne, #005437) !important; }';
                break;
            case 'black':
                $css .= '.theme--default .bloginfo-name { color: #000000 !important; }';
                break;
        }
        
        if ($css !== '') {
            echo '<style type="text/css">' . wp_strip_all_tags($css) . '</style>';
        }
    } else if ($css_active) {
        // Normaler Modus mit komplettem CSS
        $custom_css = (string)get_theme_mod('gruenerator_custom_css', '');
        if ($custom_css !== '') {
            echo '<style type="text/css">' . wp_strip_all_tags($custom_css) . '</style>';
        }
    }
}
add_action('wp_head', 'gruenerator_apply_custom_css');

// Seite für das Aktivieren/Deaktivieren von benutzerdefiniertem CSS
function gruenerator_css_page() {
    $expert_mode = (bool)get_option('gruenerator_expert_mode', false);
    $css_active = (bool)get_option('gruenerator_custom_css_active', false);
    $hide_topbar = (bool)get_option('gruenerator_hide_topbar', false);

    ?>
    <div class="wrap">
        <h1>Design-Einstellungen</h1>
        
        <form id="gruenerator-css-form">
            <?php wp_nonce_field('gruenerator_toggle_css', 'gruenerator_css_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">Expertenmodus</th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="expert_mode" id="expert_mode" value="1" <?php checked($expert_mode); ?>>
                            <span class="slider round"></span>
                        </label>
                        <span id="expert-status"><?php echo $expert_mode ? 'Aktiviert' : 'Deaktiviert'; ?></span>
                        <p class="description">Im Expertenmodus können einzelne Design-Elemente individuell angepasst werden.</p>
                    </td>
                </tr>

                <tr id="standard-mode-settings" style="<?php echo $expert_mode ? 'display: none;' : ''; ?>">
                    <th scope="row">Grünerator Design</th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="toggle_css" id="toggle_css" value="1" <?php checked($css_active); ?>>
                            <span class="slider round"></span>
                        </label>
                        <span id="css-status"><?php echo $css_active ? 'Aktiviert' : 'Deaktiviert'; ?></span>
                        <p class="description">Aktiviere diese Option, um das optimierte Grüne Design zu verwenden.</p>
                    </td>
                </tr>

                <tr id="expert-mode-settings" style="<?php echo !$expert_mode ? 'display: none;' : ''; ?>">
                    <th scope="row">Topbar ausblenden</th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="hide_topbar" id="hide_topbar" value="1" <?php checked($hide_topbar); ?>>
                            <span class="slider round"></span>
                        </label>
                        <span id="topbar-status"><?php echo $hide_topbar ? 'Ausgeblendet' : 'Sichtbar'; ?></span>
                        <p class="description">Wähle, ob die obere Leiste (Topbar) angezeigt werden soll.</p>
                    </td>
                </tr>
            </table>
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
        function updateVisibility() {
            var expertMode = $('#expert_mode').is(':checked');
            $('#standard-mode-settings').toggle(!expertMode);
            $('#expert-mode-settings').toggle(expertMode);
            if (expertMode) {
                $('#toggle_css').prop('checked', false);
            }
        }

        $('#expert_mode').on('change', function() {
            var isChecked = $(this).is(':checked');
            var nonce = $('#gruenerator_css_nonce').val();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'gruenerator_toggle_expert_mode',
                    expert_mode: isChecked ? 1 : 0,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('#expert-status').text(isChecked ? 'Aktiviert' : 'Deaktiviert');
                        updateVisibility();
                    } else {
                        alert('Es gab einen Fehler beim Aktualisieren der Einstellung.');
                        $('#expert_mode').prop('checked', !isChecked);
                    }
                }
            });
        });

        $('#hide_topbar').on('change', function() {
            var isChecked = $(this).is(':checked');
            var nonce = $('#gruenerator_css_nonce').val();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'gruenerator_toggle_topbar',
                    hide_topbar: isChecked ? 1 : 0,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('#topbar-status').text(isChecked ? 'Ausgeblendet' : 'Sichtbar');
                    } else {
                        alert('Es gab einen Fehler beim Aktualisieren der Einstellung.');
                        $('#hide_topbar').prop('checked', !isChecked);
                    }
                }
            });
        });

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

// AJAX-Handler für das Umschalten des Expertenmodus
function gruenerator_ajax_toggle_expert_mode() {
    check_ajax_referer('gruenerator_toggle_css', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Nicht autorisiert');
    }

    $expert_mode = isset($_POST['expert_mode']) ? (bool)$_POST['expert_mode'] : false;
    update_option('gruenerator_expert_mode', $expert_mode);
    
    if ($expert_mode) {
        update_option('gruenerator_custom_css_active', false);
    }
    
    wp_send_json_success();
}
add_action('wp_ajax_gruenerator_toggle_expert_mode', 'gruenerator_ajax_toggle_expert_mode');

// AJAX-Handler für das Umschalten der Topbar
function gruenerator_ajax_toggle_topbar() {
    check_ajax_referer('gruenerator_toggle_css', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Nicht autorisiert');
    }

    $hide_topbar = isset($_POST['hide_topbar']) ? (bool)$_POST['hide_topbar'] : false;
    update_option('gruenerator_hide_topbar', $hide_topbar);
    
    wp_send_json_success();
}
add_action('wp_ajax_gruenerator_toggle_topbar', 'gruenerator_ajax_toggle_topbar');

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
    if (get_option('gruenerator_expert_mode') === false) {
        add_option('gruenerator_expert_mode', false);
    }
    if (get_option('gruenerator_hide_topbar') === false) {
        add_option('gruenerator_hide_topbar', false);
    }
    gruenerator_custom_css_inject();
}
add_action('admin_init', 'gruenerator_init_css_settings');

// Registriere die Settings-Seite im WordPress-Menü
function gruenerator_register_settings_page() {
    add_submenu_page(
        'gruenerator',
        __('Design-Einstellungen', 'gruenerator'),
        __('Design', 'gruenerator'),
        'manage_options',
        'gruenerator-design',
        'gruenerator_css_page'
    );
}
add_action('admin_menu', 'gruenerator_register_settings_page');

// AJAX-Handler für das Ändern der Header-Farbe
function gruenerator_ajax_change_header_color() {
    check_ajax_referer('gruenerator_toggle_css', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Nicht autorisiert');
    }

    $header_color = isset($_POST['header_color']) ? sanitize_text_field($_POST['header_color']) : 'original';
    update_option('gruenerator_header_color', $header_color);
    
    wp_send_json_success();
}
add_action('wp_ajax_gruenerator_change_header_color', 'gruenerator_ajax_change_header_color');

// AJAX-Handler für das Ändern der Navbar-Farbe
function gruenerator_ajax_change_navbar_color() {
    check_ajax_referer('gruenerator_toggle_css', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Nicht autorisiert');
    }

    $navbar_color = isset($_POST['navbar_color']) ? sanitize_text_field($_POST['navbar_color']) : 'sand';
    update_option('gruenerator_navbar_color', $navbar_color);
    
    wp_send_json_success();
}
add_action('wp_ajax_gruenerator_change_navbar_color', 'gruenerator_ajax_change_navbar_color');

// AJAX-Handler für das Ändern der Titelfarbe
function gruenerator_ajax_change_title_color() {
    check_ajax_referer('gruenerator_toggle_css', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Nicht autorisiert');
    }

    $title_color = isset($_POST['title_color']) ? sanitize_text_field($_POST['title_color']) : 'black';
    update_option('gruenerator_title_color', $title_color);
    
    wp_send_json_success();
}
add_action('wp_ajax_gruenerator_change_title_color', 'gruenerator_ajax_change_title_color');

// AJAX-Handler für das Ändern der Navbar-Schriftfarbe
function gruenerator_ajax_change_navbar_text_color() {
    check_ajax_referer('gruenerator_toggle_css', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Nicht autorisiert');
    }

    $navbar_text_color = isset($_POST['navbar_text_color']) ? sanitize_text_field($_POST['navbar_text_color']) : 'tanne';
    update_option('gruenerator_navbar_text_color', $navbar_text_color);
    
    wp_send_json_success();
}
add_action('wp_ajax_gruenerator_change_navbar_text_color', 'gruenerator_ajax_change_navbar_text_color');
