<?php
$page = 'programming';
$title = 'Programlama - CourseApp';
include __DIR__.'/includes/header.php';
?>

<h1 class="mb-4">Programlama</h1>
<div class="row g-4">

    <!-- C# -->
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/0d/C_Sharp_wordmark.svg" class="card-img-top" alt="C#" height="200" style="object-fit:contain;">
            <div class="card-body">
                <h5 class="card-title">C#</h5>
                <p class="card-text">.NET ekosistemiyle modern uygulamalar.</p>
                <a class="btn btn-primary" target="_blank" href="https://learn.microsoft.com/dotnet/csharp/">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

    <!-- C++ -->
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <img src="https://upload.wikimedia.org/wikipedia/commons/1/18/ISO_C%2B%2B_Logo.svg" class="card-img-top" alt="C++" height="200" style="object-fit:contain;">
            <div class="card-body">
                <h5 class="card-title">C++</h5>
                <p class="card-text">Yüksek performanslı sistemler ve oyun motorları.</p>
                <a class="btn btn-primary" target="_blank" href="https://isocpp.org/">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

    <!-- MATLAB -->
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/21/Matlab_Logo.png" class="card-img-top" alt="MATLAB" height="200" style="object-fit:contain;">
            <div class="card-body">
                <h5 class="card-title">MATLAB</h5>
                <p class="card-text">Sayısal hesaplama ve veri görselleştirme.</p>
                <a class="btn btn-primary" target="_blank" href="https://www.mathworks.com/products/matlab.html">Detaylı Bilgi</a>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__.'/includes/footer.php'; ?>
