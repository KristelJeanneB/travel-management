document.addEventListener('DOMContentLoaded', function () {

    const map = L.map('map').setView([45.5231, -122.6765], 13)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',  {
        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>  contributors'
    }).addTo(map);


    document.getElementById('zoom-in').addEventListener('click', () => {
        map.zoomIn();
    });

    document.getElementById('zoom-out').addEventListener('click', () => {
        map.zoomOut();
    });


    document.getElementById('search-button').addEventListener('click', () => {
        const query = document.getElementById('search-input').value.trim();
        if (!query) return;

    
        alert(`Searching for: ${query}`);
    });


    document.getElementById('route-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const start = document.getElementById('start-from').value.trim();
        const destination = document.getElementById('destination').value.trim();

        if (!start || !destination) {
            alert('Please fill both fields.');
            return;
        }

    
        alert(`Getting directions from ${start} to ${destination}`);
    });
});