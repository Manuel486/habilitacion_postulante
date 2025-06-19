<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Proyectos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>views/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>views/assets/bootstrap-icons-1.13.1/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>views/css/preseleccionado.css" />
</head>

<body>
    <div class="p-5 my-3">
        <div class="encabezado rounded"></div>

        <div class="card my-2">
            <div class="card-body">
                COMPRESION MIPAYA
            </div>
        </div>

        <div class="row my-2 row-gap-2">
            <div class="col-lg-6">
                <label class="form-label fw-bold" for="">Buscar Requerimiento</label>
                <input
                    class="form-control border-dark"
                    type="text"
                    placeholder="Buscar requerimiento" />
            </div>
            <div class="col-lg-3 d-flex align-items-end">
                <button class="w-100 btn btn-outline-dark border-dark">
                    <i class="bi bi-search"></i> Buscar requerimiento
                </button>
            </div>
            <div class="col-lg-3 d-flex align-items-end">
                <button
                    type="button"
                    class="w-100 btn btn-outline-dark border-dark"
                    data-bs-toggle="modal"
                    data-bs-target="#modalNuevoRequerimiento">
                    <i class="bi bi-plus-circle"></i> Agregar requerimiento
                </button>
            </div>
            <div class="col p-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <th>N#</th>
                            <th>Fecha de requerimiento</th>
                            <th>Nombre del trabajador</th>
                            <th>Documento</th>
                            <th>Fase</th>
                            <th>Cargo</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>21/01/2025</td>
                                <td>Miguel Rodriguez</td>
                                <td>72411391</td>
                                <td>OBRAS CIVILES</td>
                                <td>PEÓN</td>
                                <td>
                                    <i class="bi bi-eye-fill"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>21/01/2025</td>
                                <td>Miguel Rodriguez</td>
                                <td>72411391</td>
                                <td>OBRAS CIVILES</td>
                                <td>PEÓN</td>
                                <td>
                                    <i class="bi bi-eye-fill"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>21/01/2025</td>
                                <td>Miguel Rodriguez</td>
                                <td>72411391</td>
                                <td>OBRAS CIVILES</td>
                                <td>PEÓN</td>
                                <td>
                                    <i class="bi bi-eye-fill"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Nuevo requerimiento -->
    <!-- Modal -->
    <div class="modal fade" id="modalNuevoRequerimiento" tabindex="-1" aria-labelledby="modalNuevoRequerimientoLabel" aria-hidden="true">
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
                                <label class="form-label" for="dateFechaRequerimiento">Fecha de requerimiento <span class="text-danger fw-bold">*</span></label>
                                <input class="form-control border-dark" id="dateFechaRequerimiento" type="date">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label" for="txtNroRequerimiento">Nro de Requerimiento <span class="text-danger fw-bold">*</span></label>
                                <input class="form-control border-dark" id="txtNroRequerimiento" type="text" placeholder="Ingresar Nro de requerimiento">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label" for="sltTipoRequerimiento">Tipo de requerimiento <span class="text-danger fw-bold">*</span></label>
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
                                <input class="form-control border-dark" id="txtCantidad" type="number" placeholder="Ingresar la cantidad">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label" for="">Régimen <span class="text-danger fw-bold">*</span></label>
                                <select class="form-select border-dark">
                                    <option value="" selected disabled>Seleccionar una opción</option>
                                    <option value="CIVIL">CIVIL</option>
                                    <option value="COMUN">COMÚN</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end gap-1">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="btnNuevoRequerimiento"><i class="bi bi-floppy-fill"></i> Crear requerimiento</button>
                        </div>
                    </form>

                </div>
                <!-- <div class="modal-footer">
                    
                </div> -->
            </div>
        </div>
    </div>


    <script src="<?= BASE_URL ?>views/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>views/js/proyectoDetalle.js"></script>
</body>

</html>