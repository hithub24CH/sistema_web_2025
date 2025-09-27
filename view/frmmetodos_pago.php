html
<!-- Breadcome start-->
<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcome-list shadow-reset">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="modal-area-button">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newMetodoModal">
                                    Nuevo Método de Pago
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Inicio</a> <span class="bread-slash">/</span></li>
                                <li><span class="bread-blod">Métodos de Pago</span></li>
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
            <div class="sparkline8-hd"><div class="main-sparkline8-hd"><h1>Lista de Métodos de Pago</h1></div></div>
            <div class="sparkline8-graph">
                <div class="static-table-list">
                    <table id="table" class="table table-striped" data-toggle="table" data-pagination="true" data-search="true">
                        <thead><tr><th>N°</th><th>Nombre</th><th>Descripción</th><th>Acción</th></tr></thead>
                        <tbody>
                            <?php $i = 1; foreach($this->model->Listar() as $m): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($m->nombre_metodo ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($m->descripcion ?? ''); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#edit_<?php echo $m->id_metodo_pago; ?>" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
                                            <a href="#delete_<?php echo $m->id_metodo_pago; ?>" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <!-- MODAL EDITAR -->
                                <div class="modal fade" id="edit_<?php echo $m->id_metodo_pago; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white"><h4><i class="fa fa-edit"></i> Editar Método de Pago</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body">
                                                <form action="?c=metodos_pago&a=InsEditar&ac=editar" method="post">
                                                    <input type="hidden" name="id_metodo_pago" value="<?php echo $m->id_metodo_pago; ?>">
                                                    <div class="form-group"><label>Nombre</label><input type="text" name="nombre_metodo" class="form-control" value="<?php echo htmlspecialchars($m->nombre_metodo ?? ''); ?>" required></div>
                                                    <div class="form-group"><label>Descripción</label><textarea name="descripcion" class="form-control"><?php echo htmlspecialchars($m->descripcion ?? ''); ?></textarea></div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar Cambios</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- MODAL ELIMINAR -->
                                <div class="modal fade" id="delete_<?php echo $m->id_metodo_pago; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white"><h4 class="modal-title">Eliminar Método de Pago</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body"><p class="text-center">¿Está seguro de eliminar?</p><h3 class="text-center"><?php echo htmlspecialchars($m->nombre_metodo ?? ''); ?></h3></div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                <a href="?c=metodos_pago&a=Eliminar&id_metodo_pago=<?php echo $m->id_metodo_pago; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
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
<!-- MODAL NUEVO -->
<div class="modal fade" id="newMetodoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h4><i class="fa fa-plus"></i> Nuevo Método de Pago</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <form action="?c=metodos_pago&a=InsEditar&ac=nuevo" method="post">
                    <div class="form-group"><label>Nombre</label><input type="text" name="nombre_metodo" class="form-control" required></div>
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