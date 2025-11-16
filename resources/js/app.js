import './bootstrap';



// HAPUS atau COMMENT SEMUA KODE DI BAWAH INI
// Karena sudah dihandle oleh Alpine.js di layout file

// Dark mode toggle - COMMENT atau HAPUS
// function initDarkMode() {
//     const themeToggle = document.getElementById('theme-toggle');
//     if (themeToggle) {
//         themeToggle.addEventListener('click', () => {
//             const html = document.documentElement;
//             html.classList.toggle('dark');
//             localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
//         });
//     }

//     // Initialize dark mode based on user preference
//     const html = document.documentElement;
//     if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
//         html.classList.add('dark');
//     } else {
//         html.classList.remove('dark');
//     }
// }

// Sidebar toggle for mobile - COMMENT ini
// function initSidebar() {
//     const sidebarToggle = document.getElementById('sidebar-toggle');
//     const sidebar = document.getElementById('sidebar');
    
//     if (sidebarToggle && sidebar) {
//         sidebarToggle.addEventListener('click', () => {
//             sidebar.classList.toggle('-translate-x-full');
//         });
//     }

//     // Close sidebar when clicking on a link (mobile)
//     const sidebarLinks = document.querySelectorAll('#sidebar a');
//     sidebarLinks.forEach(link => {
//         link.addEventListener('click', () => {
//             if (window.innerWidth < 1024) {
//                 sidebar.classList.add('-translate-x-full');
//             }
//         });
//     });
// }

// Initialize all functions when DOM is loaded
// document.addEventListener('DOMContentLoaded', function() {
//     // initDarkMode(); // COMMENT ini
//     initSidebar();
// });

// Re-initialize after Livewire navigation
// document.addEventListener('livewire:navigated', () => {
//     // initDarkMode(); // COMMENT ini
//     initSidebar();
// });