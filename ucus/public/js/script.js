// assets/js/flight_time.js
document.addEventListener('DOMContentLoaded', function() {
    const depDateInput = document.getElementById('departure_date');
    const depTimeInput = document.getElementById('departure_time');
    const arrDateInput = document.getElementById('arrival_date');
    const arrTimeInput = document.getElementById('arrival_time');

    function updateArrival() {
        const depDateStr = depDateInput.value; // YYYY-MM-DD
        const depTimeStr = depTimeInput.value; // HH:MM

        if (!depDateStr || !depTimeStr) {
            arrDateInput.value = '';
            arrTimeInput.value = '';
            return;
        }

        const [depYear, depMonth, depDay] = depDateStr.split('-').map(Number);
        const [depHour, depMinute] = depTimeStr.split(':').map(Number);

        const depDateTime = new Date(depYear, depMonth - 1, depDay, depHour, depMinute);

        // +1 saat ekle
        depDateTime.setHours(depDateTime.getHours() + 1);

        const newYear = depDateTime.getFullYear();
        const newMonth = (depDateTime.getMonth() + 1).toString().padStart(2, '0');
        const newDay = depDateTime.getDate().toString().padStart(2, '0');
        const newHour = depDateTime.getHours().toString().padStart(2, '0');
        const newMinute = depDateTime.getMinutes().toString().padStart(2, '0');

        arrDateInput.value = `${newYear}-${newMonth}-${newDay}`;
        arrTimeInput.value = `${newHour}:${newMinute}`;
    }

    depDateInput.addEventListener('input', updateArrival);
    depTimeInput.addEventListener('input', updateArrival);
});
