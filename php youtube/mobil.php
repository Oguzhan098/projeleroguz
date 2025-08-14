<?php
$page = 'mobile';
$title = 'Mobil Uygulama - CourseApp';
include __DIR__.'/includes/header.php';
?>

<h1 class="mb-4 text-center">Mobil Uygulama</h1>
<div class="row g-4">

    <!-- Kamera & Medya -->
    <div class="col-md-3">
        <div class="card h-100 shadow-sm">
            <img src="https://developer.android.com/images/topic/media/camerax_overview.svg" class="card-img-top" alt="Kamera ve Medya" height="160" style="object-fit:contain;">
            <div class="card-body">
                <h5 class="card-title">Kamera & Medya</h5>
                <p class="card-text">Foto/Video çekimi ve düzenleme akışları.</p>
                <a class="btn btn-primary" target="_blank" href="https://developer.android.com/training/camerax">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

    <!-- Sağlık & Fitness -->
    <div class="col-md-3">
        <div class="card h-100 shadow-sm">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Google_Fit_icon.svg" class="card-img-top" alt="Sağlık ve Fitness" height="160" style="object-fit:contain;">
            <div class="card-body">
                <h5 class="card-title">Sağlık & Fitness</h5>
                <p class="card-text">Aktivite takibi ve sağlıklı yaşam.</p>
                <a class="btn btn-primary" target="_blank" href="https://www.google.com/fit/">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

    <!-- Sosyal Medya / Mesajlaşma -->
    <div class="col-md-3">
        <div class="card h-100 shadow-sm">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" class="card-img-top" alt="Sosyal Medya" height="160" style="object-fit:contain;">
            <div class="card-body">
                <h5 class="card-title">Sosyal & Mesajlaşma</h5>
                <p class="card-text">Gerçek zamanlı iletişim ve içerik paylaşımı.</p>
                <a class="btn btn-primary" target="_blank" href="https://firebase.google.com/docs/database">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

    <!-- Verimlilik -->
    <div class="col-md-3">
        <div class="card h-100 shadow-sm">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/05/Google_Docs_logo_%282014-2020%29.svg" class="card-img-top" alt="Verimlilik" height="160" style="object-fit:contain;">
            <div class="card-body">
                <h5 class="card-title">Verimlilik</h5>
                <p class="card-text">Notlar, görevler ve zaman yönetimi.</p>
                <a class="btn btn-primary" target="_blank" href="https://www.atlassian.com/software/trello">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__.'/includes/footer.php'; ?>
