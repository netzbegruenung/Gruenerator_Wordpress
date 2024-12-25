import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';
import save from './save';
import './style.scss';
import './editor.scss';

const name = 'gruenerator/meine-themen-block';
const settings = {
    apiVersion: 2,
    name: "gruenerator/meine-themen-block",
    title: __("Meine Themen", 'gruenerator'),
    category: "gruenerator-category",
    icon: "groups",
    description: __("Füge einen Bereich hinzu, um deine wichtigsten Themen anzuzeigen", 'gruenerator'),
    attributes: {
        title: {
            type: "string",
            default: "Meine Themen"
        },
        themes: {
            type: "array",
            default: [
                {
                    imageId: 0,
                    imageUrl: "https://via.placeholder.com/600x400",
                    title: "Mit Herz für Klimaschutz.",
                    content: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.",
                    link: "",
                    buttonText: "Mehr erfahren"
                },
                {
                    imageId: 0,
                    imageUrl: "https://via.placeholder.com/600x400",
                    title: "Grüne und günstige Mobilität.",
                    content: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.",
                    link: "",
                    buttonText: "Mehr erfahren"
                },
                {
                    imageId: 0,
                    imageUrl: "https://via.placeholder.com/600x400",
                    title: "Gemeinsam gegen Hass und Hetze.",
                    content: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.",
                    link: "",
                    buttonText: "Mehr erfahren"
                }
            ]
        }
    },
    supports: {
        html: false,
        align: ['wide', 'full'],
    },
    edit,
    save,
};

registerBlockType(name, settings);
