import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { Button, PanelBody } from '@wordpress/components';
import { useEffect } from '@wordpress/element';

const Edit = ({ attributes, setAttributes }) => {
    const { backgroundImageId, backgroundImageUrl, title, subtitle } = attributes;
    const blockProps = useBlockProps();

    // Setze Standardwerte, wenn Attribute leer sind
    useEffect(() => {
        if (!backgroundImageUrl) {
            setAttributes({ backgroundImageUrl: 'https://via.placeholder.com/1200x600' });
        }
        if (!title) {
            setAttributes({ title: __('Gemeinsam in eine gute Zukunft!', 'gruenerator') });
        }
        if (!subtitle) {
            setAttributes({ subtitle: __('Füge hier deinen Claim ein...', 'gruenerator') });
        }
    }, []);

    const onSelectImage = (media) => {
        setAttributes({
            backgroundImageId: media.id,
            backgroundImageUrl: media.url,
        });
    };

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Hintergrundbild Einstellungen', 'gruenerator')} initialOpen={true}>
                    <div style={{ marginBottom: '1rem' }}>
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={onSelectImage}
                                allowedTypes={['image']}
                                value={backgroundImageId}
                                render={({ open }) => (
                                    <div>
                                        <img 
                                            src={backgroundImageUrl || 'https://via.placeholder.com/1200x600'} 
                                            alt={__('Background Image', 'gruenerator')}
                                            style={{ width: '100%', marginBottom: '0.5rem' }}
                                        />
                                        <Button 
                                            onClick={open}
                                            variant="secondary"
                                            isSecondary
                                            style={{ width: '100%' }}
                                        >
                                            {backgroundImageUrl ? __('Hintergrundbild ändern', 'gruenerator') : __('Hintergrundbild auswählen', 'gruenerator')}
                                        </Button>
                                    </div>
                                )}
                            />
                        </MediaUploadCheck>
                    </div>
                </PanelBody>
            </InspectorControls>
            <div {...blockProps}>
                <div className="hero-image-block" style={{ backgroundImage: backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none' }}>
                    <div className="hero-content">
                        <RichText
                            tagName="h2"
                            value={title}
                            onChange={(newTitle) => setAttributes({ title: newTitle })}
                            placeholder={__('Gemeinsam in eine gute Zukunft!', 'gruenerator')}
                        />
                        <RichText
                            tagName="p"
                            value={subtitle}
                            onChange={(newSubtitle) => setAttributes({ subtitle: newSubtitle })}
                            placeholder={__('Füge hier deinen Claim ein...', 'gruenerator')}
                        />
                    </div>
                </div>
            </div>
        </>
    );
};

export default Edit;
