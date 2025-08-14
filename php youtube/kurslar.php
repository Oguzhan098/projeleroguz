<?php
$page='courses';
$title='Kurslar - CourseApp';
include 'includes/header.php';
?>

<h1 class="mb-4 text-center">Kurslar</h1>
<div class="row justify-content-center g-4">

    <!-- Python -->
    <div class="col-md-3">
        <div class="card h-100 text-center shadow-sm">
            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c3/Python-logo-notext.svg" class="card-img-top" alt="Python" height="180">
            <div class="card-body">
                <h5 class="card-title">Python</h5>
                <p class="card-text">Veri bilimi, otomasyon ve web için çok yönlü dil.</p>
                <a class="btn btn-primary" target="_blank" href="https://www.python.org/">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

    <!-- PHP -->
    <div class="col-md-3">
        <div class="card h-100 text-center shadow-sm">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/27/PHP-logo.svg" class="card-img-top" alt="PHP" height="180">
            <div class="card-body">
                <h5 class="card-title">PHP</h5>
                <p class="card-text">Dinamik web siteleri ve API geliştirme.</p>
                <a class="btn btn-primary" target="_blank" href="https://www.php.net/">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

    <!-- Java -->
    <div class="col-md-3">
        <div class="card h-100 text-center shadow-sm">
            <img src="https://upload.wikimedia.org/wikipedia/en/3/30/Java_programming_language_logo.svg" class="card-img-top" alt="Java" height="180">
            <div class="card-body">
                <h5 class="card-title">Java</h5>
                <p class="card-text">Kurumsal, mobil ve masaüstü uygulamalarında güçlü.</p>
                <a class="btn btn-primary" target="_blank" href="https://www.java.com/">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

    <!-- Laravel -->
    <div class="col-md-3">
        <div class="card h-100 text-center shadow-sm">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Laravel.svg" class="card-img-top" alt="Laravel" height="180">
            <div class="card-body">
                <h5 class="card-title">Laravel</h5>
                <p class="card-text">PHP için zarif MVC web framework.</p>
                <a class="btn btn-primary" target="_blank" href="https://laravel.com/">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
