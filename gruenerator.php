<?php

/**
 * @package Gruenerator
 * @version 1.0.0
 * 
 * @wordpress-plugin
 * Plugin Name: Gruenerator Gutenberg Addon
 * Description: Fügt spezielle Gutenberg-Blöcke hinzu, die nur mit einem spezifischen Theme funktionieren. Erstellt eine Seite mit vorgefertigten Blöcken auf Knopfdruck.
 * Version: 1.0.0
 * Author: Dein Name
 * License: GPL v2 oder später
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gruenerator
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 *
 * Dieses Plugin ist ein Gutenberg-Addon speziell für grüne Websites.
 * Es erweitert WordPress um spezielle Blöcke und Funktionen.
 *
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Definiere Konstanten für Pfade
define('GRUENERATOR_PATH', plugin_dir_path(__FILE__));
define('GRUENERATOR_URL', plugin_dir_url(__FILE__));

// Lade die Hauptklassen
require_once GRUENERATOR_PATH . 'includes/class-gruenerator-customizer.php';
require_once GRUENERATOR_PATH . 'includes/class-gruenerator-blocks.php';
require_once GRUENERATOR_PATH . 'includes/class-gruenerator-meta-fields.php';
require_once GRUENERATOR_PATH . 'includes/class-gruenerator-social-media.php';

// Lade Admin-spezifische Dateien
require_once GRUENERATOR_PATH . 'admin/gruenerator-setup-wizard.php';
require_once GRUENERATOR_PATH . 'admin/social-media-settings-page.php';
require_once GRUENERATOR_PATH . 'admin/gruenerator-settings.php';

/**
 * Enqueue Frontend Styles und Inline CSS
 */
function gruenerator_enqueue_custom_css() {
    $custom_css = get_option('gruenerator_custom_css', '');
    if (!empty($custom_css)) {
        wp_add_inline_style('gruenerator-blocks-frontend', $custom_css);
    }
}
add_action('wp_enqueue_scripts', 'gruenerator_enqueue_custom_css');

// Hier können weitere Funktionen und Hooks hinzugefügt werden...

/**
 * Initialisiert das Admin-Menü
 * 
 * @since 1.0.0
 * @return void
 */
function gruenerator_add_admin_menu() {
    add_menu_page(
        __('Grünerator', 'gruenerator'),
        __('Grünerator', 'gruenerator'),
        'manage_options',
        'gruenerator-generator',
        'gruenerator_main_page',
        'dashicons-admin-generic'
    );

    add_submenu_page(
        'gruenerator-generator',
        __('Social Media Einstellungen', 'gruenerator'),
        __('Social Media', 'gruenerator'),
        'manage_options',
        'gruenerator-social-media',
        'gruenerator_social_media_settings_page'
    );

    add_submenu_page(
        'gruenerator-generator',
        __('Setup-Assistent', 'gruenerator'),
        __('Setup-Assistent', 'gruenerator'),
        'manage_options',
        'gruenerator-setup-wizard',
        'gruenerator_setup_wizard'
    );

    add_submenu_page(
        'gruenerator-generator',
        __('Einstellungen', 'gruenerator'),
        __('Einstellungen', 'gruenerator'),
        'manage_options',
        'gruenerator-settings',
        'gruenerator_settings_page'
    );
}
add_action('admin_menu', 'gruenerator_add_admin_menu');

function gruenerator_create_page_callback() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p><?php _e('Klicke auf den Button unten, um eine neue Seite mit dem Grünerator Landing Page Pattern zu erstellen.', 'gruenerator'); ?></p>
        <form method="post" action="">
            <?php wp_nonce_field('gruenerator_create_page', 'gruenerator_nonce'); ?>
            <input type="submit" name="gruenerator_create_page" class="button button-primary" value="<?php _e('Neue Seite erstellen', 'gruenerator'); ?>">
        </form>
    </div>
    <?php

    if (isset($_POST['gruenerator_create_page']) && check_admin_referer('gruenerator_create_page', 'gruenerator_nonce')) {
        $page_title = 'Grünerator Landing Page';
        $page_content = '<!-- wp:pattern {"slug":"gruenerator/landing-page"} /-->';

        $page_id = wp_insert_post(array(
            'post_title'    => $page_title,
            'post_content'  => $page_content,
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ));

        if ($page_id) {
            $page_url = get_permalink($page_id);
            echo '<div class="notice notice-success"><p>' . sprintf(__('Seite erfolgreich erstellt. <a href="%s" target="_blank">Seite anzeigen</a>', 'gruenerator'), esc_url($page_url)) . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' . __('Fehler beim Erstellen der Seite.', 'gruenerator') . '</p></div>';
        }
    }
}

