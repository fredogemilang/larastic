import tinymce from 'tinymce/tinymce';
import 'tinymce/themes/silver/theme';
import 'tinymce/models/dom/model';
import 'tinymce/icons/default/icons';

// Plugins
import 'tinymce/plugins/table';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/code';
import 'tinymce/plugins/codesample';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/wordcount';
import 'tinymce/plugins/fullscreen';

// Alpine.js data component
const tinymceEditorComponent = (initialContent) => ({
    _debounceTimer: null,
    _mediaListener: null,

    init() {
        const el = this.$refs.editorElement;
        const wire = this.$wire;
        const editorMediaFieldId = 'tinymce_' + (wire.__instance?.id || Math.random().toString(36).substring(7));

        let lastInsertTime = 0;
        // Add media listener to insert image when selected
        this._mediaListener = (event) => {
            const data = event.detail && event.detail[0] ? event.detail[0] : event.detail;
            
            if (data && data.field === editorMediaFieldId && data.url) {
                console.log('[TinyMCE Media Picker] Event Received:', data);
                
                const now = Date.now();
                if (now - lastInsertTime < 500) {
                    console.warn('[TinyMCE Media Picker] Throttled duplicate event within 500ms', data.url);
                    return; // Prevent double trigger
                }
                lastInsertTime = now;
                
                console.log('[TinyMCE Media Picker] Inserting image...', data.url);
                tinymce.activeEditor.focus();
                // Standard blog post image format with max-width to ensure responsiveness
                tinymce.activeEditor.insertContent(`<img src="${data.url}" alt="" style="max-width: 100%; height: auto; border-radius: 0.5rem;" />`);
            }
        };
        window.addEventListener('media-selected', this._mediaListener);

        tinymce.init({
            target: el,
            license_key: 'gpl',
            base_url: '/build/tinymce',
            suffix: '.min',
            skin: 'oxide-dark',
            content_css: 'dark',
            promotion: false,
            branding: false,
            height: 500,
            menubar: 'edit insert format table',
            menu: {
                format: {
                    title: 'Format',
                    items: 'bold italic underline strikethrough superscript subscript code | blocks | removeformat',
                },
            },
            plugins: 'table link image lists code codesample autolink searchreplace wordcount fullscreen',
            toolbar: [
                'undo redo | blocks | bold italic underline strikethrough',
                'bullist numlist | blockquote codesample | link custom_image table | code fullscreen',
            ],
            block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Preformatted=pre',
            // Table settings
            table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
            table_appearance_options: true,
            table_advtab: true,
            table_default_styles: {
                'border-collapse': 'collapse',
                'width': '100%',
            },
            table_default_attributes: {
                border: '1',
            },
            // Link settings
            link_default_target: '_blank',
            link_assume_external_targets: 'https',
            // Paste settings — keep HTML structure, strip all inline styles & classes
            paste_postprocess: (editor, args) => {
                args.node.querySelectorAll('[style]').forEach(el => el.removeAttribute('style'));
                args.node.querySelectorAll('[class]').forEach(el => el.removeAttribute('class'));
                args.node.querySelectorAll('[id]').forEach(el => el.removeAttribute('id'));
            },
            // Global safeguard — no inline styles allowed anywhere
            valid_styles: '',
            // Image settings
            image_advtab: true,
            relative_urls: false,
            remove_script_host: true,
            convert_urls: true,
            // Content styling inside the editor iframe
            content_style: `
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
                    font-size: 15px;
                    line-height: 1.7;
                    color: #e2e8f0;
                    background: rgba(15,23,42,0.85);
                    padding: 0.5rem;
                    max-width: 100%;
                }
                h2 { font-size: 1.375rem; font-weight: 700; color: #f1f5f9; margin: 1.5rem 0 0.75rem; }
                h3 { font-size: 1.125rem; font-weight: 600; color: #f1f5f9; margin: 1.25rem 0 0.5rem; }
                h4 { font-size: 1rem; font-weight: 600; color: #e2e8f0; margin: 1rem 0 0.5rem; }
                p { margin: 0.5rem 0; }
                a { color: #6366f1; }
                blockquote { border-left: 3px solid #6366f1; padding-left: 1rem; margin: 1rem 0; color: #94a3b8; font-style: italic; }
                pre { background: #0f172a; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; font-family: monospace; font-size: 0.875rem; margin: 1rem 0; }
                code { background: rgba(99,102,241,0.15); padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-family: monospace; font-size: 0.875rem; }
                img { max-width: 100%; border-radius: 0.5rem; }
                table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
                th, td { border: 1px solid rgba(148,163,184,0.3); padding: 0.625rem 0.875rem; }
                th { font-weight: 600; background: rgba(99,102,241,0.15); color: #f1f5f9; text-align: left; }
                td { color: #cbd5e1; }
                tr:nth-child(even) { background: rgba(15,23,42,0.3); }
            `,
            setup: (editor) => {
                editor.ui.registry.addButton('custom_image', {
                    icon: 'image',
                    tooltip: 'Insert Image from Media Library',
                    onAction: () => {
                        this.$dispatch('open-media-picker', { targetField: editorMediaFieldId });
                    }
                });

                editor.on('init', () => {
                    if (initialContent) {
                        editor.setContent(initialContent);
                        editor.undoManager.clear();
                    }
                });

                editor.on('Change KeyUp', () => {
                    clearTimeout(this._debounceTimer);
                    this._debounceTimer = setTimeout(() => {
                        wire.set('content', editor.getContent());
                    }, 400);
                });
            },
        });
    },

    destroy() {
        clearTimeout(this._debounceTimer);
        window.removeEventListener('media-selected', this._mediaListener);
        tinymce.remove(this.$refs.editorElement);
    },
});

if (window.Alpine) {
    window.Alpine.data('tinymceEditor', tinymceEditorComponent);
} else {
    document.addEventListener('alpine:init', () => {
        window.Alpine.data('tinymceEditor', tinymceEditorComponent);
    });
}

window.tinymceEditor = tinymceEditorComponent;
