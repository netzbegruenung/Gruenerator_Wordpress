import { useBlockProps, RichText } from '@wordpress/block-editor';

const deprecated = [
    {
        attributes: {
            title: {
                type: 'string',
                default: 'Wer ich bin',
            },
            content: {
                type: 'string',
                default: 'Füge hier deinen Inhalt ein...',
            },
        },
        save: ({ attributes }) => {
            const { title, content } = attributes;
            const blockProps = useBlockProps.save();

            return (
                <div {...blockProps}>
                    <div className="about-block-content">
                        <RichText.Content 
                            tagName="h2" 
                            className="about-block-title"
                            value={title} 
                        />
                        <RichText.Content 
                            tagName="div" 
                            className="about-block-text"
                            value={content} 
                        />
                    </div>
                </div>
            );
        },
    }
];

export default deprecated;
