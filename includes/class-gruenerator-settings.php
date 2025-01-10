<?php
// Verhindert direkten Zugriff auf die Datei
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Die Hauptklasse für die Plugin-Einstellungen
 */
class Gruenerator_Settings {
    /**
     * Die einzige Instanz dieser Klasse
     *
     * @var Gruenerator_Settings
     */
    private static $instance = null;

    /**
     * Konstruktor
     */
    private function __construct() {}

    /**
     * Gibt die einzige Instanz dieser Klasse zurück
     *
     * @return Gruenerator_Settings
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Gibt den Beschreibungstext für die Dashboard-Karte zurück
     *
     * @return string
     */
    public static function get_dashboard_description() {
        return __('Verwalte die Startseiten-Einstellung und setze bei Bedarf die Inhalte auf die Standardwerte zurück.', 'gruenerator');
    }

    /**
     * Setzt die Landing Page als Startseite
     *
     * @return bool|WP_Error
     */
    public function set_frontpage() {
        if (!current_user_can('manage_options')) {
            return new WP_Error('insufficient_permissions', __('Unzureichende Berechtigungen', 'gruenerator'));
        }

        $landing_page_id = get_option('gruenerator_landing_page_id');
        if (!$landing_page_id) {
            return new WP_Error('no_landing_page', __('Keine Landing Page gefunden. Bitte durchlaufe zuerst den Setup-Assistenten.', 'gruenerator'));
        }

        // Überprüfe, ob die Seite existiert
        $landing_page = get_post($landing_page_id);
        if (!$landing_page || $landing_page->post_type !== 'page') {
            return new WP_Error('invalid_landing_page', __('Die Landing Page existiert nicht mehr oder ist keine gültige Seite.', 'gruenerator'));
        }

        update_option('show_on_front', 'page');
        update_option('page_on_front', $landing_page_id);
        
        add_settings_error(
            'gruenerator_messages',
            'gruenerator_message',
            __('Die Landing Page wurde erfolgreich als Startseite festgelegt.', 'gruenerator'),
            'updated'
        );
        
        return true;
    }

    /**
     * Setzt die Startseite zurück
     *
     * @return bool|WP_Error
     */
    public function reset_frontpage() {
        if (!current_user_can('manage_options')) {
            return new WP_Error('insufficient_permissions', __('Unzureichende Berechtigungen', 'gruenerator'));
        }

        $landing_page_id = get_option('gruenerator_landing_page_id');
        $current_front_page = get_option('page_on_front');

        // Überprüfe, ob die Landing Page aktuell als Startseite festgelegt ist
        if ($landing_page_id != $current_front_page) {
            return new WP_Error('not_landing_page', __('Die Grünerator Landing Page ist aktuell nicht als Startseite festgelegt.', 'gruenerator'));
        }

        update_option('show_on_front', 'posts');
        delete_option('page_on_front');
        
        add_settings_error(
            'gruenerator_messages',
            'gruenerator_message',
            __('Die Startseiten-Einstellung wurde erfolgreich zurückgesetzt.', 'gruenerator'),
            'updated'
        );
        
        return true;
    }

    /**
     * Setzt alle Inhalte auf die Standardwerte zurück
     */
    public function reset_content_to_defaults() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Hero Content
        $hero_content = Gruenerator_Default_Content::get_hero_content();
        update_option('gruenerator_hero_image', '');
        update_option('gruenerator_hero_heading', $hero_content['heading']);
        update_option('gruenerator_hero_text', $hero_content['text']);

        // About Content
        $about_content = Gruenerator_Default_Content::get_about_content();
        update_option('gruenerator_about_me_title', $about_content['title']);
        update_option('gruenerator_about_me_content', $about_content['content']);

        // Hero Image Block
        $hero_image_content = Gruenerator_Default_Content::get_hero_image_content();
        update_option('gruenerator_hero_image_block_image', '');
        update_option('gruenerator_hero_image_block_title', $hero_image_content['title']);
        update_option('gruenerator_hero_image_subtitle', $hero_image_content['subtitle']);

