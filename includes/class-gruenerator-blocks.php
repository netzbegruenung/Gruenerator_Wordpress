<?php
// Verhindert direkten Zugriff auf die Datei
if (!defined('ABSPATH')) {
    exit;
}

// Lade die Default Content Klasse
require_once GRUENERATOR_PATH . 'includes/class-gruenerator-default-content.php';

/**
 * Registriert alle notwendigen Scripts und Styles für die Blöcke.
 */
function gruenerator_register_block_assets() {
    error_log('gruenerator_register_block_assets called');
    $dir = plugin_dir_path(__FILE__);

    // Pfad zur index.js Datei ermitteln
    $script_url = plugins_url('build/index.js', dirname(__FILE__));
    error_log('Script URL: ' . $script_url);

    // Prüfen, ob die Asset-Datei existiert
    $asset_file = plugin_dir_path(__DIR__) . 'build/assets.php';
    error_log('Grünerator: Versuche, die Datei zu finden: ' . $asset_file);
    error_log('Grünerator: Aktuelles Verzeichnis: ' . __DIR__);
    error_log('Grünerator: Plugin-Basisverzeichnis: ' . plugin_dir_path(__DIR__));
    if (!file_exists($asset_file)) {
        error_log('Grünerator: Die Datei build/assets.php existiert nicht. Bitte führen Sie `npm run build` aus.');
        return;
    }

    // Assets für den Editor registrieren
    $asset = include($asset_file);

    if (!isset($asset['index.js'])) {
        error_log('Grünerator: Der Schlüssel "index.js" fehlt in build/assets.php.');
        return;
    }

    $index_asset = $asset['index.js'];

    wp_register_script(
        'gruenerator-blocks',
        plugins_url('build/index.js', dirname(__FILE__)),
        $index_asset['dependencies'],
        $index_asset['version']
    );

    $frontend_css_file = plugin_dir_path(__DIR__) . 'build/index.css';
    $editor_css_file = plugin_dir_path(__DIR__) . 'build/editor-styles.css';

    if (file_exists($frontend_css_file)) {
        wp_register_style(
            'gruenerator-blocks-frontend',
            plugins_url('build/index.css', dirname(__FILE__)),
            array(),
            filemtime($frontend_css_file)
        );
    } else {
        error_log('Grünerator: Die Datei build/index.css existiert nicht.');
    }

    if (file_exists($editor_css_file)) {
        wp_register_style(
            'gruenerator-blocks-editor',
            plugins_url('build/editor-styles.css', dirname(__FILE__)),
            array(),
            filemtime($editor_css_file)
        );
    } else {
        error_log('Grünerator: Die Datei build/editor-styles.css existiert nicht.');
    }
}
add_action('init', 'gruenerator_register_block_assets', 10);

/**
 * Enqueues die registrierten Scripts und Styles für den Editor.
 */
function gruenerator_enqueue_block_editor_assets() {
    error_log('Enqueuing editor assets');
    wp_enqueue_script('gruenerator-blocks');
    wp_enqueue_style('gruenerator-blocks-editor');
}
add_action('enqueue_block_editor_assets', 'gruenerator_enqueue_block_editor_assets');

/**
 * Enqueues die Frontend Styles.
 */
function gruenerator_enqueue_block_frontend_assets() {
    error_log('Enqueuing frontend assets');
    if (!is_admin()) {
        wp_enqueue_style('gruenerator-blocks-frontend');
    }
}
add_action('wp_enqueue_scripts', 'gruenerator_enqueue_block_frontend_assets');

/**
 * Registriert alle Blöcke in der gewünschten Reihenfolge.
 */
