document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('settings-modal');

    window.showSettings = function () {
        modal.classList.remove('hidden');
    }

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
});