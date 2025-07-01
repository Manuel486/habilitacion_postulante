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

// document.getElementById("generarExcel").addEventListener("click", () => {
//   const seleccionadas = Array.from(columnasSeleccionadas.children).map(
//     (el) => el.dataset.nombre
//   );

//   if (seleccionadas.length === 0) {
//     alert("Selecciona al menos una columna.");
//     return;
//   }

//   let html = `<table class="table table-bordered"><thead class="table-light"><tr>`;
//   seleccionadas.forEach((nombre) => (html += `<th>${nombre}</th>`));
//   html += `</tr></thead><tbody>`;

//   //   datosEjemplo.forEach((row) => {
//   //     html += `<tr>`;
//   //     seleccionadas.forEach((k) => (html += `<td>${row[k] ?? ""}</td>`));
//   //     html += `</tr>`;
//   //   });

//   html += `</tbody></table>`;
//   document.getElementById("tablaPreview").innerHTML = html;
//   const exportarBtn = document.getElementById("btnExportar");
//   exportarBtn.classList.remove("d-none");
//   exportarBtn.dataset.columnas = JSON.stringify(seleccionadas);
// });

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

// document.getElementById("btnExportar").addEventListener("click", () => {
//   const columnasSeleccionadas =
//     document.getElementById("btnExportar").dataset.columnas;
// });

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
