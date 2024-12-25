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
function gruenerator_generate_landing_page_content() {
    $content = '';

    // 1. Hero Block
    $content .= '<!-- wp:gruenerator/hero-block {"align":"full"} /-->';

    // 2. About Block
    $content .= '<!-- wp:gruenerator/about-block {"align":"full"} /-->';

    // 3. Hero Image Block
    $content .= '<!-- wp:gruenerator/hero-image-block {"align":"full"} /-->';

    // 4. Meine Themen Block
    $content .= '<!-- wp:gruenerator/meine-themen-block {"align":"full"} /-->';

    // 5. Image Grid Block
    $content .= '<!-- wp:gruenerator/image-grid-block {"align":"full"} /-->';

    // 6. Kontaktformular Block
    $content .= '<!-- wp:gruenerator/contact-form-block {"align":"full"} /-->';

    return $content;
}

/**
 * Erstellt eine neue Seite mit dem generierten Inhalt.
 *
 * @return int|WP_Error Die ID der erstellten Seite oder ein WP_Error-Objekt bei Fehlern.
 */
function gruenerator_create_landing_page() {
    $page_content = gruenerator_generate_landing_page_content();

    $page_data = array(
        'post_title'    => 'Startseite',
        'post_content'  => $page_content,
        'post_status'   => 'publish',
        'post_type'     => 'page',
    );

    $page_id = wp_insert_post($page_data);

    if (!is_wp_error($page_id)) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_id);
    }

    return $page_id;
}