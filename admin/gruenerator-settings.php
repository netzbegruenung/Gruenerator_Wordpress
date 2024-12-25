<?php
// Verhindert direkten Zugriff auf die Datei
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Callback-Funktion für die Einstellungsseite
 */
function gruenerator_settings_page() {
    $settings = Gruenerator_Settings::get_instance();
    $settings->render_settings_page();
} 