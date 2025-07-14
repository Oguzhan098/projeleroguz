document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', function(event) {
        const confirmed = confirm("Yönlendiriliyorsunuz, devam etmek istiyor musunuz?");
        if (!confirmed) {
            event.preventDefault();
        }
    });
});