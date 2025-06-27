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

let cursos = [];
let certificaciones = [];

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

function cambiarIdRequerimiento(nuevoIdRequerimiento) {
  idRequerimiento = nuevoIdRequerimiento;
}

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
      // alertaBusquedaDocumento.innerHTML = `
      //   <div class="alert alert-primary" role="alert">
      //       Persona encontrada y agregada al requerimiento.
      //   </div>
      // `;
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

function llenarCamposCursos(cursos) {
  if (cursos.length <= 0) return;

  const tblCursosPreseleccionado = document.querySelector(
    "#tblCursosPreseleccionado tbody"
  );
  const hoy = new Date();

  cursos.forEach((curso) => {
    const tr = document.createElement("tr");
    const fechaInicio = new Date(curso.fecha_inicio);
    const fechaFin = new Date(curso.fecha_fin);
    let estado = "";

    estado =
      fechaFin > hoy
        ? "<span class='badge text-bg-success'>Vigente</span>"
        : "<span class='badge text-bg-danger'>Caducó</span>";

    tr.innerHTML = `
      <td class="" data-idCurCerti='${curso.id_curs_certi}'>${curso.nombre}</td>
      <td class="">${curso.fecha_inicio}</td>
      <td class="">${curso.fecha_fin}</td>
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

    tr.dataset.curso = JSON.stringify(curso);

    tblCursosPreseleccionado.append(tr);
  });

  document.querySelectorAll(".btn-editar").forEach((btn) => {
    btn.addEventListener("click", function () {
      const fila = btn.closest("tr");
      const curso = JSON.parse(fila.dataset.curso);
      editarCurso(fila, curso);
    });
  });
}

function llenarCamposCertificados(certificados) {
  if (certificados.length <= 0) return;

  const tblCertificacionesPreseleccionados = document.querySelector(
    "#tblCertificacionesPreseleccionados tbody"
  );
  const hoy = new Date();

  certificados.forEach((certificado) => {
    const tr = document.createElement("tr");
    const fechaInicio = new Date(certificado.fecha_inicio);
    const fechaFin = new Date(certificado.fecha_fin);
    let estado = "";

    estado =
      fechaFin > hoy
        ? "<span class='badge text-bg-success'>Vigente</span>"
        : "<span class='badge text-bg-danger'>Caducó</span>";

    tr.innerHTML = `
      <td class="" data-idCurCerti='${certificado.id_curs_certi}'>${certificado.nombre}</td>
      <td class="">${certificado.fecha_inicio}</td>
      <td class="">${certificado.fecha_fin}</td>
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

    tr.dataset.certificado = JSON.stringify(certificado);

    tblCertificacionesPreseleccionados.append(tr);
  });

  document.querySelectorAll(".btn-editar").forEach((btn) => {
    btn.addEventListener("click", function () {
      const fila = btn.closest("tr");
      const certificado = JSON.parse(fila.dataset.certificado);
      editarCurso(fila, certificado);
    });
  });
}

function editarCurso(fila, curso) {
  fila.innerHTML = `
    <td class="">
        <select class="form-select border-dark bg-light" readonly>
          <option value="${curso.id_curs_certi}" selected>${curso.nombre} 1111</option>
        </select>
    </td>
    <td class="">
        <input type="date" class="form-control border-dark" value="${curso.fecha_inicio}" />
    </td>
    <td>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-success btnGuardarActualizacion">Actualizar</button>
          <button class="btn btn-outline-danger btnCancelarActualizacion">Cancelar</button>
        </div>
    </td>
  `;

  fila
    .querySelector(".btnCancelarActualizacion")
    .addEventListener("click", function () {
      fila.innerHTML = `
      <td class="" data-idCurCerti='${curso.id_curs_certi}'>${curso.nombre}</td>
      <td class="">${curso.fecha_inicio}</td>
      <td class="">${curso.fecha_fin}</td>
      <td>${
        new Date(curso.fecha_fin) > new Date()
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

      fila.querySelector(".btn-editar").addEventListener("click", function () {
        editarCurso(fila, curso);
      });
    });

  fila
    .querySelector(".btnGuardarActualizacion")
    .addEventListener("click", async function () {
      let curso = JSON.parse(fila.dataset.curso)
      try {
        let pre_cur_cert = {
          id_prese_curs_certi: curso.id_prese_curs_certi,
          id_preseleccionado: idCandidatoExiste,
          id_curs_certi: fila.children[0].children[0].value,
          fecha_inicio: fila.children[1].children[0].value,
        };

        let formData = new FormData();
        formData.append("pre_cur_cert", JSON.stringify(pre_cur_cert));

        const resp = await fetch("api/actualizarCurCertPreseleccionado", {
          method: "POST",
          body: formData,
        });
        const data = await resp.json();

        if (data.exitoso) {
          Swal.fire({
            title: "Éxito",
            icon: "success",
            text: "Curso actualizado con éxito",
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true,
          });
          const cursoPreseleccionado = data.respuesta[0];
          fila.innerHTML = `
            <td class="">${cursoPreseleccionado.nombre}</td>
            <td class="">${cursoPreseleccionado.fecha_inicio}</td>
            <td class="">${cursoPreseleccionado.fecha_fin}</td>
            <td>${
              new Date(cursoPreseleccionado.fecha_fin) > new Date()
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

          fila.dataset.curso = JSON.stringify(cursoPreseleccionado);

          fila
            .querySelector(".btn-editar")
            .addEventListener("click", function () {
              editarCurso(fila, curso);
            });
        }
      } catch (error) {
        console.error(error);
      }

    });
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

