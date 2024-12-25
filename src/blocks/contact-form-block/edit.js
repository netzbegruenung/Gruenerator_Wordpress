import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InnerBlocks, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { Button, TextControl, Panel, PanelBody } from '@wordpress/components';
import { useEffect } from '@wordpress/element';

const ALLOWED_BLOCKS = ['sunflower/contact-form'];
const TEMPLATE = [['sunflower/contact-form']];

const Edit = ({ attributes, setAttributes }) => {
    const { backgroundImageId, backgroundImageUrl, title, email, socialMedia } = attributes;
    const blockProps = useBlockProps();

    useEffect(() => {
        if (!backgroundImageUrl) {
            setAttributes({ backgroundImageUrl: 'https://via.placeholder.com/1200x600' });
        }
        if (!title) {
            setAttributes({ title: __('Sag Hallo', 'gruenerator') });
        }
    }, []);

    const onSelectImage = (media) => {
        setAttributes({
            backgroundImageId: media.id,
            backgroundImageUrl: media.url,
        });
    };

    const updateSocialMedia = (index, field, value) => {
        const newSocialMedia = [...(socialMedia || [])];
        if (!newSocialMedia[index]) {
            newSocialMedia[index] = {};
        }
        newSocialMedia[index][field] = value;
        setAttributes({ socialMedia: newSocialMedia });
    };

    const addSocialMediaProfile = () => {
        const newSocialMedia = [...(socialMedia || []), { platform: '', url: '', icon: '' }];
        setAttributes({ socialMedia: newSocialMedia });
    };

    const removeSocialMediaProfile = (index) => {
        const newSocialMedia = socialMedia.filter((_, i) => i !== index);
        setAttributes({ socialMedia: newSocialMedia });
    };

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Hintergrundbild', 'gruenerator')} initialOpen={true}>
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
                                            alt={__('Hintergrundbild', 'gruenerator')}
                                            style={{ 
                                                width: '100%', 
                                                height: '150px',
                                                objectFit: 'cover',
                                                marginBottom: '0.5rem',
                                                borderRadius: '4px'
                                            }}
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

                <PanelBody title={__('Kontakt Einstellungen', 'gruenerator')} initialOpen={true}>
                    <TextControl
                        label={__('E-Mail Adresse', 'gruenerator')}
                        value={email || ''}
                        onChange={(value) => setAttributes({ email: value })}
                        type="email"
                        help={__('Die E-Mail-Adresse für Kontaktanfragen', 'gruenerator')}
                    />
                </PanelBody>

                <PanelBody title={__('Social Media Profile', 'gruenerator')} initialOpen={false}>
                    {socialMedia && socialMedia.map((profile, index) => (
                        <div key={index} style={{ 
                            marginBottom: '1.5rem',
                            padding: '1rem',
                            backgroundColor: '#f0f0f0',
                            borderRadius: '4px'
                        }}>
                            <h4 style={{ marginTop: 0 }}>{__('Profil', 'gruenerator')} {index + 1}</h4>
                            <TextControl
                                label={__('Plattform', 'gruenerator')}
                                value={profile.platform || ''}
                                onChange={(value) => updateSocialMedia(index, 'platform', value)}
                                help={__('Name der Social Media Plattform', 'gruenerator')}
                            />
                            <TextControl
                                label={__('URL', 'gruenerator')}
                                value={profile.url || ''}
                                onChange={(value) => updateSocialMedia(index, 'url', value)}
                                help={__('Link zum Social Media Profil', 'gruenerator')}
                            />
                            <TextControl
                                label={__('Icon-Klasse', 'gruenerator')}
                                value={profile.icon || ''}
                                onChange={(value) => updateSocialMedia(index, 'icon', value)}
                                help={__('CSS-Klasse für das Icon (z.B. "facebook")', 'gruenerator')}
                            />
                            <Button
                                isDestructive
                                onClick={() => removeSocialMediaProfile(index)}
                                style={{ marginTop: '0.5rem' }}
                            >
                                {__('Profil entfernen', 'gruenerator')}
                            </Button>
                        </div>
                    ))}
                    <Button
                        variant="primary"
                        onClick={addSocialMediaProfile}
                        style={{ width: '100%' }}
                    >
                        {__('Social Media Profil hinzufügen', 'gruenerator')}
                    </Button>
                </PanelBody>
            </InspectorControls>

            <div className="contact-form-block" style={{ backgroundImage: `url(${backgroundImageUrl})` }}>
                <div className="contact-form-content">
                    <RichText
                        tagName="h2"
                        className="contact-form-title"
                        value={title}
                        onChange={(newTitle) => setAttributes({ title: newTitle })}
                        placeholder={__('Sag Hallo', 'gruenerator')}
                    />
                    <div className="contact-form-main">
                        <div className="contact-form-left">
                            <h3 className="contact-links-title">{__('Kontaktmöglichkeiten', 'gruenerator')}</h3>
                            {email && (
                                <div className="contact-info">
                                    <div className="contact-item">
                                        <i className="fas fa-envelope"></i>
                                        <a href={`mailto:${email}`}>{email}</a>
                                    </div>
                                </div>
                            )}
                            {socialMedia && socialMedia.length > 0 && (
                                <div className="social-icons">
                                    {socialMedia.map((profile, index) => (
                                        profile.url && profile.icon && (
                                            <a key={index} href={profile.url} target="_blank" rel="noopener noreferrer">
                                                <i className={`fab fa-${profile.icon}`}></i>
                                            </a>
                                        )
                                    ))}
                                </div>
                            )}
                        </div>
                        <div className="contact-form-right">
                            <InnerBlocks
                                allowedBlocks={ALLOWED_BLOCKS}
                                template={TEMPLATE}
                                templateLock="all"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Edit;