(function() {
    const dropzone = document.getElementById('drop_zone');
    const fileInput = document.getElementById('images');
    const preview = document.getElementById('preview');

    dropzone.addEventListener('click', () => {
        fileInput.click();
    });

    dropzone.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropzone.classList.add('border-blue-500');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-blue-500');
    });

    dropzone.addEventListener('drop', (event) => {
        event.preventDefault();
        dropzone.classList.remove('border-blue-500');
        const files = Array.from(event.dataTransfer.files);
        handleFiles(files);
    });

    fileInput.addEventListener('change', (event) => {
        const files = Array.from(event.target.files);
        handleFiles(files);
    });

    function handleFiles(files) {
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'relative w-full h-32 overflow-hidden rounded-lg border border-gray-300';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover rounded-lg';

                const removeButton = document.createElement('button');
                removeButton.textContent = 'X';
                removeButton.className = 'absolute top-1 right-1 bg-red-600 text-white text-3xl rounded-full p-1 w-8';

                removeButton.addEventListener('click', () => {
                    imgContainer.remove();
                });

                imgContainer.appendChild(img);
                imgContainer.appendChild(removeButton);
                preview.appendChild(imgContainer);
            };
            reader.readAsDataURL(file);
        });
    }
})();