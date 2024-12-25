import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import edit from './edit';
import save from './save';
import './style.scss';      // Frontend Styles
import './editor.scss';     // Editor Styles

const name = 'gruenerator/hero-block';

const settings = {
    apiVersion: 2,
    title: __('Hero Block', 'gruenerator'),
    icon: 'cover-image',
    category: 'gruenerator-category',
    description: __('Add a hero section to your page', 'gruenerator'),
    attributes: {
        heroImageId: {
            type: 'number',
            default: 0,
        },
        heroImageUrl: {
            type: 'string',
            default: 'https://via.placeholder.com/600x400',
        },
        heroHeading: {
            type: 'string',
            default: __('Hi, ich bin Maxi Mustermensch', 'gruenerator'),
        },
        heroText: {
            type: 'string',
            default: __('Kandidat*in für den Wahlkreis 54 Musterstadt-Musterort.', 'gruenerator'),
        },
    },
    
    supports: {
        html: false,
        align: ['wide', 'full'],
    },
    edit,
    save,
};


registerBlockType(name, settings);
