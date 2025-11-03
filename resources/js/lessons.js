/**
 * Lesson Form Alpine.js Component
 * Handles lesson creation and editing forms
 * 
 * This file is imported by custom-admin.js, which ensures Alpine is loaded first
 */

// Register the component on alpine:init event (fires before Alpine processes DOM)
// Alpine will be available via window.Alpine (set by custom-admin.js)
document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;
    
    if (!Alpine) {
        console.error('Alpine.js is not available. Make sure custom-admin.js loads Alpine first.');
        return;
    }
    
    Alpine.data('lessonForm', () => {
        // Get data from window object (injected by Blade template)
        const teachersData = window.lessonConfig?.teachersData || [];
        const lessonPartsData = window.lessonConfig?.lessonPartsData || null;
        const locales = window.lessonConfig?.locales || {};
        const lessonId = window.lessonConfig?.lessonId || null;
        const storageUrl = window.lessonConfig?.storageUrl || '';
        const csrfToken = window.lessonConfig?.csrfToken || '';
        const deleteMaterialUrl = window.lessonConfig?.deleteMaterialUrl || '';
        const deleteAudioUrl = window.lessonConfig?.deleteAudioUrl || '';

        // Initialize lesson parts - use existing data if available (edit mode), otherwise default
        const initialParts = lessonPartsData && lessonPartsData.length > 0 
            ? lessonPartsData 
            : [
                {
                    id: 1,
                    teacher_id: '',
                    part_number: 1,
                    audio_file_urls: '',
                    duration_minutes: ''
                }
            ];

        return {
            lessonParts: initialParts,

            init() {
                console.log('Lesson form initialized with parts:', this.lessonParts);

                // Force reactivity update after initialization
                this.$nextTick(() => {
                    console.log('Alpine.js initialized, lesson parts:', this.lessonParts);

                    // Manually set selected teachers after DOM is ready (only in edit mode)
                    if (lessonPartsData && lessonPartsData.length > 0) {
                        this.lessonParts.forEach((part, index) => {
                            if (part.teacher_id) {
                                Object.keys(locales).forEach(locale => {
                                    const selectElement = document.getElementById(`teacher_id_${locale}_${part.id}`);
                                    if (selectElement) {
                                        selectElement.value = part.teacher_id;
                                        console.log(`Set teacher ${part.teacher_id} for part ${index + 1} (${locale})`);
                                    }
                                });
                            }
                            this.syncTeacherSelection(part, index);
                        });
                    }
                });
            },

            addLessonPart() {
                if (this.lessonParts.length < 2) {
                    const nextPartNumber = this.lessonParts.length + 1;
                    this.lessonParts.push({
                        id: Date.now(),
                        teacher_id: '',
                        part_number: nextPartNumber,
                        audio_file_urls: '',
                        duration_minutes: ''
                    });

                    // Set up synchronization for the new part
                    this.$nextTick(() => {
                        const newPart = this.lessonParts[this.lessonParts.length - 1];
                        this.syncTeacherSelection(newPart, this.lessonParts.length - 1);
                    });
                }
            },

            removeLessonPart(index) {
                if (this.lessonParts.length > 1) {
                    this.lessonParts.splice(index, 1);
                    // Reassign part numbers
                    this.lessonParts.forEach((part, idx) => {
                        part.part_number = idx + 1;
                    });
                }
            },

            getTeachers() {
                return teachersData;
            },

            syncTeacherSelection(part, index) {
                // Get all teacher select elements for this part across all locales
                const teacherSelects = [];

                Object.keys(locales).forEach(locale => {
                    const element = document.getElementById(`teacher_id_${locale}_${part.id}`);
                    if (element) {
                        teacherSelects.push(element);
                    }
                });

                // Set up bidirectional synchronization between all teacher selects
                // Use a data attribute to prevent duplicate listeners
                teacherSelects.forEach(select => {
                    // Skip if already synced
                    if (select.dataset.synced === 'true') {
                        return;
                    }

                    select.dataset.synced = 'true';
                    select.addEventListener('change', () => {
                        // Sync the change to all other teacher selects for this part
                        teacherSelects.forEach(otherSelect => {
                            if (otherSelect !== select && otherSelect.value !== select.value) {
                                otherSelect.value = select.value;
                            }
                        });
                    });
                });
            },

            playAudio(audioPath) {
                if (!audioPath) return;

                const audioUrl = storageUrl + '/' + audioPath;

                // Create a simple modal to show audio controls
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center';
                modal.innerHTML = `
                    <div class="relative p-8 bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Audio Player</h3>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <audio controls class="w-full" autoplay>
                            <source src="${audioUrl}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                `;

                // Close on outside click
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.remove();
                    }
                });

                document.body.appendChild(modal);
            },

            deleteAudio(partNumber, locale) {
                if (!confirm('Are you sure you want to delete this audio file?')) {
                    return;
                }

                if (!deleteAudioUrl) {
                    console.error('Delete audio URL not configured');
                    return;
                }

                // Create form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteAudioUrl;

                // Add CSRF token
                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = csrfToken;
                form.appendChild(csrfField);

                // Add method override for DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                // Add part number and locale
                const partField = document.createElement('input');
                partField.type = 'hidden';
                partField.name = 'part_number';
                partField.value = partNumber;
                form.appendChild(partField);

                const localeField = document.createElement('input');
                localeField.type = 'hidden';
                localeField.name = 'locale';
                localeField.value = locale;
                form.appendChild(localeField);

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }
        };
    });
});

/**
 * Global function to delete materials (used in edit mode)
 */
window.deleteMaterial = function(locale, index) {
    if (!confirm('Are you sure you want to delete this material?')) {
        return;
    }

    const config = window.lessonConfig;
    if (!config || !config.deleteMaterialBaseUrl) {
        console.error('Delete material URL not configured');
        return;
    }

    const deleteUrl = config.deleteMaterialBaseUrl.replace(':locale', locale).replace(':index', index);

    // Create a form to submit the delete request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = deleteUrl;

    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = config.csrfToken;
    form.appendChild(csrfToken);

    // Add method override for DELETE
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    form.appendChild(methodField);

    // Submit the form
    document.body.appendChild(form);
    form.submit();
};

