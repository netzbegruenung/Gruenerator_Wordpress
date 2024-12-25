import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import Edit from './edit';
import save from './save';
import './style.scss';
import './editor.scss';

const name = 'gruenerator/about-block';

const settings = {
  apiVersion: 2,
  name: 'gruenerator/about-block',
  title: __('About Block', 'gruenerator'),
  category: 'gruenerator-category',
  icon: 'admin-users',
  description: __('Ein Block zur Darstellung von Informationen über die Kandidatur.', 'gruenerator'),
  attributes: {
    title: {
      type: 'string',
      default: __('Wer ich bin', 'gruenerator'),
    },
    content: {
      type: 'string',
      default: __('Verwurzelt im Herzen von Musterstadt, mit einem festen Blick in die Zukunft: Dies beschreibt den Kern meiner Kandidatur für das Musterparlament. Als Musterberuf und langjährig engagierte Person in Musterorganisation habe ich stets die Bedeutung von Gemeinschaft, nachhaltiger Entwicklung und solidarischem Handeln aus nächster Nähe miterlebt. Mit einem offenen Ohr für alle Bürger*innen, einer lösungsorientierten Herangehensweise und dem festen Glauben an unsere gemeinsame Zukunft. Es geht darum, heute die Entscheidungen zu treffen, die morgen den Unterschied machen können. Für eine Politik, die auf Ausgleich und Nachhaltigkeit setzt, und für ein Musterstadt, in dem jede Stimme zählt.', 'gruenerator'),
    },
  },
  supports: {
    html: false,
    align: ['wide', 'full'],
  },
  editorScript: 'gruenerator-blocks',
  editorStyle: 'gruenerator-blocks-editor',
  style: 'gruenerator-blocks-frontend',
  edit: Edit,
  save: save,
};

registerBlockType(name, settings);
