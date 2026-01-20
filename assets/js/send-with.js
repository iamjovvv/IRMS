
document.addEventListener('DOMContentLoaded', () => {

    const authSelect = document.querySelector('select[name="auth_method"]');

    const institutionalFields = document.querySelectorAll('.form__field--institutional');

    const phoneFields = document.querySelectorAll('.form__field--phone');

    const googleFields = document.querySelectorAll('.form__field--google');

    function resetFields() {
        institutionalFields.forEach(f => f.style.display = 'none');

        phoneFields.forEach(f => f.style.display = 'none');
        
        googleFields.forEach(f => f.style.display = 'none');

    }

    authSelect.addEventListener('change', () => {
        resetFields();

        if (authSelect.value === 'org_id') {
            institutionalFields.forEach(f => f.style.display = 'block');
        }

        if (authSelect.value === 'phone') {
            phoneFields.forEach(f => f.style.display = 'block');
        }

        if (authSelect.value === 'google') {
            googleFields.forEach(f => f.style.display = 'block');
        }
    });

    resetFields();
});
