console.log('Academy ready');

function confirmDelete() {
    return confirm('Bu departmanı silmek istediğine emin misin? İlişkili kayıtların department_id alanı NULL yapılır.');
}
window.confirmDelete = confirmDelete;
