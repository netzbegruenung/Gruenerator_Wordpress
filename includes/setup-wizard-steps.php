<?php
// Verhindert direkten Zugriff auf die Datei
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Willkommensseite des Setup-Wizards
 */
function gruenerator_welcome_page() {
    if (!current_user_can('manage_options')) {
        gruenerator_log("Unzureichende Berechtigungen für Benutzer: " . wp_get_current_user()->user_login, 'error');
        wp_die('Unzureichende Berechtigungen');
    }
    ?>
    <div class="gruenerator-welcome-page">
        <h2>Willkommen beim Grünerator Setup-Assistenten</h2>
        <p>In wenigen Schritten erstellen wir gemeinsam deine professionelle politische Landingpage. Der Assistent führt dich durch alle wichtigen Einstellungen und hilft dir dabei, deine Präsenz im Web optimal zu gestalten.</p>
        
        <div class="gruenerator-welcome-features">
            <div class="feature">
                <span class="dashicons dashicons-admin-appearance"></span>
                <h3>Individuelles Design</h3>
                <p>Gestalte deine Seite im Corporate Design der Grünen.</p>
            </div>
            <div class="feature">
                <span class="dashicons dashicons-share"></span>
                <h3>Social Media Integration</h3>
                <p>Vernetze alle deine Social-Media-Kanäle.</p>
            </div>
            <div class="feature">
                <span class="dashicons dashicons-edit"></span>
                <h3>Einfache Bearbeitung</h3>
                <p>Ändere Inhalte auch später ganz einfach.</p>
            </div>
        </div>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('gruenerator_setup_nonce'); ?>
            <input type="hidden" name="action" value="gruenerator_process_setup">
            <input type="hidden" name="step" value="1">
            <input type="submit" class="button button-primary" value="Setup starten">
        </form>
    </div>
    <?php
}

/**
 * CSS-Einstellungen des Setup-Wizards
 */