function gruenerator_register_all_blocks() {
    if (!function_exists('register_block_type')) {
        return;
    }

    // Liste der Blöcke mit ihren Attributen
    $blocks = array(
        // 1. Hero Block
        'hero-block' => array(
            'attributes' => array(
                'heroImageUrl' => array(
                    'type' => 'string',
                    'default' => Gruenerator_Default_Content::get_hero_content()['image'] ?? '',
                ),
                'heroHeading' => array(
                    'type' => 'string',
                    'default' => Gruenerator_Default_Content::get_hero_content()['heading'] ?? '',
                ),
                'heroText' => array(
                    'type' => 'string',
                    'default' => Gruenerator_Default_Content::get_hero_content()['text'] ?? '',
                ),
            ),
        ),
        // 2. About Block
        'about-block' => array(
            'attributes' => array(
                'title' => array(
                    'type' => 'string',
                    'default' => Gruenerator_Default_Content::get_about_content()['title'] ?? '',
                ),
                'content' => array(
                    'type' => 'string',
                    'default' => Gruenerator_Default_Content::get_about_content()['content'] ?? '',
                ),
            ),
        ),
        // 3. Hero Image Block
        'hero-image-block' => array(
            'attributes' => array(
                'backgroundImageId' => array(
                    'type' => 'integer',
                    'default' => 0,
                ),
                'backgroundImageUrl' => array(
                    'type' => 'string',
                    'default' => Gruenerator_Default_Content::get_hero_image_content()['image'] ?? '',
                ),
                'title' => array(
                    'type' => 'string',
                    'default' => Gruenerator_Default_Content::get_hero_image_content()['title'] ?? '',
                ),
                'subtitle' => array(
                    'type' => 'string',
                    'default' => Gruenerator_Default_Content::get_hero_image_content()['subtitle'] ?? '',
                ),
            ),
        ),
        // 4. Meine Themen Block
        'meine-themen-block' => array(
            'attributes' => array(
                'title' => array(
                    'type' => 'string',
                    'default' => 'Meine Themen',
                ),
                'themes' => array(
                    'type' => 'array',
                    'default' => Gruenerator_Default_Content::get_themes(),
                    'items' => array(
                        'type' => 'object',
                        'properties' => array(
                            'imageId' => array(
                                'type' => 'integer',
                                'default' => 0,
                            ),
                            'imageUrl' => array(
                                'type' => 'string',
                                'default' => 'https://via.placeholder.com/600x400',
                            ),
                            'title' => array(
                                'type' => 'string',
                                'default' => '',
                            ),
                            'content' => array(
                                'type' => 'string',
                                'default' => '',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        // 5. Image Grid Block
        'image-grid-block' => array(
            'attributes' => array(
                'items' => array(
                    'type' => 'array',
                    'default' => Gruenerator_Default_Content::get_actions(),
                ),
            ),
        ),
        // 6. Contact Form Block
        'contact-form-block' => array(
            'attributes' => array(
                'backgroundImageId' => array(
                    'type' => 'integer',
                    'default' => 0,
                ),
                'backgroundImageUrl' => array(
                    'type' => 'string',
                    'default' => 'https://via.placeholder.com/1200x600',
                ),
                'title' => array(
                    'type' => 'string',
                    'default' => Gruenerator_Default_Content::get_contact_form_content()['title'] ?? '',
                ),
                'email' => array(
                    'type' => 'string',
                    'default' => Gruenerator_Default_Content::get_contact_form_content()['email'] ?? '',
                ),
                'socialMedia' => array(
                    'type' => 'array',
                    'default' => array(),
                    'items' => array(
                        'type' => 'object',
                        'properties' => array(
                            'platform' => array(
                                'type' => 'string',
                            ),
                            'url' => array(
                                'type' => 'string',
                            ),
                            'icon' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    );

    foreach ($blocks as $block => $options) {
        // Ersetzen von Bindestrichen durch Unterstriche für Funktionsnamen
        $function_name = 'gruenerator_render_' . str_replace('-', '_', $block);

        // Stellen Sie sicher, dass 'render_callback' nicht bereits in $options definiert ist
        if (!isset($options['render_callback'])) {
            $options['render_callback'] = $function_name;
        }

        $result = register_block_type(
            "gruenerator/$block",
            array_merge(
                $options,
                array(
                    'style'           => 'gruenerator-blocks-frontend',
                    'editor_style'    => 'gruenerator-blocks-editor',
                    'editor_script'   => 'gruenerator-blocks',
                )
            )
        );
        error_log("Registering block $block: " . ($result ? 'success' : 'failure'));
    }
}
add_action('init', 'gruenerator_register_all_blocks', 10);

/**
 * Render-Callback-Funktionen für die Blöcke.
 */

// Hero Block
function gruenerator_render_hero_block($attributes, $content) {
    try {
        error_log('Rendering hero block: ' . print_r($attributes, true));

        $default_content = Gruenerator_Default_Content::get_hero_content();
        $hero_image_url = !empty($attributes['heroImageUrl']) ? $attributes['heroImageUrl'] : $default_content['image'] ?? '';
        $hero_heading = !empty($attributes['heroHeading']) ? $attributes['heroHeading'] : $default_content['heading'] ?? '';
        $hero_text = !empty($attributes['heroText']) ? $attributes['heroText'] : $default_content['text'] ?? '';

        ob_start();
        ?>
        <div class="wp-block-gruenerator-hero-block">
            <div class="hero-block" style="width:100%">
                <div class="hero-left">
                    <?php if ($hero_image_url): ?>
                        <img src="<?php echo esc_url($hero_image_url); ?>" alt="Hero Image"/>
                    <?php endif; ?>
                </div>
                <div class="hero-right">
                    <h2 class="hero-title"><?php echo esc_html($hero_heading); ?></h2>
                    <p class="hero-description"><?php echo esc_html($hero_text); ?></p>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook"><span class="dashicons dashicons-facebook"></span></a>
                        <a href="#" aria-label="Twitter"><span class="dashicons dashicons-twitter"></span></a>
                        <a href="#" aria-label="YouTube"><span class="dashicons dashicons-youtube"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $output = ob_get_clean();
        if (empty($output)) {
            error_log('Hero block: Leere Ausgabe');
            return '';
        }
        error_log('Hero block output: ' . substr($output, 0, 100) . '...');
        return $output;
    } catch (Exception $e) {
        error_log('Fehler beim Rendern des Hero-Blocks: ' . $e->getMessage());
        return '';
    }
}

// About Block
function gruenerator_render_about_block($attributes, $content) {
    try {
        error_log('Rendering about block: ' . print_r($attributes, true));

        $default_content = Gruenerator_Default_Content::get_about_content();
        $title = !empty($attributes['title']) ? $attributes['title'] : $default_content['title'];
        $content = !empty($attributes['content']) ? $attributes['content'] : $default_content['content'];

        // Erlaubte HTML-Tags für wp_kses
        $allowed_html = array(
            'strong' => array(),
            'em' => array(),
            'b' => array(),
            'i' => array(),
            'br' => array(),
            'span' => array(
                'class' => array(),
            ),
        );

        ob_start();
        ?>
        <div class="wp-block-gruenerator-about-block">
            <div class="about-block-content">
                <h2 class="about-block-title"><?php echo wp_kses($title, $allowed_html); ?></h2>
                <div class="about-block-text">
                    <p><?php echo wp_kses($content, $allowed_html); ?></p>
                </div>
            </div>
        </div>
        <?php
        $output = ob_get_clean();
        if (empty($output)) {
            error_log('About block: Leere Ausgabe');
            return '';
        }
        error_log('About block output: ' . substr($output, 0, 100) . '...');
        return $output;
    } catch (Exception $e) {
        error_log('Fehler beim Rendern des About-Blocks: ' . $e->getMessage());
        return '';
    }
}

// Hero Image Block
function gruenerator_render_hero_image_block($attributes, $content) {
    try {
        error_log('Rendering hero image block: ' . print_r($attributes, true));

        $default_content = Gruenerator_Default_Content::get_hero_image_content();
        $background_image_url = !empty($attributes['backgroundImageUrl']) ? $attributes['backgroundImageUrl'] : $default_content['image'] ?? '';
        $title = !empty($attributes['title']) ? $attributes['title'] : $default_content['title'] ?? '';
        $subtitle = !empty($attributes['subtitle']) ? $attributes['subtitle'] : $default_content['subtitle'] ?? '';
        $align_class = isset($attributes['align']) ? ' align' . $attributes['align'] : ' alignfull';

        ob_start();
        ?>
        <div class="wp-block-gruenerator-hero-image-block<?php echo esc_attr($align_class); ?>">
            <div class="hero-image-block" style="background-image: url('<?php echo esc_url($background_image_url); ?>');">
                <div class="hero-content">
                    <h2><?php echo esc_html($title); ?></h2>
                    <p><?php echo esc_html($subtitle); ?></p>
                </div>
            </div>
        </div>
        <?php
        $output = ob_get_clean();
        if (empty($output)) {
            error_log('Hero image block: Leere Ausgabe');
            return '';
        }
        error_log('Hero image block output: ' . substr($output, 0, 100) . '...');
        return $output;
    } catch (Exception $e) {
        error_log('Fehler beim Rendern des Hero-Image-Blocks: ' . $e->getMessage());
        return '';
    }
}

// Meine Themen Block
function gruenerator_render_meine_themen_block($attributes, $content) {
    try {
        error_log('Rendering meine themen block: ' . print_r($attributes, true));

        $title = !empty($attributes['title']) ? $attributes['title'] : 'Meine Themen';
        $default_themes = Gruenerator_Default_Content::get_themes();
        $themes = !empty($attributes['themes']) && is_array($attributes['themes']) ? $attributes['themes'] : $default_themes;

        ob_start();
        ?>
        <div class="wp-block-gruenerator-meine-themen-block">
            <h2 class="meine-themen-title"><?php echo esc_html($title); ?></h2>
            <div class="meine-themen-grid">
                <?php foreach ($themes as $theme): ?>
                    <div class="theme-card">
                        <?php if (!empty($theme['imageUrl'])): ?>
                            <img src="<?php echo esc_url($theme['imageUrl']); ?>" alt="<?php echo esc_attr($theme['title']); ?>" class="theme-image" />
                        <?php endif; ?>
                        <h3 class="theme-title"><?php echo esc_html($theme['title']); ?></h3>
                        <p class="theme-content"><?php echo esc_html($theme['content']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        $output = ob_get_clean();
        if (empty($output)) {
            error_log('Meine Themen block: Leere Ausgabe');
            return '';
        }
        error_log('Meine Themen block output: ' . substr($output, 0, 100) . '...');
        return $output;
    } catch (Exception $e) {
        error_log('Fehler beim Rendern des Meine-Themen-Blocks: ' . $e->getMessage());
        return '';
    }
}

// Image Grid Block
function gruenerator_render_image_grid_block($attributes) {
    if (empty($attributes['items']) || !is_array($attributes['items'])) {
        return '';
    }

    ob_start();
    ?>
    <div class="wp-block-gruenerator-image-grid-block">
        <div class="image-grid">
            <?php foreach ($attributes['items'] as $item): ?>
                <div class="grid-item">
                    <?php if (!empty($item['link'])): ?>
                        <a href="<?php echo esc_url($item['link']); ?>">
                    <?php endif; ?>
                        <?php if (!empty($item['imageUrl'])): ?>
                            <img src="<?php echo esc_url($item['imageUrl']); ?>" alt="" />
                        <?php else: ?>
                            <div class="image-placeholder">
                                <span class="dashicons dashicons-format-image"></span>
                                <p><?php echo esc_html__('Bild wählen', 'gruenerator'); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($item['text'])): ?>
                            <h2><?php echo esc_html($item['text']); ?></h2>
                        <?php endif; ?>
                    <?php if (!empty($item['link'])): ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Contact Form Block
function gruenerator_render_contact_form_block($attributes, $content) {
    try {
        $default_content = Gruenerator_Default_Content::get_contact_form_content();
        $background_image_url = !empty($attributes['backgroundImageUrl']) ? $attributes['backgroundImageUrl'] : 'https://via.placeholder.com/1200x600';
        $title = !empty($attributes['title']) ? $attributes['title'] : $default_content['title'];
        $email = !empty($attributes['email']) ? $attributes['email'] : $default_content['email'];
        $social_media = !empty($attributes['socialMedia']) ? $attributes['socialMedia'] : array();
        $align_class = isset($attributes['align']) ? ' align' . $attributes['align'] : ' alignfull';

        ob_start();
        ?>
        <div class="wp-block-gruenerator-contact-form-block<?php echo esc_attr($align_class); ?>">
            <div class="contact-form-block" style="background-image: url('<?php echo esc_url($background_image_url); ?>');">
                <div class="contact-form-content">
                    <div class="contact-form-left">
                        <h2 class="contact-form-title"><?php echo esc_html($title); ?></h2>
                        <?php if ($email): ?>
                            <div class="contact-info">
                                <p><?php echo esc_html($default_content['description'] ?? ''); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="contact-form-right">
                        <!-- wp:sunflower/contact-form /-->
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    } catch (Exception $e) {
        error_log('Fehler beim Rendern des Contact-Form-Blocks: ' . $e->getMessage());
        return '';
    }
}

// Sunflower Contact Form Block Registration
function gruenerator_register_sunflower_contact_form_block() {
    if (function_exists('register_block_type') && !WP_Block_Type_Registry::get_instance()->is_registered('sunflower/contact-form')) {
        register_block_type('sunflower/contact-form', array(
            'render_callback' => 'gruenerator_render_sunflower_contact_form',
        ));
    }
}
add_action('init', 'gruenerator_register_sunflower_contact_form_block', 20); // Erhöhte Priorität

// Sunflower Contact Form Block Render Function
function gruenerator_render_sunflower_contact_form($attributes, $content) {
    // Prüfen, ob das Sunflower-Plugin aktiv ist
    if (!function_exists('sunflower_contact_form_shortcode')) {
        return '<p>' . __('Das Sunflower-Plugin ist nicht aktiviert. Bitte aktiviere es, um das Kontaktformular anzuzeigen.', 'gruenerator') . '</p>';
    }

    // Standardwerte für Attribute festlegen
    $default_attributes = array(
        'recipient' => get_option('admin_email'),
        'subject' => __('Neue Nachricht von deiner Website', 'gruenerator'),
        'submit_button_text' => __('Senden', 'gruenerator'),
    );

    // Attribute mit Standardwerten zusammenführen
    $form_attributes = wp_parse_args($attributes, $default_attributes);

    // Shortcode-Attribute erstellen
    $shortcode_atts = array();
    foreach ($form_attributes as $key => $value) {
        $shortcode_atts[] = $key . '="' . esc_attr($value) . '"';
    }

    // Shortcode zusammenbauen
    $shortcode = '[sunflower_contact_form ' . implode(' ', $shortcode_atts) . ']';

    // Shortcode ausführen und zurückgeben
    return do_shortcode($shortcode);
}
