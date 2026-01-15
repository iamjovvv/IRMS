// document
//   .getElementById('sidebarToggle')
//   ?.addEventListener('click', () => {
//     document
//       .querySelector('.with-sidebar')
//       ?.classList.toggle('sidebar-collapsed');
//   });

document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('sidebarToggle');
  const layout = document.querySelector('.with-sidebar');

  if(!toggle || !layout) return;

  toggle.addEventListener('click', () => {
  console.log('Sidebar toggle clicked');
  layout.classList.toggle('sidebar-collapsed');
 

  });


});