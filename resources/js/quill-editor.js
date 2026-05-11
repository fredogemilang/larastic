import Quill from 'quill';
import 'quill/dist/quill.snow.css';

// Enable Quill's built-in table module
const Table = Quill.import('formats/table');
const TableBody = Quill.import('formats/table-body');
const TableCell = Quill.import('formats/table-cell');
const TableContainer = Quill.import('formats/table-container');
const TableHeader = Quill.import('formats/table-header');
const TableRow = Quill.import('formats/table-row');

// Register table formats (some Quill builds need explicit registration)
if (Table) Quill.register(Table, true);
if (TableBody) Quill.register(TableBody, true);
if (TableCell) Quill.register(TableCell, true);
if (TableContainer) Quill.register(TableContainer, true);
if (TableHeader) Quill.register(TableHeader, true);
if (TableRow) Quill.register(TableRow, true);

// Register as Alpine.js data component
const quillEditorComponent = (initialContent) => ({
    _quill: null,
    _debounceTimer: null,

    init() {
        if (this._quill) return;

        const quill = new Quill(this.$refs.editorElement, {
            theme: 'snow',
            placeholder: 'Start writing your content...',
            modules: {
                toolbar: {
                    container: this.$refs.toolbar,
                    handlers: {
                        'table': function () {
                            const rows = prompt('Number of rows:', '3');
                            const cols = prompt('Number of columns:', '3');
                            if (rows && cols) {
                                const r = parseInt(rows, 10);
                                const c = parseInt(cols, 10);
                                if (r > 0 && c > 0) {
                                    const tableModule = this.quill.getModule('table');
                                    if (tableModule) {
                                        tableModule.insertTable(r, c);
                                    }
                                }
                            }
                        },
                    },
                },
                table: true,
            },
        });

        // Set initial HTML content via Quill's clipboard API
        if (initialContent) {
            quill.clipboard.dangerouslyPasteHTML(initialContent);
            quill.history.clear();
        }

        // Sync content to Livewire on change (debounced)
        quill.on('text-change', () => {
            clearTimeout(this._debounceTimer);
            this._debounceTimer = setTimeout(() => {
                const html = quill.root.innerHTML;
                this.$wire.set('content', html === '<p><br></p>' ? '' : html);
            }, 300);
        });

        // Store on the element — outside Alpine's reactive proxy
        this.$el._quill = quill;
        this._quill = quill;
    },

    insertLink() {
        const quill = this.$el._quill;
        if (!quill) return;
        const range = quill.getSelection();
        if (!range) return;
        const url = prompt('Enter URL:');
        if (url) {
            quill.format('link', url);
        }
    },

    insertImage() {
        const quill = this.$el._quill;
        if (!quill) return;
        const url = prompt('Enter image URL:');
        if (url) {
            const range = quill.getSelection(true);
            quill.insertEmbed(range.index, 'image', url);
        }
    },

    destroy() {
        clearTimeout(this._debounceTimer);
    },
});

if (window.Alpine) {
    window.Alpine.data('quillEditor', quillEditorComponent);
} else {
    document.addEventListener('alpine:init', () => {
        window.Alpine.data('quillEditor', quillEditorComponent);
    });
}

window.quillEditor = quillEditorComponent;
