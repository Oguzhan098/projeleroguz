// public/assets/js/script.js
(function () {
    function normalizeIntl(plate) {
        if (!plate) return "";
        const map = { "ı":"i","İ":"I","ş":"s","Ş":"S","ğ":"g","Ğ":"G","ü":"u","Ü":"U","ö":"o","Ö":"O","ç":"c","Ç":"C" };
        plate = plate.replace(/[ıİşŞğĞüÜöÖçÇ]/g, m => map[m] || m);
        plate = plate.toUpperCase().replace(/[^A-Z0-9]/g, "");
        const m = plate.match(/^(0[1-9]|[1-7][0-9]|8[01])([A-Z]{1,3})(\d{2,4})$/);
        if (m) return "TR-" + plate;
        return plate;
    }

    function round5(min) { return (Math.round(min / 5) * 5) % 60; }

    function refreshVisibility(dirSel, autoExit, entryBox, exitBox) {
        const dir = dirSel.value;
        if (dir === "in") {
            entryBox.style.display = "";
            exitBox.style.display  = (autoExit && autoExit.checked) ? "" : "none";
        } else {
            entryBox.style.display = "none";
            exitBox.style.display  = "";
        }
    }

    function initMovementForm() {
        const form      = document.getElementById("movementForm");
        if (!form) return;

        const plateTr   = document.getElementById("plate_tr");
        const plateIntl = document.getElementById("plate_intl");

        const dirSel    = document.getElementById("direction");
        const autoExit  = document.getElementById("auto_exit");
        const entryBox  = document.getElementById("entryTimeBox");
        const exitBox   = document.getElementById("exitTimeBox");

        const entryHour = document.getElementById("entry_hour");
        const entryMin  = document.getElementById("entry_min");
        const exitHour  = document.getElementById("exit_hour");
        const exitMin   = document.getElementById("exit_min");

        const now = new Date();
        const setIf = (el, val) => { if (el && !el.value) el.value = String(val); };
        setIf(entryHour, now.getHours());
        setIf(entryMin,  round5(now.getMinutes()));
        setIf(exitHour,  now.getHours());
        setIf(exitMin,   round5(now.getMinutes()));

        const rerender = () => refreshVisibility(dirSel, autoExit, entryBox, exitBox);
        dirSel.addEventListener("change", rerender);
        if (autoExit) autoExit.addEventListener("change", rerender);
        rerender();

        form.addEventListener("submit", function () {
            if (plateTr && plateIntl) {
                plateIntl.value = normalizeIntl(plateTr.value);
            }
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initMovementForm);
    } else {
        initMovementForm();
    }
})();
