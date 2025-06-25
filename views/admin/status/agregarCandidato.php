<div class="modal" id="documentoModal" tabindex="-1" aria-labelledby="documentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentoModalLabel">
                    Buscar candidato
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                placeholder="Ingresar documento de la persona" id="txtDocumentoCandidato" />
                            <button class="btn btn-outline-dark d-flex gap-2" id="btnBuscarCandidato">
                                <i class="bi bi-search"></i>Buscar
                            </button>
                        </div>
                    </div>
                    <div class="col-12 p-2" id="alertaBusquedaDocumento">
                        
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-target="#requerimientoModal" data-bs-toggle="modal">
                    Agregar candidato
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="requerimientoModal" tabindex="-1" aria-labelledby="requerimientoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requerimientoModalLabel">
                    Agregar candidato
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row row-cols-3 p-3 gap-1">
                    <div class="alert alert-danger" role="alert">
                        Tiene una observación en su último proyecto
                    </div>
                    <div class="alert alert-warning" role="alert">
                        Certificado COT cerca a caducar
                    </div>
                </div>

                <div class="encabezado rounded"></div>

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
                                <label class="form-label" for="txtApellidosNombres">Apellidos y nombres</label>
                                <input class="form-control border-dark"
                                    placeholder="Apellidos y nombres" type="text" id="txtApellidosNombres" />
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label" for="txtDocumento">Documento</label>
                                <input class="form-control  border-dark" placeholder="DNI" type="text" id="txtDocumento" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="txtFechaDeNacimiento">Fecha de nacimiento</label>
                                <input class="form-control  border-dark" placeholder="" type="date" id="txtFechaDeNacimiento" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="txtEdad">Edad</label>
                                <input class="form-control  border-dark" placeholder="Edad"
                                    type="number" id="txtEdad" />
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label" for="txtExactian">Exactian</label>
                                <input class="form-control  border-dark" placeholder="Exactian"
                                    type="text" id="txtExactian" />
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label" for="txtTelefono1">Teléfono 1</label>
                                <input class="form-control  border-dark" placeholder="Teléfono 1"
                                    type="text" id="txtTelefono1" />
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label" for="txtTelefono2">Teléfono 2</label>
                                <input class="form-control  border-dark" placeholder="Teléfono 2"
                                    type="text" id="txtTelefono2" />
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="txtEmail">Email</label>
                                <input class="form-control  border-dark" placeholder="Ingresar email"
                                    type="email" id="txtEmail"/>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="txtDepartamentoResidencia">Departamento de residencia</label>
                                <input class="form-control  border-dark"
                                    placeholder="Ingresar departamento" type="text" id="txtDepartamentoResidencia" />
                            </div>

                            <div class="col-lg-12">
                                <hr />
                            </div>

                            <div class="col-lg-3">
                                <label for="">POLIZA</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar estado" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">VIABILIDAD</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar estado" />
                            </div>
                            <div class="col-lg-6">
                                <label for="">Observación</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar observación" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">Ingreso a obra</label>
                                <input class="form-control  border-dark" type="date" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">Estado</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar estado" />
                            </div>
                            <div class="col-lg-6">
                                <label for="">Observación 2</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar observación 2" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">ALFA</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar Alfa" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">VIABILIDAD2</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar viabilidad" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">RR.HH</label>
                                <input class="form-control  border-dark bg-light" type="text"
                                    placeholder="Ingresar RR.HH" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="proyectos" role="tabpanel">
                        <div class="row">
                            <div class="p-2">
                                <div class="col-lg-8">
                                    <label class="form-label" for="">Ingresar nuevo proyecto</label>
                                    <div class="d-flex gap-2">
                                        <input type="text" class="form-control  border-dark"
                                            placeholder="Nombre del proyecto" />
                                        <button class="btn btn-outline-dark d-flex gap-2">
                                            <i class="bi bi-search"></i>Buscar
                                        </button>
                                        <button class="btn btn-outline-dark d-flex gap-2">
                                            <i class="bi bi-plus-circle"></i>Agregar
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="">
                                            <th>Proyecto</th>
                                            <th>Fecha de ingreso</th>
                                            <th>Fecha de cese</th>
                                            <th>Observación</th>
                                            <th>Corregida</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="">
                                                    <input type="text" class="form-control  border-dark"
                                                        placeholder="Ingresar el nombre del proyecto" />
                                                </td>
                                                <td class="">
                                                    <input type="date"
                                                        class="form-control  border-dark" />
                                                </td>
                                                <td class="">
                                                    <input type="date"
                                                        class="form-control  border-dark" />
                                                </td>
                                                <td class="">
                                                    <input type="text" class="form-control  border-dark"
                                                        placeholder="Ingresar observación del proyecto" />
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="">Proyecto 3</td>
                                                <td class="">21/01/2025</td>
                                                <td class="">----</td>
                                                <td class="">
                                                    <p class="bg-danger p-1 rounded text-white">
                                                        No fue admitido
                                                    </p>
                                                </td>
                                                <td class="">
                                                    <div
                                                        class="w-100 h-100 d-flex justify-content-center align-items-center">
                                                        <input type="checkbox" class="form-check" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <i class="bi bi-pencil"></i>
                                                    <i class="bi bi-trash"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="">Proyecto 3</td>
                                                <td class="">09/06/2024</td>
                                                <td class="">05/06/2024</td>
                                                <td class="">Sin observación</td>
                                                <td class="d-flex justify-content-center">
                                                    <input type="checkbox" class="form-check" />
                                                </td>
                                                <td>
                                                    <i class="bi bi-pencil"></i>
                                                    <i class="bi bi-trash"></i>
                                                </td>
                                            </tr>
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
                                        <button class="btn btn-outline-dark d-flex gap-2">
                                            <i class="bi bi-plus-circle"></i>Agregar
                                        </button>
                                    </div>
                                </div>
                                <div class="my-1 p-1">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="">
                                                <th>Nombre</th>
                                                <th>Inicio</th>
                                                <th>Fin</th>
                                                <th>Estado</th>
                                                <th></th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="">
                                                        <input type="text"
                                                            class="form-control  border-dark"
                                                            placeholder="Ingresar nombre del curso o certificación" />
                                                    </td>
                                                    <td class="">
                                                        <input type="date"
                                                            class="form-control  border-dark" />
                                                    </td>
                                                    <td class="">
                                                        <input type="date"
                                                            class="form-control  border-dark" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="">Certificacion</td>
                                                    <td class="">2/10/2024</td>
                                                    <td class="">2/10/2025</td>
                                                    <td>
                                                        <span class="badge text-bg-success">Vigente</span>
                                                    </td>
                                                    <td>
                                                        <i class="bi bi-pencil"></i>
                                                        <i class="bi bi-trash"></i>
                                                    </td>
                                                </tr>
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
                                        <button class="btn btn-outline-dark d-flex gap-2">
                                            <i class="bi bi-plus-circle"></i>Agregar
                                        </button>
                                    </div>
                                </div>
                                <div class="my-1 p-1">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="">
                                                <th>Nombre</th>
                                                <th>Inicio</th>
                                                <th>Fin</th>
                                                <th>Estado</th>
                                                <th></th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="">
                                                        <input type="text"
                                                            class="form-control  border-dark"
                                                            placeholder="Ingresar nombre del curso" />
                                                    </td>
                                                    <td class="">
                                                        <input type="date"
                                                            class="form-control  border-dark" />
                                                    </td>
                                                    <td class="">
                                                        <input type="date"
                                                            class="form-control  border-dark" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="">Curso CBS/PTEHS</td>
                                                    <td class="">2/10/2024</td>
                                                    <td class="">2/10/2025</td>
                                                    <td>
                                                        <span class="badge text-bg-success">Vigente</span>
                                                    </td>
                                                    <td>
                                                        <i class="bi bi-pencil"></i>
                                                        <i class="bi bi-trash"></i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Espacios confinados</td>
                                                    <td>11/04/2023</td>
                                                    <td>11/04/2025</td>
                                                    <td>
                                                        <span class="badge text-bg-danger">Caducó</span>
                                                    </td>
                                                    <td>
                                                        <i class="bi bi-pencil"></i>
                                                        <i class="bi bi-trash"></i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Curso repsol</td>
                                                    <td>11/04/2023</td>
                                                    <td>11/04/2025</td>
                                                    <td>
                                                        <span class="badge text-bg-danger">Caducó</span>
                                                    </td>
                                                    <td>
                                                        <i class="bi bi-pencil"></i>
                                                        <i class="bi bi-trash"></i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>CURSO.CBS/TOP/AACC.INDUC</td>
                                                    <td>11/04/2023</td>
                                                    <td>11/04/2025</td>
                                                    <td>
                                                        <span class="badge text-bg-danger">Caducó</span>
                                                    </td>
                                                    <td>
                                                        <i class="bi bi-pencil"></i>
                                                        <i class="bi bi-trash"></i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>CURSO.ALT/E.CONF/EXC/BLOQ/COT</td>
                                                    <td>11/04/2023</td>
                                                    <td>11/04/2025</td>
                                                    <td>
                                                        <span class="badge text-bg-danger">Caducó</span>
                                                    </td>
                                                    <td>
                                                        <i class="bi bi-pencil"></i>
                                                        <i class="bi bi-trash"></i>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="informacion_medica" role="tabpanel">
                        <div class="row row-gap-3">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label class="form-label" for="">4ta vacuna</label>
                                        <input class="form-control  border-dark" type="text"
                                            placeholder="Ingresar 4ta vacuna" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="form-label" for="">Curso repsol</label>
                                        <input class="form-control  border-dark" type="text"
                                            placeholder="Ingresar estado" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="form-label" for="">Departamento de residencia</label>
                                        <input class="form-control  border-dark" type="text"
                                            style="background-color: #177cdb; color: white" value="LIMA" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="">Fecha de examen médico</label>
                                <input class="form-control  border-dark" type="date" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">Clinica</label>
                                <select class="form-select  border-dark" name="" id="">
                                    <option value="" disabled selected>
                                        Seleccionar opción
                                    </option>
                                    <option value="">Medex</option>
                                    <option value="">CC.MALVINAS</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="">Resultado</label>
                                <input class="form-control  border-dark" type="date" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">Pase medico</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar estado" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">PM</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar estado" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">Informe médico</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar estado" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">CURSO.ALT/E.CONF/EXC/BLOQ/COT</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar estado" />
                            </div>
                            <div class="col-lg-3">
                                <label for="">CURSO.CBS/TOP/AACC.INDUC</label>
                                <input class="form-control  border-dark" type="text"
                                    placeholder="Ingresar estado" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button class="btn btn-success" id="btnGuardarInformacionCandidato">
                    <i class="bi bi-floppy"></i>
                    Guardar todos los cambios
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>views/js/status/agregarCandidato.js"></script>