import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';
import save from './save';
import deprecated from './deprecated';
import './editor.scss'; // Fügen Sie diese Zeile hinzu

import './style.scss';

const name = 'gruenerator/link-tile-block';

const settings = {
    apiVersion: 2,
    title: __('Link Tile Block', 'gruenerator'),
    icon: 'admin-links',
    category: 'gruenerator-category',
    description: __('Add a clickable tile with background image and title', 'gruenerator'),
    attributes: {
        title: {
            type: 'string',
            default: __('Unsere Spitzenkandidatin', 'gruenerator'),
        },
        backgroundImage: {
            type: 'string',
            default: '',
        },
        linkUrl: {
            type: 'string',
            default: 'https://beta.gruenerator.de',
        },
        ariaLabel: {
            type: 'string',
            default: '',
        },
    },
    supports: {
        html: false,
        align: ['wide', 'full'],
    },
    edit,
    save,
    deprecated,
};

registerBlockType(name, settings);

console.log('Link Tile Block registered');