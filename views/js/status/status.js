// Exportar Excel relacionado a un requerimiento
const columnasDisponibles = document.getElementById("columnasDisponibles");
const columnasSeleccionadas = document.getElementById("columnasSeleccionadas");

new Sortable(columnasDisponibles, { group: "shared", animation: 150 });
new Sortable(columnasSeleccionadas, { group: "shared", animation: 150 });

let columnas = [];

const obtenerNombreColumnas = async () => {
  try {
    const resp = await fetch("api/obtenerNombreColumnas");
    const data = await resp.json();

    if (data.exitoso) {
      return data.respuesta;
    }
  } catch (error) {
    console.error(error);
  }
};

document.getElementById("btnMoverTodoDerecha").addEventListener("click", () => {
  const items = Array.from(columnasDisponibles.children);
  items.forEach((item) => columnasSeleccionadas.appendChild(item));
});

document
  .getElementById("btnMoverTodoIzquierda")
  .addEventListener("click", () => {
    const items = Array.from(columnasSeleccionadas.children);
    items.forEach((item) => columnasDisponibles.appendChild(item));
  });

document.getElementById("generarExcel").addEventListener("click", () => {
  const seleccionadas = Array.from(columnasSeleccionadas.children).map(
    (el) => el.dataset.nombre
  );

  if (seleccionadas.length === 0) {
    alert("Selecciona al menos una columna.");
    return;
  }

  document.getElementById("inputFiltroColumnas").value =
    JSON.stringify(seleccionadas);
  document.getElementById("formExcel").submit();
});

async function exportarStatusPorRequerimiento(idRequerimiento) {
  document.getElementById("inputRequerimientoExcel").value =
    idRequerimiento ?? "";

  let columnasDB = await obtenerNombreColumnas();

  columnasDB.forEach((nombre) => {
    const li = document.createElement("li");
    li.className = "my-1 p-2 rounded card";
    li.textContent = nombre;
    li.dataset.nombre = nombre;
    li.style.cursor = "move";
    columnasDisponibles.appendChild(li);
  });
}

async function enviarInvitacion(
  btnEnviarInvitacion,
  idPreseleccionadoEnviar,
  idRequerimientoEnviar
) {
  const fila = btnEnviarInvitacion.closest(".filaPreseleecionado");
  const estadoMensaje = fila.querySelector(".estadoMensaje");
  const estadoPostulacion = fila.querySelector(".estadoPostulacion");
  const btnReenviarInvitacion = fila.querySelector(".btnReenviarInvitacion");

  try {
    Swal.fire({
      title: "Guardando como postulante y enviando correo.",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    let formData = new FormData();
    formData.append("id_preseleccionado", idPreseleccionadoEnviar);
    formData.append("id_requerimiento", idRequerimientoEnviar);

    const resp = await fetch("api/envitarInvitacionPreReque", {
      method: "POST",
      body: formData,
    });

    const data = await resp.json();

    if (data.exitoso) {
      const resultado = data.respuesta;

      if (resultado.guardado && resultado.correo_enviado) {
        Swal.fire({
          icon: "success",
          title: "Éxito",
          text: "Postulante guardado y correo enviado correctamente.",
        });
        estadoPostulacion.textContent = "Guardado";
        estadoPostulacion.classList.remove("text-bg-secondary");
        estadoPostulacion.classList.add("text-bg-success");
        btnEnviarInvitacion.disabled = true;

        estadoMensaje.textContent = "Enviado";
        estadoMensaje.classList.remove("text-bg-secondary");
        estadoMensaje.classList.add("text-bg-success");
        btnReenviarInvitacion.removeAttribute("disabled");

      } else if (resultado.guardado && !resultado.correo_enviado) {
        Swal.fire({
          icon: "warning",
          title: "Advertencia",
          text: "Postulante guardado, pero no se pudo enviar el correo.",
        });
        estadoPostulacion.textContent = "Guardado";
        estadoPostulacion.classList.remove("text-bg-secondary");
        estadoPostulacion.classList.add("text-bg-success");
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Postulante no guardado.",
        });
      } 
    } else {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: data.mensaje,
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Error de red o del servidor. Intente nuevamente.",
    });
  }
}

async function reenviarInvitacionPreReque(
  btnReenviarInvitacion,
  idPreseleccionadoEnviar,
  idRequerimientoEnviar
) {

  const fila = btnReenviarInvitacion.closest(".filaPreseleecionado");
  const estadoMensaje = fila.querySelector(".estadoMensaje");
  try {
    Swal.fire({
      title: "Enviando correo.",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    let formData = new FormData();
    formData.append("id_preseleccionado", idPreseleccionadoEnviar);
    formData.append("id_requerimiento", idRequerimientoEnviar);

    const resp = await fetch("api/reenviarInvitacionPreReque", {
      method: "POST",
      body: formData,
    });

    const data = await resp.json();

    if (data.exitoso) {
      const resultado = data.respuesta;

      if (resultado.correo_enviado) {
        Swal.fire({
          icon: "success",
          title: "Éxito",
          text: "Se volvio a enviar la clave a su correo.",
        });
        estadoMensaje.textContent = "Enviado";
        estadoMensaje.classList.remove("text-bg-secondary");
        estadoMensaje.classList.add("text-bg-success");
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo volver a enviar el correo.",
        });
      }
    } else {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: data.mensaje,
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Error de red o del servidor. Intente nuevamente.",
    });
  }
}
