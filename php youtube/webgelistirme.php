<?php
$page = 'web';
$title = 'Web Geliştirme - CourseApp';
include __DIR__.'/includes/header.php';
?>

<h1 class="mb-4">Web Geliştirme</h1>
<div class="row g-4">

    <!-- VSCode -->
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <img src="https://code.visualstudio.com/assets/images/code-stable.png" class="card-img-top" alt="VSCode" height="200" style="object-fit:contain;">
            <div class="card-body">
                <h5 class="card-title">Visual Studio Code</h5>
                <p class="card-text">Eklenti zengini kod editörü.</p>
                <a class="btn btn-primary" target="_blank" href="https://code.visualstudio.com/">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

    <!-- GitHub -->
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" class="card-img-top" alt="GitHub" height="200" style="object-fit:contain;">
            <div class="card-body">
                <h5 class="card-title">GitHub</h5>
                <p class="card-text">Kod barındırma ve işbirliği platformu.</p>
                <a class="btn btn-primary" target="_blank" href="https://github.com/">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <img src="https://getbootstrap.com/docs/5.3/assets/brand/bootstrap-logo-shadow.png" class="card-img-top" alt="Bootstrap" height="200" style="object-fit:contain;">
            <div class="card-body">
                <h5 class="card-title">Bootstrap</h5>
                <p class="card-text">Responsive UI bileşenleri ve grid sistemi.</p>
                <a class="btn btn-primary" target="_blank" href="https://getbootstrap.com/">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__.'/includes/footer.php'; ?>
