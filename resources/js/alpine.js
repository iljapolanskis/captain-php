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

window.Alpine = Alpine

Alpine.start()
