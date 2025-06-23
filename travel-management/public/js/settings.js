document.addEventListener('DOMContentLoaded', function () {
    const settingsModal = document.getElementById('settings-modal');
    const settingsButton = document.querySelector('.settings-button'); // Assuming you have a button to open settings

    settingsButton.addEventListener('click', function () {
        settingsModal.classList.toggle('hidden');
    });

    // Optional: Close modal when clicking outside
    window.addEventListener('click', function (event) {
        if (event.target === settingsModal) {
            settingsModal.classList.add('hidden');
        }
    });
});