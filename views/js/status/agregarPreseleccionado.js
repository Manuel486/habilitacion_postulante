const btnBuscarPreseleccionado = document.getElementById(
  "btnBuscarPreseleccionado"
);
const btnGuardarInformacionPreseleccionado = document.getElementById(
  "btnGuardarInformacionPreseleccionado"
);
const btnAgregarCurso = document.getElementById("btnAgregarCurso");
const btnAgregarCertificacion = document.getElementById(
  "btnAgregarCertificacion"
);

let idPreseleccionado = "";
let idRequerimiento = null;
const txtDocumentoPreseleccionado = document.getElementById(
  "txtDocumentoPreseleccionado"
);

btnBuscarPreseleccionado.addEventListener("click", async () => {
  if (!camposValidosBuscarPreseleccionado()) return;

  btnBuscarPreseleccionado.disabled = true;
  btnBuscarPreseleccionado.textContent = "Agregando...";

  let formData = new FormData();
  formData.append("documento", txtDocumentoPreseleccionado.value.trim());
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
        text: data.mensaje,
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true,
      }).then(() => location.reload());
    } else {
      alertaBusquedaDocumento.innerHTML = `
        <div class="alert alert-warning" role="alert">
          ${data.mensaje}
        </div>
      `;
      idPreseleccionado = "";
    }
  } catch (error) {
    console.error(error);
    idPreseleccionado = "";
  } finally {
    btnBuscarPreseleccionado.disabled = false;
    btnBuscarPreseleccionado.textContent = "Agregar";
  }
});

