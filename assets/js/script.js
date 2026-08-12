// ============================================
// SPAREPART USK - SCRIPT.JS
// JavaScript untuk modal dan interaksi
// ============================================

// Fungsi untuk menampilkan modal detail transaksi
function showDetail(orderId) {
    // Ambil elemen modal
    var modal = document.getElementById('orderModal');
    var modalBody = document.getElementById('modalBody');

    // Tampilkan loading
    modalBody.innerHTML = '<p>Memuat detail transaksi...</p>';
    modal.classList.add('show');

    // Fetch data detail dari server
    fetch('order_detail.php?id=' + orderId)
        .then(function(response) {
            return response.text();
        })
        .then(function(data) {
            modalBody.innerHTML = data;
        })
        .catch(function(error) {
            modalBody.innerHTML = '<p class="alert alert-danger">Gagal memuat detail transaksi.</p>';
        });
}

// Fungsi untuk menutup modal
function closeModal() {
    var modal = document.getElementById('orderModal');
    if (modal) {
        modal.classList.remove('show');
    }
}

// Tutup modal saat klik di luar modal
document.addEventListener('click', function(event) {
    var modal = document.getElementById('orderModal');
    if (modal && modal.classList.contains('show')) {
        if (event.target === modal) {
            closeModal();
        }
    }
});

// Tutup modal saat tekan tombol ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});