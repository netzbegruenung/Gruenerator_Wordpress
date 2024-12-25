jQuery(document).ready(function($) {
    // Media-Uploader für alle Bild-Upload-Felder
    $('.gruenerator-image-upload').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var customUploader = wp.media({
            title: 'Bild auswählen',
            library: {
                type: 'image'
            },
            button: {
                text: 'Bild verwenden'
            },
            multiple: false
        }).on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            button.siblings('input[type="hidden"]').val(attachment.id);
            button.siblings('.gruenerator-image-preview').html('<img src="' + attachment.url + '" style="max-width:100%;">');
        }).open();
    });

    // Bild entfernen
    $('.gruenerator-image-remove').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        button.siblings('input[type="hidden"]').val('');
        button.siblings('.gruenerator-image-preview').html('');
    });
}); 