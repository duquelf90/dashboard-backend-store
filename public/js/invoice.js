function invoiceData() {
    return {
        downloadPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
            const spanElement = document.getElementById('invoice');
            const invoiceId = spanElement.getAttribute('data-id');
            const invoiceElement = document.querySelector('.invoice-box');

            html2canvas(invoiceElement, { scale: 2 }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const imgProps = doc.getImageProperties(imgData);
                const pdfWidth = doc.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                const pageHeight = doc.internal.pageSize.getHeight();

                // Scale to fit one page
                let height = pdfHeight;
                if (pdfHeight > pageHeight) {
                    height = pageHeight;
                }

                doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, height);
                doc.save(`invoice-${invoiceId}.pdf`);
            });
        }
    }
}