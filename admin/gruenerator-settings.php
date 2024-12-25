<?php
// Verhindert direkten Zugriff auf die Datei
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Zeigt Admin-Benachrichtigungen an
 */
function gruenerator_settings_admin_notices() {
    settings_errors('gruenerator_messages');
}
add_action('admin_notices', 'gruenerator_settings_admin_notices'); 