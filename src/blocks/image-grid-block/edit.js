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
                <PanelBody title={__('Bildgitter Einstellungen', 'gruenerator')}>
                    {items.map((item, index) => (
                        <div key={index} style={{ marginBottom: '2rem', padding: '1rem', backgroundColor: '#f0f0f0', borderRadius: '4px' }}>
                            <h3 style={{ marginTop: 0 }}>{__('Bild', 'gruenerator')} {index + 1}</h3>
                            
                            <div style={{ marginBottom: '1rem' }}>
                                <MediaUploadCheck>
                                    <MediaUpload
                                        onSelect={(media) => {
                                            updateItem(index, 'imageId', media.id);
                                            updateItem(index, 'imageUrl', media.url);
                                        }}
                                        allowedTypes={['image']}
                                        value={item.imageId}
                                        render={({ open }) => (
                                            <div>
                                                {item.imageUrl && (
                                                    <img 
                                                        src={item.imageUrl}
                                                        alt=""
                                                        style={{ 
                                                            width: '100%', 
                                                            height: '150px',
                                                            objectFit: 'cover',
                                                            marginBottom: '0.5rem',
                                                            borderRadius: '4px'
                                                        }}
                                                    />
                                                )}
                                                <Button 
                                                    onClick={open}
                                                    variant="secondary"
                                                    isSecondary
                                                    style={{ width: '100%' }}
                                                >
                                                    {item.imageUrl ? __('Bild ändern', 'gruenerator') : __('Bild auswählen', 'gruenerator')}
                                                </Button>
                                            </div>
                                        )}
                                    />
                                </MediaUploadCheck>
                            </div>

                            <TextControl
                                label={__('Link URL', 'gruenerator')}
                                value={item.link || ''}
                                onChange={(value) => updateItem(index, 'link', value)}
                                help={__('Füge hier die URL ein, zu der das Bild verlinken soll', 'gruenerator')}
                            />

                            <TextControl
                                label={__('Bildtext', 'gruenerator')}
                                value={item.text || ''}
                                onChange={(value) => updateItem(index, 'text', value)}
                                help={__('Der Text, der unter dem Bild angezeigt wird', 'gruenerator')}
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