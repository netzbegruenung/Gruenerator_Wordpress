import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';
import save from './save';
import './style.scss';
import './editor.scss';

const DEFAULT_TITLE = __('Sag Hallo', 'gruenerator');

const name = 'gruenerator/contact-form-block';

const settings = {
    apiVersion: 2,
    title: __('Kontaktformular', 'gruenerator'),
    category: 'gruenerator-category',
    icon: 'email',
    description: __('Füge ein Kontaktformular mit Hintergrundbild, Titel und Social Media Icons hinzu', 'gruenerator'),
    attributes: {
        backgroundImageId: {
            type: 'number',
            default: 0
        },
        backgroundImageUrl: {
            type: 'string',
            default: 'https://via.placeholder.com/1200x600'
        },
        title: {
            type: 'string',
            default: DEFAULT_TITLE
        },
        email: {
            type: 'string',
            default: ''
        },
        socialMedia: {
            type: 'array',
            default: []
        },
        sunflowerFormAttributes: {
            type: 'object',
            default: {}
        }
    },
    supports: {
        html: false,
        align: true,
        alignWide: true,
        defaultAlign: 'full'
    },
    edit,
    save,
};

registerBlockType(name, settings);
