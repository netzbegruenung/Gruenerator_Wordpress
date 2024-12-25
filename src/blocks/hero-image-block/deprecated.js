import { useBlockProps, RichText } from '@wordpress/block-editor';

const deprecated = [
    {
        attributes: {
            backgroundImage: {
                type: 'string',
                default: '',
            },
            title: {
                type: 'string',
                default: 'Gemeinsam in eine gute Zukunft!',
            },
            subtitle: {
                type: 'string',
                default: 'Füge hier deinen Claim ein... Die Herausforderungen sind groß, aber gemeinsam mit dir können wir sie stemmen.',
            },
        },
        save: ({ attributes }) => {
            const { backgroundImage, title, subtitle } = attributes;
            const blockProps = useBlockProps.save();

            return (
                <div {...blockProps}>
                    <div className="hero-image-block" style={{ backgroundImage: backgroundImage ? `url(${backgroundImage})` : 'none' }}>
                        <div className="hero-content">
                            <RichText.Content tagName="h2" value={title} />
                            <RichText.Content tagName="p" value={subtitle} />
                        </div>
                    </div>
                </div>
            );
        },
    }
];

export default deprecated;