formularioNuevoCandidato.addEventListener("submit", async (e) => {
  e.preventDefault();

  guardarNuevoCandidato();
});

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

async function cargarDatosPreseleccionado(
  idPreseleccionado,
  idRequerimientoPreseleccionado
) {
  idCandidatoExiste = idPreseleccionado;
  idRequerimiento = idRequerimientoPreseleccionado;
  try {
    let formData = new FormData();
    formData.append("id_preseleccionado", idPreseleccionado);
    const resp = await fetch("api/buscarDetallePreseleccionado", {
      method: "POST",
      body: formData,
    });
    const data = await resp.json();
    llenarCamposDatosGenerales(data.respuesta);
    llenarCamposCursos(data.respuesta.cursos);
    llenarCamposCertificados(data.respuesta.certificados);
    obtenerCursosCertificaciones();
  } catch (error) {
    console.error(error);
  }
}

document
  .getElementById("requerimientoModal")
  .addEventListener("click", function (e) {
    if (e.target && e.target.matches("#btnAgregarCurso")) {
      const tblCursosPreseleccionado = document.querySelector(
        "#tblCursosPreseleccionado tbody"
      );

      const tr = document.createElement("tr");
      tr.innerHTML = `
        <tr>
            <td class="">
                <select class="form-select border-dark">
                  <option selected disabled>Seleccionar un curso</option>
                  ${cursos.map(
                    (curso) =>
                      `<option value="${curso.id_curso_certificacion}">${curso.nombre}</option>`
                  )}
                </select>
            </td>
            <td class="">
                <input type="date"
                    class="form-control  border-dark" />
            </td>
            <td class="align-content-center">
                <div class="d-flex gap-2">
                  <button class="btn btn-outline-success btnNuevoCursoGuardar">Guardar</button>
                  <button class="btn btn-outline-danger btnEliminarCurso">Cancelar</button>
                </div>
            </td>
        </tr>
      `;

      tblCursosPreseleccionado.prepend(tr);

      tr.querySelector(".btnNuevoCursoGuardar").addEventListener(
        "click",
        async function () {
          try {
            let pre_cur_cert = {
              id_preseleccionado: idCandidatoExiste,
              id_curs_certi: tr.children[0].children[0].value,
              fecha_inicio: tr.children[1].children[0].value,
            };
            let formData = new FormData();
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
                text: "Curso agregado con éxito",
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true,
              });
              const cursoPreseleccionado = data.respuesta[0];
              tr.innerHTML = `
                <td class="" data-idCurCerti='${cursoPreseleccionado.id_curs_certi}'>${
                  cursoPreseleccionado.nombre
                }</td>
                <td class="">${cursoPreseleccionado.fecha_inicio}</td>
                <td class="">${cursoPreseleccionado.fecha_fin}</td>
                <td>${
                  new Date(cursoPreseleccionado.fecha_fin) > new Date()
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
              tr.dataset.curso = JSON.stringify(cursoPreseleccionado);

              tr.querySelector(".btn-editar").addEventListener(
                "click",
                function () {
                  editarCurso(tr, cursoPreseleccionado);
                }
              );
            }
          } catch (error) {
            console.error(error);
          }
        }
      );
    }

    document
      .querySelector("#tblCursosPreseleccionado tbody")
      .addEventListener("click", (e) => {
        if (e.target.classList.contains("btnEliminarCurso")) {
          const fila = e.target.closest("tr");
          fila.remove();
        }
      });
  });

document
  .getElementById("requerimientoModal")
  .addEventListener("click", function (e) {
    if (e.target && e.target.matches("#btnAgregarCertificacion")) {
      const tblCertificacionesPreseleccionado = document.querySelector(
        "#tblCertificacionesPreseleccionados tbody"
      );

      const tr = document.createElement("tr");
      tr.innerHTML = `
        <tr>
            <td class="">
                <select class="form-select border-dark">
                  <option selected disabled>Seleccionar un curso</option>
                  ${certificaciones.map(
                    (curso) =>
                      `<option value="${curso.id_curso_certificacion}">${curso.nombre}</option>`
                  )}
                </select>
            </td>
            <td class="">
                <input type="date"
                    class="form-control  border-dark" />
            </td>
            <td class="align-content-center">
                <i class="bi bi-trash3-fill text-danger btnEliminarCertificacion cursor-pointer"></i>
            </td>
        </tr>
      `;

      tblCertificacionesPreseleccionado.prepend(tr);

      document
        .querySelector("#tblCertificacionesPreseleccionados tbody")
        .addEventListener("click", (e) => {
          if (e.target.classList.contains("btnEliminarCertificacion")) {
            const fila = e.target.closest("tr");
            fila.remove();
          }
        });
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
