<?php
/**
 * Klasse für die Verwaltung der Plugin-Einstellungen
 *
 * @package Gruenerator
 * @since 1.0.0
 */

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
     * @since 1.0.0
     * @access private
     * @var Gruenerator_Settings
     */
    private static $instance = null;

    /**
     * Konstruktor
     *
     * @since 1.0.0
     * @access private
     */
    private function __construct() {
        add_action('admin_post_gruenerator_reset_settings', array($this, 'handle_reset_settings'));
        add_action('admin_post_gruenerator_set_frontpage', array($this, 'handle_set_frontpage'));
        add_action('admin_post_gruenerator_reset_frontpage', array($this, 'handle_reset_frontpage'));
        add_action('admin_notices', array($this, 'show_admin_notices'));
    }

    /**
     * Gibt die einzige Instanz dieser Klasse zurück
     *
     * @since 1.0.0
     * @access public
     *
     * @return Gruenerator_Settings Die einzige Instanz dieser Klasse
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Rendert die Einstellungsseite
     *
     * @since 1.0.0
     * @access public
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die('Unzureichende Berechtigungen');
        }

        // Hole die aktuelle Startseiten-ID
        $front_page_id = get_option('page_on_front');
        $landing_page_id = get_option('gruenerator_landing_page_id');
        $is_landing_page_front = ($front_page_id == $landing_page_id);
        ?>
        <div class="wrap">
            <h1>Grünerator Einstellungen</h1>

            <div class="gruenerator-settings-section">
                <h2>Startseite</h2>
                <div class="gruenerator-settings-card">
                    <h3>Startseiten-Einstellung</h3>
                    <?php if ($landing_page_id): ?>
                        <?php if ($is_landing_page_front): ?>
                            <p>Deine Grünerator Landing Page ist aktuell als Startseite festgelegt.</p>
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Möchtest du die Startseiten-Einstellung wirklich zurücksetzen? Die Standard-Blogansicht wird dann als Startseite verwendet.');">
                                <?php wp_nonce_field('gruenerator_reset_frontpage_nonce'); ?>
                                <input type="hidden" name="action" value="gruenerator_reset_frontpage">
                                <input type="submit" class="button button-secondary" value="Startseiten-Einstellung zurücksetzen">
                            </form>
                        <?php else: ?>
                            <p>Die Grünerator Landing Page ist aktuell nicht als Startseite festgelegt.</p>
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                <?php wp_nonce_field('gruenerator_set_frontpage_nonce'); ?>
                                <input type="hidden" name="action" value="gruenerator_set_frontpage">
                                <input type="submit" class="button button-primary" value="Als Startseite festlegen">
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>Es wurde noch keine Landing Page erstellt. Bitte durchlaufe zuerst den Setup-Assistenten.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="gruenerator-settings-section">
                <h2>Zurücksetzen</h2>
                <p>Hier kannst du alle Einstellungen des Grünerators auf die Standardwerte zurücksetzen.</p>
                
                <div class="gruenerator-settings-card">
                    <h3>Alle Einstellungen zurücksetzen</h3>
                    <p>Diese Aktion setzt alle Einstellungen des Grünerators auf die Standardwerte zurück. Dies betrifft:</p>
                    <ul>
                        <li>Design-Einstellungen</li>
                        <li>Social Media Verknüpfungen</li>
                        <li>Hero-Bereich</li>
                        <li>Über mich</li>
                        <li>Hauptthema</li>
                        <li>Politische Schwerpunkte</li>
                        <li>Aktionsbereich</li>
                        <li>Kontaktbereich</li>
                    </ul>
                    <p class="description">Achtung: Diese Aktion kann nicht rückgängig gemacht werden!</p>
                    
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Bist du sicher, dass du alle Einstellungen zurücksetzen möchtest? Diese Aktion kann nicht rückgängig gemacht werden!');">
                        <?php wp_nonce_field('gruenerator_reset_settings_nonce'); ?>
                        <input type="hidden" name="action" value="gruenerator_reset_settings">
                        <input type="submit" class="button button-secondary" value="Alle Einstellungen zurücksetzen">
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Verarbeitet das Zurücksetzen aller Einstellungen
     *
     * @since 1.0.0
     * @access public
     */
    public function handle_reset_settings() {
        if (!current_user_can('manage_options')) {
            wp_die('Unzureichende Berechtigungen');
        }

        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'gruenerator_reset_settings_nonce')) {
            wp_die('Sicherheitsüberprüfung fehlgeschlagen');
        }

        // Liste aller Optionen, die zurückgesetzt werden sollen
        $options_to_reset = array(
            'gruenerator_use_css',
            'gruenerator_social_facebook',
            'gruenerator_social_twitter',
            'gruenerator_social_instagram',
            'gruenerator_hero_image',
            'gruenerator_hero_heading',
            'gruenerator_hero_text',
            'gruenerator_about_me_title',
            'gruenerator_about_me_content',
            'gruenerator_hero_image_block_image',
            'gruenerator_hero_image_title',
            'gruenerator_hero_image_subtitle',
        );

        // Füge die Themen-Optionen hinzu
        for ($i = 1; $i <= 3; $i++) {
            $options_to_reset[] = 'gruenerator_theme_image_' . $i;
            $options_to_reset[] = 'gruenerator_theme_title_' . $i;
            $options_to_reset[] = 'gruenerator_theme_content_' . $i;
        }

        // Füge die Aktions-Optionen hinzu
        for ($i = 1; $i <= 3; $i++) {
            $options_to_reset[] = 'gruenerator_action_image_' . $i;
            $options_to_reset[] = 'gruenerator_action_text_' . $i;
            $options_to_reset[] = 'gruenerator_action_link_' . $i;
        }

        // Füge die Kontaktformular-Optionen hinzu
        $options_to_reset[] = 'gruenerator_contact_form_title';
        $options_to_reset[] = 'gruenerator_contact_form_image';
        $options_to_reset[] = 'gruenerator_contact_form_email';

        // Lösche alle Optionen
        foreach ($options_to_reset as $option) {
            delete_option($option);
        }

        // Setze eine Erfolgsmeldung
        add_settings_error(
            'gruenerator_messages',
            'gruenerator_reset_success',
            'Alle Einstellungen wurden erfolgreich zurückgesetzt.',
            'success'
        );

        // Leite zurück zur Einstellungsseite
        wp_safe_redirect(add_query_arg(
            array('page' => 'gruenerator-settings', 'settings-updated' => 'true'),
            admin_url('admin.php')
        ));
        exit;
    }

    /**
     * Setzt die Landing Page als Startseite
     *
     * @since 1.0.0
     * @access public
     */
    public function handle_set_frontpage() {
        if (!current_user_can('manage_options')) {
            wp_die('Unzureichende Berechtigungen');
        }

        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'gruenerator_set_frontpage_nonce')) {
            wp_die('Sicherheitsüberprüfung fehlgeschlagen');
        }

        $landing_page_id = get_option('gruenerator_landing_page_id');
        if ($landing_page_id) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $landing_page_id);
        }

        wp_safe_redirect(add_query_arg(
            array('page' => 'gruenerator-settings', 'settings-updated' => 'true'),
            admin_url('admin.php')
        ));
        exit;
    }

    /**
     * Setzt die Startseite zurück auf die Standard-Blogansicht
     *
     * @since 1.0.0
     * @access public
     */
    public function handle_reset_frontpage() {
        if (!current_user_can('manage_options')) {
            wp_die('Unzureichende Berechtigungen');
        }

        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'gruenerator_reset_frontpage_nonce')) {
            wp_die('Sicherheitsüberprüfung fehlgeschlagen');
        }

        update_option('show_on_front', 'posts');
        delete_option('page_on_front');

        wp_safe_redirect(add_query_arg(
            array('page' => 'gruenerator-settings', 'settings-updated' => 'true'),
            admin_url('admin.php')
        ));
        exit;
    }

    /**
     * Zeigt Admin-Benachrichtigungen an
     *
     * @since 1.0.0
     * @access public
     */
    public function show_admin_notices() {
        settings_errors('gruenerator_messages');
    }
} 