const formularioRequerimiento = document.getElementById(
  "formularioRequerimiento"
);
const btnNuevoRequerimiento = document.getElementById("btnNuevoRequerimiento");

formularioRequerimiento.addEventListener("submit", (e) => {
  e.preventDefault();

  const elementos = formularioRequerimiento.elements;

  if (!camposValidos(elementos)) {
    console.log("Debe completar todos los campos");
    return;
  }

  console.log("Formulario enviado");
});

function camposValidos(elementos) {
  let validacion = true;
  for (const elemento of elementos) {
    if (elemento.value === "" && elemento.type !== "submit") {
      validacion = false;
      elemento.classList.add("is-invalid");
      elemento.classList.remove("border-dark");
    }

    elemento.addEventListener("input", () => {
      elemento.classList.remove("is-invalid");
      elemento.classList.add("border-dark");
    });
  }
  return validacion;
}
