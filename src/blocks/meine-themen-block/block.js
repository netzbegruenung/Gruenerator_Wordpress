import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';
import save from './save';
import './style.scss';
import './editor.scss'; // Fügen Sie diese Zeile hinzu

const name = 'gruenerator/meine-themen-block';
const settings = {
    
        "apiVersion": 2,
        "name": "gruenerator/meine-themen-block",
        "title": "Meine Themen Block",
        "category": "gruenerator-category",
        "icon": "groups",
        "description": "Add a section to display your main topics",
        "attributes": {
          "title": {
            "type": "string",
            "default": "Meine Themen"
          },
          "themes": {
            "type": "array",
            "default": [
              {
                "imageId": 0,
                "imageUrl": "https://via.placeholder.com/600x400",
                "title": "Mit Herz für Klimaschutz.",
                "content": "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua."
              },
              {
                "imageId": 0,
                "imageUrl": "https://via.placeholder.com/600x400",
                "title": "Grüne und günstige Mobilität.",
                "content": "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua."
              },
              {
                "imageId": 0,
                "imageUrl": "https://via.placeholder.com/600x400",
                "title": "Gemeinsam gegen Hass und Hetze.",
                "content": "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua."
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
