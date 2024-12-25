import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
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
            <MediaUploadCheck>
                <MediaUpload
                    onSelect={onSelectImage}
                    allowedTypes={['image']}
                    value={backgroundImageId}
                    render={({ open }) => (
                        <Button onClick={open}>
                            {backgroundImageUrl ? __('Change background image', 'gruenerator') : __('Choose background image', 'gruenerator')}
                        </Button>
                    )}
                />
            </MediaUploadCheck>
        </div>
    );
};

export default Edit;
