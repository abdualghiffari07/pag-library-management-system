import './landing';
import './book-edit';
/* =========================================================
   PAG LIBRARY - ADMIN DASHBOARD
   Entry point JS. Daftarkan fitur interaktif baru sebagai
   fungsi init terpisah, lalu panggil di dalam DOMContentLoaded
   di bagian paling bawah file ini.
   ========================================================= */

'use strict';

/* =========================================================
   SIDEBAR MOBILE TOGGLE (placeholder)
   ---------------------------------------------------------
   TODO: Saat ini sidebar disembunyikan penuh di layar
   <= 600px (lihat dashboard.css) tanpa ada tombol untuk
   membukanya kembali. Tambahkan tombol hamburger di
   .header (mirip #mobile-menu-button pada landing page),
   lalu implementasikan toggle-nya di sini, misalnya:

   function initSidebarToggle() {
       const toggleButton = document.getElementById('sidebar-toggle');
       const sidebar = document.querySelector('.sidebar');

       if (!toggleButton || !sidebar) {
           return;
       }

       toggleButton.addEventListener('click', () => {
           sidebar.classList.toggle('sidebar-open');
       });
   }
   ========================================================= */

function initSidebarToggle() {
    // Belum diimplementasikan - lihat TODO di atas.
}


/* =========================================================
   STAT CARD INTERACTIVITY (placeholder)
   ---------------------------------------------------------
   Tempat untuk menambahkan interaktivitas pada kartu
   statistik di masa depan, misalnya refresh data via
   fetch/AJAX tanpa reload halaman.
   ========================================================= */

function initStatCards() {
    // Belum diimplementasikan.
}


/* =========================================================
   INIT
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {

    initSidebarToggle();
    initStatCards();

});