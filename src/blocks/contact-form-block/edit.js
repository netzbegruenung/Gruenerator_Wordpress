import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InnerBlocks, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
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
            <div className="contact-form-block" style={{ backgroundImage: `url(${backgroundImageUrl})` }}>
                <div className="contact-form-content">
                    <div className="contact-form-left">
                        <RichText
                            tagName="h2"
                            className="contact-form-title"
                            value={title}
                            onChange={(newTitle) => setAttributes({ title: newTitle })}
                            placeholder={__('Sag Hallo', 'gruenerator')}
                        />
                        <TextControl
                            label={__('E-Mail Adresse', 'gruenerator')}
                            value={email}
                            onChange={(value) => setAttributes({ email: value })}
                            type="email"
                        />
                        <Panel>
                            <PanelBody title={__('Social Media Profile', 'gruenerator')} initialOpen={false}>
                                {socialMedia && socialMedia.map((profile, index) => (
                                    <div key={index} className="social-media-profile">
                                        <TextControl
                                            label={__('Plattform', 'gruenerator')}
                                            value={profile.platform}
                                            onChange={(value) => updateSocialMedia(index, 'platform', value)}
                                        />
                                        <TextControl
                                            label={__('URL', 'gruenerator')}
                                            value={profile.url}
                                            onChange={(value) => updateSocialMedia(index, 'url', value)}
                                        />
                                        <TextControl
                                            label={__('Icon-Klasse', 'gruenerator')}
                                            value={profile.icon}
                                            onChange={(value) => updateSocialMedia(index, 'icon', value)}
                                        />
                                        <Button
                                            isDestructive
                                            onClick={() => removeSocialMediaProfile(index)}
                                        >
                                            {__('Entfernen', 'gruenerator')}
                                        </Button>
                                    </div>
                                ))}
                                <Button
                                    isPrimary
                                    onClick={addSocialMediaProfile}
                                >
                                    {__('Social Media Profil hinzufügen', 'gruenerator')}
                                </Button>
                            </PanelBody>
                        </Panel>
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
            <MediaUploadCheck>
                <MediaUpload
                    onSelect={onSelectImage}
                    allowedTypes={['image']}
                    value={backgroundImageId}
                    render={({ open }) => (
                        <Button 
                            onClick={open}
                            variant="secondary"
                        >
                            {backgroundImageUrl ? __('Hintergrundbild ändern', 'gruenerator') : __('Hintergrundbild wählen', 'gruenerator')}
                        </Button>
                    )}
                />
            </MediaUploadCheck>
        </div>
    );
};

export default Edit;