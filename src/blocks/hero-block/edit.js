import { __ } from '@wordpress/i18n'; 
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { Button, PanelBody, TextControl } from '@wordpress/components';
import { useEffect } from '@wordpress/element';

const Edit = ({ attributes, setAttributes }) => {
    const blockProps = useBlockProps();
    const { heroImageId, heroImageUrl, heroHeading, heroText, socialLinks } = attributes;

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
        if (!socialLinks) {
            setAttributes({
                socialLinks: {
                    facebook: '',
                    twitter: '',
                    youtube: ''
                }
            });
        }
    }, []);

    const onSelectImage = (media) => {
        setAttributes({
            heroImageId: media.id,
            heroImageUrl: media.url,
        });
    };

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Bild Einstellungen', 'gruenerator')} initialOpen={true}>
                    <div style={{ marginBottom: '1rem' }}>
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={onSelectImage}
                                allowedTypes={['image']}
                                value={heroImageId}
                                render={({ open }) => (
                                    <div>
                                        <img 
                                            src={heroImageUrl || 'https://via.placeholder.com/600x400'} 
                                            alt={__('Hero Image', 'gruenerator')}
                                            style={{ width: '100%', marginBottom: '0.5rem' }}
                                        />
                                        <Button 
                                            onClick={open}
                                            variant="secondary"
                                            isSecondary
                                            style={{ width: '100%' }}
                                        >
                                            {heroImageUrl ? __('Bild ändern', 'gruenerator') : __('Bild auswählen', 'gruenerator')}
                                        </Button>
                                    </div>
                                )}
                            />
                        </MediaUploadCheck>
                    </div>
                </PanelBody>
                <PanelBody title={__('Social Media Links', 'gruenerator')}>
                    <TextControl
                        label={__('Facebook URL', 'gruenerator')}
                        value={socialLinks?.facebook || ''}
                        onChange={(value) => setAttributes({ 
                            socialLinks: { ...socialLinks, facebook: value }
                        })}
                    />
                    <TextControl
                        label={__('Twitter URL', 'gruenerator')}
                        value={socialLinks?.twitter || ''}
                        onChange={(value) => setAttributes({ 
                            socialLinks: { ...socialLinks, twitter: value }
                        })}
                    />
                    <TextControl
                        label={__('YouTube URL', 'gruenerator')}
                        value={socialLinks?.youtube || ''}
                        onChange={(value) => setAttributes({ 
                            socialLinks: { ...socialLinks, youtube: value }
                        })}
                    />
                </PanelBody>
            </InspectorControls>
            <div {...blockProps}>
                <div className="hero-block" style={{ width: '100%' }}>
                    <div className="hero-left">
                        <img 
                            src={heroImageUrl || 'https://via.placeholder.com/600x400'} 
                            alt={__('Hero Image', 'gruenerator')} 
                        />
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
                            {socialLinks?.facebook && <Button icon="facebook" href={socialLinks.facebook} target="_blank" />}
                            {socialLinks?.twitter && <Button icon="twitter" href={socialLinks.twitter} target="_blank" />}
                            {socialLinks?.youtube && <Button icon="youtube" href={socialLinks.youtube} target="_blank" />}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default Edit;
