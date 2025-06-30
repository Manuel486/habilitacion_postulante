<div class="modal" id="documentoModal" tabindex="-1" aria-labelledby="documentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header encabezado text-white">
                <h5 class="modal-title" id="documentoModalLabel">
                    Buscar candidato
                </h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row row-gap-3">
                    <div class="col-12">
                        <label for="" class="form-label">Obtener información de la persona si ya se encuentra
                            registrada</label>
                    </div>
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control  border-dark"
                                placeholder="Ingresar documento de la persona" id="txtDocumentoPreseleccionado" />
                            <button class="btn btn-outline-dark d-flex gap-2" id="btnBuscarPreseleccionado">
                                <i class="bi bi-plus-circle-fill"></i> Agregar
                            </button>
                        </div>
                    </div>
                    <div class="col-12 p-2" id="alertaBusquedaDocumento">


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" data-bs-target="#formularioPreseleccionadoModal"
                    data-bs-toggle="modal">
                    Registrar nuevo candidato
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="formularioPreseleccionadoModal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="formularioPreseleccionadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header encabezado text-white">
                <h5 class="modal-title" id="formularioPreseleccionadoModalLabel">
                    Nuevo candidato
                </h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formularioNuevoPreseleccionado">
                    <div class="row row-gap-3">
                        <div class="col-lg-4">
                            <label class="form-label" for="txtApellidosNombres">Apellidos y nombres <span
                                    class="text-danger fw-bold">*</span></label>
                            <input class="form-control border-dark" placeholder="Apellidos y nombres" type="text"
                                id="txtApellidosNombres" />
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label" for="txtDocumento">Documento <span
                                    class="text-danger fw-bold">*</span></label>
                            <input class="form-control  border-dark" placeholder="DNI" type="text" id="txtDocumento" />
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="txtFechaDeNacimiento">Fecha de nacimiento <span
                                    class="text-danger fw-bold">*</span></label>
                            <input class="form-control  border-dark" placeholder="" type="date"
                                id="txtFechaDeNacimiento" />
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="txtEdad">Edad <span
                                    class="text-danger fw-bold">*</span></label>
                            <input class="form-control  border-dark" placeholder="Edad" type="number" id="txtEdad" />
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label" for="txtExactian">Exactian</label>
                            <input class="form-control  border-dark" placeholder="Exactian" type="text"
                                id="txtExactian" />
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label" for="txtTelefono1">Teléfono 1</label>
                            <input class="form-control  border-dark" placeholder="Teléfono 1" type="text"
                                id="txtTelefono1" />
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label" for="txtTelefono2">Teléfono 2</label>
                            <input class="form-control  border-dark" placeholder="Teléfono 2" type="text"
                                id="txtTelefono2" />
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="txtEmail">Email <span
                                    class="text-danger fw-bold">*</span></label>
                            <input class="form-control  border-dark" placeholder="Ingresar email" type="email"
                                id="txtEmail" />
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="txtDepartamentoResidencia">Departamento de
                                residencia</label>
                            <input class="form-control  border-dark" placeholder="Ingresar departamento" type="text"
                                id="txtDepartamentoResidencia" />
                        </div>
                    </div>

                    <div class="my-4 d-flex justify-content-end w-100">
                        <button type="submit" class="btn btn-success" id="btnGuardarInformacionPreseleccionado">
                            <i class="bi bi-floppy"></i>
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="requerimientoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="requerimientoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header encabezado text-white">
                <h5 class="modal-title" id="requerimientoModalLabel">
                    Actualizar datos
                </h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row row-cols-4 row-gap-1" id="alertasPreseleccionado">
                </div>

                <!-- <div class="encabezado rounded"></div> -->

                <!-- NAV DE PESTAÑAS -->
                <ul class="nav nav-tabs my-3" id="myTab" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="datos-tab" data-bs-toggle="tab" data-bs-target="#datos"
                            type="button" role="tab">
                            Datos Generales
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="proyectos-tab" data-bs-toggle="tab" data-bs-target="#proyectos"
                            type="button" role="tab">
                            Proyectos
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="mx-1 nav-link" id="certificados-tab" data-bs-toggle="tab"
                            data-bs-target="#certificados" type="button" role="tab">
                            Certificaciones
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="mx-1 nav-link" id="cursos-tab" data-bs-toggle="tab" data-bs-target="#cursos"
                            type="button" role="tab">
                            Cursos
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="mx-1 nav-link" id="informacion_medica-tab" data-bs-toggle="tab"
                            data-bs-target="#informacion_medica" type="button" role="tab">
                            Información médica
                        </button>
                    </li>
                </ul>

                <!-- CONTENIDO DE CADA TAB -->

                <div class="tab-content mt-3" id="">
                    <div class="tab-pane fade show active" id="datos" role="tabpanel">
                        <div class="row row-gap-3">

                            <div class="col-lg-4">
                                <label class="form-label" for="txtApellidosNombresActualizar">Apellidos y
                                    nombres</label>
                                <input class="form-control border-dark" placeholder="Apellidos y nombres" type="text"
                                    id="txtApellidosNombresActualizar" />
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label" for="txtDocumentoActualizar">Documento</label>
                                <input class="form-control  border-dark" placeholder="DNI" type="text"
                                    id="txtDocumentoActualizar" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="txtFechaDeNacimientoActualizar">Fecha de
                                    nacimiento</label>
                                <input class="form-control  border-dark" placeholder="" type="date"
                                    id="txtFechaDeNacimientoActualizar" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="txtEdadActualizar">Edad</label>
                                <input class="form-control  border-dark" placeholder="Edad" type="number"
                                    id="txtEdadActualizar" />
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label" for="txtExactianActualizar">Exactian</label>
                                <input class="form-control  border-dark" placeholder="Exactian" type="text"
                                    id="txtExactianActualizar" />
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label" for="txtTelefono1Actualizar">Teléfono 1</label>
                                <input class="form-control  border-dark" placeholder="Teléfono 1" type="text"
                                    id="txtTelefono1Actualizar" />
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label" for="txtTelefono2Actualizar">Teléfono 2</label>
                                <input class="form-control  border-dark" placeholder="Teléfono 2" type="text"
                                    id="txtTelefono2Actualizar" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="txtEmailActualizar">Email</label>
                                <input class="form-control  border-dark" placeholder="Ingresar email" type="email"
                                    id="txtEmailActualizar" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="txtDepartamentoResidenciaActualizar">Departamento de
                                    residencia</label>
                                <input class="form-control  border-dark" placeholder="Ingresar departamento" type="text"
                                    id="txtDepartamentoResidenciaActualizar" />
                            </div>

                            <div class="col-lg-12">
                                <hr />
                            </div>

                            <div class="col-lg-3">
                                <label for="">POLIZA</label>
                                <input class="form-control  border-dark" type="text" placeholder="Ingresar estado"
                                    id="txtPoliza" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">VIABILIDAD</label>
                                <input class="form-control  border-dark" type="text" placeholder="Ingresar estado"
                                    id="txtViabilidad" />
                            </div>
                            <div class="col-lg-6">
                                <label for="">Observación</label>
                                <input class="form-control  border-dark" type="text" placeholder="Ingresar observación"
                                    id="txtObservacion" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">Ingreso a obra</label>
                                <input class="form-control  border-dark" type="date" id="txtIngresoObra" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">Estado</label>
                                <input class="form-control  border-dark" type="text" placeholder="Ingresar estado"
                                    id="txtEstado" />
                            </div>
                            <div class="col-lg-6">
                                <label for="">Observación 2</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar observación 2" id="txtObservacion2" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">ALFA</label>
                                <input class="form-control  border-dark" type="text" placeholder="Ingresar Alfa"
                                    id="txtAlfa" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">VIABILIDAD2</label>
                                <input class="form-control  border-dark" type="text" placeholder="Ingresar viabilidad"
                                    id="txtViabilidad2" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">RR.HH</label>
                                <input class="form-control  border-dark bg-light" type="text"
                                    placeholder="Ingresar RR.HH" readonly id="txtRRHH" />
                            </div>
                            <div class="col-lg-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-success" id="btnActualizarInforDatGenerales"><i
                                        class="bi bi-floppy-fill"></i>
                                    Guardar información</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="proyectos" role="tabpanel">
                        <div class="row">
                            <div class="p-2">
                                <div class="col-lg-8">
                                    <label class="form-label" for="">Ingresar nombre del proyecto</label>
                                    <div class="d-flex gap-2">
                                        <input type="text" class="form-control  border-dark"
                                            placeholder="Nombre del proyecto" />
                                        <button class="btn btn-outline-dark d-flex gap-2">
                                            <i class="bi bi-search"></i>Buscar
                                        </button>
                                        <!-- <button class="btn btn-outline-dark d-flex gap-2">
                                            <i class="bi bi-plus-circle"></i>Agregar
                                        </button> -->
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="tblProyectosPreseleccionado">
                                        <thead class="">
                                            <th>Proyecto</th>
                                            <th>Cargo</th>
                                            <th>Fecha de ingreso</th>
                                            <th>Fecha de cese</th>
                                            <th>Observación</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="certificados" role="tabpanel">
                        <div class="row">
                            <div class="">
                                <div class="col-lg-8">
                                    <label class="form-label" for="">Ingresar el nombre de la certificación</label>
                                    <div class="d-flex gap-2">
                                        <input type="text" class="form-control  border-dark"
                                            placeholder="Nombre de la certificación" />
                                        <button class="btn btn-outline-dark d-flex gap-2">
                                            <i class="bi bi-search"></i>Buscar
                                        </button>
                                        <button class="btn btn-outline-dark d-flex gap-2" id="btnAgregarCertificacion">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                                <div class="my-1 p-1">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="tblCertificacionesPreseleccionados">
                                            <thead class="">
                                                <th>Nombre</th>
                                                <th>Inicio</th>
                                                <th>Fin</th>
                                                <th>Estado</th>
                                                <th></th>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cursos" role="tabpanel">
                        <div class="row">
                            <div class="">
                                <div class="col-lg-8">
                                    <label class="form-label" for="">Ingresar el nombre del curso</label>
                                    <div class="d-flex gap-2">
                                        <input type="text" class="form-control  border-dark"
                                            placeholder="Nombre del curso" />
                                        <button class="btn btn-outline-dark d-flex gap-2">
                                            <i class="bi bi-search"></i>Buscar
                                        </button>
                                        <button class="btn btn-outline-dark d-flex gap-2" id="btnAgregarCurso">
                                            <i class="bi bi-plus-circle"></i>Agregar
                                        </button>
                                    </div>
                                </div>
                                <div class="my-1 p-1">
                                    <div class="table-responsive">
                                        <table class="table" id="tblCursosPreseleccionado">
                                            <thead class="">
                                                <th>Nombre</th>
                                                <th>Inicio</th>
                                                <th>Fin</th>
                                                <th>Estado</th>
                                                <th></th>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="informacion_medica" role="tabpanel">
                        <div class="row row-gap-3">
                            <div class="col-lg-3">
                                <label class="form-label" for="">4ta vacuna</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar 4ta vacuna" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="">Fecha de examen médico</label>
                                <input class="form-control  border-dark" type="date" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="">Clinica</label>
                                <select class="form-select  border-dark" name="" id="">
                                    <option value="" disabled selected>
                                        Seleccionar opción
                                    </option>
                                    <option value="">Medex</option>
                                    <option value="">CC.MALVINAS</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="">Resultado</label>
                                <input class="form-control  border-dark" type="date" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="">Pase medico</label>
                                <input class="form-control  border-dark" type="text" placeholder="Ingresar estado" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="">PM</label>
                                <input class="form-control  border-dark" type="text" placeholder="Ingresar estado" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="">Informe médico</label>
                                <input class="form-control  border-dark" type="text" placeholder="Ingresar estado" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="exportExcelPreseRequeModal" tabindex="-1" aria-labelledby="exportExcelPreseRequeModalLabel"
    data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header encabezado text-white">
                <h5 class="modal-title" id="exportExcelPreseRequeModalLabel">
                    Exportar Excel de Status
                </h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body ">
                <div class="d-flex gap-2 my-2">
                    <button class="btn btn-outline-dark border-dark">Plantilla 1</button>
                </div>
                <div class="d-flex gap-3">
                    <div class="column border rounded p-2 flex-fill" id="allFields">
                        <h6>Todos los Campos</h6>
                        <div class="item list-group" id="column1">
                            <div class="list-group-item" data-id="1">ID</div>
                            <div class="list-group-item" data-id="2">Nombre</div>
                            <div class="list-group-item" data-id="3">Email</div>
                            <div class="list-group-item" data-id="4">Teléfono</div>
                            <div class="list-group-item" data-id="5">Fecha Registro</div>
                            <div class="list-group-item" data-id="1">ID</div>
                            <div class="list-group-item" data-id="2">Nombre</div>
                            <div class="list-group-item" data-id="3">Email</div>
                            <div class="list-group-item" data-id="4">Teléfono</div>
                            <div class="list-group-item" data-id="5">Fecha Registro</div>
                            <div class="list-group-item" data-id="1">ID</div>
                            <div class="list-group-item" data-id="2">Nombre</div>
                            <div class="list-group-item" data-id="3">Email</div>
                            <div class="list-group-item" data-id="4">Teléfono</div>
                            <div class="list-group-item" data-id="5">Fecha Registro</div>
                        </div>
                    </div>
                    <div class="column border rounded p-2 flex-fill" id="selectedFields">
                        <h6>Campos a Exportar</h6>
                        <div class="item list-group" id="column2">

                        </div>
                    </div>
                </div>



            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="exportarSeleccion()">Exportar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>