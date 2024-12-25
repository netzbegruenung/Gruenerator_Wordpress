import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const deprecated = [
    {
        attributes: {
            title: {
                type: 'string',
                default: __('Meine Themen', 'gruenerator'),
            },
            themes: {
                type: 'array',
                default: [
                    {
                        image: '',
                        title: __('Mit Herz für Klimaschutz.', 'gruenerator'),
                        content: __('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 'gruenerator'),
                    },
                    {
                        image: '',
                        title: __('Grüne und günstige Mobilität.', 'gruenerator'),
                        content: __('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 'gruenerator'),
                    },
                    {
                        image: '',
                        title: __('Gemeinsam gegen Hass und Hetze.', 'gruenerator'),
                        content: __('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 'gruenerator'),
                    },
                ],
            },
        },
        save: ({ attributes }) => {
            const { title, themes } = attributes;
            const blockProps = useBlockProps.save();

            return (
                <div {...blockProps}>
                    <RichText.Content
                        tagName="h2"
                        className="meine-themen-title"
                        value={title}
                    />
                    <div className="meine-themen-grid">
                        {themes.map((theme, index) => (
                            <div key={index} className="theme-card">
                                {theme.image && (
                                    <img src={theme.image} alt={__('Theme image', 'gruenerator')} className="theme-image" />
                                )}
                                <RichText.Content
                                    tagName="h3"
                                    className="theme-title"
                                    value={theme.title}
                                />
                                <RichText.Content
                                    tagName="p"
                                    className="theme-content"
                                    value={theme.content}
                                />
                            </div>
                        ))}
                    </div>
                </div>
            );
        },
    }
];

export default deprecated;