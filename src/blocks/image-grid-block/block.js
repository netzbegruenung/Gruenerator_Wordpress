import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';
import save from './save';
import './style.scss';
import './editor.scss';

const name = 'gruenerator/image-grid-block';

const settings = {
    apiVersion: 2,
    title: __('Image Grid Block', 'gruenerator'),
    category: 'gruenerator-category',
    icon: 'grid-view',
    description: __('Add a grid of clickable images with text overlays', 'gruenerator'),
    supports: {
        html: false,
        align: ['wide', 'full']
    },
    attributes: {
        items: {
            type: 'array',
            default: [
                {
                    imageId: 0,
                    imageUrl: '',
                    text: '',
                    link: ''
                },
                {
                    imageId: 0,
                    imageUrl: '',
                    text: '',
                    link: ''
                },
                {
                    imageId: 0,
                    imageUrl: '',
                    text: '',
                    link: ''
                }
            ]
        }
    },
    edit,
    save
};

registerBlockType(name, settings);