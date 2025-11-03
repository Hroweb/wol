// import './admin.js';

import Alpine from 'alpinejs';
window.Alpine = Alpine;

// Import Quill editor
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
window.Quill = Quill;

// A tiny, reusable selection store for tables
// Usage: x-data="tableSelect({ items: [1,2,3] })"

import './lessons.js';

// Initialize Quill editors for page content
function initQuillEditors() {
    // Find all content textareas
    const contentTextareas = document.querySelectorAll('textarea[id*="_content"]');

    contentTextareas.forEach((textarea) => {
        // Skip if already initialized
        if (textarea.dataset.quillInitialized === 'true') {
            return;
        }

        const textareaId = textarea.id;
        const containerId = `${textareaId}_quill`;

        // Check if container already exists (from previous initialization attempt)
        let quillContainer = document.getElementById(containerId);

        if (!quillContainer) {
            // Create Quill container div
            quillContainer = document.createElement('div');
            quillContainer.id = containerId;
            quillContainer.className = 'quill-editor-container';
            textarea.parentNode.insertBefore(quillContainer, textarea);
        }

        // Hide the original textarea but keep it for form submission
        textarea.style.display = 'none';

        try {
            // Initialize Quill with a simple toolbar
            const quill = new Quill(`#${containerId}`, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['link'],
                        ['clean']
                    ]
                },
                placeholder: 'Start writing...'
            });

            // Set initial content
            if (textarea.value) {
                quill.root.innerHTML = textarea.value;
            }

            // Sync Quill content to textarea on change
            quill.on('text-change', function() {
                textarea.value = quill.root.innerHTML;
            });

            // Also sync on paste and format changes
            quill.on('editor-change', function() {
                textarea.value = quill.root.innerHTML;
            });

            // Mark as initialized and store reference
            textarea.dataset.quillInitialized = 'true';
            textarea.dataset.quillId = containerId;
            // Store Quill instance reference on the container for easy access
            quillContainer.__quillInstance = quill;
        } catch (error) {
            console.error('Error initializing Quill editor:', error);
        }
    });
}

// Sync all Quill editors before form submission
function syncAllQuillEditors() {
    document.querySelectorAll('textarea[id*="_content"][data-quill-initialized="true"]').forEach((textarea) => {
        const quillId = textarea.dataset.quillId;
        if (quillId) {
            const quillContainer = document.getElementById(quillId);
            if (quillContainer && quillContainer.__quillInstance) {
                textarea.value = quillContainer.__quillInstance.root.innerHTML;
            }
        }
    });
}

// Initialize on DOM load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initQuillEditors();
        // Ensure content is synced before form submission
        document.addEventListener('submit', (e) => {
            if (e.target.tagName === 'FORM') {
                syncAllQuillEditors();
            }
        });
    });
} else {
    initQuillEditors();
    // Ensure content is synced before form submission
    document.addEventListener('submit', (e) => {
        if (e.target.tagName === 'FORM') {
            syncAllQuillEditors();
        }
    });
}

document.addEventListener('alpine:init', () => {
    Alpine.data('tableSelect', (config = {}) => {
        const norm = (items) => {
            if (!Array.isArray(items)) return [];
            // accept [1,2] or [{id:1},{id:2}]
            return items.map((it) => (typeof it === 'object' ? it.id : it)).filter((v) => v != null);
        };

        return {
            items: norm(config.items || []),
            selected: [],

            init() {
                // Optional: preselected from config
                if (Array.isArray(config.selected)) this.selected = norm(config.selected);
                // Listen to optional window events (event-based usage)
                window.addEventListener('ts:toggle-all', this.toggleAll.bind(this));
                window.addEventListener('ts:clear', this.clear.bind(this));
            },

            // ----- API you’ll call from Blade -----
            toggleAll() {
                this.selected = this.isAllSelected() ? [] : [...this.items];
                this.emit();
            },
            isAllSelected() {
                return this.items.length > 0 && this.selected.length === this.items.length;
            },

            toggle(id) {
                const i = this.selected.indexOf(id);
                if (i > -1) this.selected.splice(i, 1);
                else this.selected.push(id);
                this.emit();
            },
            isSelected(id) {
                return this.selected.includes(id);
            },

            clear() {
                this.selected = [];
                this.emit();
            },
            count() {
                return this.selected.length;
            },

            // Notify parent listeners
            emit() {
                this.$dispatch('selection-changed', {
                    selectedItems: this.selected,
                    count: this.selected.length,
                });
            },
        };
    });

    // Photo Upload with Live Preview
    Alpine.data('photoUpload', () => {
        return {
            previewUrl: null,

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        alert('Please select a valid image file.');
                        return;
                    }

                    // Validate file size (max 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File size must be less than 5MB.');
                        return;
                    }

                    // Create preview URL
                    this.previewUrl = URL.createObjectURL(file);
                } else {
                    this.previewUrl = null;
                }
            },

            // Clean up object URL when component is destroyed
            destroy() {
                if (this.previewUrl) {
                    URL.revokeObjectURL(this.previewUrl);
                }
            }
        };
    });
});


Alpine.start();

// Global slug generation function for pages
window.generateSlugFromTitle = function(title) {
    if (!title) {
        return '';
    }
    let slug = title.toLowerCase().trim();
    // Replace special characters
    slug = slug.replace(/[^\w\s\u00C0-\u017F-]/g, ''); // Remove special chars but keep accented letters
    slug = slug.replace(/[\s_]+/g, '-'); // Replace spaces and underscores with hyphens
    slug = slug.replace(/-+/g, '-'); // Replace multiple hyphens with single hyphen
    slug = slug.replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
    return slug;
};
