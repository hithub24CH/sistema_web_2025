<!-- ARCHIVO: view/frmventa.php -->

<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid"><div class="row"><div class="col-lg-12"><div class="breadcome-list shadow-reset"><div class="row">
        <div class="col-lg-6"><a href="index.php?c=ventas&a=Nueva" class="btn btn-primary"><i class="fa fa-plus"></i> Nueva Venta</a></div>
        <div class="col-lg-6"><ul class="breadcome-menu"><li><a href="index.php">Inicio</a> <span class="bread-slash">/</span></li><li><span class="bread-blod">Ventas</span></li></ul></div>
    </div></div></div></div></div>
</div>

<div class="container-fluid">
    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['mensaje']['tipo']; ?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo htmlspecialchars($_SESSION['mensaje']['texto']); ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="sparkline8-list shadow-reset">
        <div class="sparkline8-hd"><div class="main-sparkline8-hd"><h1>Historial de Ventas</h1></div></div>
        <div class="sparkline8-graph"><div class="static-table-list">
            <table id="table" class="table table-striped" data-toggle="table" data-pagination="true" data-search="true">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th class="text-right">Total</th>
                        <th>Etapa</th>
                        <th class="text-center">Pago</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($this->model->Listar() as $v): ?>
                        <tr>
                            <td>#<?php echo $v->id_venta; ?></td>
                            <td><?php echo htmlspecialchars($v->cliente); ?></td>
                            <td><?php echo htmlspecialchars($v->vendedor); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($v->fecha_venta)); ?></td>
                            <td>
                                <?php 
                                    $tipos = [1 => 'Productos', 2 => 'Servicios', 3 => 'Mixto'];
                                    echo $tipos[$v->tipo_venta] ?? 'N/A';
                                ?>
                            </td>
                            <td class="text-right"><?php echo number_format($v->total_venta, 2); ?> Bs.</td>
                            <td>
                                <?php $etapas = ['Cotizacion'=>'info', 'Confirmada'=>'primary', 'Facturada'=>'success', 'Anulada'=>'danger']; ?>
                                <span class="label label-<?php echo $etapas[$v->etapa] ?? 'default'; ?>"><?php echo htmlspecialchars($v->etapa); ?></span>
                            </td>
                            <td class="text-center">
                                <?php $pagos = ['Pendiente'=>'warning', 'Parcialmente Pagado'=>'info', 'Pagado'=>'success']; ?>
                                <span class="label label-<?php echo $pagos[$v->estado_pago] ?? 'default'; ?>"><?php echo htmlspecialchars($v->estado_pago); ?></span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#view_<?php echo $v->uuid; ?>" title="Ver Detalle"><i class="fa fa-eye"></i></button>
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_<?php echo $v->uuid; ?>" title="Editar Proceso"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete_<?php echo $v->uuid; ?>" title="Anular Venta"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div></div>
    </div>
</div>

