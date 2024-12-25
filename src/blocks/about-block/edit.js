import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { useEffect } from '@wordpress/element';

const Edit = ({ attributes, setAttributes }) => {
    const { title, content } = attributes;
    const blockProps = useBlockProps();

    // Setze Standardwerte, falls leer
    useEffect(() => {
        if (!title) {
            setAttributes({ title: __('Wer ich bin', 'gruenerator') });
        }
        if (!content) {
            setAttributes({ 
                content: __('Verwurzelt im Herzen von Musterstadt, mit einem festen Blick in die Zukunft: Dies beschreibt den Kern meiner Kandidatur für das Musterparlament. Als Musterberuf und langjährig engagierte Person in Musterorganisation habe ich stets die Bedeutung von Gemeinschaft, nachhaltiger Entwicklung und solidarischem Handeln aus nächster Nähe miterlebt. Mit einem offenen Ohr für alle Bürger*innen, einer lösungsorientierten Herangehensweise und dem festen Glauben an unsere gemeinsame Zukunft. Es geht darum, heute die Entscheidungen zu treffen, die morgen den Unterschied machen können. Für eine Politik, die auf Ausgleich und Nachhaltigkeit setzt, und für ein Musterstadt, in dem jede Stimme zählt.', 'gruenerator') 
            });
        }
    }, [title, content]);

    return (
        <div {...blockProps}>
            <div className="about-block-content">
                <RichText
                    tagName="h2"
                    className="about-block-title"
                    value={title}
                    onChange={(newTitle) => setAttributes({ title: newTitle })}
                    placeholder={__('Wer ich bin', 'gruenerator')}
                />
                <div className="about-block-text">
                    <RichText
                        tagName="p"
                        value={content}
                        onChange={(newContent) => setAttributes({ content: newContent })}
                        placeholder={__('Füge hier deinen Text ein...', 'gruenerator')}
                    />
                </div>
            </div>
        </div>
    );
};

export default Edit;
