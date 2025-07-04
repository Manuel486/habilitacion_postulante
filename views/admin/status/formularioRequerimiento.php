<!-- Modal: Formulario requerimiento -->
<!-- Modal -->
<div class="modal fade" id="modalFormularioRequerimiento" tabindex="-1"
    aria-labelledby="modalFormularioRequerimientoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header encabezado text-white">
                <h1 class="modal-title fs-5" id="modalFormularioRequerimientoLabel">Nuevo requerimiento</h1>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formularioRequerimiento">
                    <div class="row row-gap-3">
                        <div class="col-lg-4">
                            <label class="form-label" for="sltProyecto">Proyecto <span
                                    class="text-danger fw-bold">*</span></label>
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
                            <label class="form-label" for="sltFase">Fase <span
                                    class="text-danger fw-bold">*</span></label>
                            <!-- <select class="form-select border-dark" id="sltFase">
                                <option value="" disabled selected>Seleccionar una opción</option>
                                <?php foreach ($fases as $fase): ?>
                                    <option value="<?= $fase["cod"] ?>"><?= $fase["descripcion"] ?></option>
                                <?php endforeach; ?>
                            </select> -->
                            <div><select class="form-select border-dark" id="sltFase">
                                <option value="" disabled selected>Seleccionar una fase</option>    
                                <?php foreach ($fases as $fase): ?>
                                    <option value="<?= $fase["cod"] ?>"><?= $fase["descripcion"] ?></option>
                                <?php endforeach; ?>
                            </select></div>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="sltCargo">Cargo <span
                                    class="text-danger fw-bold">*</span></label>
                            <select class="form-select border-dark" id="sltCargo">
                                <option value="" disabled selected>Seleccionar una opción</option>
                                <?php foreach ($cargos as $cargo): ?>
                                    <option value="<?= $cargo["cod"] ?>"><?= $cargo["descripcion"] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="txtCantidad">Cantidad <span
                                    class="text-danger fw-bold">*</span></label>
                            <input class="form-control border-dark" id="txtCantidad" type="number"
                                placeholder="Ingresar la cantidad">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="sltRegimen">Régimen <span
                                    class="text-danger fw-bold">*</span></label>
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
                        <button type="submit" class="btn btn-success" id="btnNuevoRequerimiento"><i
                                class="bi bi-floppy-fill"></i>
                            Crear requerimiento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>views/js/status/formularioRequerimiento.js"></script>