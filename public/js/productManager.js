document.addEventListener('alpine:init', () => {
    Alpine.data('productManager', () => ({
        form: {
            name: '',
            price: 0,
            quantity: 1
        },
        products: [],
        addProduct() {
            this.products.push({ ...this.form });
            console.log('Productos actuales:', this.products);
            this.form.name = '';
            this.form.price = 0;
            this.form.quantity = 1;
        },
        removeProduct(index) {
            this.products.splice(index, 1); // Elimina el producto en la posición especificada
            console.log('Productos actuales después de eliminar:', this.products);
        }
    }));
});