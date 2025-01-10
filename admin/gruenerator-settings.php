<?php
// Verhindert direkten Zugriff auf die Datei
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Callback für die Settings-Seite
 */
function gruenerator_settings_page() {
    $settings = Gruenerator_Settings::get_instance();
    
    // Verarbeite das Formular für die Startseiten-Einstellung
    if (isset($_POST['gruenerator_set_frontpage'])) {
        check_admin_referer('gruenerator_frontpage_settings');
        $settings->set_frontpage();
    } elseif (isset($_POST['gruenerator_reset_frontpage'])) {
        check_admin_referer('gruenerator_frontpage_settings');
        $settings->reset_frontpage();
    }
    
    // Verarbeite das Formular für das Zurücksetzen der Inhalte
    if (isset($_POST['gruenerator_reset_content'])) {
        check_admin_referer('gruenerator_reset_content');
        $settings->reset_content_to_defaults();
    }
    
    $settings->render_settings_page();
} 