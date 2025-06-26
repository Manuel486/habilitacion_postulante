const formularioRequerimiento = document.getElementById(
  "formularioRequerimiento"
);
const btnNuevoRequerimiento = document.getElementById("btnNuevoRequerimiento");

formularioRequerimiento.addEventListener("submit", async (e) => {
  e.preventDefault();

  const elementos = formularioRequerimiento.elements;

  if (!camposValidos(elementos)) {
    return;
  }

  const btnNuevoRequerimiento = document.getElementById(
    "btnNuevoRequerimiento"
  );
  btnNuevoRequerimiento.disabled = true;
  btnNuevoRequerimiento.textContent = "Guardando...";

  let formdata = new FormData();
  let requerimiento = {
    // id_requerimiento: elementos["id_requerimiento"].value,
    // fecha_registro: elementos["fecha_registro"].value,
    id_proyecto: elementos["sltProyecto"].value,
    fecha_requerimiento: elementos["dateFechaRequerimiento"].value,
    numero_requerimiento: elementos["txtNroRequerimiento"].value,
    tipo_requerimiento: elementos["sltTipoRequerimiento"].value,
    id_fase: elementos["sltFase"].value,
    id_cargo: elementos["sltCargo"].value,
    cantidad: elementos["txtCantidad"].value,
    regimen: elementos["sltRegimen"].value,
  };

  formdata.append("requerimiento", JSON.stringify(requerimiento));

  try {
    const resp = await fetch("api/guardarRequerimiento", {
      method: "POST",
      body: formdata,
    });
    const data = await resp.json();

    if (data.exitoso) {
      Swal.fire({
        title: "Éxito",
        icon: "success",
        text: "Requerimiento guardado con éxito.",
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true,
      }).then(() => location.reload());
    } else {
      alert("Error: " + data.mensaje);
    }
  } catch (error) {
    console.error(error);
  } finally {
    btnNuevoRequerimiento.disabled = false;
    btnNuevoRequerimiento.textContent = "Guardar";
  }
});

function camposValidos(elementos) {
  let validacion = true;
  for (const elemento of elementos) {
    if (
      elemento.value === "" &&
      elemento.type !== "submit" &&
      elemento.type !== "button"
    ) {
      validacion = false;
      elemento.classList.add("is-invalid");
      elemento.classList.remove("border-dark");
    }
  }
  return validacion;
}

const elementos = formularioRequerimiento.elements;
for (const elemento of elementos) {
  if (elemento.type !== "submit" && elemento.type !== "button") {
    elemento.addEventListener("input", () => {
      elemento.classList.remove("is-invalid");
      elemento.classList.add("border-dark");
    });
  }
}
