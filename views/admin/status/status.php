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

<body class="p-5 bg-light">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">Postulante</li>
    <li class="breadcrumb-item"><a href="#">Status</a></li>
  </ol>

  <div class="card p-4 shadow-sm my-3">
    <div class="d-flex justify-content-start my-2 gap-2">
      <div class="col-lg-4">
        <label class="form-label fw-bold" for="txtBuscarRequerimiento">Buscar Requerimiento</label>
        <input class="form-control border-dark" type="text" placeholder="Buscar requerimiento"
          id="txtBuscarRequerimiento" />
      </div>
      <div class="d-flex align-items-end">
        <button class="btn btn-outline-dark border-dark">
          <i class="bi bi-search"></i> Buscar requerimiento
        </button>
      </div>
      <div class="d-flex align-items-end">
        <button type="button" class="btn btn-outline-dark border-dark" data-bs-toggle="modal"
          data-bs-target="#modalFormularioRequerimiento">
          <i class="bi bi-plus-circle-fill"></i> Agregar requerimiento
        </button>
      </div>
      <div class="d-flex align-items-end">
        <button type="button" class="btn btn-outline-dark border-dark" data-bs-toggle="modal"
          data-bs-target="#exportExcelPreseRequeModal" onClick="exportarStatusPorRequerimiento()">
          <i class="bi bi-file-earmark-excel-fill"></i> Exportar
        </button>
      </div>
    </div>
  </div>

  <div class="card p-4 shadow-sm my-3">
    <div class="row my-2 row-gap-2">
      <div class="col p-3">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="encabezado text-center text-white">
              <!-- <th>N#</th> -->
              <th class="encabezado">Fecha de registro</th>
              <th>Número del requerimiento</th>
              <th>Fecha de requerimiento</th>
              <!-- <th>Tipo de requerimiento</th> -->
              <th>Fase</th>
              <th>Cargo</th>
              <th>Cantidad</th>
              <th>Proyecto</th>
              <th></th>
            </thead>
            <tbody>
              <?php foreach ($requerimientos as $index => $requerimiento): ?>
                <tr class="text-center">
                  <td><?= $requerimiento["fecha_registro"] ?></td>
                  <td><?= $requerimiento["numero_requerimiento"] ?></td>
                  <td><?= implode("-", array_reverse(explode("-", $requerimiento["fecha_requerimiento"]))) ?></td>
                  <!-- <td><?= $requerimiento["tipo_requerimiento"] ?></td> -->
                  <td><?= $requerimiento["nombreFase"] ?></td>
                  <td><?= $requerimiento["nombreCargo"] ?></td>
                  <td>
                    <span class="px-3 py-1 bg-success rounded text-white fw-bold">
                      <?= $requerimiento["cubiertos"] ?>/<?= $requerimiento["cantidad"] ?>
                    </span>
                  </td>
                  <td><?= $requerimiento["nombreProyecto"] ?></td>
                  <td>
                    <button type="button" class="btn btn-outline-dark border-dark" data-bs-toggle="modal"
                      data-bs-target="#documentoModal"
                      onClick="cambiarIdRequerimiento('<?= $requerimiento["id_requerimiento"] ?>')">
                      <i class="bi bi-plus-circle-fill"></i> Agregar
                    </button>
                    <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="collapse"
                      data-bs-target="#detalle<?= $index ?>" aria-expanded="false" aria-controls="detalle<?= $index ?>">
                      <i class="bi bi-three-dots-vertical"></i>
                    </button>
                  </td>
                </tr>
                <tr class="collapse bg-white" id="detalle<?= $index ?>">
                  <td colspan="10">
                    <div class="p-3 border rounded shadow-sm bg-body">
                      <div class="d-flex justify-content-between align-items-center my-2">
                        <h6 class="text-primary fw-bold mb-3">👥 Personas asignadas:</h6>
                        <button type="button" class="btn btn-outline-dark border-dark" data-bs-toggle="modal"
                          data-bs-target="#exportExcelPreseRequeModal"
                          onClick="exportarStatusPorRequerimiento('<?= $requerimiento["id_requerimiento"] ?>')">
                          <i class="bi bi-file-earmark-excel-fill"></i> Exportar
                        </button>
                        <!-- <button class="btn btn-outline-success" onClick="exportarStatusPorRequerimiento('<?= $requerimiento["id_requerimiento"] ?>')">
                          <i class="bi bi-file-earmark-excel-fill"></i> Exportar
                        </button> -->
                      </div>
                      <div class="table-responsive">
                        <div class="table table-bordered" id="tblRequerimientoPreseleccionados">

                          <div class="row g-0 bg-light text-dark text-center fw-semibold">
                            <div class="col p-2">Fecha de registro</div>
                            <div class="col p-2">Nombre completo</div>
                            <div class="col p-2">Documento</div>
                            <div class="col p-2">Fecha Nacimiento</div>
                            <div class="col p-2">Celular</div>
                            <div class="col p-2">Observaciones</div>
                            <div class="col p-2">Cursos</div>
                            <div class="col p-2">Certificados</div>
                            <div class="col p-2">Información médica</div>
                            <div class="col p-2">Postulacion guardada</div>
                            <div class="col p-2">Correo enviado</div>
                            <div class="col p-2">Opciones</div>
                          </div>

                          <?php foreach ($requerimiento["preseleccionados"] as $preseleccionado): ?>
                            <div class="row g-0 text-center border filaPreseleecionado">
                              <div class="col p-2 d-flex align-items-center justify-content-center">
                                <?= $preseleccionado["fecha_registro"] ?>
                              </div>
                              <div class="col p-2 d-flex align-items-center justify-content-center">
                                <?= $preseleccionado["apellidos_nombres"] ?>
                              </div>
                              <div class="col p-2 d-flex align-items-center justify-content-center">
                                <?= $preseleccionado["documento"] ?>
                              </div>
                              <div class="col p-2 d-flex align-items-center justify-content-center">
                                <?= $preseleccionado["fecha_nacimiento"] ?>
                              </div>
                              <div class="col p-2 d-flex align-items-center justify-content-center">
                                <?= $preseleccionado["telefono_1"] ?>
                              </div>
                              <div class="col p-2 d-flex align-items-center justify-content-center">
                                <span class="badge rounded-pill text-bg-secondary">Sin observaciones</span>
                              </div>
                              <div class="col p-2 d-flex align-items-center justify-content-center">
                                <span class="badge rounded-pill text-bg-secondary">No aprobado</span>
                              </div>
                              <div class="col p-2 d-flex align-items-center justify-content-center">
                                <span class="badge rounded-pill text-bg-success">Aprobado</span>
                              </div>
                              <div class="col p-2 d-flex align-items-center justify-content-center">
                                <span class="badge rounded-pill text-bg-secondary">No aprobado</span>
                              </div>
                              <div class="col p-2 d-flex align-items-center justify-content-center">
                                <?php if (isset($preseleccionado["id_postulante"])): ?>
                                  <span class="badge rounded-pill text-bg-success estadoPostulacion">Guardada</span>
                                <?php else: ?>
                                  <span class="badge rounded-pill text-bg-secondary estadoPostulacion">No guardado</span>
                                <?php endif; ?>
                              </div>
                              <div class="col p-2 d-flex align-items-center justify-content-center">
                                <?php if (isset($preseleccionado["enviado"]) && $preseleccionado["enviado"] == 1): ?>
                                  <span class="badge rounded-pill text-bg-success estadoMensaje">Enviado</span>
                                <?php else: ?>
                                  <span class="badge rounded-pill text-bg-secondary estadoMensaje">No enviado</span>
                                <?php endif; ?>
                              </div>
                              <div class="col p-2 my-2">
                                <button type="button" class="btn btn-info" data-bs-target="#requerimientoModal"
                                  data-bs-toggle="modal"
                                  onClick="cargarDatosPreseleccionado('<?= $preseleccionado["id_preseleccionado"] ?>','<?= $requerimiento["id_requerimiento"] ?>')">
                                  <i class="bi bi-eye-fill"></i>
                                </button>
                                <button type="button" class="btn btn-success" <?= !isset($preseleccionado["id_postulante"]) ? "" : "disabled" ?>
                                  onClick="enviarInvitacion(this,'<?= $preseleccionado["id_preseleccionado"] ?>','<?= $requerimiento["id_requerimiento"] ?>')">
                                  <i class="bi bi-send-fill"></i>
                                </button>
                                <button type="button" class="btn btn-info btnReenviarInvitacion"
                                  <?= isset($preseleccionado["id_postulante"]) ? "" : "disabled" ?>
                                  onClick="reenviarInvitacionPreReque(this,'<?= $preseleccionado["id_preseleccionado"] ?>','<?= $requerimiento["id_requerimiento"] ?>')">
                                  <i class="bi bi-arrow-repeat"></i>
                                </button>
                                <!-- <button class="btn btn-outline-danger" id="eliminarPreReque"
                                  onClick="eliminarPreseDelReque(this,'<?= $preseleccionado["id_preseleccionado"] ?>','<?= $requerimiento["id_requerimiento"] ?>')">
                                  <i class="bi bi-trash3"></i>
                                </button> -->
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      </div>
                    </div>
                    <div class="bg-dark rounded" style="height: 15px;"></div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <form id="formExcel" action="api/generarExcelStatus" method="POST" style="display: none;">
    <input type="hidden" name="id_requerimiento" id="inputRequerimientoExcel">
    <input type="hidden" name="filtro_columnas" id="inputFiltroColumnas">
  </form>

  <?php include_once "agregarPreseleccionado.php"; ?>
  <?php include_once "formularioRequerimiento.php"; ?>

  <script src="
  https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js
  "></script>
  <script src="<?= BASE_URL ?>views/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>views/js/status/status.js"></script>
  <script src="<?= BASE_URL ?>views/js/status/agregarPreseleccionado.js"></script>

</body>

</html>