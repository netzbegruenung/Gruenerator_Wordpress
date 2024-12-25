<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function gruenerator_add_meta_box() {
    $post_types = apply_filters('gruenerator_contact_background_post_types', array('page', 'post'));
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'gruenerator_contact_background',
            __('Kontaktformular Hintergrundbild', 'gruenerator'),
            'gruenerator_meta_box_callback',
            $post_type,
            'side',
            'default'
        );
    }
}
add_action('add_meta_boxes', 'gruenerator_add_meta_box');

function gruenerator_meta_box_callback($post) {
    wp_nonce_field('gruenerator_save_meta_box_data', 'gruenerator_meta_box_nonce');
    $value = get_post_meta($post->ID, '_gruenerator_contact_background', true);
    ?>
    <p>
        <label for="gruenerator_contact_background"><?php _e('Hintergrundbild URL', 'gruenerator'); ?></label>
        <input type="text" id="gruenerator_contact_background" name="gruenerator_contact_background" value="<?php echo esc_attr($value); ?>" size="25" />
    </p>
    <p>
        <input type="button" class="button button-secondary" value="<?php _e('Bild auswählen', 'gruenerator'); ?>" id="gruenerator_contact_background_button" />
    </p>
    <script>
    jQuery(document).ready(function($){
        $('#gruenerator_contact_background_button').click(function(e) {
            e.preventDefault();
            var image = wp.media({ 
                title: '<?php _e('Hintergrundbild auswählen', 'gruenerator'); ?>',
                multiple: false
            }).open()
            .on('select', function(e){
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('#gruenerator_contact_background').val(image_url);
            });
        });
    });
    </script>
    <?php
}

function gruenerator_save_meta_box_data($post_id) {
    if (!isset($_POST['gruenerator_meta_box_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['gruenerator_meta_box_nonce'], 'gruenerator_save_meta_box_data')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (!isset($_POST['gruenerator_contact_background'])) {
        return;
    }
    $my_data = sanitize_text_field($_POST['gruenerator_contact_background']);
    update_post_meta($post_id, '_gruenerator_contact_background', $my_data);
}
add_action('save_post', 'gruenerator_save_meta_box_data');

// Umbenennung der Funktion, um Konflikte zu vermeiden
function gruenerator_enqueue_admin_scripts_for_meta_fields($hook) {
    if ('post.php' != $hook && 'post-new.php' != $hook) {
        return;
    }
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'gruenerator_enqueue_admin_scripts_for_meta_fields');
