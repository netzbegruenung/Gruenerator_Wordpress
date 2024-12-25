<?php
// Verhindert direkten Zugriff auf die Datei
if (!defined('ABSPATH')) {
    exit;
}

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
                'heroImageId' => array(
                    'type' => 'integer',
                    'default' => 0,
                ),
                'heroImageUrl' => array(
                    'type' => 'string',
                    'default' => 'https://via.placeholder.com/600x400',
                ),
                'heroHeading' => array(
                    'type' => 'string',
                    'default' => 'Hi, ich bin Maxi Mustermensch',
                ),
                'heroText' => array(
                    'type' => 'string',
                    'default' => 'Kandidat*in für den Wahlkreis 54 Musterstadt-Musterort.',
                ),
            ),
        ),
        // 2. About Block
        'about-block' => array(
            'attributes' => array(
                'title' => array(
                    'type' => 'string',
                    'default' => 'Wer ich bin',
                ),
                'content' => array(
                    'type' => 'string',
                    'default' => 'Verwurzelt im Herzen von Musterstadt, mit einem festen Blick in die Zukunft: Dies beschreibt den Kern meiner Kandidatur für das Musterparlament. Als Musterberuf und langjährig engagierte Person in Musterorganisation habe ich stets die Bedeutung von Gemeinschaft, nachhaltiger Entwicklung und solidarischem Handeln aus nächster Nähe miterlebt. Mit einem offenen Ohr für alle Bürger*innen, einer lösungsorientierten Herangehensweise und dem festen Glauben an unsere gemeinsame Zukunft. Es geht darum, heute die Entscheidungen zu treffen, die morgen den Unterschied machen können. Für eine Politik, die auf Ausgleich und Nachhaltigkeit setzt, und für ein Musterstadt, in dem jede Stimme zählt.',
                    'gruenerator'
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
                    'default' => 'https://via.placeholder.com/1200x600',
                ),
                'title' => array(
                    'type' => 'string',
                    'default' => 'Gemeinsam in eine gute Zukunft!',
                ),
                'subtitle' => array(
                    'type' => 'string',
                    'default' => 'Füge hier deinen Claim ein... Die Herausforderungen sind groß, aber gemeinsam mit dir können wir sie stemmen.',
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
                    'default' => array(
                        [
                            'imageId' => 0,
                            'imageUrl' => 'https://via.placeholder.com/600x400',
                            'title' => 'Mit Herz für Klimaschutz.',
                            'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.'
                        ],
                        [
                            'imageId' => 0,
                            'imageUrl' => 'https://via.placeholder.com/600x400',
                            'title' => 'Grüne und günstige Mobilität.',
                            'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.'
                        ],
                        [
                            'imageId' => 0,
                            'imageUrl' => 'https://via.placeholder.com/600x400',
                            'title' => 'Gemeinsam gegen Hass und Hetze.',
                            'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.'
                        ]
                    ),
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
                                'default' => 'Mit Herz für Klimaschutz.',
                            ),
                            'content' => array(
                                'type' => 'string',
                                'default' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.',
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
                    'default' => array(
                        array(
                            'imageId'  => 0,
                            'imageUrl' => '',
                            'text'     => 'Spenden für Grün',
                            'link'     => '',
                        ),
                        array(
                            'imageId'  => 0,
                            'imageUrl' => '',
                            'text'     => 'Werde Mitglied',
                            'link'     => '',
                        ),
                        array(
                            'imageId'  => 0,
                            'imageUrl' => '',
                            'text'     => 'Haustürwahl-kampf',
                            'link'     => '',
                        ),
                    ),
                ),
            ),
            'render_callback' => 'gruenerator_render_image_grid_block',
            'style'           => 'gruenerator-blocks-frontend',
            'editor_style'    => 'gruenerator-blocks-editor',
            'editor_script'   => 'gruenerator-blocks',
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
                    'default' => 'Sag Hallo!',
                ),
                'email' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'phone' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'address' => array(
                    'type' => 'string',
                    'default' => '',
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

        $hero_image_url = !empty($attributes['heroImageUrl']) ? $attributes['heroImageUrl'] : 'https://via.placeholder.com/600x400';
        $hero_heading = !empty($attributes['heroHeading']) ? $attributes['heroHeading'] : 'Hi, ich bin Maxi Mustermensch';
        $hero_text = !empty($attributes['heroText']) ? $attributes['heroText'] : 'Kandidat*in für den Wahlkreis 54 Musterstadt-Musterort.';

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

        // Hole die Attribute mit Fallback-Werten
        $title = !empty($attributes['title']) ? $attributes['title'] : __('Wer ich bin', 'gruenerator');
        $content = !empty($attributes['content']) ? $attributes['content'] : __(
            'Verwurzelt im Herzen von Musterstadt, mit einem festen Blick in die Zukunft: Dies beschreibt den Kern meiner Kandidatur für das Musterparlament. Als Musterberuf und langjährig engagierte Person in Musterorganisation habe ich stets die Bedeutung von Gemeinschaft, nachhaltiger Entwicklung und solidarischem Handeln aus nächster Nähe miterlebt. Mit einem offenen Ohr für alle Bürger*innen, einer lösungsorientierten Herangehensweise und dem festen Glauben an unsere gemeinsame Zukunft. Es geht darum, heute die Entscheidungen zu treffen, die morgen den Unterschied machen können. Für eine Politik, die auf Ausgleich und Nachhaltigkeit setzt, und für ein Musterstadt, in dem jede Stimme zählt.',
            'gruenerator'
        );

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

        $background_image_url = !empty($attributes['backgroundImageUrl']) ? $attributes['backgroundImageUrl'] : 'https://via.placeholder.com/1200x600';
        $title = !empty($attributes['title']) ? $attributes['title'] : 'Gemeinsam in eine gute Zukunft!';
        $subtitle = !empty($attributes['subtitle']) ? $attributes['subtitle'] : 'Füge hier deinen Claim ein... Die Herausforderungen sind groß, aber gemeinsam mit dir können wir sie stemmen.';
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
        $themes = !empty($attributes['themes']) && is_array($attributes['themes']) ? $attributes['themes'] : [
            [
                'imageId' => 0,
                'imageUrl' => 'https://via.placeholder.com/600x400',
                'title' => 'Mit Herz für Klimaschutz.',
                'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.'
            ],
            [
                'imageId' => 0,
                'imageUrl' => 'https://via.placeholder.com/600x400',
                'title' => 'Grüne und günstige Mobilität.',
                'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.'
            ],
            [
                'imageId' => 0,
                'imageUrl' => 'https://via.placeholder.com/600x400',
                'title' => 'Gemeinsam gegen Hass und Hetze.',
                'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.'
            ]
        ];

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
        $background_image_url = !empty($attributes['backgroundImageUrl']) ? $attributes['backgroundImageUrl'] : 'https://via.placeholder.com/1200x600';
        $title = !empty($attributes['title']) ? $attributes['title'] : __('Sag Hallo!', 'gruenerator');
        $email = !empty($attributes['email']) ? $attributes['email'] : '';
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
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($social_media)): ?>
                            <div class="social-icons">
                                <?php foreach ($social_media as $profile): ?>
                                    <a href="<?php echo esc_url($profile['url']); ?>" 
                                       target="_blank"
                                       rel="noopener noreferrer" 
                                       aria-label="<?php echo esc_attr($profile['platform']); ?>">
                                        <i class="<?php echo esc_attr($profile['icon']); ?>"></i>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="contact-form-right">
                        <!-- wp:sunflower/contact-form -->
                        <div class="wp-block-sunflower-contact-form">
                            <?php echo do_blocks('<!-- wp:sunflower/contact-form --><!-- /wp:sunflower/contact-form -->'); ?>
                        </div>
                        <!-- /wp:sunflower/contact-form -->
                    </div>
                </div>
            </div>
        </div>
        <?php
        $output = ob_get_clean();
        if (empty($output)) {
            error_log('Contact form block: Leere Ausgabe');
            return '';
        }
        return $output;
    } catch (Exception $e) {
        error_log('Fehler beim Rendern des Kontaktformular-Blocks: ' . $e->getMessage());
        return '';
    }
}

function gruenerator_enqueue_icon_styles() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');
    wp_enqueue_style('fork-awesome', 'https://cdn.jsdelivr.net/npm/fork-awesome@1.1.7/css/fork-awesome.min.css');
}
add_action('wp_enqueue_scripts', 'gruenerator_enqueue_icon_styles');

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