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
          data-bs-target="#modalFormularioRequerimiento">
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
                      <?= $requerimiento["cubiertos"] ?>/<?= $requerimiento["cantidad"] ?>
                    </span>
                  </td>
                  <td><?= $requerimiento["regimen"] ?></td>
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
                        <button class="btn btn-outline-success">
                          <i class="bi bi-file-earmark-excel-fill"></i> Exportar
                        </button>
                      </div>
                      <div class="table-responsive">
                        <div class="table table-bordered">
                          <div class="row g-0 bg-light text-dark text-center text-uppercase fw-semibold">
                            <div class="col p-2">Fecha de registro</div>
                            <div class="col p-2">Nombre completo</div>
                            <div class="col p-2">Documento</div>
                            <div class="col p-2">Fecha nacimiento</div>
                            <div class="col p-2">Exactian</div>
                            <div class="col p-2">Ingreso último proyecto</div>
                            <div class="col p-2">Cese último proyecto</div>
                            <div class="col p-2">Departamento</div>
                            <div class="col p-2">Opciones</div>
                          </div>

                          <?php foreach ($requerimiento["candidatos"] as $candidato): ?>
                            <div class="row g-0 text-center text-uppercase">
                              <div class="col p-2"><?= $candidato["fecha_registro"] ?></div>
                              <div class="col p-2"><?= $candidato["apellidos_nombres"] ?></div>
                              <div class="col p-2"><?= $candidato["documento"] ?></div>
                              <div class="col p-2"><?= $candidato["edad"] ?></div>
                              <div class="col p-2"><?= $candidato["exactian"] ?></div>
                              <div class="col p-2"><?= $candidato["fecha_ingreso_ultimo_proyecto"] ?></div>
                              <div class="col p-2"><?= $candidato["fecha_cese_ultimo_proyecto"] ?></div>
                              <div class="col p-2"><?= $candidato["departamento_residencia"] ?></div>
                              <div class="col p-2">
                                <button class="btn btn-outline-info">
                                  <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-outline-danger">
                                  <i class="bi bi-trash3"></i>
                                </button>
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

  <?php include_once "agregarPreseleccionado.php"; ?>
  <?php include_once "formularioRequerimiento.php"; ?>

  <script src="<?= BASE_URL ?>views/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>views/js/status/status.js"></script>
</body>

</html>