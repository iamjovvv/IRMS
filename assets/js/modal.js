
const modal = document.getElementById('createUserModal');
const openBtn = document.getElementById('openCreateUser');
const closeBtn = document.getElementById('closeModal');

openBtn.onclick = () => modal.classList.add('active');
closeBtn.onclick = () => modal.classList.remove('active');

modal.addEventListener('click', e => {
    if (e.target === modal) modal.classList.remove('active');
});

// ROLE SWITCH
const roleSelect = document.getElementById('roleSelect');
const reporterFields = document.getElementById('reporterFields');
const staffFields = document.getElementById('staffFields');

roleSelect.addEventListener('change', () => {
    reporterFields.style.display = 'none';
    staffFields.style.display = 'none';

    if (roleSelect.value === 'reporter') reporterFields.style.display = 'block';
    if (roleSelect.value === 'staff') staffFields.style.display = 'block';
});

