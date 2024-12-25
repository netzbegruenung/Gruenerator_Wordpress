<?php
// Verhindert direkten Zugriff auf die Datei
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generiert den Inhalt für die Landing Page.
 *
 * @return string Der generierte Inhalt der Landing Page.
 */


function gruenerator_process_setup_wizard() {
    gruenerator_log("gruenerator_process_setup_wizard wurde aufgerufen", 'debug');
    
    $current_step = isset($_POST['step']) ? intval($_POST['step']) : 0;
    
    switch ($current_step) {
        case 1:
            gruenerator_process_css_settings();
            break;
        case 2:
            gruenerator_process_social_networks();
            break;
        case 3:
            gruenerator_process_hero_section();
            break;
        case 4:
            gruenerator_process_about_me();
            break;
        case 5:
            gruenerator_process_hero_image_block();
            break;
        case 6:
            gruenerator_process_my_themes();
            break;
        case 7:
            gruenerator_process_image_grid();
            break;
        case 8:
            gruenerator_process_contact_form();
            break;
        case 9:
            gruenerator_process_final_step();
            break;
        default:
            gruenerator_log("Unbekannter Schritt: " . $current_step, 'error');
            return false;
    }
    
    gruenerator_log("Schritt " . $current_step . " erfolgreich verarbeitet", 'info');
    return true;
}

function gruenerator_process_css_settings() {
    $use_css = isset($_POST['gruenerator_use_css']) ? 1 : 0;
    update_option('gruenerator_use_css', $use_css);
    gruenerator_log("CSS-Einstellungen aktualisiert: " . $use_css, 'info');
}

function gruenerator_process_social_networks() {
    $social_networks = array('facebook', 'twitter', 'instagram');
    foreach ($social_networks as $network) {
        $value = isset($_POST['gruenerator_social_' . $network]) ? esc_url_raw($_POST['gruenerator_social_' . $network]) : '';
        update_option('gruenerator_social_' . $network, $value);
        gruenerator_log($network . " URL aktualisiert: " . $value, 'info');
    }
}

function gruenerator_process_hero_section() {
    $hero_image = isset($_POST['gruenerator_hero_image']) ? intval($_POST['gruenerator_hero_image']) : 0;
    $hero_heading = isset($_POST['gruenerator_hero_heading']) ? sanitize_text_field($_POST['gruenerator_hero_heading']) : '';
    $hero_text = isset($_POST['gruenerator_hero_text']) ? wp_kses_post($_POST['gruenerator_hero_text']) : '';

    update_option('gruenerator_hero_image', $hero_image);
    update_option('gruenerator_hero_heading', $hero_heading);
    update_option('gruenerator_hero_text', $hero_text);

    gruenerator_log("Hero-Bereich aktualisiert", 'info');
}

function gruenerator_process_about_me() {
    $about_title = isset($_POST['gruenerator_about_me_title']) ? sanitize_text_field($_POST['gruenerator_about_me_title']) : '';
    $about_content = isset($_POST['gruenerator_about_me_content']) ? wp_kses_post($_POST['gruenerator_about_me_content']) : '';

    // Speichere die Werte
    update_option('gruenerator_about_me_title', $about_title);
    update_option('gruenerator_about_me_content', $about_content);

    gruenerator_log("Über mich Bereich aktualisiert - Titel: " . $about_title, 'info');
}

function gruenerator_process_hero_image_block() {
    $hero_image_block_image = isset($_POST['gruenerator_hero_image_block_image']) ? intval($_POST['gruenerator_hero_image_block_image']) : 0;
    $hero_image_block_title = isset($_POST['gruenerator_hero_image_block_title']) ? sanitize_text_field($_POST['gruenerator_hero_image_block_title']) : '';
    $hero_image_block_text = isset($_POST['gruenerator_hero_image_block_text']) ? wp_kses_post($_POST['gruenerator_hero_image_block_text']) : '';

    update_option('gruenerator_hero_image_block_image', $hero_image_block_image);
    update_option('gruenerator_hero_image_block_title', $hero_image_block_title);
    update_option('gruenerator_hero_image_block_text', $hero_image_block_text);

    gruenerator_log("Hero Image Block aktualisiert", 'info');
}

