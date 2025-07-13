// Auto-apply custom validity message dari data-error
document.addEventListener('DOMContentLoaded', () => {
    const fields = document.querySelectorAll('[required][data-error]');

    fields.forEach(field => {
        field.addEventListener('invalid', function (e) {
            e.target.setCustomValidity(e.target.getAttribute('data-error'));
        });

        field.addEventListener('input', function (e) {
            e.target.setCustomValidity('');
        });
    });
});

// Reusable global form validator (optional dipakai via onsubmit)
function validateForm() {
    const requiredFields = document.querySelectorAll('select[required], input[required]');
    
    for (let i = 0; i < requiredFields.length; i++) {
        const field = requiredFields[i];
        if (!field.value.trim()) {
            const label = document.querySelector(`label[for="${field.id}"]`);
            const labelText = label ? label.innerText.replace('*', '').trim() : field.name;

            // 🔥 Ganti alert biasa dengan SweetAlert
            Swal.fire({
                icon: 'warning',
                title: 'Mohon Lengkapi Data',
                text: `Field "${labelText}" harus diisi.`,
                confirmButtonText: 'Oke',
                customClass: {
                    confirmButton: 'bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] font-medium rounded-lg px-4 py-2'
                },
                buttonsStyling: false  // ⛔ ini WAJIB buat nonaktifin default SweetAlert style
            });

            field.focus();
            return false;
        }
    }

    const submitButton = document.getElementById('submitButton');
    if (submitButton) {
        submitButton.setAttribute('disabled', true);
        submitButton.innerHTML = 'Menyimpan...';
    }

    return true;
}
