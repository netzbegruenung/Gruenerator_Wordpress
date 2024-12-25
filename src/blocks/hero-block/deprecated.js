import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const deprecated = [
    {
        attributes: {
            heroImage: {
                type: 'string',
                default: 'https://via.placeholder.com/600x400',
            },
            heroHeading: {
                type: 'string',
                source: 'html',
                selector: 'h2',
                default: __('Hi, ich bin Maxi Mustermensch', 'gruenerator'),
            },
            heroText: {
                type: 'string',
                source: 'html',
                selector: 'p',
                default: __('Kandidat*in für den Wahlkreis 54 Musterstadt-Musterort. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'gruenerator'),
            },
        },
        save: ({ attributes }) => {
            const { heroImage, heroHeading, heroText } = attributes;
            const blockProps = useBlockProps.save();

            return (
                <div {...blockProps}>
                    <div className="hero-block" style={{ width: '100%' }}>
                        <div className="hero-left">
                            {heroImage && (
                                <img src={heroImage} alt={__('Hero Image', 'gruenerator')} />
                            )}
                        </div>
                        <div className="hero-right">
                            <h2 className="hero-title">{heroHeading}</h2>
                            <p className="hero-description">{heroText}</p>
                            <div className="social-icons">
                                <a href="#" aria-label={__('Facebook', 'gruenerator')}><span className="dashicons dashicons-facebook"></span></a>
                                <a href="#" aria-label={__('Twitter', 'gruenerator')}><span className="dashicons dashicons-twitter"></span></a>
                                <a href="#" aria-label={__('YouTube', 'gruenerator')}><span className="dashicons dashicons-youtube"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            );
        },
    }
];

export default deprecated;