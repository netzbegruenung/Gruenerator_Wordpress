import React, { useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { Button, TextControl, PanelBody } from '@wordpress/components';

const ArrowIcon = () => (
    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M5 12H19" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
        <path d="M12 5L19 12L12 19" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    </svg>
);

const Edit = ({ attributes, setAttributes }) => {
    const { title, backgroundImageId, backgroundImageUrl, linkUrl, ariaLabel } = attributes;
    const blockProps = useBlockProps();

    // Setze Standardwerte, wenn die Attribute leer sind
    useEffect(() => {
        if (!backgroundImageUrl) {
            setAttributes({ backgroundImageUrl: 'https://via.placeholder.com/600x400' });
        }
        if (!title) {
            setAttributes({ title: __('Unsere Spitzenkandidatin', 'gruenerator') });
        }
        if (!linkUrl) {
            setAttributes({ linkUrl: '' });
        }
        if (!ariaLabel) {
            setAttributes({ ariaLabel: '' });
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
                <PanelBody title={__('Link Settings', 'gruenerator')}>
                    <TextControl
                        label={__('Link URL', 'gruenerator')}
                        value={linkUrl}
                        onChange={(value) => setAttributes({ linkUrl: value })}
                    />
                    <TextControl
                        label={__('Aria Label', 'gruenerator')}
                        value={ariaLabel}
                        onChange={(value) => setAttributes({ ariaLabel: value })}
                        help={__('Provide a description for screen readers', 'gruenerator')}
                    />
                </PanelBody>
            </InspectorControls>
            <div {...blockProps}>
                <div className="link-tile">
                    <div 
                        className="link-tile-background" 
                        style={{ backgroundImage: backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none' }}
                    ></div>
                    <div className="link-tile-overlay"></div>
                    <div className="link-tile-content">
                        <RichText
                            tagName="h2"
                            className="link-tile-title"
                            value={title}
                            onChange={(newTitle) => setAttributes({ title: newTitle })}
                            placeholder={__('Unsere Spitzenkandidatin', 'gruenerator')}
                        />
                        <ArrowIcon />
                    </div>
                </div>
                <MediaUploadCheck>
                    <MediaUpload
                        onSelect={onSelectImage}
                        allowedTypes={['image']}
                        value={backgroundImageId}
                        render={({ open }) => (
                            <Button onClick={open} isSecondary>
                                {backgroundImageUrl ? __('Change Background Image', 'gruenerator') : __('Choose Background Image', 'gruenerator')}
                            </Button>
                        )}
                    />
                </MediaUploadCheck>
            </div>
        </>
    );
};

export default Edit;
