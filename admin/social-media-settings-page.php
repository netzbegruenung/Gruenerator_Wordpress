<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function gruenerator_social_media_settings_page() {
    // Überprüfen Sie die Benutzerberechtigungen
    if (!current_user_can('manage_options')) {
        return;
    }

    // Liste der unterstützten sozialen Netzwerke
    $social_networks = array(
        'email' => array('name' => 'E-Mail', 'icon' => 'fas fa-envelope', 'type' => 'email'),
        'facebook' => array('name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'type' => 'url'),
        'twitter' => array('name' => 'Twitter', 'icon' => 'fab fa-twitter', 'type' => 'url'),
        'instagram' => array('name' => 'Instagram', 'icon' => 'fab fa-instagram', 'type' => 'url'),
        'linkedin' => array('name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'type' => 'url'),
        'youtube' => array('name' => 'YouTube', 'icon' => 'fab fa-youtube', 'type' => 'url'),
        'snapchat' => array('name' => 'Snapchat', 'icon' => 'fab fa-snapchat-ghost', 'type' => 'url'),
        'tiktok' => array('name' => 'TikTok', 'icon' => 'fab fa-tiktok', 'type' => 'url'),
        'whatsapp' => array('name' => 'WhatsApp', 'icon' => 'fab fa-whatsapp', 'type' => 'url'),
        'telegram' => array('name' => 'Telegram', 'icon' => 'fab fa-telegram', 'type' => 'url'),
    );

    // Speichern Sie die Einstellungen, wenn das Formular gesendet wurde
    if (isset($_POST['gruenerator_save_social_media'])) {
        check_admin_referer('gruenerator_social_media_nonce');
        $social_media_data = array();
        foreach ($social_networks as $key => $network) {
            if (!empty($_POST[$key . '_value'])) {
                $value = sanitize_text_field($_POST[$key . '_value']);
                if ($value !== null) {
                    if ($network['type'] === 'email') {
                        $value = 'mailto:' . $value;
                    }
                    $social_media_data[] = $network['icon'] . ';' . $network['name'] . ';' . $value;
                }
            }
        }
        update_option('gruenerator_social_media_profiles', implode("\n", $social_media_data));
        echo '<div class="notice notice-success"><p>' . esc_html__('Einstellungen gespeichert.', 'gruenerator') . '</p></div>';
    }

    // Holen Sie die aktuellen Einstellungen
    $social_media_profiles = get_option('gruenerator_social_media_profiles', '');
    $current_profiles = array();
    foreach (explode("\n", $social_media_profiles) as $profile) {
        $parts = explode(';', $profile);
        if (count($parts) >= 3) {
            $icon = $parts[0];
            $name = $parts[1];
            $value = $parts[2];
            
            foreach ($social_networks as $key => $network) {
                if ($network['icon'] === $icon) {
                    $current_profiles[$key] = ($network['type'] === 'email') ? str_replace('mailto:', '', $value) : $value;
                    break;
                }
            }
        }
    }

    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('gruenerator_social_media_nonce'); ?>
            <table class="form-table">
                <?php foreach ($social_networks as $key => $network) : ?>
                    <tr>
                        <th scope="row">
                            <label for="<?php echo esc_attr($key); ?>_value">
                                <i class="<?php echo esc_attr($network['icon']); ?>"></i>
                                <?php echo esc_html($network['name']); ?>
                            </label>
                        </th>
                        <td>
                            <input type="<?php echo esc_attr($network['type']); ?>" 
                                   id="<?php echo esc_attr($key); ?>_value" 
                                   name="<?php echo esc_attr($key); ?>_value" 
                                   value="<?php echo isset($current_profiles[$key]) ? esc_attr($current_profiles[$key]) : ''; ?>" 
                                   class="regular-text">
                            <p class="description">
                                <?php 
                                if ($network['type'] === 'email') {
                                    esc_html_e('Gib deine E-Mail-Adresse ein.', 'gruenerator');
                                } else {
                                    printf(esc_html__('Füge hier den vollständigen Link zu deinem %s-Profil ein.', 'gruenerator'), esc_html($network['name']));
                                }
                                ?>
                            </p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p class="submit">
                <input type="submit" name="gruenerator_save_social_media" class="button-primary" value="<?php esc_attr_e('Einstellungen speichern', 'gruenerator'); ?>">
            </p>
        </form>
    </div>
    <?php
}

// Die Funktion zum Hinzufügen der Menüseite sollte bereits in deiner Hauptplugin-Datei existieren.
// Wenn nicht, füge sie dort hinzu, nicht hier.
