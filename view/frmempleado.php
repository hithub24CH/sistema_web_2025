<!-- Breadcome start-->
<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcome-list shadow-reset">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="modal-area-button">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newEmpleadoModal">
                                    Nuevo Empleado
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Inicio</a> <span class="bread-slash">/</span></li>
                                <li><span class="bread-blod">Empleados</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcome End-->

<div class="static-table-area mg-b-15">
    <div class="container-fluid">
        <div class="sparkline8-list shadow-reset">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>Lista de Empleados</h1>
                </div>
            </div>
            <div class="sparkline8-graph">
                <div class="static-table-list">
                    <table id="table" class="table table-striped" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>CI</th>
                                <th>Nombre Completo</th>
                                <th>Cargo</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            foreach($this->model->Listar() as $e): 
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($e->ci ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($e->nombre . ' ' . $e->appaterno); ?></td>
                                    <td><?php echo htmlspecialchars($e->cargo ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($e->telefono ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($e->correo ?? ''); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#edit_<?php echo $e->id_empleado; ?>" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
                                            <a href="#delete_<?php echo $e->id_empleado; ?>" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL EDITAR EMPLEADO -->
                                <div class="modal fade" id="edit_<?php echo $e->id_empleado; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white"><h4><i class="fa fa-edit"></i> Editar Empleado</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body">
                                                <form action="?c=empleado&a=InsEditar&ac=editar" method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="id_empleado" value="<?php echo $e->id_empleado; ?>">
                                                    
                                                    <div class="row">
                                                        <div class="col-md-4 form-group"><label>Nombre</label><input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($e->nombre ?? ''); ?>" required></div>
                                                        <div class="col-md-4 form-group"><label>Apellido Paterno</label><input type="text" name="appaterno" class="form-control" value="<?php echo htmlspecialchars($e->appaterno ?? ''); ?>" required></div>
                                                        <div class="col-md-4 form-group"><label>Apellido Materno</label><input type="text" name="apmaterno" class="form-control" value="<?php echo htmlspecialchars($e->apmaterno ?? ''); ?>"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group"><label>CI</label><input type="text" name="ci" class="form-control" value="<?php echo htmlspecialchars($e->ci ?? ''); ?>" required></div>
                                                        
                                                        <!-- MEJORA 1: Restricción de Fecha de Nacimiento -->
                                                        <div class="col-md-6 form-group">
                                                            <label>Fecha de Nacimiento</label>
                                                            <input type="date" name="fecha_nacimiento" class="form-control" 
                                                                   value="<?php echo htmlspecialchars($e->fecha_nacimiento ?? ''); ?>"
                                                                   max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 form-group"><label>Género</label>
                                                            <select name="genero" class="form-control">
                                                                <option value="" <?php echo empty($e->genero) ? 'selected' : ''; ?>>-- Seleccione --</option>
                                                                <option value="Masculino" <?php echo ($e->genero ?? '') == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                                                <option value="Femenino" <?php echo ($e->genero ?? '') == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                                                                <option value="Otro" <?php echo ($e->genero ?? '') == 'Otro' ? 'selected' : ''; ?>>Otro</option>
                                                            </select>
                                                        </div>

                                                        <!-- MEJORA 2: Lista Desplegable para Estado Civil -->
                                                        <div class="col-md-4 form-group"><label>Estado Civil</label>
                                                            <select name="estado_civil" class="form-control">
                                                                <option value="" <?php echo empty($e->estado_civil) ? 'selected' : ''; ?>>-- Seleccione --</option>
                                                                <option value="Soltero/a" <?php echo ($e->estado_civil ?? '') == 'Soltero/a' ? 'selected' : ''; ?>>Soltero/a</option>
                                                                <option value="Casado/a" <?php echo ($e->estado_civil ?? '') == 'Casado/a' ? 'selected' : ''; ?>>Casado/a</option>
                                                                <option value="Viudo/a" <?php echo ($e->estado_civil ?? '') == 'Viudo/a' ? 'selected' : ''; ?>>Viudo/a</option>
                                                                <option value="Divorciado/a" <?php echo ($e->estado_civil ?? '') == 'Divorciado/a' ? 'selected' : ''; ?>>Divorciado/a</option>
                                                                <option value="Unión Libre" <?php echo ($e->estado_civil ?? '') == 'Unión Libre' ? 'selected' : ''; ?>>Unión Libre</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 form-group"><label>Nacionalidad</label><input type="text" name="nacionalidad" class="form-control" value="<?php echo htmlspecialchars($e->nacionalidad ?? ''); ?>"></div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group"><label>Cargo</label><input type="text" name="cargo" class="form-control" value="<?php echo htmlspecialchars($e->cargo ?? ''); ?>" required></div>
                                                        <div class="col-md-6 form-group"><label>Correo Electrónico</label><input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($e->correo ?? ''); ?>"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group"><label>Teléfono</label><input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($e->telefono ?? ''); ?>"></div>
                                                        <div class="col-md-6 form-group"><label>Dirección</label><input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($e->direccion ?? ''); ?>"></div>
                                                    </div>
                                                    <div class="row">
                                                         <div class="col-md-6 form-group"><label>Fecha de Ingreso</label><input type="date" name="fecha_ingreso" class="form-control" value="<?php echo htmlspecialchars($e->fecha_ingreso ?? ''); ?>" required></div>
                                                         <div class="col-md-6 form-group"><label>Fecha Fin de Contrato</label><input type="date" name="fecha_fin_contrato" class="form-control" value="<?php echo htmlspecialchars($e->fecha_fin_contrato ?? ''); ?>"></div>
                                                    </div>
                                                    <div class="form-group"><label>Notas / Observaciones</label><textarea name="notas_observaciones" class="form-control"><?php echo htmlspecialchars($e->notas_observaciones ?? ''); ?></textarea></div>
                                                    <div class="form-group">
                                                        <label>Imagen Actual:</label>
                                                        <div><?php if (!empty($e->imagen)): ?><img src="uploads/empleados/<?php echo htmlspecialchars($e->imagen); ?>" style="max-width: 100px; margin-bottom: 10px;"><?php endif; ?></div>
                                                        <label>Cambiar Imagen</label><input type="file" name="imagen_nueva" class="form-control-file">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar Cambios</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- MODAL ELIMINAR EMPLEADO -->
                                <div class="modal fade" id="delete_<?php echo $e->id_empleado; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white"><h4 class="modal-title">Eliminar Empleado</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body"><p class="text-center">¿Está seguro de eliminar al empleado?</p><h3 class="text-center"><?php echo htmlspecialchars($e->nombre . ' ' . $e->appaterno); ?></h3></div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                <a href="?c=empleado&a=Eliminar&id_empleado=<?php echo $e->id_empleado; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL NUEVO EMPLEADO -->
<div class="modal fade" id="newEmpleadoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h4><i class="fa fa-plus"></i> Nuevo Empleado</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <form action="?c=empleado&a=InsEditar&ac=nuevo" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4 form-group"><label>Nombre</label><input type="text" name="nombre" class="form-control" required></div>
                        <div class="col-md-4 form-group"><label>Apellido Paterno</label><input type="text" name="appaterno" class="form-control" required></div>
                        <div class="col-md-4 form-group"><label>Apellido Materno</label><input type="text" name="apmaterno" class="form-control"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>CI</label><input type="text" name="ci" class="form-control" required></div>
                        <!-- MEJORA 1: Restricción de Fecha de Nacimiento -->
                        <div class="col-md-6 form-group">
                            <label>Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control"
                                   max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group"><label>Género</label>
                            <select name="genero" class="form-control">
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <!-- MEJORA 2: Lista Desplegable para Estado Civil -->
                        <div class="col-md-4 form-group"><label>Estado Civil</label>
                            <select name="estado_civil" class="form-control">
                                <option value="Soltero/a">Soltero/a</option>
                                <option value="Casado/a">Casado/a</option>
                                <option value="Viudo/a">Viudo/a</option>
                                <option value="Divorciado/a">Divorciado/a</option>
                                <option value="Unión Libre">Unión Libre</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group"><label>Nacionalidad</label><input type="text" name="nacionalidad" class="form-control" value="Boliviana"></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Cargo</label><input type="text" name="cargo" class="form-control" placeholder="Ej: Vendedor de Campo" required></div>
                        <div class="col-md-6 form-group"><label>Correo Electrónico</label><input type="email" name="correo" class="form-control" placeholder="ejemplo@correo.com"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Teléfono</label><input type="text" name="telefono" class="form-control"></div>
                        <div class="col-md-6 form-group"><label>Dirección</label><input type="text" name="direccion" class="form-control"></div>
                    </div>
                    <div class="row">
                         <div class="col-md-6 form-group"><label>Fecha de Ingreso</label><input type="date" name="fecha_ingreso" class="form-control" required></div>
                         <div class="col-md-6 form-group"><label>Fecha Fin de Contrato</label><input type="date" name="fecha_fin_contrato" class="form-control"></div>
                    </div>
                    <div class="form-group"><label>Notas / Observaciones</label><textarea name="notas_observaciones" class="form-control"></textarea></div>
                    <div class="form-group"><label>Imagen</label><input type="file" name="imagen_nueva" class="form-control-file"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>