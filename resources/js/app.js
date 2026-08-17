import './bootstrap';

import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import 'flatpickr/dist/themes/dark.css';
import './editor.css';

window.Quill = Quill;
window.flatpickr = flatpickr;

Alpine.data('richEditor', (initialContent = '', target = 'content') => ({
    words: 0,

    init() {
        this.quill = new Quill(this.$refs.editor, {
            theme: 'snow',
            placeholder: 'Type agreement content here...',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ align: [] }],
                    ['blockquote', 'code-block'],
                    ['link'],
                    ['clean'],
                ],
            },
        });

        if (initialContent) {
            this.quill.clipboard.dangerouslyPasteHTML(initialContent);
        }

        this.updateWords();
        this.quill.on('text-change', () => {
            this.$wire.set(target, this.quill.root.innerHTML, false);
            this.updateWords();
        });
    },

    updateWords() {
        const text = this.quill?.getText().trim() || '';
        this.words = text ? text.split(/\s+/).length : 0;
    },
}));

Alpine.data('validityDatePicker', (initialDate = '', target = 'validity_date') => ({
    init() {
        flatpickr(this.$refs.input, {
            dateFormat: 'Y-m-d',
            minDate: 'today',
            defaultDate: initialDate || null,
            allowInput: true,
            onChange: (selectedDates, dateStr) => {
                this.$wire.set(target, dateStr || null, false);
            },
        });
    },
}));

Alpine.data('paymentDropdown', (options = [], selected = 'none') => ({
    open: false,
    value: selected,
    options,

    get current() {
        return this.options.find((opt) => opt.value === this.value) ?? this.options[0];
    },

    select(opt) {
        this.value = opt.value;
        this.open = false;
        this.$wire.set('payment_type', opt.value, true);
    },
}));

Livewire.start();