function gruenerator_main_page() {
    ?>
    <div class="wrap gruenerator-dashboard">
        <div class="gruenerator-header">
            <h1>
                <span class="dashicons dashicons-admin-generic"></span>
                Willkommen beim Grünerator
            </h1>
            <p class="about-description">
                Erstelle eine professionelle politische Landingpage in wenigen Minuten.
            </p>
        </div>

        <div class="gruenerator-grid">
            <div class="gruenerator-card">
                <div class="gruenerator-card-header">
                    <span class="dashicons dashicons-admin-settings"></span>
                    <h2>Setup-Assistent</h2>
                </div>
                <p>Starte den Setup-Assistenten, um deine Landingpage Schritt für Schritt zu erstellen.</p>
                <a href="<?php echo admin_url('admin.php?page=gruenerator-setup-wizard'); ?>" class="button button-primary">
                    Zum Setup-Assistenten
                </a>
            </div>

            <div class="gruenerator-card">
                <div class="gruenerator-card-header">
                    <span class="dashicons dashicons-share"></span>
                    <h2>Social Media</h2>
                </div>
                <p>Verwalte deine Social Media Links und Einstellungen.</p>
                <a href="<?php echo admin_url('admin.php?page=gruenerator-social-media'); ?>" class="button button-primary">
                    Social Media verwalten
                </a>
            </div>

            <div class="gruenerator-footer">
                <h3>Hilfe & Support</h3>
                <p>
                    Benötigst du Hilfe? Besuche unsere <a href="https://github.com/netzbegruenung/Gruenerator_Wordpress" target="_blank">GitHub-Seite</a> 
                    oder schreibe eine E-Mail an <a href="mailto:info@moritz-waechter.de">info@moritz-waechter.de</a>.
                </p>
            </div>
        </div>
    </div>

    <style>
    :root {
        --gruenerator-tanne: #005538;
        --gruenerator-klee: #008939;
        --gruenerator-grashalm: #8ABD24;
        --gruenerator-sand: #F5F1E9;
        --gruenerator-himmel: #0BA1DD;
        --gruenerator-sonne: #FFF17A;
        --gruenerator-weiss: #ffffff;
        --gruenerator-dunkelgruen: #005437;
        --gruenerator-dunkelgruen-alt: #004d40;
        --gruenerator-backgroundgruen: #f3faf6;
        --gruenerator-dunkelgrau: #333;
        --gruenerator-anthrazit: #222;
    }

    .gruenerator-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: var(--gruenerator-backgroundgruen);
    }

    .gruenerator-header {
        text-align: center;
        margin-bottom: 2.5rem;
        padding: 2rem;
        background: var(--gruenerator-weiss);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .gruenerator-header h1 {
        color: var(--gruenerator-tanne);
        font-size: 2.5em;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
    }

    .gruenerator-header .dashicons {
        font-size: 2.5em;
        width: auto;
        height: auto;
        color: var(--gruenerator-klee);
    }

    .about-description {
        font-size: 1.2em;
        color: var(--gruenerator-dunkelgrau);
        margin-top: 0.625rem;
    }

    .gruenerator-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2.5rem;
    }

    .gruenerator-card {
        background: var(--gruenerator-weiss);
        border-radius: 8px;
        padding: 1.25rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .gruenerator-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .gruenerator-card-header {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        margin-bottom: 1rem;
    }

    .gruenerator-card-header .dashicons {
        font-size: 1.5em;
        width: auto;
        height: auto;
        color: var(--gruenerator-klee);
    }

    .gruenerator-card h2 {
        margin: 0;
        color: var(--gruenerator-dunkelgruen);
        font-size: 1.3em;
        font-weight: 600;
    }

    .gruenerator-card p {
        color: var(--gruenerator-dunkelgrau);
        margin-bottom: 1.25rem;
        line-height: 1.5;
    }

    .gruenerator-card .button.button-primary {
        width: 100%;
        text-align: center;
        background-color: var(--gruenerator-tanne);
        border-color: var(--gruenerator-tanne);
        color: var(--gruenerator-weiss);
        padding: 0.625rem;
        height: auto;
        line-height: 1.4;
        transition: all 0.2s ease;
    }

    .gruenerator-card .button.button-primary:hover,
    .gruenerator-card .button.button-primary:focus {
        background-color: var(--gruenerator-dunkelgruen-alt);
        border-color: var(--gruenerator-dunkelgruen-alt);
        color: var(--gruenerator-weiss);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .gruenerator-footer {
        text-align: center;
        padding: 1.25rem;
        background: var(--gruenerator-weiss);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .gruenerator-footer h3 {
        color: var(--gruenerator-tanne);
        margin-bottom: 0.625rem;
        font-size: 1.2em;
    }

    .gruenerator-footer p {
        color: var(--gruenerator-dunkelgrau);
        line-height: 1.6;
    }

    .gruenerator-footer a {
        color: var(--gruenerator-klee);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .gruenerator-footer a:hover,
    .gruenerator-footer a:focus {
        color: var(--gruenerator-tanne);
        text-decoration: underline;
    }

    @media screen and (max-width: 782px) {
        .gruenerator-grid {
            grid-template-columns: 1fr;
        }

        .gruenerator-header h1 {
            font-size: 2em;
        }

        .gruenerator-card {
            margin-bottom: 1rem;
        }
    }
    </style>
    <?php
}

/**
 * Definiere die Block-Kategorie
 */
function gruenerator_block_category( $categories, $post ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'gruenerator-category',
                'title' => __( 'Grünerator Blöcke', 'gruenerator' ),
                'icon'  => null,
            ),
        )
    );
}
add_filter('block_categories_all', 'gruenerator_block_category', 10, 2);

