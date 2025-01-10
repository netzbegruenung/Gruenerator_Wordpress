<?php
// Verhindert direkten Zugriff auf die Datei
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Klasse für die Verwaltung der Standardinhalte
 */
class Gruenerator_Default_Content {

    /**
     * Holt den Inhalt aus der gewählten Quelle
     *
     * @param string $section Der Name des Inhaltsbereichs
     * @param mixed $default Der Standardwert
     * @return mixed
     */
    private static function get_content_from_source($section, $default) {
        if (Gruenerator_Content_Source::get_content_source() === 'json') {
            $json_content = json_decode(Gruenerator_Content_Source::get_json_content(), true);
            if (isset($json_content[$section])) {
                return $json_content[$section];
            }
        }
        return $default;
    }

    /**
     * Gibt den Hero-Content zurück
     *
     * @return array
     */
    public static function get_hero_content() {
        $default = [
            'heading' => __('Hi, ich bin Maxi Mustermensch', 'gruenerator'),
            'text' => __('Kandidat*in für den Wahlkreis 54 Musterstadt-Musterort', 'gruenerator')
        ];
        
        return self::get_content_from_source('hero', $default);
    }

    /**
     * Gibt den About-Content zurück
     *
     * @return array
     */
    public static function get_about_content() {
        $default = [
            'title' => __('Wer ich bin', 'gruenerator'),
            'content' => __(
                'Verwurzelt im Herzen von Musterstadt, mit einem festen Blick in die Zukunft: ' .
                'Dies beschreibt den Kern meiner Kandidatur für das Musterparlament. Als Musterberuf ' .
                'und langjährig engagierte Person in Musterorganisation habe ich stets die Bedeutung ' .
                'von Gemeinschaft, nachhaltiger Entwicklung und solidarischem Handeln aus nächster Nähe miterlebt. ' .
                'Mit einem offenen Ohr für alle Bürger*innen, einer lösungsorientierten Herangehensweise ' .
                'und dem festen Glauben an unsere gemeinsame Zukunft. Es geht darum, heute die ' .
                'Entscheidungen zu treffen, die morgen den Unterschied machen können. ' .
                'Für eine Politik, die auf Ausgleich und Nachhaltigkeit setzt, und für ein ' .
                'Musterstadt, in dem jede Stimme zählt.',
                'gruenerator'
            )
        ];
        
        return self::get_content_from_source('about', $default);
    }

    /**
     * Gibt die Themen zurück
     *
     * @return array
     */
    public static function get_themes() {
        $default = [
            [
                'title' => __('Mit Herz für Klimaschutz', 'gruenerator'),
                'content' => __('Wir setzen uns für konkrete Klimaschutzmaßnahmen in unserer Kommune ein. Von erneuerbaren Energien bis hin zu nachhaltiger Stadtplanung - gemeinsam gestalten wir eine grüne Zukunft.', 'gruenerator')
            ],
            [
                'title' => __('Grüne und günstige Mobilität', 'gruenerator'),
                'content' => __('Wir machen uns stark für ein modernes Verkehrskonzept: Bessere Radwege, attraktiver ÖPNV und sichere Fußwege für alle. So schaffen wir eine lebenswerte Stadt mit hoher Mobilität.', 'gruenerator')
            ],
            [
                'title' => __('Gemeinsam gegen Hass und Hetze', 'gruenerator'),
                'content' => __('Wir kämpfen für bezahlbares Wohnen, gute Bildung und faire Chancen für alle. Denn eine gerechte Gesellschaft ist die Basis für ein harmonisches Zusammenleben.', 'gruenerator')
            ]
        ];
        
        return self::get_content_from_source('themes', $default);
    }

    /**
     * Gibt die Aktionen zurück
     *
     * @return array
     */
    public static function get_actions() {
        $default = [
            [
                'text' => __('Unterstütze uns', 'gruenerator'),
                'link' => '#spenden'
            ],
            [
                'text' => __('Werde Mitglied', 'gruenerator'),
                'link' => 'https://www.gruene.de/mitglied-werden'
            ],
            [
                'text' => __('Mach mit', 'gruenerator'),
                'link' => '#kontakt'
            ]
        ];
        
        return self::get_content_from_source('actions', $default);
    }

    /**
     * Gibt den Kontaktformular-Content zurück
     *
     * @return array
     */
    public static function get_contact_form_content() {
        $default = [
            'title' => __('Sag Hallo!', 'gruenerator'),
            'email' => get_option('admin_email')
        ];
        
        return self::get_content_from_source('contact', $default);
    }

    /**
     * Gibt den Hero-Image-Content zurück
     *
     * @return array
     */
    public static function get_hero_image_content() {
        $default = [
            'title' => __('Gemeinsam für eine nachhaltige Zukunft!', 'gruenerator'),
            'subtitle' => __('Mit deiner Unterstützung können wir unsere Region nachhaltiger, gerechter und lebenswerter gestalten. Lass uns gemeinsam die Herausforderungen angehen.', 'gruenerator')
        ];
        
        return self::get_content_from_source('hero_image', $default);
    }

    /**
     * Gibt die Bildgrößen zurück
     *
     * @return array
     */
    public static function get_image_sizes() {
        return [
            'hero' => [
                'width' => 600,
                'height' => 400,
                'description' => __('Porträtfoto im Querformat', 'gruenerator'),
            ],
            'hero_image' => [
                'width' => 1920,
                'height' => 1080,
                'description' => __('Ausdrucksstarkes Themenbild', 'gruenerator'),
            ],
            'theme' => [
                'width' => 600,
                'height' => 400,
                'description' => __('Aussagekräftiges Themenbild', 'gruenerator'),
            ],
            'action' => [
                'width' => 600,
                'height' => 400,
                'description' => __('Aktivierendes Aktionsbild', 'gruenerator'),
            ],
        ];
    }
} 