//estilização da quantidade
document.addEventListener("click", e => {
  if (!e.target.matches(".mais, .menos")) return;
  e.preventDefault();

  const box = e.target.closest(".quantidade");
  const input = box.querySelector(".qtde");
  const form = e.target.closest("form");
  const hidden = form ? form.querySelector(".QTDE") : null;

  let valor = +input.value;

  if (e.target.classList.contains("mais")) valor++;
  else if (valor > 1) valor--;

  input.value = valor;
  hidden.value = valor;
});
