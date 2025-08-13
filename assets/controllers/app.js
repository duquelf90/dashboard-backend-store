// Importa Alpine
import Alpine from 'alpinejs'

// Define nuestro "manager" central
Alpine.data('appManager', () => ({
    // ====== CONFIGURACIÓN DE TEMA ======
    dark: (function () {
        if (window.localStorage.getItem('dark')) {
            return JSON.parse(window.localStorage.getItem('dark'));
        }
        return (
            !!window.matchMedia &&
            window.matchMedia('(prefers-color-scheme: dark)').matches
        );
    })(),

    toggleTheme() {
        this.dark = !this.dark;
        window.localStorage.setItem('dark', this.dark);
    },

    // ====== MENÚS ======
    isSideMenuOpen: false,
    toggleSideMenu() {
        this.isSideMenuOpen = !this.isSideMenuOpen;
    },
    closeSideMenu() {
        this.isSideMenuOpen = false;
    },

    isPagesMenuOpen: false,
    togglePagesMenu() {
        this.isPagesMenuOpen = !this.isPagesMenuOpen;
    },

    // ====== PRODUCTOS ======
    form: {
        name: '',
        price: 1,
        quantity: 1
    },
    products: [],

    addProduct() {
        if (this.form.price < 1) {
            alert('El precio debe ser al menos 1.');
            return;
        }
        this.products.push({ ...this.form });
        console.log('Productos actuales:', this.products);
        this.form.name = '';
        this.form.price = 1;
        this.form.quantity = 1;
    },

    removeProduct(index) {
        this.products.splice(index, 1);
        console.log('Productos actuales después de eliminar:', this.products);
    }
}));

// 📌 Edición de productos (eliminar imágenes)
Alpine.data('productEdit', (locale, productId) => ({
    locale: locale,
    productId: productId,

    removeImage(imageId) {
        fetch(`/${this.locale}/product/${this.productId}/remove-image/${imageId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Image removed successfully') {
                const imgElement = document.querySelector(`img[data-id="${imageId}"]`)
                if (imgElement && imgElement.parentElement) {
                    imgElement.parentElement.remove()
                }
            } else {
                alert(data.message)
            }
        })
        .catch(error => console.error('Error:', error))
    }
}))


// Inicia Alpine
window.Alpine = Alpine;
Alpine.start();
