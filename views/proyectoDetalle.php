<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Proyectos</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>views/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>views/assets/bootstrap-icons-1.13.1/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>views/css/preseleccionado.css" />
  <script src="<?= BASE_URL ?>views/js/sweetalert2.js"></script>
</head>

<body>
  <div class="p-5 my-3">
    <div class="encabezado rounded"></div>

    <!-- <div class="card my-2">
      <div class="card-body">
        COMPRESION MIPAYA
      </div>
    </div> -->

    <div class="row my-2 row-gap-2">
      <div class="col-lg-6">
        <label class="form-label fw-bold" for="txtBuscarRequerimiento">Buscar Requerimiento</label>
        <input class="form-control border-dark" type="text" placeholder="Buscar requerimiento"
          id="txtBuscarRequerimiento" />
      </div>
      <div class="col-lg-3 d-flex align-items-end">
        <button class="w-100 btn btn-outline-dark border-dark">
          <i class="bi bi-search"></i> Buscar requerimiento
        </button>
      </div>
      <div class="col-lg-3 d-flex align-items-end">
        <button type="button" class="w-100 btn btn-outline-dark border-dark" data-bs-toggle="modal"
          data-bs-target="#modalNuevoRequerimiento">
          <i class="bi bi-plus-circle"></i> Agregar requerimiento
        </button>
      </div>
      <div class="col p-3">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="encabezado text-center text-white">
              <!-- <th>N#</th> -->
              <th>Fecha de registro</th>
              <th>Número del requerimiento</th>
              <th>Fecha de requerimiento</th>
              <th>Tipo de requerimiento</th>
              <th>Fase</th>
              <th>Cargo</th>
              <th>Cantidad</th>
              <th>Regimen</th>
              <th></th>
            </thead>
            <tbody>
              <?php foreach ($requerimientos as $index => $requerimiento): ?>
                <tr class="text-center">
                  <!-- <td>
                    <span class="encabezado fw-medium px-3 py-1 rounded text-white"><?= $requerimiento["id_requerimiento"] ?></span>
                  </td> -->
                  <td><?= $requerimiento["fecha_registro"] ?></td>
                  <td><?= $requerimiento["numero_requerimiento"] ?></td>
                  <td><?= $requerimiento["fecha_requerimiento"] ?></td>
                  <td><?= $requerimiento["tipo_requerimiento"] ?></td>
                  <td><?= $requerimiento["nombreFase"] ?></td>
                  <td><?= $requerimiento["nombreCargo"] ?></td>
                  <td>
                    <span class="px-3 py-1 bg-success rounded text-white fw-bold">
                      0/<?= $requerimiento["cantidad"] ?>
                    </span>
                  </td>
                  <td><?= $requerimiento["regimen"] ?></td>
                  <td>
                    <button type="button" class="btn btn-outline-dark border-dark" data-bs-toggle="modal"
                      data-bs-target="#documentoModal">
                      <i class="bi bi-plus-circle-fill"></i> Agregar
                    </button>
                    <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="collapse"
                      data-bs-target="#detalle<?= $index ?>" aria-expanded="false" aria-controls="detalle<?= $index ?>">
                      <i class="bi bi-three-dots-vertical"></i>
                    </button>
                  </td>
                </tr>
                <tr class="collapse" id="detalle<?= $index ?>">
                  <td colspan="10">
                    <div class="p-3 bg-light border rounded">
                      <strong class="">Personas asignadas:</strong>
                      <div class="row my-2">
                        <div class="col-lg-12">
                          <div class="row">
                            <span class="col-lg-1 p-2 border border-dark">Fecha de registro</span>
                            <span class="col-lg-2 p-2 border border-dark">Nombre completo</span>
                            <span class="col-lg-1 p-2 border border-dark">DNI</span>
                            <span class="col-lg-1 p-2 border border-dark">Fecha de nacimiento</span>
                            <span class="col-lg-1 p-2 border border-dark">Exactian</span>
                            <span class="col-lg-2 p-2 border border-dark">Fecha Ingreso Ultimo Proyecto</span>
                            <span class="col-lg-2 p-2 border border-dark">Fecha Cese Ultimo Proyecto</span>
                            <span class="col-lg-1 p-2 border border-dark">Departamento</span>
                            <span class="col-lg-1 p-2 border border-dark">Opciones</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Nuevo requerimiento -->
  <!-- Modal -->
  <div class="modal fade" id="modalNuevoRequerimiento" tabindex="-1" aria-labelledby="modalNuevoRequerimientoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header encabezado">
          <h1 class="modal-title fs-5" id="modalNuevoRequerimientoLabel">Nuevo requerimiento</h1>
          <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" id="formularioRequerimiento">
            <div class="row row-gap-3">
              <div class="col-lg-4">
                <label class="form-label" for="sltProyecto">Proyecto <span class="text-danger fw-bold">*</span></label>
                <select class="form-select border-dark" id="sltProyecto">
                  <option value="" selected disabled>Seleccionar un proyecto</option>
                  <?php foreach ($proyectos as $proyecto): ?>
                    <option value="<?= $proyecto["ncodpry"] ?>"><?= $proyecto["cdespry"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-lg-4">
                <label class="form-label" for="dateFechaRequerimiento">Fecha de requerimiento <span
                    class="text-danger fw-bold">*</span></label>
                <input class="form-control border-dark" id="dateFechaRequerimiento" type="date">
              </div>
              <div class="col-lg-4">
                <label class="form-label" for="txtNroRequerimiento">Nro de Requerimiento <span
                    class="text-danger fw-bold">*</span></label>
                <input class="form-control border-dark" id="txtNroRequerimiento" type="text"
                  placeholder="Ingresar Nro de requerimiento">
              </div>
              <div class="col-lg-4">
                <label class="form-label" for="sltTipoRequerimiento">Tipo de requerimiento <span
                    class="text-danger fw-bold">*</span></label>
                <select class="form-select border-dark" id="sltTipoRequerimiento">
                  <option value="" disabled selected>Selecionar una opción</option>
                  <option value="INCREMENTO DE ACTIVIDAD">INCREMENTO DE ACTIVIDAD</option>
                  <option value="REEMPLAZO">REEMPLAZO</option>
                  <option value="INICIO DE ACTIVIDADES">INICIO DE ACTIVIDADES</option>
                </select>
              </div>
              <div class="col-lg-4">
                <label class="form-label" for="sltFase">Fase <span class="text-danger fw-bold">*</span></label>
                <select class="form-select border-dark" id="sltFase">
                  <option value="" disabled selected>Seleccionar una opción</option>
                  <?php foreach ($fases as $fase): ?>
                    <option value="<?= $fase["cod"] ?>"><?= $fase["descripcion"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-lg-4">
                <label class="form-label" for="sltCargo">Cargo <span class="text-danger fw-bold">*</span></label>
                <select class="form-select border-dark" id="sltCargo">
                  <option value="" disabled selected>Seleccionar una opción</option>
                  <?php foreach ($cargos as $cargo): ?>
                    <option value="<?= $cargo["cod"] ?>"><?= $cargo["descripcion"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-lg-4">
                <label class="form-label" for="txtCantidad">Cantidad <span class="text-danger fw-bold">*</span></label>
                <input class="form-control border-dark" id="txtCantidad" type="number"
                  placeholder="Ingresar la cantidad">
              </div>
              <div class="col-lg-4">
                <label class="form-label" for="sltRegimen">Régimen <span class="text-danger fw-bold">*</span></label>
                <select class="form-select border-dark" id="sltRegimen">
                  <option value="" selected disabled>Seleccionar una opción</option>
                  <option value="CIVIL">CIVIL</option>
                  <option value="COMUN">COMÚN</option>
                </select>
              </div>
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-1">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-success" id="btnNuevoRequerimiento"><i class="bi bi-floppy-fill"></i>
                Crear requerimiento</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

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
                <input type="text" class="form-control form-control-sm border-dark"
                  placeholder="Ingresar documento de la persona" />
                <button class="btn btn-outline-dark d-flex gap-2">
                  <i class="bi bi-search"></i>Buscar
                </button>
              </div>
            </div>
            <div class="col-12 p-2">
              <div class="alert alert-primary" role="alert">
                Persona encontrada.
              </div>
              <div class="alert alert-warning" role="alert">
                Persona no encontrada.
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" data-bs-target="#requerimientoModal" data-bs-toggle="modal">
            Crear requerimiento
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
            Nuevo requerimiento
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
              <button class="nav-link active" id="datos-tab" data-bs-toggle="tab" data-bs-target="#datos" type="button"
                role="tab">
                Datos Generales
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="proyectos-tab" data-bs-toggle="tab" data-bs-target="#proyectos" type="button"
                role="tab">
                Proyectos
              </button>
            </li>
            <li class="nav-item">
              <button class="mx-1 nav-link" id="certificados-tab" data-bs-toggle="tab" data-bs-target="#certificados"
                type="button" role="tab">
                Certificaciones
              </button>
            </li>
            <li class="nav-item">
              <button class="mx-1 nav-link" id="cursos-tab" data-bs-toggle="tab" data-bs-target="#cursos" type="button"
                role="tab">
                Cursos
              </button>
            </li>
            <li class="nav-item">
              <button class="mx-1 nav-link" id="informacion_medica-tab" data-bs-toggle="tab"
                data-bs-target="#informacion_medica" type="button" role="tab">
                Información médica
              </button>
            </li>
            <!-- <li>
                  <button class="btn btn-success">
                    <i class="bi bi-floppy"></i>
                    Guardar
                  </button>
                </li> -->
          </ul>

          <!-- CONTENIDO DE CADA TAB -->

          <div class="tab-content mt-3" id="">
            <div class="tab-pane fade show active" id="datos" role="tabpanel">
              <div class="row row-gap-3">
                <div class="col-lg-4">
                  <label class="form-label" for="">Apellidos y nombres</label>
                  <input class="form-control form-control-sm border-dark" placeholder="Apellidos y nombres"
                    type="text" />
                </div>
                <div class="col-lg-2">
                  <label class="form-label" for="">DNI</label>
                  <input class="form-control form-control-sm border-dark" placeholder="DNI" type="text" />
                </div>
                <div class="col-lg-3">
                  <label class="form-label" for="">Fecha de nacimiento</label>
                  <input class="form-control form-control-sm border-dark" placeholder="" type="date" />
                </div>
                <div class="col-lg-3">
                  <label class="form-label" for="">Edad</label>
                  <input class="form-control form-control-sm border-dark" placeholder="Edad" type="number" />
                </div>
                <div class="col-lg-2">
                  <label class="form-label" for="">Exactian</label>
                  <input class="form-control form-control-sm border-dark" placeholder="Exactian" type="text" />
                  <!-- <select
                name=""
                id=""
                class="form-select form-select-sm border-dark"
              >
                <option value="" disabled selected>Seleccionar Exactian</option>
                <option value="">Exactian 1</option>
                <option value="">Exactian 2</option>
                <option value="">Exactian 3</option>
              </select> -->
                </div>
                <div class="col-lg-2">
                  <label class="form-label" for="">Teléfono 1</label>
                  <input class="form-control form-control-sm border-dark" placeholder="Teléfono 1" type="text" />
                </div>
                <div class="col-lg-2">
                  <label class="form-label" for="">Teléfono 2</label>
                  <input class="form-control form-control-sm border-dark" placeholder="Teléfono 2" type="text" />
                </div>
                <div class="col-lg-3">
                  <label class="form-label" for="">Email</label>
                  <input class="form-control form-control-sm border-dark" placeholder="Ingresar email" type="email" />
                </div>
                <div class="col-lg-3">
                  <label class="form-label" for="">Departamento de residencia</label>
                  <input class="form-control form-control-sm border-dark" placeholder="Ingresar departamento"
                    type="text" />
                </div>

                <div class="col-lg-12">
                  <hr />
                </div>

                <div class="col-lg-3">
                  <label for="">POLIZA</label>
                  <input class="form-control form-control-sm border-dark" type="text" placeholder="Ingresar estado" />
                </div>
                <div class="col-lg-3">
                  <label for="">VIABILIDAD</label>
                  <input class="form-control form-control-sm border-dark" type="text" placeholder="Ingresar estado" />
                </div>
                <div class="col-lg-6">
                  <label for="">Observación</label>
                  <input class="form-control form-control-sm border-dark" type="text"
                    placeholder="Ingresar observación" />
                </div>
                <div class="col-lg-3">
                  <label for="">Ingreso a obra</label>
                  <input class="form-control form-control-sm border-dark" type="date" />
                </div>
                <div class="col-lg-3">
                  <label for="">Estado</label>
                  <input class="form-control form-control-sm border-dark" type="text" placeholder="Ingresar estado" />
                </div>
                <div class="col-lg-6">
                  <label for="">Observación 2</label>
                  <input class="form-control form-control-sm border-dark" type="text"
                    placeholder="Ingresar observación 2" />
                </div>
                <div class="col-lg-3">
                  <label for="">ALFA</label>
                  <input class="form-control form-control-sm border-dark" type="text" placeholder="Ingresar Alfa" />
                </div>
                <div class="col-lg-3">
                  <label for="">VIABILIDAD2</label>
                  <input class="form-control form-control-sm border-dark" type="text"
                    placeholder="Ingresar viabilidad" />
                </div>
                <div class="col-lg-3">
                  <label for="">RR.HH</label>
                  <input class="form-control form-control-sm border-dark bg-light" type="text"
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
                      <input type="text" class="form-control form-control-sm border-dark"
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
                            <input type="text" class="form-control form-control-sm border-dark"
                              placeholder="Ingresar el nombre del proyecto" />
                          </td>
                          <td class="">
                            <input type="date" class="form-control form-control-sm border-dark" />
                          </td>
                          <td class="">
                            <input type="date" class="form-control form-control-sm border-dark" />
                          </td>
                          <td class="">
                            <input type="text" class="form-control form-control-sm border-dark"
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
                            <div class="w-100 h-100 d-flex justify-content-center align-items-center">
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
                      <input type="text" class="form-control form-control-sm border-dark"
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
                              <input type="text" class="form-control form-control-sm border-dark"
                                placeholder="Ingresar nombre del curso o certificación" />
                            </td>
                            <td class="">
                              <input type="date" class="form-control form-control-sm border-dark" />
                            </td>
                            <td class="">
                              <input type="date" class="form-control form-control-sm border-dark" />
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
                      <input type="text" class="form-control form-control-sm border-dark"
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
                              <input type="text" class="form-control form-control-sm border-dark"
                                placeholder="Ingresar nombre del curso" />
                            </td>
                            <td class="">
                              <input type="date" class="form-control form-control-sm border-dark" />
                            </td>
                            <td class="">
                              <input type="date" class="form-control form-control-sm border-dark" />
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
                      <input class="form-control form-control-sm border-dark" type="text"
                        placeholder="Ingresar 4ta vacuna" />
                    </div>
                    <div class="col-lg-3">
                      <label class="form-label" for="">Curso repsol</label>
                      <input class="form-control form-control-sm border-dark" type="text"
                        placeholder="Ingresar estado" />
                    </div>
                    <div class="col-lg-3">
                      <label class="form-label" for="">Departamento de residencia</label>
                      <input class="form-control form-control-sm border-dark" type="text"
                        style="background-color: #177cdb; color: white" value="LIMA" readonly />
                    </div>
                  </div>
                </div>
                <div class="col-lg-3">
                  <label for="">Fecha de examen médico</label>
                  <input class="form-control form-control-sm border-dark" type="date" />
                </div>
                <div class="col-lg-3">
                  <label for="">Clinica</label>
                  <select class="form-select form-select-sm border-dark" name="" id="">
                    <option value="" disabled selected>
                      Seleccionar opción
                    </option>
                    <option value="">Medex</option>
                    <option value="">CC.MALVINAS</option>
                  </select>
                </div>
                <div class="col-lg-3">
                  <label for="">Resultado</label>
                  <input class="form-control form-control-sm border-dark" type="date" />
                </div>
                <div class="col-lg-3">
                  <label for="">Pase medico</label>
                  <input class="form-control form-control-sm border-dark" type="text" placeholder="Ingresar estado" />
                </div>
                <div class="col-lg-3">
                  <label for="">PM</label>
                  <input class="form-control form-control-sm border-dark" type="text" placeholder="Ingresar estado" />
                </div>
                <div class="col-lg-3">
                  <label for="">Informe médico</label>
                  <input class="form-control form-control-sm border-dark" type="text" placeholder="Ingresar estado" />
                </div>
                <div class="col-lg-3">
                  <label for="">CURSO.ALT/E.CONF/EXC/BLOQ/COT</label>
                  <input class="form-control form-control-sm border-dark" type="text" placeholder="Ingresar estado" />
                </div>
                <div class="col-lg-3">
                  <label for="">CURSO.CBS/TOP/AACC.INDUC</label>
                  <input class="form-control form-control-sm border-dark" type="text" placeholder="Ingresar estado" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cerrar
          </button>
          <button class="btn btn-success">
            <i class="bi bi-floppy"></i>
            Guardar todos los cambios
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="<?= BASE_URL ?>views/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>views/js/proyectoDetalle.js"></script>
</body>

</html>