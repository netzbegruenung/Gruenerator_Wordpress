import { useBlockProps, RichText } from '@wordpress/block-editor';

const ArrowIcon = () => (
    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M5 12H19" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
        <path d="M12 5L19 12L12 19" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    </svg>
);

const deprecated = [
    {
        attributes: {
            title: {
                type: 'string',
                default: 'Unsere Spitzenkandidatin',
            },
            backgroundImage: {
                type: 'string',
                default: '',
            },
            linkUrl: {
                type: 'string',
                default: 'https://beta.gruenerator.de',
            },
            ariaLabel: {
                type: 'string',
                default: '',
            },
        },
        save: ({ attributes }) => {
            const { title, backgroundImage, linkUrl, ariaLabel } = attributes;
            const blockProps = useBlockProps.save();

            return (
                <div {...blockProps}>
                    <div className="link-tile">
                        <div 
                            className="link-tile-background" 
                            style={{ backgroundImage: backgroundImage ? `url(${backgroundImage})` : 'none' }}
                        ></div>
                        <div className="link-tile-overlay"></div>
                        <div className="link-tile-content">
                            <RichText.Content tagName="h2" className="link-tile-title" value={title} />
                            <ArrowIcon />
                        </div>
                        <a href={linkUrl} target="_blank" rel="noopener noreferrer" className="link-tile-link" aria-label={ariaLabel}></a>
                    </div>
                </div>
            );
        },
    }
];

export default deprecated;