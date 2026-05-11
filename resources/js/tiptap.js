import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import { Table } from '@tiptap/extension-table';
import { TableRow } from '@tiptap/extension-table-row';
import { TableCell } from '@tiptap/extension-table-cell';
import { TableHeader } from '@tiptap/extension-table-header';

// Store editor instances OUTSIDE Alpine's reactive proxy system.
// ProseMirror's internal state breaks when wrapped in a JS Proxy because
// identity checks like `tr.before.eq(this.doc)` fail through the Proxy.
const editorInstances = new WeakMap();

// Register as Alpine.js data component
const tiptapEditorComponent = (initialContent) => ({
    // Reactive toolbar state — safe for Alpine to proxy
    active: {
        bold: false,
        italic: false,
        strike: false,
        h2: false,
        h3: false,
        h4: false,
        bulletList: false,
        orderedList: false,
        blockquote: false,
        codeBlock: false,
    },
    _debounceTimer: null,

    init() {
        const container = this.$el;

        // Don't re-initialize
        if (editorInstances.has(container)) return;

        const editor = new Editor({
            element: this.$refs.editorElement,
            extensions: [
                StarterKit.configure({
                    heading: { levels: [2, 3, 4] },
                    link: false,
                }),
                Image.configure({
                    HTMLAttributes: { loading: 'lazy' },
                }),
                Link.configure({
                    openOnClick: false,
                    HTMLAttributes: { rel: 'noopener noreferrer' },
                }),
                Table.configure({ resizable: false }),
                TableRow,
                TableCell,
                TableHeader,
            ],
            content: initialContent || '',
            editorProps: {
                attributes: {
                    class: 'ProseMirror',
                },
            },
            onUpdate: ({ editor }) => {
                // Debounce sync to Livewire
                clearTimeout(this._debounceTimer);
                this._debounceTimer = setTimeout(() => {
                    this.$wire.set('content', editor.getHTML());
                }, 300);
            },
            onTransaction: () => {
                // Update reactive toolbar state from the raw editor
                this._syncActive();
            },
        });

        // Store the raw editor in the WeakMap, NOT in a reactive property
        editorInstances.set(container, editor);

        // Initial toolbar sync
        this._syncActive();
    },

    _getEditor() {
        return editorInstances.get(this.$el) || null;
    },

    _syncActive() {
        const editor = this._getEditor();
        if (!editor || editor.isDestroyed) return;
        this.active = {
            bold: editor.isActive('bold'),
            italic: editor.isActive('italic'),
            strike: editor.isActive('strike'),
            h2: editor.isActive('heading', { level: 2 }),
            h3: editor.isActive('heading', { level: 3 }),
            h4: editor.isActive('heading', { level: 4 }),
            bulletList: editor.isActive('bulletList'),
            orderedList: editor.isActive('orderedList'),
            blockquote: editor.isActive('blockquote'),
            codeBlock: editor.isActive('codeBlock'),
        };
    },

    cmd(fn) {
        const editor = this._getEditor();
        if (!editor || editor.isDestroyed) return;
        fn(editor);
    },

    toggleBold()       { this.cmd(e => e.chain().focus().toggleBold().run()); },
    toggleItalic()     { this.cmd(e => e.chain().focus().toggleItalic().run()); },
    toggleStrike()     { this.cmd(e => e.chain().focus().toggleStrike().run()); },
    toggleH2()         { this.cmd(e => e.chain().focus().toggleHeading({ level: 2 }).run()); },
    toggleH3()         { this.cmd(e => e.chain().focus().toggleHeading({ level: 3 }).run()); },
    toggleH4()         { this.cmd(e => e.chain().focus().toggleHeading({ level: 4 }).run()); },
    toggleBulletList() { this.cmd(e => e.chain().focus().toggleBulletList().run()); },
    toggleOrderedList(){ this.cmd(e => e.chain().focus().toggleOrderedList().run()); },
    toggleBlockquote() { this.cmd(e => e.chain().focus().toggleBlockquote().run()); },
    toggleCodeBlock()  { this.cmd(e => e.chain().focus().toggleCodeBlock().run()); },
    undo()             { this.cmd(e => e.chain().focus().undo().run()); },
    redo()             { this.cmd(e => e.chain().focus().redo().run()); },

    insertLink() {
        const url = prompt('Enter URL:');
        if (url) {
            this.cmd(e => e.chain().focus().setLink({ href: url }).run());
        }
    },

    insertImage() {
        const url = prompt('Enter image URL:');
        if (url) {
            this.cmd(e => e.chain().focus().setImage({ src: url }).run());
        }
    },

    destroy() {
        clearTimeout(this._debounceTimer);
        const editor = this._getEditor();
        if (editor) {
            editor.destroy();
            editorInstances.delete(this.$el);
        }
    },
});

if (window.Alpine) {
    window.Alpine.data('tiptapEditor', tiptapEditorComponent);
} else {
    document.addEventListener('alpine:init', () => {
        window.Alpine.data('tiptapEditor', tiptapEditorComponent);
    });
}

window.tiptapEditor = tiptapEditorComponent;
