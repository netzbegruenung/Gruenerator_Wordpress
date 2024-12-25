import { __ } from '@wordpress/i18n'; 
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import { useEffect } from '@wordpress/element';

const Edit = ({ attributes, setAttributes }) => {
    const blockProps = useBlockProps();
    const { heroImageId, heroImageUrl, heroHeading, heroText } = attributes;

    // Standardwerte setzen, wenn Attribute leer sind
    useEffect(() => {
        if (!heroImageUrl) {
            setAttributes({ heroImageUrl: 'https://via.placeholder.com/600x400' });
        }
        if (!heroHeading) {
            setAttributes({ heroHeading: __('Hi, ich bin Maxi Mustermensch', 'gruenerator') });
        }
        if (!heroText) {
            setAttributes({ heroText: __('Kandidat*in für den Wahlkreis 54 Musterstadt-Musterort.', 'gruenerator') });
        }
    }, []);

    const onSelectImage = (media) => {
        setAttributes({
            heroImageId: media.id,
            heroImageUrl: media.url,
        });
    };

    return (
        <div {...blockProps}>
            <div className="hero-block" style={{ width: '100%' }}>
                <div className="hero-left">
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={onSelectImage}
                            allowedTypes={['image']}
                            value={heroImageId}
                            render={({ open }) => (
                                <Button onClick={open}>
                                    <img 
                                        src={heroImageUrl || 'https://via.placeholder.com/600x400'} 
                                        alt={__('Hero Image', 'gruenerator')} 
                                    />
                                </Button>
                            )}
                        />
                    </MediaUploadCheck>
                </div>
                <div className="hero-right">
                    <RichText
                        tagName="h2"
                        value={heroHeading}
                        onChange={(value) => setAttributes({ heroHeading: value })}
                        placeholder={__('Hi, ich bin Maxi Mustermensch', 'gruenerator')}
                    />
                    <RichText
                        tagName="p"
                        value={heroText}
                        onChange={(value) => setAttributes({ heroText: value })}
                        placeholder={__('Kandidat*in für den Wahlkreis 54 Musterstadt-Musterort.', 'gruenerator')}
                    />
                    <div className="social-icons">
                        <Button icon="facebook" />
                        <Button icon="twitter" />
                        <Button icon="youtube" />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Edit;