<!-- MODALES -->
<?php if (!empty($this->model->Listar())): foreach($this->model->Listar() as $v): ?>
    <!-- VER DETALLE -->
    <div class="modal fade" id="view_<?php echo $v->uuid; ?>" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header bg-info"><h4 class="modal-title" style="color:white"><i class="fa fa-list-alt"></i> Detalle de Venta #<?php echo $v->id_venta; ?></h4></div>
        <div class="modal-body">
            <?php $detalles = $this->model->ObtenerDetalles($v->uuid); ?>
            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($detalles['cabecera']->cliente ?? ''); ?></p><hr>
            <h5>Productos</h5>
            <table class="table table-bordered">
                <thead><tr><th>Cant.</th><th>Producto</th><th>P. Unit.</th><th>Desc.</th><th>Subtotal</th></tr></thead>
                <tbody>
                <?php if(!empty($detalles['productos'])): foreach($detalles['productos'] as $p): ?>
                    <tr><td><?php echo $p->cantidad; ?></td><td><?php echo htmlspecialchars($p->nombre_producto); ?></td><td class="text-right"><?php echo number_format($p->precio_unitario, 2); ?></td><td class="text-right"><?php echo number_format($p->descuento_linea, 2); ?></td><td class="text-right"><?php echo number_format($p->subtotal_linea, 2); ?></td></tr>
                <?php endforeach; else: ?><tr><td colspan="5" class="text-center">No hay productos en esta venta.</td></tr><?php endif; ?>
                </tbody>
            </table><hr>
            <h5>Servicios</h5>
            <table class="table table-bordered">
                <thead><tr><th>Cant.</th><th>Servicio</th><th>P. Unit.</th><th>Subtotal</th></tr></thead>
                <tbody>
                <?php if(!empty($detalles['servicios'])): foreach($detalles['servicios'] as $s): ?>
                    <tr><td><?php echo $s->cantidad; ?></td><td><?php echo htmlspecialchars($s->nombre_servicio); ?></td><td class="text-right"><?php echo number_format($s->precio_unitario, 2); ?></td><td class="text-right"><?php echo number_format($s->subtotal_linea, 2); ?></td></tr>
                <?php endforeach; else: ?><tr><td colspan="4" class="text-center">No hay servicios en esta venta.</td></tr><?php endif; ?>
                </tbody>
            </table>
            <h3 class="text-right"><strong>Total Venta: <?php echo number_format($v->total_venta, 2); ?> Bs.</strong></h3>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button></div>
    </div></div></div>

    <!-- EDITAR PROCESO -->
    <div class="modal fade" id="edit_<?php echo $v->uuid; ?>" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
        <form action="?c=ventas&a=ActualizarProceso" method="post">
            <div class="modal-header bg-primary"><h4 class="modal-title" style="color:white;"><i class="fa fa-pencil"></i> Editar Proceso #<?php echo $v->id_venta; ?></h4></div>
            <div class="modal-body">
                <input type="hidden" name="uuid" value="<?php echo $v->uuid; ?>">
                <div class="form-group"><label>Etapa de la Venta</label><select name="etapa" class="form-control">
                    <option value="Cotizacion" <?php if($v->etapa=='Cotizacion') echo 'selected'; ?>>Cotización</option>
                    <option value="Confirmada" <?php if($v->etapa=='Confirmada') echo 'selected'; ?>>Confirmada</option>
                    <option value="Facturada" <?php if($v->etapa=='Facturada') echo 'selected'; ?>>Facturada</option>
                    <option value="Anulada" <?php if($v->etapa=='Anulada') echo 'selected'; ?>>Anulada</option>
                </select></div>
                <div class="form-group"><label>Estado del Pago</label><select name="estado_pago" class="form-control">
                    <option value="Pendiente" <?php if($v->estado_pago=='Pendiente') echo 'selected'; ?>>Pendiente</option>
                    <option value="Parcialmente Pagado" <?php if($v->estado_pago=='Parcialmente Pagado') echo 'selected'; ?>>Parcialmente Pagado</option>
                    <option value="Pagado" <?php if($v->estado_pago=='Pagado') echo 'selected'; ?>>Pagado</option>
                </select></div>
                 <div class="form-group"><label>Notas / Observaciones</label><textarea name="notas_obs" class="form-control" rows="3"><?php echo htmlspecialchars($v->notas_obs ?? ''); ?></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div></div></div>

    <!-- ANULAR VENTA -->
    <div class="modal fade" id="delete_<?php echo $v->uuid; ?>" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-danger"><h4 class="modal-title" style="color:white;"><i class="fa fa-warning"></i> Anular Venta</h4></div>
        <div class="modal-body"><p class="text-center">¿Realmente desea anular la venta <strong>#<?php echo $v->id_venta; ?></strong>?</p></div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <a href="?c=ventas&a=Anular&uuid=<?php echo $v->uuid; ?>" class="btn btn-danger">Sí, Anular</a>
        </div>
    </div></div></div>
<?php endforeach; endif; ?>