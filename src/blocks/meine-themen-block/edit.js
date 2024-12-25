import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

const Edit = ({ attributes, setAttributes }) => {
    const { title, themes = [] } = attributes; // Standardwert setzen
    const blockProps = useBlockProps();

    const onSelectImage = (media, index) => {
        const newThemes = [...themes];
        newThemes[index].imageId = media.id;
        newThemes[index].imageUrl = media.url;
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
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={(media) => onSelectImage(media, index)}
                                allowedTypes={['image']}
                                value={theme.imageId}
                                render={({ open }) => (
                                    <div className="theme-image-container">
                                        {theme.imageUrl ? (
                                            <>
                                                <img
                                                    src={theme.imageUrl}
                                                    alt={__('Theme Image', 'gruenerator')}
                                                    className="theme-image"
                                                />
                                                <Button onClick={open} isSecondary>
                                                    {__('Change Image', 'gruenerator')}
                                                </Button>
                                            </>
                                        ) : (
                                            <Button onClick={open} isPrimary>
                                                {__('Choose Image', 'gruenerator')}
                                            </Button>
                                        )}
                                    </div>
                                )}
                            />
                        </MediaUploadCheck>
                        <RichText
                            tagName="h2"
                            className="theme-title"
                            value={theme.title}
                            onChange={(newTitle) => {
                                const newThemes = [...themes];
                                newThemes[index].title = newTitle;
                                setAttributes({ themes: newThemes });
                            }}
                            placeholder={__('Theme Title', 'gruenerator')}
                        />
                        <RichText
                            tagName="p"
                            className="theme-content"
                            value={theme.content}
                            onChange={(newContent) => {
                                const newThemes = [...themes];
                                newThemes[index].content = newContent;
                                setAttributes({ themes: newThemes });
                            }}
                            placeholder={__('Theme Content', 'gruenerator')}
                        />
                    </div>
                ))}
            </div>
        </div>
    );
};

export default Edit;
