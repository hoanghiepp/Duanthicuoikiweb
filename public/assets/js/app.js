document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");
  if (toggle && sidebar)
    toggle.addEventListener("click", () => sidebar.classList.toggle("open"));

  const employeeSelects = [
    ...document.querySelectorAll('select[name="employee_id"]'),
  ];
  for (const select of employeeSelects) {
    select.addEventListener("change", () => {
      const op = select.options[select.selectedIndex];
      const base = document.querySelector('input[name="base_salary"]');
      const allowance = document.querySelector('input[name="allowance"]');
      if (base && op.dataset.base) base.value = op.dataset.base;
      if (allowance && op.dataset.allowance)
        allowance.value = op.dataset.allowance;
    });
  }
});