        // Themes
        $themes = Gruenerator_Default_Content::get_themes();
        for ($i = 1; $i <= 3; $i++) {
            $theme = isset($themes[$i - 1]) ? $themes[$i - 1] : array();
            update_option('gruenerator_theme_image_' . $i, '');
            update_option('gruenerator_theme_title_' . $i, isset($theme['title']) ? $theme['title'] : '');
            update_option('gruenerator_theme_content_' . $i, isset($theme['content']) ? $theme['content'] : '');
        }

        // Actions
        $actions = Gruenerator_Default_Content::get_actions();
        for ($i = 1; $i <= 3; $i++) {
            $action = isset($actions[$i - 1]) ? $actions[$i - 1] : array();
            update_option('gruenerator_action_image_' . $i, '');
            update_option('gruenerator_action_text_' . $i, isset($action['text']) ? $action['text'] : '');
            update_option('gruenerator_action_link_' . $i, isset($action['link']) ? $action['link'] : '');
        }

        // Contact Form
        $contact_form = Gruenerator_Default_Content::get_contact_form_content();
        update_option('gruenerator_contact_form_title', $contact_form['title']);
        update_option('gruenerator_contact_form_email', $contact_form['email']);
        update_option('gruenerator_contact_form_image', '');

        add_settings_error(
            'gruenerator_messages',
            'gruenerator_message',
            __('Alle Inhalte wurden erfolgreich auf die Standardwerte zurückgesetzt.', 'gruenerator'),
            'updated'
        );
    }

    /**
     * Rendert die Settings-Seite
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Du hast nicht die erforderlichen Berechtigungen, um diese Seite anzuzeigen.', 'gruenerator'));
        }

        $front_page_id = get_option('page_on_front');
        $landing_page_id = get_option('gruenerator_landing_page_id');
        $is_landing_page_front = ($front_page_id == $landing_page_id);
        $css_active = (bool)get_option('gruenerator_custom_css_active', false);
        $expert_mode = (bool)get_option('gruenerator_expert_mode', false);
        $hide_topbar = (bool)get_option('gruenerator_hide_topbar', false);
        $header_color = get_option('gruenerator_header_color', 'original');
        $navbar_color = get_option('gruenerator_navbar_color', 'sand');
        $title_color = get_option('gruenerator_title_color', 'black');
        $navbar_text_color = get_option('gruenerator_navbar_text_color', 'tanne');

        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <?php settings_errors('gruenerator_messages'); ?>

            <div class="gruenerator-settings-section">
                <h2><?php _e('Startseiten-Einstellung', 'gruenerator'); ?></h2>
                <p><?php _e('Hier kannst du festlegen, ob die Grünerator Landing Page als Startseite deiner Website angezeigt werden soll.', 'gruenerator'); ?></p>
                
                <form method="post" action="">
                    <?php wp_nonce_field('gruenerator_frontpage_settings'); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="gruenerator_frontpage">
                                    <?php _e('Startseite', 'gruenerator'); ?>
                                </label>
                            </th>
                            <td>
                                <?php if (!$landing_page_id): ?>
                                    <p class="description"><?php _e('Es wurde noch keine Landing Page erstellt. Bitte durchlaufe zuerst den Setup-Assistenten.', 'gruenerator'); ?></p>
                                <?php elseif ($is_landing_page_front): ?>
                                    <p><?php _e('Die Grünerator Landing Page ist aktuell als Startseite festgelegt.', 'gruenerator'); ?></p>
                                    <button type="submit" name="gruenerator_reset_frontpage" class="button button-secondary">
                                        <?php _e('Startseiten-Einstellung zurücksetzen', 'gruenerator'); ?>
                                    </button>
                                <?php else: ?>
                                    <p><?php _e('Die Grünerator Landing Page ist aktuell nicht als Startseite festgelegt.', 'gruenerator'); ?></p>
                                    <button type="submit" name="gruenerator_set_frontpage" class="button button-primary">
                                        <?php _e('Als Startseite festlegen', 'gruenerator'); ?>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="gruenerator-settings-section">
                <h2><?php _e('Design-Einstellungen', 'gruenerator'); ?></h2>
                <p><?php _e('Hier kannst du das Design deiner Website anpassen.', 'gruenerator'); ?></p>
                
                <form id="gruenerator-css-form">
                    <?php wp_nonce_field('gruenerator_toggle_css', 'gruenerator_css_nonce'); ?>
                    
                    <table class="form-table">
                        <tr id="standard-mode-settings">
                            <th scope="row">Grünerator Design</th>
                            <td>
                                <div class="gruenerator-toggle-switch">
                                    <label class="switch">
                                        <input type="checkbox" name="toggle_css" id="toggle_css" value="1" <?php checked($css_active); ?>>
                                        <span class="slider round"></span>
                                    </label>
                                    <span id="css-status"><?php echo $css_active ? 'Aktiviert' : 'Deaktiviert'; ?></span>
                                </div>
                                <p class="description">Aktiviere diese Option, um das optimierte Grüne Design zu verwenden.</p>
                            </td>
                        </tr>

                        <tr class="expert-mode-settings">
                            <th scope="row">Header-Farbe</th>
                            <td>
                                <select name="header_color" id="header_color">
                                    <option value="original" <?php selected($header_color, 'original'); ?>>Original</option>
                                    <option value="tanne" <?php selected($header_color, 'tanne'); ?>>Tannengrün</option>
                                    <option value="white" <?php selected($header_color, 'white'); ?>>Weiß</option>
                                </select>
                                <p class="description">Wähle die Farbe für den Header deiner Website.</p>
                            </td>
                        </tr>

                        <tr class="expert-mode-settings">
                            <th scope="row">Navbar-Farbe</th>
                            <td>
                                <select name="navbar_color" id="navbar_color">
                                    <option value="sand" <?php selected($navbar_color, 'sand'); ?>>Sand</option>
                                    <option value="white" <?php selected($navbar_color, 'white'); ?>>Weiß</option>
                                    <option value="tanne" <?php selected($navbar_color, 'tanne'); ?>>Tannengrün</option>
                                </select>
                                <p class="description">Wähle die Farbe für die Hauptnavigation deiner Website.</p>
                            </td>
                        </tr>

                        <tr class="expert-mode-settings">
                            <th scope="row">Navbar-Schriftfarbe</th>
                            <td>
                                <select name="navbar_text_color" id="navbar_text_color">
                                    <option value="black" <?php selected($navbar_text_color, 'black'); ?>>Schwarz</option>
                                    <option value="white" <?php selected($navbar_text_color, 'white'); ?>>Weiß</option>
                                    <option value="sand" <?php selected($navbar_text_color, 'sand'); ?>>Sand</option>
                                    <option value="tanne" <?php selected($navbar_text_color, 'tanne'); ?>>Tannengrün</option>
                                </select>
                                <p class="description">Wähle die Schriftfarbe für die Links in der Hauptnavigation.</p>
                            </td>
                        </tr>

                        <tr class="expert-mode-settings">
                            <th scope="row">Titel-Farbe</th>
                            <td>
                                <select name="title_color" id="title_color">
                                    <option value="black" <?php selected($title_color, 'black'); ?>>Schwarz</option>
                                    <option value="white" <?php selected($title_color, 'white'); ?>>Weiß</option>
                                    <option value="sand" <?php selected($title_color, 'sand'); ?>>Sand</option>
                                    <option value="tanne" <?php selected($title_color, 'tanne'); ?>>Tannengrün</option>
                                </select>
                                <p class="description">Wähle die Farbe für den Titel deiner Website.</p>
                            </td>
                        </tr>

                        <tr class="expert-mode-settings">
                            <th scope="row">Topbar ausblenden</th>
                            <td>
                                <div class="gruenerator-toggle-switch">
                                    <label class="switch">
                                        <input type="checkbox" name="hide_topbar" id="hide_topbar" value="1" <?php checked($hide_topbar); ?>>
                                        <span class="slider round"></span>
                                    </label>
                                    <span id="topbar-status"><?php echo $hide_topbar ? 'Ausgeblendet' : 'Sichtbar'; ?></span>
                                </div>
                                <p class="description">Wähle, ob die obere Leiste (Topbar) angezeigt werden soll.</p>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="gruenerator-settings-section">
                <h2><?php _e('Inhalte zurücksetzen', 'gruenerator'); ?></h2>
                <p><?php _e('Hier kannst du alle im Setup-Wizard eingegebenen Inhalte auf die Standardwerte zurücksetzen.', 'gruenerator'); ?></p>
                
                <form method="post" action="">
                    <?php wp_nonce_field('gruenerator_reset_content'); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="gruenerator_reset_content">
                                    <?php _e('Inhalte zurücksetzen', 'gruenerator'); ?>
                                </label>
                            </th>
                            <td>
                                <button type="submit" name="gruenerator_reset_content" id="gruenerator_reset_content" class="button button-secondary">
                                    <?php _e('Auf Standardwerte zurücksetzen', 'gruenerator'); ?>
                                </button>
                                <p class="description">
                                    <?php _e('ACHTUNG: Diese Aktion kann nicht rückgängig gemacht werden. Alle benutzerdefinierten Inhalte werden durch die Standardwerte ersetzt.', 'gruenerator'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="gruenerator-settings-section">
                <h2><?php _e('Erweiterte Einstellungen', 'gruenerator'); ?></h2>
                <p><?php _e('Diese Einstellungen sind für fortgeschrittene Benutzer gedacht.', 'gruenerator'); ?></p>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">Expertenmodus</th>
                        <td>
                            <div class="gruenerator-toggle-switch">
                                <label class="switch">
                                    <input type="checkbox" name="expert_mode" id="expert_mode" value="1" <?php checked($expert_mode); ?>>
                                    <span class="slider round"></span>
                                </label>
                                <span id="expert-status"><?php echo $expert_mode ? 'Aktiviert' : 'Deaktiviert'; ?></span>
                            </div>
                            <p class="description">Im Expertenmodus können einzelne Design-Elemente individuell angepasst werden.</p>
                        </td>
                    </tr>
                </table>
            </div>
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
                if (expertMode) {
                    $('.expert-mode-settings').show();
                    $('#standard-mode-settings').hide();
                    $('#toggle_css').prop('checked', false);
                } else {
                    $('.expert-mode-settings').hide();
                    $('#standard-mode-settings').show();
                }
            }

            // Initial visibility
            updateVisibility();

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

            $('#header_color').on('change', function() {
                var selectedColor = $(this).val();
                var nonce = $('#gruenerator_css_nonce').val();
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'gruenerator_change_header_color',
                        header_color: selectedColor,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (!response.success) {
                            alert('Es gab einen Fehler beim Aktualisieren der Header-Farbe.');
                        }
                    }
                });
            });

            $('#navbar_color').on('change', function() {
                var selectedColor = $(this).val();
                var nonce = $('#gruenerator_css_nonce').val();
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'gruenerator_change_navbar_color',
                        navbar_color: selectedColor,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (!response.success) {
                            alert('Es gab einen Fehler beim Aktualisieren der Navbar-Farbe.');
                        }
                    }
                });
            });

            $('#title_color').on('change', function() {
                var selectedColor = $(this).val();
                var nonce = $('#gruenerator_css_nonce').val();
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'gruenerator_change_title_color',
                        title_color: selectedColor,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (!response.success) {
                            alert('Es gab einen Fehler beim Aktualisieren der Titel-Farbe.');
                        }
                    }
                });
            });

            $('#navbar_text_color').on('change', function() {
                var selectedColor = $(this).val();
                var nonce = $('#gruenerator_css_nonce').val();
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'gruenerator_change_navbar_text_color',
                        navbar_text_color: selectedColor,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (!response.success) {
                            alert('Es gab einen Fehler beim Aktualisieren der Navbar-Schriftfarbe.');
                        }
                    }
                });
            });
        });
        </script>
        <?php
    }
}
