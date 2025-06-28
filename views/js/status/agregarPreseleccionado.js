const btnBuscarCandidato = document.getElementById("btnBuscarCandidato");
const btnGuardarInformacionCandidato = document.getElementById(
  "btnGuardarInformacionCandidato"
);
const btnAgregarCurso = document.getElementById("btnAgregarCurso");
const btnAgregarCertificacion = document.getElementById(
  "btnAgregarCertificacion"
);

let idCandidatoExiste = "";
let idRequerimiento = null;
const txtDocumentoCandidato = document.getElementById("txtDocumentoCandidato");

btnBuscarCandidato.addEventListener("click", async () => {
  if (!camposValidosBuscarCandidato()) return;

  btnBuscarCandidato.disabled = true;
  btnBuscarCandidato.textContent = "Guardando...";

  let formData = new FormData();
  formData.append("documento", txtDocumentoCandidato.value.trim());
  formData.append("id_requerimiento", idRequerimiento);

  try {
    const resp = await fetch("api/buscarDocumentoPreseleccionado", {
      method: "POST",
      body: formData,
    });
    const data = await resp.json();
    const alertaBusquedaDocumento = document.getElementById(
      "alertaBusquedaDocumento"
    );

    if (data.exitoso) {
      Swal.fire({
        title: "Éxito",
        icon: "success",
        text: "Candidato agregado con éxito",
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true,
      }).then(() => location.reload());
    } else {
      alertaBusquedaDocumento.innerHTML = `
        <div class="alert alert-warning" role="alert">
            Persona no encontrada.
        </div>
      `;
      idCandidatoExiste = "";
    }
  } catch (error) {
    console.error(error);
    idCandidatoExiste = "";
  } finally {
    btnBuscarCandidato.disabled = false;
    btnBuscarCandidato.textContent = "Buscar";
  }
});

const guardarNuevoCandidato = async () => {
  if (!camposValidosAgregarCandidato()) return;

  btnGuardarInformacionCandidato.disabled = true;
  btnGuardarInformacionCandidato.textContent = "Guardando...";

  try {
    const txtApellidosNombres = document.getElementById("txtApellidosNombres");
    const txtDocumento = document.getElementById("txtDocumento");
    const txtFechaDeNacimiento = document.getElementById(
      "txtFechaDeNacimiento"
    );
    const txtEdad = document.getElementById("txtEdad");
    const txtExactian = document.getElementById("txtExactian");
    const txtTelefono1 = document.getElementById("txtTelefono1");
    const txtTelefono2 = document.getElementById("txtTelefono2");
    const txtEmail = document.getElementById("txtEmail");
    const txtDepartamentoResidencia = document.getElementById(
      "txtDepartamentoResidencia"
    );

    let candidato = {
      apellidos_nombres: txtApellidosNombres.value,
      documento: txtDocumento.value,
      fecha_nacimiento: txtFechaDeNacimiento.value,
      edad: txtEdad.value,
      exactian: txtExactian.value,
      fecha_ingreso_ultimo_proyecto: txtExactian.value,
      fecha_cese_ultimo_proyecto: "",
      nombre_ultimo_proyecto: "",
      telefono_1: txtTelefono1.value,
      telefono_2: txtTelefono2.value,
      email: txtEmail.value,
      departamento_residencia: txtDepartamentoResidencia.value,
    };

    let formData = new FormData();
    formData.append("preseleccionado", JSON.stringify(candidato));
    formData.append("id_requerimiento", idRequerimiento);
    formData.append("id_preseleccionado", idCandidatoExiste);

    const resp = await fetch("api/guardarInformacionCandidato", {
      method: "POST",
      body: formData,
    });

    const data = await resp.json();
    if (data.exitoso) {
      Swal.fire({
        title: "Éxito",
        icon: "success",
        text: "Candidato agregado éxito.",
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true,
      }).then(() => location.reload());
    } else {
      console.error("No se pudo guardar");
    }
  } catch (error) {
    console.error(error);
  } finally {
    btnGuardarInformacionCandidato.disabled = false;
    btnGuardarInformacionCandidato.textContent = "Guardar";
  }
};

