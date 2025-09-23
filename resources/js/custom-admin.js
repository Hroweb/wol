// import './admin.js';

import Alpine from 'alpinejs';
window.Alpine = Alpine;

// A tiny, reusable selection store for tables
// Usage: x-data="tableSelect({ items: [1,2,3] })"

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
});


Alpine.start();