function gruenerator_process_my_themes() {
    // Verarbeite die drei Themen
    for ($i = 1; $i <= 3; $i++) {
        $theme_image = isset($_POST['gruenerator_theme_image_' . $i]) ? intval($_POST['gruenerator_theme_image_' . $i]) : 0;
        $theme_title = isset($_POST['gruenerator_theme_title_' . $i]) ? sanitize_text_field($_POST['gruenerator_theme_title_' . $i]) : '';
        $theme_content = isset($_POST['gruenerator_theme_content_' . $i]) ? wp_kses_post($_POST['gruenerator_theme_content_' . $i]) : '';

        update_option('gruenerator_theme_image_' . $i, $theme_image);
        update_option('gruenerator_theme_title_' . $i, $theme_title);
        update_option('gruenerator_theme_content_' . $i, $theme_content);
    }

    gruenerator_log("Meine Themen aktualisiert", 'info');
}

function gruenerator_process_image_grid() {
    // Verarbeite die drei Aktionen
    for ($i = 1; $i <= 3; $i++) {
        $action_image = isset($_POST['gruenerator_action_image_' . $i]) ? intval($_POST['gruenerator_action_image_' . $i]) : 0;
        $action_text = isset($_POST['gruenerator_action_text_' . $i]) ? sanitize_text_field($_POST['gruenerator_action_text_' . $i]) : '';
        $action_link = isset($_POST['gruenerator_action_link_' . $i]) ? esc_url_raw($_POST['gruenerator_action_link_' . $i]) : '';

        update_option('gruenerator_action_image_' . $i, $action_image);
        update_option('gruenerator_action_text_' . $i, $action_text);
        update_option('gruenerator_action_link_' . $i, $action_link);
    }

    gruenerator_log("Aktionsbereich aktualisiert", 'info');
}

function gruenerator_process_contact_form() {
    $contact_form_title = isset($_POST['gruenerator_contact_form_title']) ? sanitize_text_field($_POST['gruenerator_contact_form_title']) : '';
    $contact_form_email = isset($_POST['gruenerator_contact_form_email']) ? sanitize_email($_POST['gruenerator_contact_form_email']) : '';
    $contact_form_image = isset($_POST['gruenerator_contact_form_image']) ? intval($_POST['gruenerator_contact_form_image']) : 0;

    update_option('gruenerator_contact_form_title', $contact_form_title);
    update_option('gruenerator_contact_form_email', $contact_form_email);
    update_option('gruenerator_contact_form_image', $contact_form_image);

    gruenerator_log("Kontaktformular aktualisiert - Titel: " . $contact_form_title, 'info');
}

