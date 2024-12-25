<?php
/**
 * Title: Grünerator Landing Page
 * Slug: gruenerator/landing-page
 * Categories: featured, landing-page
 * Description: Eine Vorlage für eine Landing Page mit Grünerator-Blöcken
 */

// Generiere die Bild-URLs
$upload_dir = wp_upload_dir();
$image1 = esc_url($upload_dir['baseurl'] . '/2024/09/mika-baumeister-bnuFRiQDYIM-unsplash.jpg');
$image2 = esc_url($upload_dir['baseurl'] . '/2024/09/aaron-doucett-JwAeeifBANs-unsplash.jpg');
$image3 = esc_url($upload_dir['baseurl'] . '/2024/09/janosch-lino-USKsnSIDNIA-unsplash.jpg');
?>

<!-- wp:group {"className":"styled-layout gruenerator-landing-page"} -->
<div class="wp-block-group styled-layout gruenerator-landing-page">

    <?php echo do_blocks('
    <!-- wp:gruenerator/hero-block {
        "heroImage":"https://via.placeholder.com/600x400",
        "heroHeading":"Hi, ich bin Maxi Mustermensch",
        "heroText":"Kandidat*in für den Wahlkreis 54 Musterstadt-Musterort. Lorem ipsum dolor sit amet, consectetur adipiscing elit."
    } /-->

    <!-- wp:group -->
    <div class="wp-block-group">
        <!-- wp:gruenerator/about-block {"title":"Wer ich bin"} -->
        <!-- wp:paragraph -->
        <p>Verwurzelt im Herzen von Musterstadt, mit einem festen Blick in die Zukunft: Dies beschreibt den Kern meiner Kandidatur für das Musterparlament. Als Musterberuf und langjährig engagierte Person in Musterorganisation habe ich stets die Bedeutung von <strong>Gemeinschaft, nachhaltiger Entwicklung</strong> und <strong>solidarischem Handeln</strong> aus nächster Nähe miterlebt.</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph -->
        <p>Mit einem offenen Ohr für alle Bürger*innen, einer <strong>lösungsorientierten Herangehensweise</strong> und dem festen Glauben an unsere <strong>gemeinsame Zukunft</strong>. Es geht darum, heute die <strong>Entscheidungen</strong> zu treffen, die morgen den <strong>Unterschied</strong> machen können. Für eine Politik, die auf Ausgleich und <strong>Nachhaltigkeit</strong> setzt, und für ein Musterstadt, in dem <strong>jede Stimme zählt</strong>.</p>
        <!-- /wp:paragraph -->
        <!-- /wp:gruenerator/about-block -->
    </div>
    <!-- /wp:group -->

    <!-- wp:gruenerator/hero-image-block {
        "backgroundImage":"' . $image1 . '",
        "title":"Gemeinsam in eine gute Zukunft!",
        "subtitle":"Füge hier deinen Claim ein... Die Herausforderungen sind groß, aber gemeinsam mit dir können wir sie stemmen."
    } /-->

    <!-- wp:gruenerator/meine-themen-block {
        "title":"Meine Themen",
        "themes":[
            {
                "image":"' . $image1 . '",
                "title":"Mit Herz für Klimaschutz.",
                "content":"Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua."
            },
            {
                "image":"' . $image2 . '",
                "title":"Grüne und günstige Mobilität.",
                "content":"Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua."
            },
            {
                "image":"' . $image3 . '",
                "title":"Gemeinsam gegen Hass und Hetze.",
                "content":"Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua."
            }
        ]
    } /-->

    <!-- wp:group {"className":"link-tiles-section"} -->
    <div class="wp-block-group link-tiles-section">
        <!-- wp:heading {"level":2, "className":"link-tiles-heading"} -->
        <h2 class="link-tiles-heading">Aktuelle Themen</h2>
        <!-- /wp:heading -->

        <!-- wp:gruenerator/link-tile-block {
            "title":"Unsere Spitzenkandidatin",
            "backgroundImage":"' . $image2 . '",
            "linkUrl":"https://beta.gruenerator.de",
            "ariaLabel":"Mehr über unsere Spitzenkandidatin"
        } /-->

        <!-- wp:gruenerator/link-tile-block {
            "title":"Unsere Spitzenkandidatin",
            "backgroundImage":"' . $image3 . '",
            "linkUrl":"https://beta.gruenerator.de",
            "ariaLabel":"Mehr über unsere Spitzenkandidatin"
        } /-->
    </div>
    <!-- /wp:group -->
    '); ?>

</div>
<!-- /wp:group -->