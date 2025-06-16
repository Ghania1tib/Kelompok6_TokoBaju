// Contoh: Kode di script.js yang perlu dihapus atau diubah
document.addEventListener('DOMContentLoaded', function() {
    // Bagian ini mungkin ada di script.js Anda dan perlu dihapus atau diubah
    const settingsForm = document.querySelector('#settings .settings-form');
    if (settingsForm) {
        settingsForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Ini mencegah form disubmit ke server
            alert('Pengaturan disimpan! (Ini hanya simulasi, data tidak disimpan ke database)'); // Ini yang menyebabkan alert muncul
            // Atau kode lain yang memanipulasi DOM untuk menampilkan pesan ini
        });
    }

    // Kode sidebar toggle (biarkan ini)
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    }

    // Kode untuk navigasi sidebar di index.php (yang akan kita hapus karena PHP sudah menanganinya)
    // const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    // const dashboardSections = document.querySelectorAll('.dashboard-section');

    // sidebarLinks.forEach(link => {
    //     link.addEventListener('click', function(e) {
    //         e.preventDefault();
    //         const targetSectionId = this.dataset.section;

    //         sidebarLinks.forEach(item => item.classList.remove('active'));
    //         this.classList.add('active');

    //         dashboardSections.forEach(section => {
    //             if (section.id === targetSectionId) {
    //                 section.classList.add('active');
    //             } else {
    //                 section.classList.remove('active');
    //             }
    //         });
    //     });
    // });
});