<?php
// Verhindert direkten Zugriff auf die Datei
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Führt die automatische Einrichtung des Grünerator-Plugins durch.
 */
function gruenerator_auto_setup() {
    if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['_wpnonce'], 'gruenerator_auto_setup_nonce')) {
        gruenerator_log("Unzureichende Berechtigungen oder ungültiges Nonce für Auto-Setup", 'error');
        wp_send_json_error(array('message' => 'Unzureichende Berechtigungen oder ungültiges Nonce'));
        return;
    }

    gruenerator_log("Starte Auto-Setup", 'info');

    // Beispielhafte Einstellungen für das Auto-Setup
    $auto_setup_options = array(
        'gruenerator_use_css' => 1,
        'gruenerator_social_facebook' => 'https://facebook.com/example',
        'gruenerator_social_twitter' => 'https://twitter.com/example',
        'gruenerator_hero_heading' => 'Willkommen auf meiner Seite',
        'gruenerator_hero_text' => 'Dies ist ein automatisch generierter Beispieltext.',
        'gruenerator_about_title' => 'Über mich',
        'gruenerator_about_content' => 'Dies ist ein Beispieltext für den Über-mich-Bereich.',
        'gruenerator_current_themes_title' => 'Aktuelle Themen'
    );

    // Anwenden der Auto-Setup-Einstellungen
    foreach ($auto_setup_options as $option_name => $option_value) {
        update_option($option_name, $option_value);
        gruenerator_log("Option $option_name auf $option_value gesetzt", 'info');
    }

    // Landing Page erstellen
    $page_id = gruenerator_auto_setup_create_landing_page();
    if (is_wp_error($page_id)) {
        gruenerator_log("Fehler beim Erstellen der Landing Page: " . $page_id->get_error_message(), 'error');
        wp_send_json_error(array('message' => 'Fehler beim Erstellen der Landing Page'));
        return;
    } else {
        gruenerator_log("Landing Page erfolgreich erstellt mit ID: " . $page_id, 'info');
    }

    // Setup als abgeschlossen markieren
    update_option('gruenerator_setup_completed', true);
    gruenerator_log("Setup als abgeschlossen markiert", 'info');

    // Erfolgreiche Nachricht zurücksenden
    wp_send_json_success(array('message' => 'Auto-Setup erfolgreich abgeschlossen'));
}

// Hook für AJAX-Aktion hinzufügen
add_action('wp_ajax_gruenerator_auto_setup', 'gruenerator_auto_setup');

function gruenerator_auto_setup_create_landing_page() {
    $page_content = gruenerator_generate_landing_page_content();
    $page_title = 'Grünerator Landing Page';

    $page_id = wp_insert_post(array(
        'post_title'    => $page_title,
        'post_content'  => $page_content,
        'post_status'   => 'publish',
        'post_type'     => 'page',
    ));

    if (is_wp_error($page_id)) {
        gruenerator_log("Fehler beim Erstellen der Landing Page: " . $page_id->get_error_message(), 'error');
        return $page_id;
    }

    update_option('gruenerator_landing_page_id', $page_id);
    gruenerator_log("Neue Landing Page mit ID: " . $page_id . " erstellt", 'info');

    return $page_id;
}