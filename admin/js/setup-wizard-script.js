jQuery(document).ready(function($) {
    console.log('Setup Wizard Script geladen');
    
    // Media-Uploader für alle Bild-Upload-Felder
    $('.gruenerator-image-upload').on('click', function(e) {
        console.log('Bildauswahl-Button geklickt');
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
            console.log('Bild ausgewählt');
            var attachment = customUploader.state().get('selection').first().toJSON();
            button.siblings('input[type="hidden"]').val(attachment.id);
            button.siblings('.gruenerator-image-preview').html('<img src="' + attachment.url + '" style="max-width:100%;">');
        }).open();
    });

    // Bild entfernen
    $('.gruenerator-image-remove').on('click', function(e) {
        console.log('Bild-Entfernen-Button geklickt');
        e.preventDefault();
        var button = $(this);
        button.siblings('input[type="hidden"]').val('');
        button.siblings('.gruenerator-image-preview').html('');
    });
}); 