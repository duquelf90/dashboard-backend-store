document.addEventListener('alpine:init', () => {
    Alpine.data('productManager', () => ({
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
            this.products.splice(index, 1); // Elimina el producto en la posición especificada
            console.log('Productos actuales después de eliminar:', this.products);
        }
    }));
});