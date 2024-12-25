import { __ } from '@wordpress/i18n';
import domReady from '@wordpress/dom-ready';

// CSS-Import hinzufügen
import './index.scss'; // oder './style.css', wenn Sie CSS statt SCSS verwenden

// Import aller Block-Dateien
import './blocks/hero-block/block';
import './blocks/about-block/block';
import './blocks/hero-image-block/block';
import './blocks/meine-themen-block/block';
import './blocks/link-kacheln/block';
import './blocks/contact-form-block/block';
// Image Grid Block importieren
import './blocks/image-grid-block/block';

domReady(() => {
    console.log('DOM ist bereit, Blöcke initialisiert');
    console.log('Gruenerator plugin script wurde geladen');
});