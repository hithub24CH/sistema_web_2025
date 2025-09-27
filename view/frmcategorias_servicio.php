<!-- Breadcome start-->
<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcome-list shadow-reset">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="modal-area-button">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newCategoriaModal">
                                    Nueva Categoría de Servicio
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Inicio</a> <span class="bread-slash">/</span></li>
                                <li><span class="bread-blod">Categorías de Servicios</span></li>
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
                    <h1>Lista de Categorías de Servicios</h1>
                </div>
            </div>
            <div class="sparkline8-graph">
                <div class="static-table-list">
                    <table id="table" class="table table-striped" data-toggle="table" data-pagination="true" data-search="true">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            foreach($this->model->Listar() as $cat): 
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($cat->nombre_categoria_servicio ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($cat->descripcion ?? ''); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#edit_<?php echo $cat->id_categoria_servicio; ?>" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
                                            <a href="#delete_<?php echo $cat->id_categoria_servicio; ?>" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL EDITAR -->
                                <div class="modal fade" id="edit_<?php echo $cat->id_categoria_servicio; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white"><h4><i class="fa fa-edit"></i> Editar Categoría</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body">
                                                <form action="?c=categorias_servicio&a=InsEditar&ac=editar" method="post">
                                                    <input type="hidden" name="id_categoria_servicio" value="<?php echo $cat->id_categoria_servicio; ?>">
                                                    <div class="form-group">
                                                        <label>Nombre</label>
                                                        <input type="text" name="nombre_categoria_servicio" class="form-control" value="<?php echo htmlspecialchars($cat->nombre_categoria_servicio ?? ''); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Descripción</label>
                                                        <textarea name="descripcion" class="form-control"><?php echo htmlspecialchars($cat->descripcion ?? ''); ?></textarea>
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
                                <!-- FIN MODAL EDITAR -->

                                <!-- MODAL ELIMINAR -->
                                <div class="modal fade" id="delete_<?php echo $cat->id_categoria_servicio; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white"><h4 class="modal-title">Eliminar Categoría</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body">	
                                                <p class="text-center">¿Está seguro de eliminar la categoría?</p>
                                                <h3 class="text-center"><?php echo htmlspecialchars($cat->nombre_categoria_servicio ?? ''); ?></h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                <a href="?c=categorias_servicio&a=Eliminar&id_categoria_servicio=<?php echo $cat->id_categoria_servicio; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
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

<!-- MODAL NUEVA CATEGORÍA -->
<div class="modal fade" id="newCategoriaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h4><i class="fa fa-plus"></i> Nueva Categoría</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <form action="?c=categorias_servicio&a=InsEditar&ac=nuevo" method="post">
                    <div class="form-group"><label>Nombre</label><input type="text" name="nombre_categoria_servicio" class="form-control" required></div>
                    <div class="form-group"><label>Descripción</label><textarea name="descripcion" class="form-control"></textarea></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- FIN MODAL NUEVO -->s