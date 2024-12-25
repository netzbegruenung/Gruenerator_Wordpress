import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { Button, PanelBody, TextControl } from '@wordpress/components';

const Edit = ({ attributes, setAttributes }) => {
    const { title, themes = [] } = attributes;
    const blockProps = useBlockProps();

    const onSelectImage = (media, index) => {
        const newThemes = [...themes];
        newThemes[index].imageId = media.id;
        newThemes[index].imageUrl = media.url;
        setAttributes({ themes: newThemes });
    };

    const updateTheme = (index, property, value) => {
        const newThemes = [...themes];
        newThemes[index] = { ...newThemes[index], [property]: value };
        setAttributes({ themes: newThemes });
    };

    if (!themes.length) {
        return (
            <div {...blockProps}>
                <p>{__('Keine Themen verfügbar', 'gruenerator')}</p>
            </div>
        );
    }

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Themen Einstellungen', 'gruenerator')} initialOpen={true}>
                    {themes.map((theme, index) => (
                        <div key={index} style={{ marginBottom: '2rem', padding: '1rem', backgroundColor: '#f0f0f0', borderRadius: '4px' }}>
                            <h3 style={{ marginTop: 0 }}>{__('Thema', 'gruenerator')} {index + 1}</h3>
                            
                            <div style={{ marginBottom: '1rem' }}>
                                <MediaUploadCheck>
                                    <MediaUpload
                                        onSelect={(media) => onSelectImage(media, index)}
                                        allowedTypes={['image']}
                                        value={theme.imageId}
                                        render={({ open }) => (
                                            <div>
                                                {theme.imageUrl && (
                                                    <img 
                                                        src={theme.imageUrl}
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
                                                    {theme.imageUrl ? __('Bild ändern', 'gruenerator') : __('Bild auswählen', 'gruenerator')}
                                                </Button>
                                            </div>
                                        )}
                                    />
                                </MediaUploadCheck>
                            </div>

                            <TextControl
                                label={__('Titel', 'gruenerator')}
                                value={theme.title || ''}
                                onChange={(value) => updateTheme(index, 'title', value)}
                                help={__('Der Haupttitel des Themas', 'gruenerator')}
                            />

                            <TextControl
                                label={__('Beschreibung', 'gruenerator')}
                                value={theme.content || ''}
                                onChange={(value) => updateTheme(index, 'content', value)}
                                help={__('Die Beschreibung des Themas', 'gruenerator')}
                            />
                        </div>
                    ))}
                </PanelBody>
            </InspectorControls>
            <div {...blockProps}>
                <RichText
                    tagName="h2"
                    className="meine-themen-title"
                    value={title}
                    onChange={(newTitle) => setAttributes({ title: newTitle })}
                    placeholder={__('Meine Themen', 'gruenerator')}
                />
                <div className="meine-themen-grid">
                    {themes.map((theme, index) => (
                        <div key={index} className="theme-card">
                            <img 
                                src={theme.imageUrl || 'https://via.placeholder.com/600x400'} 
                                alt={theme.title} 
                            />
                            <RichText
                                tagName="h2"
                                className="theme-title"
                                value={theme.title}
                                onChange={(newTitle) => updateTheme(index, 'title', newTitle)}
                                placeholder={__('Thema Titel', 'gruenerator')}
                            />
                            <RichText
                                tagName="p"
                                className="theme-content"
                                value={theme.content}
                                onChange={(newContent) => updateTheme(index, 'content', newContent)}
                                placeholder={__('Thema Beschreibung', 'gruenerator')}
                            />
                        </div>
                    ))}
                </div>
            </div>
        </>
    );
};

export default Edit;