const guardarNuevoPreseleccionado = async () => {
  if (!camposValidosAgregarPreseleccionado()) return;

  btnGuardarInformacionPreseleccionado.disabled = true;
  btnGuardarInformacionPreseleccionado.textContent = "Guardando...";

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

    let preseleecionado = {
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
    formData.append("preseleccionado", JSON.stringify(preseleecionado));
    formData.append("id_requerimiento", idRequerimiento);
    formData.append("id_preseleccionado", idPreseleccionado);

    const resp = await fetch("api/guardarInformacionPreseleccionado", {
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
      Swal.fire({
        icon: "error",
        title: "Error al cargar datos",
        text: data.mensaje,
      });
    }
  } catch (error) {
    console.error(error);
  } finally {
    btnGuardarInformacionPreseleccionado.disabled = false;
    btnGuardarInformacionPreseleccionado.textContent = "Guardar";
  }
};

function camposValidosAgregarPreseleccionado() {
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

formularioNuevoPreseleccionado.addEventListener("submit", async (e) => {
  e.preventDefault();

  guardarNuevoPreseleccionado();
});

function cambiarIdRequerimiento(nuevoIdRequerimiento) {
  idRequerimiento = nuevoIdRequerimiento;
}

let cursos = [];
let certificaciones = [];

async function cargarDatosPreseleccionado(
  nuevoIdPreseleccionado,
  idRequerimientoPreseleccionado
) {
  idPreseleccionado = nuevoIdPreseleccionado;
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
    formData.append("id_requerimiento", idRequerimiento);

    const resp = await fetch("api/buscarDetallePreseleccionado", {
      method: "POST",
      body: formData,
    });

    const data = await resp.json();

    llenarCamposAlertasCurCert(data.respuesta.alertas_cur_cert);
    llenarCamposDatosGenerales(data.respuesta);
    llenarInformacionPreReque(data.respuesta.preseleccionado_requerimiento[0]);
    llenarCamposCursosCertificados(data.respuesta.cursos, "curso");
    llenarCamposCursosCertificados(data.respuesta.certificados, "certificado");
    llenarInformacionMedica(data.respuesta.preseleccionado_requerimiento[0]);
    
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

obtenerCursosCertificaciones();

function llenarCamposAlertasCurCert(alertas_cur_cert) {
  const alertasPreseleccionado = document.getElementById(
    "alertasPreseleccionado"
  );
  alertasPreseleccionado.innerHTML = "";

  alertas = alertas_cur_cert.filter(
    (alerta) => alerta.estado == "caduco" || alerta.estado == "cerca"
  );

  if (alertas.length < 0) return;

  alertas = alertas.slice(0, 5);
  alertas.every((alerta, i) => {
    const div = document.createElement("div");
    div.innerHTML = `
      <div class="alert alert-${
        alerta.estado == "caduco" ? "danger" : "warning"
      } fw-6" role="alert">
          ${
            alerta.estado == "caduco"
              ? `El ${alerta.nombre} ya caducó`
              : `El ${alerta.nombre} esta cerca a caducar`
          }
      </div>
    `;
    alertasPreseleccionado.append(div);
    return true;
  });
}

function llenarCamposDatosGenerales(preseleccionado) {
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

  txtApellidosNombres.value = preseleccionado.apellidos_nombres;
  txtDocumento.value = preseleccionado.documento;
  txtFechaDeNacimiento.value = preseleccionado.fecha_nacimiento;
  txtEdad.value = preseleccionado.edad;
  txtExactian.value = preseleccionado.exactian;
  txtTelefono1.value = preseleccionado.telefono_1;
  txtTelefono2.value = preseleccionado.telefono_2;
  txtEmail.value = preseleccionado.email;
  txtDepartamentoResidencia.value = preseleccionado.departamento_residencia;
}

function llenarInformacionPreReque(pre_reque) {
  const txtPoliza = document.getElementById("txtPoliza");
  const txtViabilidad = document.getElementById("txtViabilidad");
  const txtObservacion = document.getElementById("txtObservacion");
  const txtIngresoObra = document.getElementById("txtIngresoObra");
  const txtEstado = document.getElementById("txtEstado");
  const txtObservacion2 = document.getElementById("txtObservacion2");
  const txtAlfa = document.getElementById("txtAlfa");
  const txtViabilidad2 = document.getElementById("txtViabilidad2");
  const txtRRHH = document.getElementById("txtRRHH");

  txtPoliza.value = pre_reque.poliza;
  txtViabilidad.value = pre_reque.viabilidad;
  txtObservacion.value = pre_reque.observacion;
  txtIngresoObra.value = pre_reque.ingreso_obra;
  txtEstado.value = pre_reque.estado;
  txtObservacion2.value = pre_reque.observacion2;
  txtAlfa.value = pre_reque.alfa;
  txtViabilidad2.value = pre_reque.viabilidad2;
  txtRRHH.value = pre_reque.rrhh;
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
        <button class="btn btn-outline btn-outline-danger border-dark btn-eliminar">
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

  document.querySelectorAll(`${selectorTabla} .btn-eliminar`).forEach((btn) => {
    btn.addEventListener("click", function () {
      const fila = btn.closest("tr");
      const datos = JSON.parse(fila.dataset[tipo]);
      eliminarCursoCertificado(fila, datos, tipo, btn);
    });
  });
}

function llenarInformacionMedica(informacion_medica) {
  const txt4taVacuna = document.getElementById("txt4taVacuna");
  const txtFechaExamenMedico = document.getElementById("txtFechaExamenMedico");
  const sltClinica = document.getElementById("sltClinica");
  const txtResultado = document.getElementById("txtResultado");
  const txtPaseMedico = document.getElementById("txtPaseMedico");
  const txtPM = document.getElementById("txtPM");
  const txtInformeMedico = document.getElementById("txtInformeMedico");

  txt4taVacuna.value = informacion_medica.cuarta_vacuna;
  txtFechaExamenMedico.value = informacion_medica.fecha_examen_medico;
  if(informacion_medica.clinica !== null){
    sltClinica.value = informacion_medica.clinica;
  }
  txtResultado.value = informacion_medica.resultado;
  txtPaseMedico.value = informacion_medica.pase_medico;
  txtPM.value = informacion_medica.pm;
  txtInformeMedico.value = informacion_medica.informe_medico;
}

async function eliminarCursoCertificado(fila, item, tipo, btnEliminar) {
  Swal.fire({
    icon: "warning",
    title: "Advertencia",
    text: "¿Está seguro de eliminarlo?",
    showCancelButton: true,
    confirmButtonText: "Sí",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        btnEliminar.disabled = true;
        btnEliminar.textContent = "Eliminando...";

        let formData = new FormData();
        formData.append("id_prese_curs_certi", item.id_prese_curs_certi);

        const resp = await fetch("api/eliminarCursCertPreseleccionado", {
          method: "POST",
          body: formData,
        });
        const data = await resp.json();

        if (data.exitoso) {
          Swal.fire({
            icon: "success",
            title: "Éxito",
            text: data.mensaje,
          });
          fila.remove();
        } else {
          Swal.fire({
            icon: "error",
            title: "Error al realizar la acción",
            text: data.mensaje,
          });
        }
      } catch (error) {
        console.error(error);
        Swal.fire({
          icon: "error",
          title: "Error al realizar la acción",
          text: data.mensaje,
        });
      } finally {
        btnEliminar.disabled = false;
        btnEliminar.textContent = "Eliminar";
      }
    }
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
        <button class="btn btn-outline btn-outline-danger border-dark btn-eliminar">
          <i class="bi bi-trash-fill"></i>
        </button>
      </td>
    `;
    fila.dataset[tipo] = JSON.stringify(obj);
    fila.querySelector(".btn-editar").addEventListener("click", () => {
      editarCursoCertificado(fila, obj, tipo);
    });
    fila.querySelector(".btn-eliminar").addEventListener("click", () => {
      eliminarCursoCertificado(
        fila,
        obj,
        tipo,
        fila.querySelector(".btn-eliminar")
      );
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
          id_preseleccionado: idPreseleccionado,
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
          <td class="">${
            proyecto.ingreso?.split(" ")[0].split("-").reverse().join("-") ??
            "--"
          }</td>
          <td class="">${
            proyecto.cese?.split(" ")[0].split("-").reverse().join("-") ?? "--"
          }</td>
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
  txtDocumentoPreseleccionado.value = "";
});

const requerimientoModal = document.getElementById("requerimientoModal");

requerimientoModal.addEventListener("hidden.bs.modal", function (event) {
  idPreseleccionado = "";
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

function camposValidosBuscarPreseleccionado() {
  if (txtDocumentoPreseleccionado.value.trim() === "") {
    txtDocumentoPreseleccionado.classList.add("is-invalid");
    txtDocumentoPreseleccionado.classList.remove("border-dark");
    return false;
  }

  return true;
}

txtDocumentoPreseleccionado.addEventListener("input", () => {
  txtDocumentoPreseleccionado.classList.remove("is-invalid");
  txtDocumentoPreseleccionado.classList.add("border-dark");
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
      const btnNuevoGuardar = tr.querySelector(".btnNuevoGuardar");

      const id_curs_certi = tr.children[0].children[0].value;
      const fecha_inicio = tr.children[1].children[0].value;

      if (id_curs_certi == "" || fecha_inicio == "") {
        Swal.fire({
          title: "Error",
          text: `Debe seleccionar un ${
            tipo === "curso" ? "curso" : "certificado"
          } y poner su fecha de inicio`,
          icon: "error",
        });
        return;
      }

      btnNuevoGuardar.disabled = true;
      btnNuevoGuardar.textContent = "Guardando...";

      try {
        const pre_cur_cert = {
          id_preseleccionado: idPreseleccionado,
          id_curs_certi: id_curs_certi,
          fecha_inicio: fecha_inicio,
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
        btnNuevoGuardar.disabled = false;
        btnNuevoGuardar.textContent = "Guardar";
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

  if (e.target.matches("#btnActualizarInforDatGenerales")) {
    guardarInformacionPreseleRequerimiento();
  }

  if (e.target.matches("#btnActualizarInforMedica")) {
    guardarInforemacionPreseleMedica();
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

async function eliminarPreseDelReque(btn, idPreseleccionado, idRequerimiento) {
  const btnEliminar = document.getElementById("eliminarPreReque");
  const fila = btn.closest(".filaPreseleecionado");

  Swal.fire({
    icon: "warning",
    title: "Advertencia",
    text: "¿Está seguro de eliminarlo?",
    showCancelButton: true,
    confirmButtonText: "Sí",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        btnEliminar.disabled = true;
        let formData = new FormData();
        formData.append("id_preseleccionado", idPreseleccionado);
        formData.append("id_requerimiento", idRequerimiento);
        const resp = await fetch("api/eliminarPreseRequer", {
          method: "POST",
          body: formData,
        });
        const data = await resp.json();

        if (data.exitoso) {
          Swal.fire({
            icon: "success",
            title: "Éxito",
            text: data.mensaje,
          });
          fila.remove();
        } else {
          Swal.fire({
            icon: "error",
            title: "Error al realizar la acción",
            text: data.mensaje,
          });
        }
      } catch (error) {
        console.error(error);
        Swal.fire({
          icon: "error",
          title: "Error al realizar la acción",
          text: data.mensaje,
        });
      } finally {
        btnEliminar.disabled = false;
      }
    }
  });
}

async function guardarInformacionPreseleRequerimiento() {
  if (!camposValidosActualizarPreseleccionado()) return;

  const btnActualizarInforDatGenerales = document.getElementById(
    "btnActualizarInforDatGenerales"
  );

  btnActualizarInforDatGenerales.disabled = true;
  btnActualizarInforDatGenerales.textContent = "Guardando...";

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

  let preseleccionado = {
    id_preseleccionado: idPreseleccionado,
    apellidos_nombres: txtApellidosNombres.value,
    documento: txtDocumento.value,
    fecha_nacimiento: txtFechaDeNacimiento.value,
    edad: txtEdad.value,
    exactian: txtExactian.value,
    // fecha_ingreso_ultimo_proyecto: "",
    // fecha_cese_ultimo_proyecto: "",
    // nombre_ultimo_proyecto: "",
    telefono_1: txtTelefono1.value,
    telefono_2: txtTelefono2.value,
    email: txtEmail.value,
    departamento_residencia: txtDepartamentoResidencia.value,
  };

  let preseleccionado_requerimiento = {
    id_reque_proy: idRequerimiento,
    id_preseleccionado: idPreseleccionado,
    poliza: txtPoliza.value,
    viabilidad: txtViabilidad.value,
    observacion: txtObservacion.value,
    ingreso_obra: txtIngresoObra.value,
    estado: txtEstado.value,
    observacion2: txtObservacion2.value,
    alfa: txtAlfa.value,
    viabilidad2: txtViabilidad2.value,
    rrhh: txtRRHH.value,
  };

  try {
    let formData = new FormData();
    formData.append("preseleccionado", JSON.stringify(preseleccionado));
    formData.append(
      "preseleccionado_requerimiento",
      JSON.stringify(preseleccionado_requerimiento)
    );
    const resp = await fetch("api/actualizarInformacionPreReque", {
      method: "POST",
      body: formData,
    });
    const data = await resp.json();
    if (data.exitoso) {
      Swal.fire({
        icon: "success",
        title: "Éxito",
        text: data.mensaje,
      });
      // actualizarInformacionPreseleccionado(preseleecionado);
    } else {
      Swal.fire({
        icon: "error",
        title: "Error al realizar la acción",
        text: data.mensaje,
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error al realizar la acción",
      text: data.mensaje,
    });
  } finally {
    btnActualizarInforDatGenerales.disabled = false;
    btnActualizarInforDatGenerales.innerHTML = `
      <i class="bi bi-floppy-fill"></i> Guardar información`;
  }
}

async function guardarInforemacionPreseleMedica() {
  const btnActualizarInforMedica = document.getElementById(
    "btnActualizarInforMedica"
  );

  btnActualizarInforMedica.disabled = true;
  btnActualizarInforMedica.textContent = "Guardando...";

  const txt4taVacuna = document.getElementById("txt4taVacuna");
  const txtFechaExamenMedico = document.getElementById("txtFechaExamenMedico");
  const sltClinica = document.getElementById("sltClinica");
  const txtResultado = document.getElementById("txtResultado");
  const txtPaseMedico = document.getElementById("txtPaseMedico");
  const txtPM = document.getElementById("txtPM");
  const txtInformeMedico = document.getElementById("txtInformeMedico");

  let informacionMedica = {
    id_reque_proy: idRequerimiento,
    id_preseleccionado: idPreseleccionado,
    cuarta_vacuna: txt4taVacuna.value,
    fecha_examen_medico: txtFechaExamenMedico.value,
    clinica: sltClinica.value,
    resultado: txtResultado.value,
    pase_medico: txtPaseMedico.value,
    pm: txtPM.value,
    informe_medico: txtInformeMedico.value,
  };

  try {
    let formData = new FormData();
    formData.append("informacion_medica", JSON.stringify(informacionMedica));
    const resp = await fetch("api/actualizarInformacionMedica", {
      method: "POST",
      body: formData,
    });
    const data = await resp.json();
    if (data.exitoso) {
      Swal.fire({
        icon: "success",
        title: "Éxito",
        text: data.mensaje,
      });
    } else {
      Swal.fire({
        icon: "error",
        title: "Error al realizar la acción",
        text: data.mensaje,
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error al realizar la acción",
      text: data.mensaje,
    });
  } finally {
    btnActualizarInforMedica.disabled = false;
    btnActualizarInforMedica.innerHTML = `
      <i class="bi bi-floppy-fill"></i> Guardar información`;
  }
}

function camposValidosActualizarPreseleccionado() {
  let campos = [
    "txtApellidosNombresActualizar",
    "txtDocumentoActualizar",
    "txtFechaDeNacimientoActualizar",
    "txtEdadActualizar",
    // "txtExactianActualizar",
    // "txtTelefono1Actualizar",
    // "txtTelefono2Actualizar",
    "txtEmailActualizar",
    // "txtDepartamentoResidenciaActualizar",
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

let estadoInicialModal;

document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("requerimientoModal");
  estadoInicialModal = modal.innerHTML;
});

const modalElement = document.getElementById("requerimientoModal");

modalElement.addEventListener("hidden.bs.modal", function () {
  modalElement.innerHTML = estadoInicialModal;
});