function camposValidosAgregarCandidato() {
  let campos = [
    "txtApellidosNombres",
    "txtDocumento",
    "txtFechaDeNacimiento",
    "txtEdad",
    // "txtExactian",
    // "txtTelefono1",
    // "txtTelefono2",
    "txtEmail",
    // "txtDepartamentoResidencia",
  ];
  let validacion = true;

  campos.forEach((campo) => {
    const elemento = document.getElementById(campo);
    if (elemento.value.trim() === "") {
      elemento.classList.add("is-invalid");
      elemento.classList.remove("border-dark");
      validacion = false;
    }
  });

  return validacion;
}

formularioNuevoCandidato.addEventListener("submit", async (e) => {
  e.preventDefault();

  guardarNuevoCandidato();
});

function cambiarIdRequerimiento(nuevoIdRequerimiento) {
  idRequerimiento = nuevoIdRequerimiento;
}

let cursos = [];
let certificaciones = [];

async function cargarDatosPreseleccionado(idPreseleccionado, idRequerimientoPreseleccionado) {
  idCandidatoExiste = idPreseleccionado;
  idRequerimiento = idRequerimientoPreseleccionado;

  try {
    Swal.fire({
      title: "Cargando datos...",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    const formData = new FormData();
    formData.append("id_preseleccionado", idPreseleccionado);

    const resp = await fetch("api/buscarDetallePreseleccionado", {
      method: "POST",
      body: formData,
    });

    const data = await resp.json();

    llenarCamposDatosGenerales(data.respuesta);
    llenarCamposCursosCertificados(data.respuesta.cursos, "curso");
    llenarCamposCursosCertificados(data.respuesta.certificados, "certificado");

    await llenarHistorialProyectos(data.respuesta.documento);

    Swal.close();
  } catch (error) {
    console.error(error);
    Swal.fire({
      icon: "error",
      title: "Error al cargar datos",
      text: "Intente nuevamente.",
    });
  }
}


const obtenerCursosCertificaciones = async () => {
  try {
    const resp = await fetch("api/obtenerCursosCertificaciones", {
      method: "GET",
    });
    const data = await resp.json();
    cursos = data.respuesta.filter((elemento) => {
      return elemento.tipo == "curso";
    });
    certificaciones = data.respuesta.filter((elemento) => {
      return elemento.tipo == "certificado";
    });
  } catch (error) {
    console.error(error);
  }
};

function llenarCamposDatosGenerales(candidato) {
  const txtApellidosNombres = document.getElementById(
    "txtApellidosNombresActualizar"
  );
  const txtDocumento = document.getElementById("txtDocumentoActualizar");
  const txtFechaDeNacimiento = document.getElementById(
    "txtFechaDeNacimientoActualizar"
  );
  const txtEdad = document.getElementById("txtEdadActualizar");
  const txtExactian = document.getElementById("txtExactianActualizar");
  const txtTelefono1 = document.getElementById("txtTelefono1Actualizar");
  const txtTelefono2 = document.getElementById("txtTelefono2Actualizar");
  const txtEmail = document.getElementById("txtEmailActualizar");
  const txtDepartamentoResidencia = document.getElementById(
    "txtDepartamentoResidenciaActualizar"
  );

  txtApellidosNombres.value = candidato.apellidos_nombres;
  txtDocumento.value = candidato.documento;
  txtFechaDeNacimiento.value = candidato.fecha_nacimiento;
  txtEdad.value = candidato.edad;
  txtExactian.value = candidato.exactian;
  txtTelefono1.value = candidato.telefono_1;
  txtTelefono2.value = candidato.telefono_2;
  txtEmail.value = candidato.email;
  txtDepartamentoResidencia.value = candidato.departamento_residencia;
}

function llenarCamposCursosCertificados(items, tipo) {
  if (items.length <= 0) return;

  const selectorTabla =
    tipo === "curso"
      ? "#tblCursosPreseleccionado"
      : "#tblCertificacionesPreseleccionados";
  const tbody = document.querySelector(`${selectorTabla} tbody`);
  const hoy = new Date();

  items.forEach((item) => {
    const tr = document.createElement("tr");
    const fechaFin = new Date(item.fecha_fin);
    const estado =
      fechaFin > hoy
        ? "<span class='badge text-bg-success'>Vigente</span>"
        : "<span class='badge text-bg-danger'>Caducó</span>";

    tr.innerHTML = `
      <td class="" data-idCurCerti='${item.id_curs_certi}'>${item.nombre}</td>
      <td class="">${item.fecha_inicio.split("-").reverse().join("-")}</td>
      <td class="">${item.fecha_fin.split("-").reverse().join("-")}</td>
      <td>${estado}</td>
      <td>
        <button class="btn btn-outline btn-outline-warning border-dark btn-editar">
          <i class="bi bi-pencil-fill"></i>
        </button>
        <button class="btn btn-outline btn-outline-danger border-dark">
          <i class="bi bi-trash-fill"></i>
        </button>
      </td>
    `;

    tr.dataset[tipo] = JSON.stringify(item);
    tbody.append(tr);
  });

  document.querySelectorAll(`${selectorTabla} .btn-editar`).forEach((btn) => {
    btn.addEventListener("click", function () {
      const fila = btn.closest("tr");
      const datos = JSON.parse(fila.dataset[tipo]);
      editarCursoCertificado(fila, datos, tipo);
    });
  });
}

function editarCursoCertificado(fila, item, tipo) {
  fila.innerHTML = `
    <td>
      <select class="form-select border-dark bg-light" readonly>
        <option value="${item.id_curs_certi}" selected>${item.nombre}</option>
      </select>
    </td>
    <td>
      <input type="date" class="form-control border-dark" value="${item.fecha_inicio}" />
    </td>
    <td>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-success btnGuardarActualizacion">Actualizar</button>
        <button class="btn btn-outline-danger btnCancelarActualizacion">Cancelar</button>
      </div>
    </td>
  `;

  const volverVistaNormal = (obj) => {
    fila.innerHTML = `
      <td class="" data-idCurCerti='${obj.id_curs_certi}'>${obj.nombre}</td>
      <td class="">${obj.fecha_inicio.split("-").reverse().join("-")}</td>
      <td class="">${obj.fecha_fin.split("-").reverse().join("-")}</td>
      <td>${
        new Date(obj.fecha_fin) > new Date()
          ? "<span class='badge text-bg-success'>Vigente</span>"
          : "<span class='badge text-bg-danger'>Caducó</span>"
      }</td>
      <td>
        <button class="btn btn-outline btn-outline-warning border-dark btn-editar">
          <i class="bi bi-pencil-fill"></i>
        </button>
        <button class="btn btn-outline btn-outline-danger border-dark">
          <i class="bi bi-trash-fill"></i>
        </button>
      </td>
    `;
    fila.dataset[tipo] = JSON.stringify(obj);
    fila.querySelector(".btn-editar").addEventListener("click", () => {
      editarCursoCertificado(fila, obj, tipo);
    });
  };

  fila
    .querySelector(".btnCancelarActualizacion")
    .addEventListener("click", () => {
      volverVistaNormal(item);
    });

  fila
    .querySelector(".btnGuardarActualizacion")
    .addEventListener("click", async () => {
      const nuevoItem = JSON.parse(fila.dataset[tipo]);

      try {
        const actualizado = {
          id_prese_curs_certi: nuevoItem.id_prese_curs_certi,
          id_preseleccionado: idCandidatoExiste,
          id_curs_certi: fila.children[0].children[0].value,
          fecha_inicio: fila.children[1].children[0].value,
        };

        let formData = new FormData();
        formData.append("pre_cur_cert", JSON.stringify(actualizado));

        const resp = await fetch("api/actualizarCurCertPreseleccionado", {
          method: "POST",
          body: formData,
        });

        const data = await resp.json();

        if (data.exitoso) {
          Swal.fire({
            title: "Éxito",
            icon: "success",
            text: `${
              tipo === "curso" ? "Curso" : "Certificado"
            } actualizado con éxito`,
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true,
          });

          const actualizadoItem = data.respuesta[0];
          volverVistaNormal(actualizadoItem);
        }
      } catch (error) {
        console.error(error);
      }
    });
}

async function llenarHistorialProyectos(documento) {
  try {
    let formData = new FormData();
    formData.append("documento", documento);
    const resp = await fetch("api/obtenerHistorialProyectos", {
      method: "POST",
      body: formData,
    });
    const data = await resp.json();
    const proyectos = data;
    if (proyectos.length > 0) {
      const tblProyectosPreseleccionado = document.querySelector(
        "#tblProyectosPreseleccionado tbody"
      );
      proyectos.forEach((proyecto) => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
          <td class="">${proyecto.des_cc ?? "--"}</td>
          <td class="">${proyecto.cargo ?? "--"}</td>
          <td class="">${proyecto.ingreso?.split(" ")[0] ?? "--"}</td>
          <td class="">${proyecto.cese?.split(" ")[0] ?? "--"}</td>
          <td class="">${proyecto.obs ?? "--"}</td>
          `;
        
        tblProyectosPreseleccionado.append(tr);
      });
    }
  } catch (error) {
    console.error(error);
  }
}

const documentoModal = document.getElementById("documentoModal");

documentoModal.addEventListener("hidden.bs.modal", function (event) {
  const alertaBusquedaDocumento = document.getElementById(
    "alertaBusquedaDocumento"
  );
  alertaBusquedaDocumento.innerHTML = "";
  txtDocumentoCandidato.value = "";
});

const requerimientoModal = document.getElementById("requerimientoModal");

requerimientoModal.addEventListener("hidden.bs.modal", function (event) {
  idCandidatoExiste = "";
  const txtApellidosNombres = document.getElementById("txtApellidosNombres");
  const txtDocumento = document.getElementById("txtDocumento");
  const txtFechaDeNacimiento = document.getElementById("txtFechaDeNacimiento");
  const txtEdad = document.getElementById("txtEdad");
  const txtExactian = document.getElementById("txtExactian");
  const txtTelefono1 = document.getElementById("txtTelefono1");
  const txtTelefono2 = document.getElementById("txtTelefono2");
  const txtEmail = document.getElementById("txtEmail");
  const txtDepartamentoResidencia = document.getElementById(
    "txtDepartamentoResidencia"
  );

  txtApellidosNombres.value = "";
  txtDocumento.value = "";
  txtFechaDeNacimiento.value = "";
  txtEdad.value = "";
  txtExactian.value = "";
  txtTelefono1.value = "";
  txtTelefono2.value = "";
  txtEmail.value = "";
  txtDepartamentoResidencia.value = "";
});

function camposValidosBuscarCandidato() {
  if (txtDocumentoCandidato.value.trim() === "") {
    txtDocumentoCandidato.classList.add("is-invalid");
    txtDocumentoCandidato.classList.remove("border-dark");
    return false;
  }

  return true;
}

txtDocumentoCandidato.addEventListener("input", () => {
  txtDocumentoCandidato.classList.remove("is-invalid");
  txtDocumentoCandidato.classList.add("border-dark");
});

function agregarNuevoCursoCertificado(tipo, listaItems) {
  const selectorTabla =
    tipo === "curso"
      ? "#tblCursosPreseleccionado"
      : "#tblCertificacionesPreseleccionados";
  const tbody = document.querySelector(`${selectorTabla} tbody`);

  const tr = document.createElement("tr");
  tr.innerHTML = `
    <td>
      <select class="form-select border-dark">
        <option selected disabled>Seleccionar una opción</option>
        ${listaItems
          .map(
            (item) =>
              `<option value="${item.id_curso_certificacion}">${item.nombre}</option>`
          )
          .join("")}
      </select>
    </td>
    <td>
      <input type="date" class="form-control border-dark" />
    </td>
    <td class="align-content-center">
      <div class="d-flex gap-2">
        <button class="btn btn-outline-success btnNuevoGuardar">Guardar</button>
        <button class="btn btn-outline-danger btnEliminarTemp">Cancelar</button>
      </div>
    </td>
  `;

  tbody.prepend(tr);

  tr.querySelector(".btnNuevoGuardar").addEventListener(
    "click",
    async function () {
      try {
        const pre_cur_cert = {
          id_preseleccionado: idCandidatoExiste,
          id_curs_certi: tr.children[0].children[0].value,
          fecha_inicio: tr.children[1].children[0].value,
        };

        const formData = new FormData();
        formData.append("pre_cur_cert", JSON.stringify(pre_cur_cert));

        const resp = await fetch("api/guardarCurCertPreseleccionado", {
          method: "POST",
          body: formData,
        });

        const data = await resp.json();

        if (data.exitoso) {
          Swal.fire({
            title: "Éxito",
            icon: "success",
            text: `${
              tipo === "curso" ? "Curso" : "Certificación"
            } agregado con éxito`,
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true,
          });

          const item = data.respuesta[0];
          const estado =
            new Date(item.fecha_fin) > new Date()
              ? "<span class='badge text-bg-success'>Vigente</span>"
              : "<span class='badge text-bg-danger'>Caducó</span>";

          tr.innerHTML = `
          <td class="" data-idCurCerti='${item.id_curs_certi}'>${
            item.nombre
          }</td>
          <td class="">${item.fecha_inicio.split("-").reverse().join("-")}</td>
          <td class="">${item.fecha_fin.split("-").reverse().join("-")}</td>
          <td>${estado}</td>
          <td>
            <button class="btn btn-outline btn-outline-warning border-dark btn-editar">
              <i class="bi bi-pencil-fill"></i>
            </button>
            <button class="btn btn-outline btn-outline-danger border-dark">
              <i class="bi bi-trash-fill"></i>
            </button>
          </td>
        `;
          tr.dataset[tipo] = JSON.stringify(item);

          tr.querySelector(".btn-editar").addEventListener("click", () => {
            editarCursoCertificado(tr, item, tipo);
          });
        }
      } catch (error) {
        console.error(error);
      }
    }
  );

  tr.querySelector(".btnEliminarTemp").addEventListener("click", () => {
    tr.remove();
  });
}

document.getElementById("requerimientoModal").addEventListener("click", (e) => {
  if (e.target.matches("#btnAgregarCurso")) {
    agregarNuevoCursoCertificado("curso", cursos);
  }

  if (e.target.matches("#btnAgregarCertificacion")) {
    agregarNuevoCursoCertificado("certificado", certificaciones);
  }

  if (
    e.target.closest("#tblCursosPreseleccionado") &&
    e.target.classList.contains("btnEliminarCurso")
  ) {
    e.target.closest("tr").remove();
  }

  if (
    e.target.closest("#tblCertificacionesPreseleccionados") &&
    e.target.classList.contains("btnEliminarCertificacion")
  ) {
    e.target.closest("tr").remove();
  }
});

let estadoInicialModal;

document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("requerimientoModal");
  estadoInicialModal = modal.innerHTML;
});

const modalElement = document.getElementById("requerimientoModal");

modalElement.addEventListener("hidden.bs.modal", function () {
  modalElement.innerHTML = estadoInicialModal;
});
