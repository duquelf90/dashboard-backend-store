document.addEventListener('alpine:init', () => {
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
                    const imgElement = document.querySelector(`img[data-id="${imageId}"]`);
                    console.log('Trying to remove image with ID:', imageId); // Verifica el ID
                    imgElement.parentElement.remove(); // Elimina el contenedor de la imagen
                } else {
                    alert(data.message); // Muestra un mensaje de error
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }));
});