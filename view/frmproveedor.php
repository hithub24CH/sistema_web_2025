<!-- Breadcome start-->
<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcome-list shadow-reset">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="modal-area-button">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newProveedorModal">
                                    Nuevo Proveedor
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Inicio</a> <span class="bread-slash">/</span></li>
                                <li><span class="bread-blod">Proveedores</span></li>
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
                    <h1>Lista de Proveedores</h1>
                </div>
            </div>
            <div class="sparkline8-graph">
                <div class="static-table-list">
                    <table id="table" class="table table-striped" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-export="true">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre Proveedor</th>
                                <th>Contacto Principal</th>
                                <th>Teléfono</th>
                                <th>Categoría Principal</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            foreach($this->model->Listar() as $pr): 
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($pr->nombre_proveedor ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($pr->contacto_principal ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($pr->telefono ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($pr->categoria ?? 'Sin categoría'); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#edit_<?php echo $pr->id_proveedor; ?>" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
                                            <a href="#delete_<?php echo $pr->id_proveedor; ?>" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL EDITAR PROVEEDOR -->
                                <div class="modal fade" id="edit_<?php echo $pr->id_proveedor; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h4><i class="fa fa-edit"></i> Editar Proveedor</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="?c=proveedor&a=InsEditar&ac=editar" method="post">
                                                    <input type="hidden" name="id_proveedor" value="<?php echo $pr->id_proveedor; ?>">
                                                    
                                                    <div class="form-group"><label>Nombre del Proveedor</label><input type="text" name="nombre_proveedor" class="form-control" value="<?php echo htmlspecialchars($pr->nombre_proveedor ?? ''); ?>" required></div>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group"><label>Identificador Fiscal (NIT/RUC)</label><input type="text" name="identificador_fiscal" class="form-control" value="<?php echo htmlspecialchars($pr->identificador_fiscal ?? ''); ?>"></div>
                                                        <div class="col-md-6 form-group"><label>Contacto Principal</label><input type="text" name="contacto_principal" class="form-control" value="<?php echo htmlspecialchars($pr->contacto_principal ?? ''); ?>"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group"><label>Teléfono</label><input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($pr->telefono ?? ''); ?>"></div>
                                                        <div class="col-md-6 form-group"><label>Correo</label><input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($pr->correo ?? ''); ?>"></div>
                                                    </div>
                                                    <div class="form-group"><label>Dirección</label><textarea name="direccion" class="form-control"><?php echo htmlspecialchars($pr->direccion ?? ''); ?></textarea></div>
                                                    <div class="form-group"><label>Categoría Principal</label>
                                                        <select name="id_categoria_producto" class="form-control">
                                                            <option value="">-- Sin categoría --</option>
                                                            <?php foreach($categorias as $cat): ?>
                                                                <option value="<?php echo $cat->id_categoria_producto; ?>" <?php echo $cat->id_categoria_producto == $pr->id_categoria_producto ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($cat->nombre_cat); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group"><label>Notas</label><textarea name="notas" class="form-control"><?php echo htmlspecialchars($pr->notas ?? ''); ?></textarea></div>
                                                    
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar Cambios</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN MODAL EDITAR -->

                                <!-- MODAL ELIMINAR -->
                                <div class="modal fade" id="delete_<?php echo $pr->id_proveedor; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white"><h4 class="modal-title">Eliminar Proveedor</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body">	
                                                <p class="text-center">¿Está seguro de eliminar al proveedor?</p>
                                                <h3 class="text-center"><?php echo htmlspecialchars($pr->nombre_proveedor ?? ''); ?></h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                <a href="?c=proveedor&a=Eliminar&id_proveedor=<?php echo $pr->id_proveedor; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN MODAL ELIMINAR -->
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL NUEVO PROVEEDOR -->
<div class="modal fade" id="newProveedorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h4><i class="fa fa-plus"></i> Nuevo Proveedor</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <form action="?c=proveedor&a=InsEditar&ac=nuevo" method="post">
                    <div class="form-group"><label>Nombre del Proveedor</label><input type="text" name="nombre_proveedor" class="form-control" required></div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Identificador Fiscal (NIT/RUC)</label><input type="text" name="identificador_fiscal" class="form-control"></div>
                        <div class="col-md-6 form-group"><label>Contacto Principal</label><input type="text" name="contacto_principal" class="form-control"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Teléfono</label><input type="text" name="telefono" class="form-control"></div>
                        <div class="col-md-6 form-group"><label>Correo</label><input type="email" name="correo" class="form-control"></div>
                    </div>
                    <div class="form-group"><label>Dirección</label><textarea name="direccion" class="form-control"></textarea></div>
                    <div class="form-group"><label>Categoría Principal</label>
                        <select name="id_categoria_producto" class="form-control">
                            <option value="">-- Seleccione una categoría --</option>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?php echo $cat->id_categoria_producto; ?>"><?php echo htmlspecialchars($cat->nombre_cat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Notas</label><textarea name="notas" class="form-control"></textarea></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- FIN MODAL NUEVO -->