// Fügen Sie diese neue Funktion am Ende der Datei hinzu
function gruenerator_process_final_step() {
    // Erstelle die Landingpage
    $page_title = 'Grünerator Landing Page';
    
    // Hole alle gespeicherten Werte
    $hero_image = wp_get_attachment_url(get_option('gruenerator_hero_image'));
    $hero_heading = get_option('gruenerator_hero_heading');
    $hero_text = get_option('gruenerator_hero_text');
    
    $about_title = get_option('gruenerator_about_me_title');
    $about_content = get_option('gruenerator_about_me_content');
    
    $hero_block_image = wp_get_attachment_url(get_option('gruenerator_hero_image_block_image'));
    $hero_block_title = get_option('gruenerator_hero_image_block_title');
    $hero_block_subtitle = get_option('gruenerator_hero_image_subtitle');
    
    // Erstelle den Seiteninhalt mit den gespeicherten Werten
    $page_content = '<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">

<!-- wp:gruenerator/hero-block {"heroImageUrl":"' . esc_url($hero_image) . '","heroHeading":"' . esc_attr($hero_heading) . '","heroText":"' . esc_attr($hero_text) . '"} /-->

<!-- wp:gruenerator/about-block {
    "title": "' . esc_attr($about_title) . '",
    "content": "' . esc_attr($about_content) . '"
} /-->

<!-- wp:gruenerator/hero-image-block {"align":"full","backgroundImageUrl":"' . esc_url($hero_block_image) . '","title":"' . esc_attr($hero_block_title) . '","subtitle":"' . esc_attr($hero_block_subtitle) . '"} /-->';

    // Füge die Themenbereiche hinzu
    $page_content .= '<!-- wp:gruenerator/meine-themen-block {"themes":[';
    
    $themes = array();
    for ($i = 1; $i <= 3; $i++) {
        $theme_image = wp_get_attachment_url(get_option('gruenerator_theme_image_' . $i));
        $theme_title = get_option('gruenerator_theme_title_' . $i);
        $theme_content = get_option('gruenerator_theme_content_' . $i);
        
        if ($theme_image || $theme_title || $theme_content) {
            $themes[] = '{
                "imageUrl":"' . esc_url($theme_image ? $theme_image : '') . '",
                "title":"' . esc_attr($theme_title) . '",
                "content":"' . esc_attr($theme_content) . '"
            }';
        }
    }
    $page_content .= implode(',', $themes);
    $page_content .= ']} /-->';

    // Füge die Aktionsbereiche hinzu
    $page_content .= '<!-- wp:gruenerator/image-grid-block {"align":"full","items":[';
    
    $actions = array();
    for ($i = 1; $i <= 3; $i++) {
        $action_image = wp_get_attachment_url(get_option('gruenerator_action_image_' . $i));
        $action_text = get_option('gruenerator_action_text_' . $i);
        $action_link = get_option('gruenerator_action_link_' . $i);
        
        if ($action_image || $action_text) {
            $actions[] = '{
                "imageUrl":"' . esc_url($action_image ? $action_image : '') . '",
                "text":"' . esc_attr($action_text) . '",
                "link":"' . esc_url($action_link) . '"
            }';
        }
    }
    $page_content .= implode(',', $actions);
    $page_content .= ']} /-->';

    // Füge das Kontaktformular hinzu
    $contact_title = get_option('gruenerator_contact_form_title');
    $contact_image = wp_get_attachment_url(get_option('gruenerator_contact_form_image'));
    $contact_email = get_option('gruenerator_contact_form_email');
    
    $page_content .= '<!-- wp:gruenerator/contact-form-block {
        "align":"full",
        "backgroundImageUrl":"' . esc_url($contact_image ? $contact_image : '') . '",
        "title":"' . esc_attr($contact_title) . '",
        "email":"' . esc_attr($contact_email) . '"
    } -->
    <div class="wp-block-gruenerator-contact-form-block alignfull">
        <div class="contact-form-block" style="background-image: url(\'' . esc_url($contact_image ? $contact_image : '') . '\');">
            <div class="contact-form-content">
                <div class="contact-form-left">
                    <h2 class="contact-form-title">' . esc_html($contact_title) . '</h2>
                </div>
                <div class="contact-form-right">
                    <!-- wp:sunflower/contact-form /-->
                </div>
            </div>
        </div>
    </div>
    <!-- /wp:gruenerator/contact-form-block -->';

    $page_content .= '</div>
<!-- /wp:group -->';

    $page_id = wp_insert_post(array(
        'post_title'    => $page_title,
        'post_content'  => $page_content,
        'post_status'   => 'publish',
        'post_type'     => 'page',
    ));

    if ($page_id) {
        // Speichere die ID der erstellten Seite
        update_option('gruenerator_landing_page_id', $page_id);
        
        // Markiere das Setup als abgeschlossen
        update_option('gruenerator_setup_completed', true);
        
        // Leite zur Erfolgsseite weiter
        wp_safe_redirect(add_query_arg(
            array(
                'page' => 'gruenerator-setup-wizard',
                'show_completion' => 'true'
            ),
            admin_url('admin.php')
        ));
        exit;
    } else {
        gruenerator_log("Fehler beim Erstellen der Landingpage", 'error');
        wp_die('Fehler beim Erstellen der Landingpage');
    }
}