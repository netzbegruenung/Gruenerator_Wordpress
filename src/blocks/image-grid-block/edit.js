import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';

const Edit = ({ attributes, setAttributes }) => {
    const { items } = attributes;
    const blockProps = useBlockProps();

    const updateItem = (index, property, value) => {
        const newItems = [...items];
        newItems[index] = { ...newItems[index], [property]: value };
        setAttributes({ items: newItems });
    };

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Image Grid Settings', 'gruenerator')}>
                    {items.map((item, index) => (
                        <div key={index}>
                            <h3>{__('Item', 'gruenerator')} {index + 1}</h3>
                            <MediaUploadCheck>
                                <MediaUpload
                                    onSelect={(media) => {
                                        updateItem(index, 'imageId', media.id);
                                        updateItem(index, 'imageUrl', media.url);
                                    }}
                                    allowedTypes={['image']}
                                    value={item.imageId}
                                    render={({ open }) => (
                                        <Button onClick={open} isSecondary>
                                            {item.imageUrl ? __('Change Image', 'gruenerator') : __('Choose Image', 'gruenerator')}
                                        </Button>
                                    )}
                                />
                            </MediaUploadCheck>
                            <TextControl
                                label={__('Link', 'gruenerator')}
                                value={item.link}
                                onChange={(value) => updateItem(index, 'link', value)}
                            />
                        </div>
                    ))}
                </PanelBody>
            </InspectorControls>
            <div className="image-grid">
                {items.map((item, index) => (
                    <div key={index} className="grid-item">
                        <div className="image-container">
                            {item.imageUrl ? (
                                <img 
                                    src={item.imageUrl} 
                                    alt="" 
                                    style={{ 
                                        objectFit: 'cover', 
                                        width: '100%', 
                                        height: '100%',
                                        objectPosition: 'center center'
                                    }} 
                                />
                            ) : (
                                <div className="image-placeholder">
                                    <span className="dashicons dashicons-format-image"></span>
                                    <p>{__('Bild wählen', 'gruenerator')}</p>
                                </div>
                            )}
                        </div>
                        <RichText
                            tagName="h2"
                            value={item.text}
                            onChange={(value) => updateItem(index, 'text', value)}
                            placeholder={__('Text eingeben...', 'gruenerator')}
                        />
                    </div>
                ))}
            </div>
        </div>
    );
};

export default Edit;