function gruenerator_css_settings() {
    if (!current_user_can('manage_options')) {
        gruenerator_log("Unzureichende Berechtigungen für Benutzer: " . wp_get_current_user()->user_login, 'error');
        wp_die('Unzureichende Berechtigungen');
    }
    ?>
    <div class="gruenerator-step-content">
        <h2>Design-Einstellungen</h2>
        <p>Wähle die grundlegenden Design-Einstellungen für deine Landingpage.</p>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="gruenerator_use_css">Standard-Design verwenden</label></th>
                <td>
                    <input type="checkbox" id="gruenerator_use_css" name="gruenerator_use_css" value="1" <?php checked(get_option('gruenerator_use_css', 1)); ?>>
                    <p class="description">Aktiviere diese Option, um das optimierte Grüne Design zu verwenden. Deaktiviere sie nur, wenn du ein komplett eigenes Design einsetzen möchtest.</p>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Soziale Netzwerke Einstellungen des Setup-Wizards
 */
function gruenerator_social_networks() {
    if (!current_user_can('manage_options')) {
        wp_die('Unzureichende Berechtigungen');
    }
    ?>
    <div class="gruenerator-step-content">
        <h2>Social Media Einbindung</h2>
        <p>Verknüpfe deine Social-Media-Profile, um deine Online-Präsenz zu stärken.</p>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="gruenerator_social_facebook">Facebook</label></th>
                <td>
                    <input type="url" id="gruenerator_social_facebook" name="gruenerator_social_facebook" value="<?php echo esc_url(get_option('gruenerator_social_facebook', '')); ?>" class="regular-text">
                    <p class="description">z.B. https://facebook.com/IhrName</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="gruenerator_social_twitter">X (Twitter)</label></th>
                <td>
                    <input type="url" id="gruenerator_social_twitter" name="gruenerator_social_twitter" value="<?php echo esc_url(get_option('gruenerator_social_twitter', '')); ?>" class="regular-text">
                    <p class="description">z.B. https://x.com/IhrName</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="gruenerator_social_instagram">Instagram</label></th>
                <td>
                    <input type="url" id="gruenerator_social_instagram" name="gruenerator_social_instagram" value="<?php echo esc_url(get_option('gruenerator_social_instagram', '')); ?>" class="regular-text">
                    <p class="description">z.B. https://instagram.com/IhrName</p>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Hero-Bereich Einstellungen des Setup-Wizards
 */
function gruenerator_hero_section() {
    if (!current_user_can('manage_options')) {
        wp_die('Unzureichende Berechtigungen');
    }
    ?>
    <div class="gruenerator-step-content">
        <h2>Dein persönlicher Hero-Bereich</h2>
        <p>Gestalte den ersten Eindruck deiner Landingpage mit einem professionellen Header-Bereich.</p>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="gruenerator_hero_image">Dein Porträtfoto</label></th>
                <td>
                    <input type="hidden" id="gruenerator_hero_image" name="gruenerator_hero_image" value="<?php echo esc_attr(get_option('gruenerator_hero_image', '')); ?>">
                    <button type="button" class="button gruenerator-image-upload">Foto auswählen</button>
                    <button type="button" class="button gruenerator-image-remove">Foto entfernen</button>
                    <div class="gruenerator-image-preview">
                        <?php
                        $image_id = get_option('gruenerator_hero_image', '');
                        if ($image_id) {
                            echo wp_get_attachment_image($image_id, 'medium');
                        }
                        ?>
                    </div>
                    <p class="description">Wähle ein professionelles Porträtfoto im Querformat (empfohlen: 1200x800 Pixel).</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="gruenerator_hero_heading">Deine Begrüßung</label></th>
                <td>
                    <input type="text" id="gruenerator_hero_heading" name="gruenerator_hero_heading" value="<?php echo esc_attr(get_option('gruenerator_hero_heading', 'Hallo, ich bin Maxi Mustermensch')); ?>" class="regular-text">
                    <p class="description">Eine persönliche Begrüßung macht deine Seite einladend.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="gruenerator_hero_text">Kurze Vorstellung</label></th>
                <td>
                    <textarea id="gruenerator_hero_text" name="gruenerator_hero_text" rows="3" class="large-text"><?php echo esc_textarea(get_option('gruenerator_hero_text', 'Kandidat*in für den Wahlkreis Musterstadt-Nord. Ich setze mich für eine nachhaltige und gerechte Zukunft ein.')); ?></textarea>
                    <p class="description">Ein kurzer, prägnanter Text über deine politische Rolle und Motivation.</p>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Über mich Einstellungen des Setup-Wizards
 */
function gruenerator_about_me() {
    if (!current_user_can('manage_options')) {
        wp_die('Unzureichende Berechtigungen');
    }
    ?>
    <div class="gruenerator-step-content">
        <h2>Über mich</h2>
        <p>Erzähle deine Geschichte und teile deine politische Vision.</p>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="gruenerator_about_me_title">Überschrift</label></th>
                <td>
                    <input type="text" id="gruenerator_about_me_title" name="gruenerator_about_me_title" value="<?php echo esc_attr(get_option('gruenerator_about_me_title', 'Wer ich bin')); ?>" class="regular-text">
                    <p class="description">Eine einladende Überschrift für deinen persönlichen Bereich.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="gruenerator_about_me_content">Deine Geschichte</label></th>
                <td>
                    <?php
                    $default_content = "Verwurzelt im Herzen von Musterstadt, mit einem festen Blick in die Zukunft: Dies beschreibt den Kern meiner Kandidatur für das Musterparlament. Als Musterberuf und langjährig engagierte Person in Musterorganisation habe ich stets die Bedeutung von Gemeinschaft, nachhaltiger Entwicklung und solidarischem Handeln aus nächster Nähe miterlebt. Mit einem offenen Ohr für alle Bürger*innen, einer lösungsorientierten Herangehensweise und dem festen Glauben an unsere gemeinsame Zukunft. Es geht darum, heute die Entscheidungen zu treffen, die morgen den Unterschied machen können. Für eine Politik, die auf Ausgleich und Nachhaltigkeit setzt, und für ein Musterstadt, in dem jede Stimme zählt.";
                    
                    wp_editor(
                        get_option('gruenerator_about_me_content', $default_content),
                        'gruenerator_about_me_content',
                        array(
                            'textarea_name' => 'gruenerator_about_me_content',
                            'media_buttons' => false,
                            'textarea_rows' => 10,
                            'teeny' => true,
                            'quicktags' => array('buttons' => 'strong,em'),
                        )
                    );
                    ?>
                    <p class="description">Erzähle von deinem Werdegang, deiner Motivation und deinen Zielen. Sei authentisch und persönlich.</p>
                </td>
            </tr>
        </table>
    </div>
    <?php
}


/**
 * Hero Image Block Einstellungen des Setup-Wizards
 */
/**
 * Hero Image Block Einstellungen des Setup-Wizards
 */
function gruenerator_hero_image_block() {
    if (!current_user_can('manage_options')) {
        wp_die('Unzureichende Berechtigungen');
    }
    ?>
    <div class="gruenerator-step-content">
        <h2>Dein Hauptthema</h2>
        <p>Präsentiere dein wichtigstes politisches Anliegen mit einem eindrucksvollen Bild und einer starken Botschaft.</p>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="gruenerator_hero_image_block_image">Themenbild</label></th>
                <td>
                    <input type="hidden" id="gruenerator_hero_image_block_image" name="gruenerator_hero_image_block_image" value="<?php echo esc_attr(get_option('gruenerator_hero_image_block_image', '')); ?>">
                    <button type="button" class="button gruenerator-image-upload">Bild auswählen</button>
                    <button type="button" class="button gruenerator-image-remove">Bild entfernen</button>
                    <div class="gruenerator-image-preview">
                        <?php
                        $image_id = get_option('gruenerator_hero_image_block_image', '');
                        if ($image_id) {
                            echo wp_get_attachment_image($image_id, 'medium');
                        }
                        ?>
                    </div>
                    <p class="description">Wähle ein ausdrucksstarkes Bild, das dein Hauptthema visualisiert (empfohlen: 1920x1080 Pixel).</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="gruenerator_hero_image_title">Hauptbotschaft</label></th>
                <td>
                    <input type="text" id="gruenerator_hero_image_title" name="gruenerator_hero_image_title" value="<?php echo esc_attr(get_option('gruenerator_hero_image_title', 'Gemeinsam für eine nachhaltige Zukunft!')); ?>" class="regular-text">
                    <p class="description">Eine prägnante Botschaft, die deine politische Vision zusammenfasst.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="gruenerator_hero_image_subtitle">Erläuterung</label></th>
                <td>
                    <textarea id="gruenerator_hero_image_subtitle" name="gruenerator_hero_image_subtitle" rows="3" class="large-text"><?php echo esc_textarea(get_option('gruenerator_hero_image_subtitle', 'Mit deiner Unterstützung können wir unsere Region nachhaltiger, gerechter und lebenswerter gestalten. Lass uns gemeinsam die Herausforderungen angehen.')); ?></textarea>
                    <p class="description">Ergänze deine Hauptbotschaft mit einem motivierenden Aufruf zum Mitmachen.</p>
                </td>
            </tr>
        </table>
    </div>
    <?php
}


/**
 * Meine Themen Einstellungen des Setup-Wizards
 */
/**
 * Meine Themen Einstellungen des Setup-Wizards
 */
function gruenerator_my_themes() {
    if (!current_user_can('manage_options')) {
        wp_die('Unzureichende Berechtigungen');
    }
    ?>
    <div class="gruenerator-step-content">
        <h2>Deine politischen Schwerpunkte</h2>
        <p>Stelle deine drei wichtigsten politischen Themen vor.</p>
        
        <?php 
        $default_themes = array(
            1 => array(
                'title' => 'Klimaschutz vor Ort umsetzen',
                'content' => 'Wir setzen uns für konkrete Klimaschutzmaßnahmen in unserer Kommune ein. Von erneuerbaren Energien bis hin zu nachhaltiger Stadtplanung - gemeinsam gestalten wir eine grüne Zukunft.'
            ),
            2 => array(
                'title' => 'Nachhaltige Mobilität fördern',
                'content' => 'Wir machen uns stark für ein modernes Verkehrskonzept: Bessere Radwege, attraktiver ÖPNV und sichere Fußwege für alle. So schaffen wir eine lebenswerte Stadt mit hoher Mobilität.'
            ),
            3 => array(
                'title' => 'Soziale Gerechtigkeit stärken',
                'content' => 'Wir kämpfen für bezahlbares Wohnen, gute Bildung und faire Chancen für alle. Denn eine gerechte Gesellschaft ist die Basis für ein harmonisches Zusammenleben.'
            )
        );

        for ($i = 1; $i <= 3; $i++) : 
            $theme_image = get_option('gruenerator_theme_image_' . $i, '');
            $theme_title = get_option('gruenerator_theme_title_' . $i, $default_themes[$i]['title']);
            $theme_content = get_option('gruenerator_theme_content_' . $i, $default_themes[$i]['content']);
        ?>
            <div class="gruenerator-theme-section">
                <h3>Schwerpunkt <?php echo $i; ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="gruenerator_theme_image_<?php echo $i; ?>">Bild</label></th>
                        <td>
                            <input type="hidden" id="gruenerator_theme_image_<?php echo $i; ?>" name="gruenerator_theme_image_<?php echo $i; ?>" value="<?php echo esc_attr($theme_image); ?>">
                            <button type="button" class="button gruenerator-image-upload">Bild auswählen</button>
                            <button type="button" class="button gruenerator-image-remove">Bild entfernen</button>
                            <div class="gruenerator-image-preview">
                                <?php if ($theme_image) : ?>
                                    <img src="<?php echo esc_url(wp_get_attachment_url($theme_image)); ?>" style="max-width:100%;">
                                <?php endif; ?>
                            </div>
                            <p class="description">Wähle ein aussagekräftiges Bild für diesen Schwerpunkt.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="gruenerator_theme_title_<?php echo $i; ?>">Titel</label></th>
                        <td>
                            <input type="text" id="gruenerator_theme_title_<?php echo $i; ?>" name="gruenerator_theme_title_<?php echo $i; ?>" value="<?php echo esc_attr($theme_title); ?>" class="regular-text">
                            <p class="description">Gib deinem Schwerpunkt einen prägnanten Titel.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="gruenerator_theme_content_<?php echo $i; ?>">Beschreibung</label></th>
                        <td>
                            <textarea id="gruenerator_theme_content_<?php echo $i; ?>" name="gruenerator_theme_content_<?php echo $i; ?>" rows="4" class="large-text"><?php echo esc_textarea($theme_content); ?></textarea>
                            <p class="description">Beschreibe kurz und prägnant, wofür du dich in diesem Bereich einsetzt.</p>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endfor; ?>
    </div>
    <?php
}

/**
 * Bildergalerie Einstellungen des Setup-Wizards
 */

/**
 * Bildergalerie Einstellungen des Setup-Wizards
 */
function gruenerator_image_grid() {
    if (!current_user_can('manage_options')) {
        wp_die('Unzureichende Berechtigungen');
    }
    ?>
    <div class="gruenerator-step-content">
        <h2>Aktionsbereich</h2>
        <p>Gestalte drei Aktionsfelder, die Besucher*innen zum Mitmachen einladen.</p>
        <table class="form-table">
            <?php 
            $default_actions = array(
                1 => array(
                    'text' => 'Unterstütze uns',
                    'link' => '#spenden'
                ),
                2 => array(
                    'text' => 'Werde Mitglied',
                    'link' => 'https://www.gruene.de/mitglied-werden'
                ),
                3 => array(
                    'text' => 'Mach mit',
                    'link' => '#kontakt'
                )
            );
            
            for ($i = 1; $i <= 3; $i++): ?>
                <tr>
                    <th scope="row"><label for="gruenerator_action_image_<?php echo $i; ?>">Aktion <?php echo $i; ?> - Bild</label></th>
                    <td>
                        <input type="hidden" name="gruenerator_action_image_<?php echo $i; ?>" id="gruenerator_action_image_<?php echo $i; ?>" value="<?php echo esc_attr(get_option('gruenerator_action_image_' . $i, '')); ?>">
                        <button type="button" class="button gruenerator-image-upload">Bild auswählen</button>
                        <button type="button" class="button gruenerator-image-remove">Bild entfernen</button>
                        <div class="gruenerator-image-preview">
                            <?php
                            $image_id = get_option('gruenerator_action_image_' . $i, '');
                            if ($image_id) {
                                echo wp_get_attachment_image($image_id, 'medium');
                            }
                            ?>
                        </div>
                        <p class="description">Wähle ein aktivierendes Bild (empfohlen: 600x400 Pixel).</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="gruenerator_action_text_<?php echo $i; ?>">Aktion <?php echo $i; ?> - Text</label></th>
                    <td>
                        <input type="text" id="gruenerator_action_text_<?php echo $i; ?>" name="gruenerator_action_text_<?php echo $i; ?>" value="<?php echo esc_attr(get_option('gruenerator_action_text_' . $i, $default_actions[$i]['text'])); ?>" class="regular-text">
                        <p class="description">Ein kurzer, aktivierender Aufruf.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="gruenerator_action_link_<?php echo $i; ?>">Aktion <?php echo $i; ?> - Link</label></th>
                    <td>
                        <input type="text" id="gruenerator_action_link_<?php echo $i; ?>" name="gruenerator_action_link_<?php echo $i; ?>" value="<?php echo esc_attr(get_option('gruenerator_action_link_' . $i, $default_actions[$i]['link'])); ?>" class="regular-text">
                        <p class="description">Optional: Die Zielseite für die Aktion (z.B. Spendenformular, Mitgliedsantrag). Kann auch später ergänzt werden.</p>
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
    </div>
    <?php
}


/**
 * Kontaktformular Einstellungen des Setup-Wizards
 */
/**
 * Kontaktformular Einstellungen des Setup-Wizards
 */
function gruenerator_contact_form() {
    if (!current_user_can('manage_options')) {
        wp_die('Unzureichende Berechtigungen');
    }
    ?>
    <div class="gruenerator-step-content">
        <h2>Kontaktbereich</h2>
        <p>Gestalte deinen Kontaktbereich, damit Interessierte dich einfach erreichen können.</p>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="gruenerator_contact_form_title">Überschrift</label></th>
                <td>
                    <input type="text" id="gruenerator_contact_form_title" name="gruenerator_contact_form_title" value="<?php echo esc_attr(get_option('gruenerator_contact_form_title', 'Sag Hallo!')); ?>" class="regular-text">
                    <p class="description">Eine einladende Überschrift für den Kontaktbereich.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="gruenerator_contact_form_image">Hintergrundbild</label></th>
                <td>
                    <input type="hidden" name="gruenerator_contact_form_image" id="gruenerator_contact_form_image" value="<?php echo esc_attr(get_option('gruenerator_contact_form_image', '')); ?>">
                    <button type="button" class="button gruenerator-image-upload">Bild auswählen</button>
                    <button type="button" class="button gruenerator-image-remove">Bild entfernen</button>
                    <div class="gruenerator-image-preview">
                        <?php
                        $image_id = get_option('gruenerator_contact_form_image', '');
                        if ($image_id) {
                            echo wp_get_attachment_image($image_id, 'medium');
                        }
                        ?>
                    </div>
                    <p class="description">Wähle ein einladendes Hintergrundbild (empfohlen: 1920x1080 Pixel).</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="gruenerator_contact_form_email">Deine E-Mail-Adresse</label></th>
                <td>
                    <input type="email" id="gruenerator_contact_form_email" name="gruenerator_contact_form_email" value="<?php echo esc_attr(get_option('gruenerator_contact_form_email', get_option('admin_email'))); ?>" class="regular-text">
                    <p class="description">An diese Adresse werden die Kontaktanfragen gesendet.</p>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Finaler Schritt des Setup-Wizards
 */
function gruenerator_final_step() {
    if (!current_user_can('manage_options')) {
        wp_die('Unzureichende Berechtigungen');
    }
    ?>
    <div class="gruenerator-step-content">
        <h2>Fertigstellung</h2>
        <p>Herzlichen Glückwunsch! Du hast alle Einstellungen vorgenommen. Klicke auf "Abschließen", um deine Landingpage zu erstellen.</p>
        
        <div class="gruenerator-final-summary">
            <h3>Was als Nächstes passiert:</h3>
            <ul>
                <li>Deine Landingpage wird mit allen eingegebenen Inhalten erstellt</li>
                <li>Du kannst die Seite sofort nach der Erstellung ansehen und bearbeiten</li>
                <li>Alle Einstellungen können später über das Grünerator-Dashboard angepasst werden</li>
            </ul>
        </div>

        <table class="form-table">
            <tr>
                <th scope="row"><label for="gruenerator_set_as_frontpage">Als Startseite festlegen</label></th>
                <td>
                    <input type="checkbox" id="gruenerator_set_as_frontpage" name="gruenerator_set_as_frontpage" value="1">
                    <p class="description">Aktiviere diese Option, um die erstellte Landingpage als Startseite deiner Website festzulegen.</p>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Abschlussseite des Setup-Wizards
 */
function gruenerator_setup_complete_page() {
    if (!current_user_can('manage_options')) {
        gruenerator_log("Unzureichende Berechtigungen für Benutzer: " . wp_get_current_user()->user_login, 'error');
        wp_die('Unzureichende Berechtigungen');
    }
    
    gruenerator_log("Anzeigen der Erfolgsseite", 'info');
    $landing_page_id = get_option('gruenerator_landing_page_id');
    $landing_page_url = $landing_page_id ? get_permalink($landing_page_id) : '';
    ?>
    <div class="gruenerator-setup-complete">
        <div class="gruenerator-success-message">
            <span class="dashicons dashicons-yes-alt"></span>
            <h2>Glückwunsch! Dein Grünerator Setup ist abgeschlossen.</h2>
            <p>Deine personalisierte Landingpage wurde erfolgreich erstellt und ist jetzt bereit für die Veröffentlichung.</p>
        </div>
        <div class="gruenerator-action-buttons">
            <?php if ($landing_page_url): ?>
                <a href="<?php echo esc_url($landing_page_url); ?>" class="button button-primary" target="_blank">Landingpage anzeigen</a>
            <?php endif; ?>
            <a href="<?php echo admin_url('admin.php?page=gruenerator-generator'); ?>" class="button button-secondary">Zum Grünerator Dashboard</a>
        </div>
        <div class="gruenerator-next-steps">
            <h3>Nächste Schritte:</h3>
            <ul>
                <li>Überprüfe deine Landingpage und nimm bei Bedarf weitere Anpassungen vor.</li>
                <li>Füge zusätzliche Inhalte oder Blöcke hinzu, um deine Seite zu vervollständigen.</li>
                <li>Teile deine neue Landingpage in deinen sozialen Netzwerken.</li>
            </ul>
        </div>
    </div>
    <?php
}

add_action('admin_post_gruenerator_process_setup', 'gruenerator_process_setup');