const btnBuscarCandidato = document.getElementById("btnBuscarCandidato");
const btnGuardarInformacionCandidato = document.getElementById(
  "btnGuardarInformacionCandidato"
);

let idCandidatoExiste = "";
let idRequerimiento = null;
const txtDocumentoCandidato = document.getElementById("txtDocumentoCandidato");

function cambiarIdRequerimiento(nuevoIdRequerimiento) {
  idRequerimiento = nuevoIdRequerimiento;
}

btnBuscarCandidato.addEventListener("click", async () => {
  if (!camposValidosBuscarCandidato()) return;

  btnBuscarCandidato.disabled = true;
  btnBuscarCandidato.textContent = "Guardando...";

  let formData = new FormData();
  formData.append("documento", txtDocumentoCandidato.value.trim());

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
      alertaBusquedaDocumento.innerHTML = `
        <div class="alert alert-primary" role="alert">
            Persona encontrada.
        </div>
      `;
      candidato = data.respuesta;
      idCandidatoExiste = data.respuesta.id_preseleccionado;
      llenarCamposCandidato(candidato);
    } else {
      alertaBusquedaDocumento.innerHTML = `
        <div class="alert alert-warning" role="alert">
            Persona no encontrada.
        </div>
      `;
      idCandidatoExiste = "";
      limpiarCamposCandidato();
    }
  } catch (error) {
    console.error(error);
    idCandidatoExiste = "";
  } finally {
    btnBuscarCandidato.disabled = false;
    btnBuscarCandidato.textContent = "Buscar";
  }
});

function llenarCamposCandidato(candidato) {
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

function limpiarCamposCandidato() {
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

btnGuardarInformacionCandidato.addEventListener("click", async () => {

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

    console.log(idCandidatoExiste);

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