/**
 * Modifiziert den Output des Kontaktformular-Blocks, um ein Hintergrundbild hinzuzufügen
 */
function gruenerator_modify_contact_form_output($block_content, $block) {
    if ($block['blockName'] === 'sunflower/contact-form') {
        $background_image = isset($block['attrs']['grueneratorBackgroundImage']) ? $block['attrs']['grueneratorBackgroundImage'] : '';
        $title = isset($block['attrs']['grueneratorTitle']) ? $block['attrs']['grueneratorTitle'] : '';
        
        if ($background_image || $title) {
            $wrapper_start = '<div class="wp-block-sunflower-contact-form-wrapper"' . ($background_image ? ' style="background-image: url(\'' . esc_url($background_image) . '\');"' : '') . '>';
            $wrapper_end = '</div>';
            
            if ($title) {
                $title_html = '<h2 class="wp-block-sunflower-contact-form-title">' . esc_html($title) . '</h2>';
                $block_content = $title_html . $block_content;
            }
            
            $block_content = $wrapper_start . $block_content . $wrapper_end;
        }
    }
    return $block_content;
}
add_filter('render_block', 'gruenerator_modify_contact_form_output', 10, 2);

function gruenerator_enqueue_admin_scripts($hook) {
    if (strpos($hook, 'gruenerator') !== false) {
        wp_enqueue_media();
        wp_enqueue_script('gruenerator-admin-js', plugin_dir_url(__FILE__) . 'gruenerator-admin.js', array('jquery'), '1.0.0', true);
    }
}
add_action('admin_enqueue_scripts', 'gruenerator_enqueue_admin_scripts');

/**
 * Enqueue Admin Styles
 */
function gruenerator_enqueue_admin_styles($hook) {
    // Fügen Sie hier Bedingungen hinzu, um die Styles nur auf bestimmten Admin-Seiten zu laden
    wp_enqueue_style('gruenerator-admin-styles', GRUENERATOR_URL . 'build/index.css', array(), '1.0.0');
}
add_action('admin_enqueue_scripts', 'gruenerator_enqueue_admin_styles');

?>