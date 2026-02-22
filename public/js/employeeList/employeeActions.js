// State Management
let currentEmployeeCode = '';
let currentUrlRoot = '';

/** 1. Action Choice Modal (Cog Popup) **/
function openActionModal(code, urlRoot) {
    currentEmployeeCode = code;
    currentUrlRoot = urlRoot;
    document.getElementById('modal-edit-link').href = `${urlRoot}/EditUser/index/${code}`;

    const modal = document.getElementById('action-modal');
    modal.classList.replace('hidden', 'flex');
}

function closeActionModal() {
    document.getElementById('action-modal').classList.replace('flex', 'hidden');
}

/** 2. Resignation Logic Handoff **/
function triggerResignFromAction() {
    closeActionModal();
    // Open the confirmation check
    openDeleteModal(currentEmployeeCode, `${currentUrlRoot}/EmployeeList/delete/${currentEmployeeCode}`);
}

/** 3. Final Confirmation Modal **/
function openDeleteModal(code, url) {
    document.getElementById('modal-employee-code').innerText = code;
    document.getElementById('modal-delete-form').action = url;
    document.getElementById('delete-modal').classList.replace('hidden', 'flex');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.replace('flex', 'hidden');
}