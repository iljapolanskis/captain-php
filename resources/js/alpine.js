import Alpine from 'alpinejs'

Alpine.data('mobile', () => ({
    isMenuOpen: false,

    toggleMenu() {
        this.isMenuOpen = !this.isMenuOpen
    },

    openMenu() {
        this.isMenuOpen = true
    },

    closeMenu() {
        this.isMenuOpen = false
    }
}));

Alpine.data('dropdown', () => ({
    open: false,
    options: [],
    selected: [],
    toggle(index) {
        if (this.selected.includes(this.options[index])) {
            this.selected = this.selected.filter(i => i !== this.options[index]);
        } else {
            this.selected.push(this.options[index]);
        }
    }
}));

window.Alpine = Alpine

Alpine.start()
