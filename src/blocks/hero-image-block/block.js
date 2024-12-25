import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';
import save from './save';
import './style.scss';
import './editor.scss';

const name = 'gruenerator/hero-image-block';

const settings = {
    apiVersion: 2,
    name: 'gruenerator/hero-image-block',
    title: __('Hero Image Block', 'gruenerator'),
    category: 'gruenerator-category',
    icon: 'format-image',
    description: __('Add a hero image section with title and subtitle', 'gruenerator'),
    attributes: {
        backgroundImageId: {
            type: 'integer',
            default: 0
        },
        backgroundImageUrl: {
            type: 'string',
            default: 'https://via.placeholder.com/1200x600'
        },
        title: {
            type: 'string',
            default: __('Gemeinsam in eine gute Zukunft!', 'gruenerator')
        },
        subtitle: {
            type: 'string',
            default: __('Füge hier deinen Claim ein... Die Herausforderungen sind groß, aber gemeinsam mit dir können wir sie stemmen.', 'gruenerator')
        },
        items: {
            type: 'array',
            default: [
                { imageId: 0, imageUrl: '', text: 'Spenden für Grün', link: '' },
                { imageId: 0, imageUrl: '', text: 'Werde Mitglied', link: '' },
                { imageId: 0, imageUrl: '', text: 'Haustürwahl-kampf', link: '' }
            ]
        }
    },
    supports: {
        html: false,
        align: true,
        alignWide: true,
        defaultAlign: 'full'
    },
    edit,
    save
};

registerBlockType(name, settings);