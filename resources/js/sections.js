/**
 * Page Sections Management
 * Handles all JavaScript logic for managing page sections in the admin panel
 */

// Wait for Alpine to be ready (Alpine is available via custom-admin.js)
document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;

    if (!Alpine) {
        console.error('Alpine.js is not available. Make sure custom-admin.js loads Alpine first.');
        return;
    }

    // Register pageSectionsManager Alpine component
    Alpine.data('pageSectionsManager', (initialSections = []) => {
        // Get locales from window object (injected by Blade template)
        const locales = window.pageSectionsConfig?.locales || [];

        return {
            sections: initialSections.map(s => ({
                ...s,
                is_active: !!(+s.is_active), // ensure boolean
                expanded: false
            })),
            deletedSections: [],

            // helpers to build names/ids consistently
            inputName(path, i) { return `sections[${i}][${path}]`; },
            checkboxId(i) { return `section_active_${i}`; },

            addNewSection() {
                this.sections.push({
                    id: null,
                    section_type: 'hero',
                    order: this.sections.length + 1,
                    is_active: true,
                    settings: {},
                    translations: locales.map(locale => ({
                        locale, title: '', subtitle: '', content: '', cta_text: '', cta_link: ''
                    })),
                    expanded: true
                });
            },

            deleteSection(index) {
                if (confirm('Delete this section?')) {
                    const section = this.sections[index];
                    if (section.id) this.deletedSections.push(section.id);
                    this.sections.splice(index, 1);
                }
            },

            formatSectionType(type) {
                return type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            }
        };
    });
});

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initPageSections();
});

/**
 * Initialize page sections functionality
 */
function initPageSections() {
    // Handle delete confirmations
    initDeleteConfirmations();

    // Handle accordion toggles
    initAccordionToggles();
}

/**
 * Initialize delete confirmation dialogs
 */
function initDeleteConfirmations() {
    const deleteForms = document.querySelectorAll('.section-delete-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const confirmed = confirm('Are you sure you want to delete this section? This action cannot be undone.');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Initialize accordion toggles for section previews
 */
function initAccordionToggles() {
    const accordionHeaders = document.querySelectorAll('[data-section-toggle]');

    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const sectionId = this.dataset.sectionToggle;
            const content = document.querySelector(`[data-section-content="${sectionId}"]`);
            const icon = this.querySelector('[data-toggle-icon]');

            if (content) {
                const isHidden = content.classList.contains('hidden');

                // Close all other sections
                document.querySelectorAll('[data-section-content]').forEach(el => {
                    if (el !== content) {
                        el.classList.add('hidden');
                    }
                });

                // Reset all other icons
                document.querySelectorAll('[data-toggle-icon]').forEach(el => {
                    if (el !== icon) {
                        el.classList.remove('rotate-90');
                    }
                });

                // Toggle current section
                if (isHidden) {
                    content.classList.remove('hidden');
                    content.classList.add('fade-in');
                    if (icon) icon.classList.add('rotate-90');
                } else {
                    content.classList.add('hidden');
                    content.classList.remove('fade-in');
                    if (icon) icon.classList.remove('rotate-90');
                }
            }
        });
    });
}

/**
 * Show add section form
 */
function showAddSectionForm() {
    const form = document.getElementById('add-section-form');
    const list = document.getElementById('sections-list');
    const editForms = document.querySelectorAll('[data-edit-form]');

    if (form) {
        form.classList.remove('hidden');
        if (list) list.classList.add('hidden');
        editForms.forEach(f => f.classList.add('hidden'));
    }
}

/**
 * Hide add section form
 */
function hideAddSectionForm() {
    const form = document.getElementById('add-section-form');
    const list = document.getElementById('sections-list');

    if (form) {
        form.classList.add('hidden');
        if (list) list.classList.remove('hidden');
    }
}

/**
 * Show edit section form
 */
function showEditSectionForm(sectionId) {
    const editForm = document.querySelector(`[data-edit-form="${sectionId}"]`);
    const addForm = document.getElementById('add-section-form');
    const list = document.getElementById('sections-list');

    // Hide everything else
    if (addForm) addForm.classList.add('hidden');
    if (list) list.classList.add('hidden');
    document.querySelectorAll('[data-edit-form]').forEach(f => f.classList.add('hidden'));

    // Show target edit form
    if (editForm) {
        editForm.classList.remove('hidden');
    }
}

/**
 * Hide edit section form
 */
function hideEditSectionForm() {
    const list = document.getElementById('sections-list');
    const editForms = document.querySelectorAll('[data-edit-form]');

    editForms.forEach(f => f.classList.add('hidden'));
    if (list) list.classList.remove('hidden');
}

/**
 * Handle section type change in form
 */
function handleSectionTypeChange(selectElement) {
    const selectedType = selectElement.value;
    const formContainer = selectElement.closest('form');
    const templateContainers = formContainer.querySelectorAll('[data-section-template]');

    // Hide all templates and disable their inputs
    templateContainers.forEach(container => {
        container.classList.add('hidden');
        // Disable all inputs in hidden templates to prevent validation
        const inputs = container.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.disabled = true;
        });
    });

    // Show selected template and enable its inputs
    if (selectedType) {
        const targetTemplate = formContainer.querySelector(`[data-section-template="${selectedType}"]`);
        if (targetTemplate) {
            targetTemplate.classList.remove('hidden');
            // Enable all inputs in the visible template
            const inputs = targetTemplate.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.disabled = false;
            });
        }
    }
}

// Make functions available globally for inline handlers
window.pageSections = {
    showAddForm: showAddSectionForm,
    hideAddForm: hideAddSectionForm,
    showEditForm: showEditSectionForm,
    hideEditForm: hideEditSectionForm,
    handleTypeChange: handleSectionTypeChange
};
