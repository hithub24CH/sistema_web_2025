<!-- ARCHIVO: view/frmcompras.php -->

<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcome-list shadow-reset">
                    <div class="row">
                        <div class="col-lg-6"><a href="index.php?c=compras&a=Nueva" class="btn btn-primary"><i class="fa fa-plus"></i> Nueva Compra</a></div>
                        <div class="col-lg-6"><ul class="breadcome-menu"><li><a href="index.php">Inicio</a> <span class="bread-slash">/</span></li><li><span class="bread-blod">Compras</span></li></ul></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Bloque para mostrar los mensajes de sesión -->
    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['mensaje']['tipo']; ?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo htmlspecialchars($_SESSION['mensaje']['texto']); ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="sparkline8-list shadow-reset">
        <div class="sparkline8-hd"><div class="main-sparkline8-hd"><h1>Historial de Compras</h1></div></div>
        <div class="sparkline8-graph">
            <div class="static-table-list">
                <table id="table" class="table table-striped" data-toggle="table" data-pagination="true" data-search="true">
                    <thead><tr><th>ID</th><th>Proveedor</th><th>Comprador</th><th>Fecha</th><th class="text-right">Total</th><th>Etapa</th><th class="text-center">Estado</th><th class="text-center">Acciones</th></tr></thead>
                    <tbody>
                        <?php foreach($this->model->Listar() as $c): ?>
                            <tr>
                                <td>#<?php echo $c->id_compra; ?></td>
                                <td><?php echo htmlspecialchars($c->proveedor); ?></td>
                                <td><?php echo htmlspecialchars($c->comprador ?? 'N/A'); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($c->fecha_compra)); ?></td>
                                <td class="text-right"><?php echo number_format($c->total_compra, 2); ?> Bs.</td>
                                <td><?php echo htmlspecialchars($c->etapa); ?></td>
                                <td class="text-center">
                                    <?php $clase = ['Recibida'=>'success', 'Anulada'=>'danger', 'Solicitada'=>'info']; ?>
                                    <span class="label label-<?php echo $clase[$c->estado] ?? 'default'; ?>"><?php echo htmlspecialchars($c->estado); ?></span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#view_<?php echo $c->uuid; ?>" data-toggle="tooltip" title="Ver Detalle"><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_<?php echo $c->uuid; ?>" data-toggle="tooltip" title="Editar Proceso"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete_<?php echo $c->uuid; ?>" data-toggle="tooltip" title="Anular Compra"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODALES -->
<?php if (!empty($this->model->Listar())): foreach($this->model->Listar() as $c): ?>
    <!-- VER DETALLE -->
    <div class="modal fade" id="view_<?php echo $c->uuid; ?>" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header bg-info"><h4 class="modal-title" style="color:white"><i class="fa fa-list-alt"></i> Detalle de Compra #<?php echo $c->id_compra; ?></h4></div>
        <div class="modal-body">
            <p><strong>Proveedor:</strong> <?php echo htmlspecialchars($this->model->ObtenerDetalles($c->uuid)['cabecera']->proveedor ?? ''); ?></p>
            <table class="table table-bordered">
                <thead><tr><th>Cant.</th><th>Producto</th><th>Costo Unit.</th><th>Subtotal</th></tr></thead>
                <tbody>
                <?php $detalles = $this->model->ObtenerDetalles($c->uuid)['detalle']; if(!empty($detalles)): foreach($detalles as $p): ?>
                    <tr><td><?php echo $p->cantidad; ?></td><td><?php echo htmlspecialchars($p->nombre_producto); ?></td><td class="text-right"><?php echo number_format($p->precio_unitario_adquisicion, 2); ?></td><td class="text-right"><?php echo number_format($p->subtotal_linea, 2); ?></td></tr>
                <?php endforeach; else: ?><tr><td colspan="4">No hay productos.</td></tr><?php endif; ?>
                </tbody>
            </table>
            <h3 class="text-right"><strong>Total: <?php echo number_format($c->total_compra, 2); ?> Bs.</strong></h3>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button></div>
    </div></div></div>

    <!-- EDITAR PROCESO -->
    <div class="modal fade" id="edit_<?php echo $c->uuid; ?>" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
        <form action="?c=compras&a=ActualizarProceso" method="post">
            <div class="modal-header bg-primary"><h4 class="modal-title" style="color:white;"><i class="fa fa-pencil"></i> Editar Proceso #<?php echo $c->id_compra; ?></h4></div>
            <div class="modal-body">
                <input type="hidden" name="uuid" value="<?php echo $c->uuid; ?>">
                <div class="form-group"><label>Etapa del Proceso</label><input type="text" name="etapa" class="form-control" value="<?php echo htmlspecialchars($c->etapa); ?>"></div>
                <div class="form-group"><label>Estado Físico</label><select name="estado" class="form-control">
                    <option value="Solicitada" <?php if($c->estado=='Solicitada') echo 'selected'; ?>>Solicitada</option>
                    <option value="Recibida" <?php if($c->estado=='Recibida') echo 'selected'; ?>>Recibida</option>
                    <option value="Anulada" <?php if($c->estado=='Anulada') echo 'selected'; ?>>Anulada</option>
                </select></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div></div></div>

    <!-- ANULAR COMPRA -->
    <div class="modal fade" id="delete_<?php echo $c->uuid; ?>" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-danger"><h4 class="modal-title" style="color:white;"><i class="fa fa-warning"></i> Anular Compra</h4></div>
        <div class="modal-body"><p class="text-center">¿Realmente desea anular la compra <strong>#<?php echo $c->id_compra; ?></strong>?</p></div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <a href="?c=compras&a=Anular&uuid=<?php echo $c->uuid; ?>" class="btn btn-danger">Sí, Anular</a>
        </div>
    </div></div></div>
<?php endforeach; endif; ?>
<script>$(function () { $('[data-toggle="tooltip"]').tooltip() });</script>