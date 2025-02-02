document.addEventListener('DOMContentLoaded', function () {
    var toggler = document.querySelector('.navbar-toggler');
    var darkModeToggle = document.getElementById('darkModeToggle');
    var darkModeIcon = document.getElementById('darkModeIcon');
    var body = document.body;

    // Memeriksa apakah dark mode aktif di localStorage
    if (localStorage.getItem('dark-mode') === 'enabled') {
        body.classList.add('dark-mode');
        setTitlesColor('#FFFFFF'); // Set warna title menjadi putih
        darkModeIcon.classList.replace('fa-moon', 'fa-sun'); // Ganti ikon menjadi matahari
    }

    // Mengaktifkan/menonaktifkan menu hamburger
    toggler.addEventListener('click', function () {
        toggler.classList.toggle('collapsed');
    });


    // Fungsi untuk mengubah warna title
    function setTitlesColor(color) {
        var titles = document.querySelectorAll('.title');
        titles.forEach(function (title) {
            title.style.color = color;
        });
    }
});