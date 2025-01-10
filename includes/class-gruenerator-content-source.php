<?php
// Verhindert direkten Zugriff auf die Datei
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Klasse für die Verwaltung der Inhaltsquellen
 */
class Gruenerator_Content_Source {
    
    /**
     * Speichert die Quelle der Inhalte
     */
    public static function set_content_source($source, $json_content = '') {
        if ($source === 'json' && !empty($json_content)) {
            $decoded_content = json_decode($json_content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                update_option('gruenerator_content_source', 'json');
                update_option('gruenerator_json_content', $json_content);
                return true;
            }
            return false;
        } else {
            update_option('gruenerator_content_source', 'default');
            delete_option('gruenerator_json_content');
            return true;
        }
    }

    /**
     * Holt die aktuelle Inhaltsquelle
     */
    public static function get_content_source() {
        return get_option('gruenerator_content_source', 'default');
    }

    /**
     * Holt den JSON-Inhalt wenn vorhanden
     */
    public static function get_json_content() {
        return get_option('gruenerator_json_content', '');
    }

    /**
     * Prüft ob die Inhaltsquelle bereits gewählt wurde
     */
    public static function is_source_selected() {
        return get_option('gruenerator_content_source_selected', false);
    }

    /**
     * Markiert die Inhaltsquelle als ausgewählt
     */
    public static function mark_source_selected() {
        update_option('gruenerator_content_source_selected', true);
    }

    /**
     * Gibt das JSON-Schema für die Inhalte zurück
     */
    public static function get_json_schema() {
        return array(
            'hero' => array(
                'heading' => 'string',
                'text' => 'string'
            ),
            'about' => array(
                'title' => 'string',
                'content' => 'string'
            ),
            'hero_image' => array(
                'title' => 'string',
                'subtitle' => 'string'
            ),
            'themes' => array(
                array(
                    'title' => 'string',
                    'content' => 'string'
                )
            ),
            'actions' => array(
                array(
                    'text' => 'string',
                    'link' => 'url'
                )
            ),
            'contact' => array(
                'title' => 'string',
                'email' => 'email'
            )
        );
    }

    /**
     * Validiert den JSON-Inhalt gegen das Schema
     */
    public static function validate_json_content($json_content) {
        $content = json_decode($json_content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        $schema = self::get_json_schema();
        return self::validate_against_schema($content, $schema);
    }

    /**
     * Rekursive Validierung gegen das Schema
     */
    private static function validate_against_schema($content, $schema) {
        foreach ($schema as $key => $value) {
            if (!isset($content[$key])) {
                return false;
            }

            if (is_array($value)) {
                if (isset($value[0])) {
                    // Array von Objekten
                    if (!is_array($content[$key])) {
                        return false;
                    }
                    foreach ($content[$key] as $item) {
                        if (!self::validate_against_schema($item, $value[0])) {
                            return false;
                        }
                    }
                } else {
                    // Einzelnes Objekt
                    if (!self::validate_against_schema($content[$key], $value)) {
                        return false;
                    }
                }
            } else {
                // Einfacher Typ
                switch ($value) {
                    case 'string':
                        if (!is_string($content[$key])) {
                            return false;
                        }
                        break;
                    case 'url':
                        if (!filter_var($content[$key], FILTER_VALIDATE_URL)) {
                            return false;
                        }
                        break;
                    case 'email':
                        if (!filter_var($content[$key], FILTER_VALIDATE_EMAIL)) {
                            return false;
                        }
                        break;
                }
            }
        }
        return true;
    }
} 