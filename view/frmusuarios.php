<!-- Breadcome start-->
<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcome-list shadow-reset">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="modal-area-button">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newUsuarioModal">
                                    Nuevo Usuario
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Inicio</a> <span class="bread-slash">/</span></li>
                                <li><span class="bread-blod">Usuarios del Sistema</span></li>
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
                    <h1>Lista de Usuarios</h1>
                </div>
            </div>
            <div class="sparkline8-graph">
                <div class="static-table-list">
                    <table id="table" class="table table-striped" data-toggle="table" data-pagination="true" data-search="true">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre de Usuario</th>
                                <th>Correo de Login</th>
                                <th>Empleado Asociado</th>
                                <th>Rol</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            foreach($this->model->Listar() as $u): 
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($u->nombre_usuario ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($u->correo_login ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($u->nombre_empleado ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($u->nombre_rol ?? ''); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#edit_<?php echo $u->id_usuario; ?>" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
                                            <a href="#delete_<?php echo $u->id_usuario; ?>" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL EDITAR USUARIO -->
                                <div class="modal fade" id="edit_<?php echo $u->id_usuario; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white"><h4><i class="fa fa-edit"></i> Editar Usuario</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body">
                                                <form action="?c=usuarios&a=InsEditar&ac=editar" method="post">
                                                    <input type="hidden" name="id_usuario" value="<?php echo $u->id_usuario; ?>">
                                                    
                                                    <div class="form-group"><label>Empleado</label><input type="text" class="form-control" value="<?php echo htmlspecialchars($u->nombre_empleado ?? ''); ?>" readonly></div>
                                                    <div class="form-group"><label>Correo de Login</label><input type="email" class="form-control" value="<?php echo htmlspecialchars($u->correo_login ?? ''); ?>" readonly></div>
                                                    <div class="form-group"><label>Nombre de Usuario</label><input type="text" name="nombre_usuario" class="form-control" value="<?php echo htmlspecialchars($u->nombre_usuario ?? ''); ?>" required></div>
                                                    <div class="form-group"><label>Rol</label>
                                                        <select name="id_rol" class="form-control" required>
                                                            <?php foreach($roles as $rol): ?>
                                                                <option value="<?php echo $rol->id_rol; ?>" <?php echo ($this->model->Obtener($u->id_usuario)->id_rol == $rol->id_rol) ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($rol->nombre_rol); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <p class="text-muted"><small>La contraseña y el empleado asociado no se pueden cambiar por seguridad.</small></p>
                                                    
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar Cambios</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- MODAL ELIMINAR USUARIO -->
                                <div class="modal fade" id="delete_<?php echo $u->id_usuario; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white"><h4 class="modal-title">Eliminar Usuario</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body"><p class="text-center">¿Está seguro de eliminar al usuario?</p><h3 class="text-center"><?php echo htmlspecialchars($u->nombre_usuario ?? ''); ?></h3></div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                <a href="?c=usuarios&a=Eliminar&id_usuario=<?php echo $u->id_usuario; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
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

<!-- MODAL NUEVO USUARIO -->
<div class="modal fade" id="newUsuarioModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h4><i class="fa fa-plus"></i> Nuevo Usuario</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <form action="?c=usuarios&a=InsEditar&ac=nuevo" method="post">
                    <div class="form-group"><label>Empleado</label>
                        <select name="id_empleado" class="form-control" required>
                            <option value="">-- Seleccione un Empleado --</option>
                            <?php foreach($empleados as $emp): ?>
                                <option value="<?php echo $emp->id_empleado; ?>"><?php echo htmlspecialchars($emp->nombre . ' ' . $emp->appaterno); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Rol</label>
                        <select name="id_rol" class="form-control" required>
                            <option value="">-- Seleccione un Rol --</option>
                            <?php foreach($roles as $rol): ?>
                                <option value="<?php echo $rol->id_rol; ?>"><?php echo htmlspecialchars($rol->nombre_rol); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Nombre de Usuario</label><input type="text" name="nombre_usuario" class="form-control" required></div>
                    <div class="form-group"><label>Correo de Login</label><input type="email" name="correo_login" class="form-control" required></div>
                    <div class="form-group"><label>Contraseña</label><input type="password" name="contraseña_login" class="form-control" required></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>