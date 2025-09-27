<!-- Breadcome start-->
<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcome-list shadow-reset">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="modal-area-button">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newProductoModal">
                                    Nuevo Producto
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Inicio</a> <span class="bread-slash">/</span></li>
                                <li><span class="bread-blod">Productos</span></li>
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
                    <h1>Lista de Productos</h1>
                </div>
            </div>
            <div class="sparkline8-graph">
                <div class="static-table-list">
                    <table id="table" class="table table-striped" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-export="true">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>SKU</th>
                                <th>Nombre Producto</th>
                                <th>Marca</th>
                                <th>Categoría</th>
                                <th>Precio Venta</th>
                                <th>Stock</th>
                                <th>Imagen</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            foreach($this->model->Listar() as $p): 
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($p->codigo_sku ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($p->nombre_producto ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($p->marca ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($p->nombre_categoria ?? ''); ?></td>
                                    <td><?php echo number_format($p->precio_venta_unit, 2); ?> Bs.</td>
                                    <td><?php echo $p->stock_actual; ?></td>
                                    <td>
                                        <?php if (!empty($p->imagen)): ?>
                                            <img src="uploads/productos/<?php echo htmlspecialchars($p->imagen); ?>" alt="Imagen" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            Sin Imagen
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#edit_<?php echo $p->id_producto; ?>" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
                                            <a href="#delete_<?php echo $p->id_producto; ?>" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL EDITAR PRODUCTO (CON CORRECCIONES) -->
                                <div class="modal fade" id="edit_<?php echo $p->id_producto; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h4><i class="fa fa-edit"></i> Editar Producto</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="?c=producto&a=InsEditar&ac=editar" method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="id_producto" value="<?php echo $p->id_producto; ?>">
                                                    
                                                    <div class="form-group"><label>Nombre del Producto</label><input type="text" name="nombre_producto" class="form-control" value="<?php echo htmlspecialchars($p->nombre_producto ?? ''); ?>" required></div>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group"><label>Categoría</label>
                                                            <select name="id_categoria_producto" class="form-control" required>
                                                                <?php foreach($categorias as $cat): ?>
                                                                    <option value="<?php echo $cat->id_categoria_producto; ?>" <?php echo $cat->id_categoria_producto == $p->id_categoria_producto ? 'selected' : ''; ?>>
                                                                        <?php echo htmlspecialchars($cat->nombre_cat); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 form-group"><label>Marca</label><input type="text" name="marca" class="form-control" value="<?php echo htmlspecialchars($p->marca ?? ''); ?>"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group"><label>Código SKU</label><input type="text" name="codigo_sku" class="form-control" value="<?php echo htmlspecialchars($p->codigo_sku ?? ''); ?>"></div>
                                                        <div class="col-md-6 form-group"><label>Nro. de Serie</label><input type="text" name="nro_serie" class="form-control" value="<?php echo htmlspecialchars($p->nro_serie ?? ''); ?>"></div>
                                                    </div>
                                                    <div class="form-group"><label>Descripción</label><textarea name="descripcion" class="form-control"><?php echo htmlspecialchars($p->descripcion ?? ''); ?></textarea></div>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group"><label>Precio Venta</label><input type="number" step="0.01" name="precio_venta_unit" class="form-control" value="<?php echo $p->precio_venta_unit; ?>" required></div>
                                                        <div class="col-md-6 form-group"><label>Costo Adquisición</label><input type="number" step="0.01" name="costo_unit_adquisicion" class="form-control" value="<?php echo $p->costo_unit_adquisicion; ?>"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group"><label>Stock Actual</label><input type="number" name="stock_actual" class="form-control" value="<?php echo $p->stock_actual; ?>" required></div>
                                                        <div class="col-md-6 form-group"><label>Unidad de Medida</label><input type="text" name="unidad_medida" class="form-control" value="<?php echo htmlspecialchars($p->unidad_medida ?? 'Unidad'); ?>"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Imagen Actual:</label>
                                                        <div>
                                                            <?php if (!empty($p->imagen)): ?>
                                                                <img src="uploads/productos/<?php echo htmlspecialchars($p->imagen); ?>" style="max-width: 100px; margin-bottom: 10px;">
                                                            <?php endif; ?>
                                                        </div>
                                                        <label>Cambiar Imagen (opcional)</label>
                                                        <input type="file" name="imagen_nueva" class="form-control-file">
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
                                <div class="modal fade" id="delete_<?php echo $p->id_producto; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white"><h4 class="modal-title">Eliminar Producto</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body">	
                                                <p class="text-center">¿Está seguro de eliminar el producto?</p>
                                                <h3 class="text-center"><?php echo htmlspecialchars($p->nombre_producto ?? ''); ?></h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                <a href="?c=producto&a=Eliminar&id_producto=<?php echo $p->id_producto; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
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

<!-- MODAL NUEVO PRODUCTO -->
<div class="modal fade" id="newProductoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h4><i class="fa fa-plus"></i> Nuevo Producto</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <form action="?c=producto&a=InsEditar&ac=nuevo" method="post" enctype="multipart/form-data">
                    <div class="form-group"><label>Nombre del Producto</label><input type="text" name="nombre_producto" class="form-control" required></div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Categoría</label>
                            <select name="id_categoria_producto" class="form-control" required>
                                <option value="">Seleccione una categoría...</option>
                                <?php foreach($categorias as $cat): ?>
                                    <option value="<?php echo $cat->id_categoria_producto; ?>"><?php echo htmlspecialchars($cat->nombre_cat); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group"><label>Marca</label><input type="text" name="marca" class="form-control"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Código SKU</label><input type="text" name="codigo_sku" class="form-control"></div>
                        <div class="col-md-6 form-group"><label>Nro. de Serie</label><input type="text" name="nro_serie" class="form-control"></div>
                    </div>
                    <div class="form-group"><label>Descripción</label><textarea name="descripcion" class="form-control"></textarea></div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Precio Venta</label><input type="number" step="0.01" name="precio_venta_unit" class="form-control" required></div>
                        <div class="col-md-6 form-group"><label>Costo Adquisición</label><input type="number" step="0.01" name="costo_unit_adquisicion" class="form-control"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Stock Actual</label><input type="number" name="stock_actual" class="form-control" required></div>
                        <div class="col-md-6 form-group"><label>Unidad de Medida</label><input type="text" name="unidad_medida" class="form-control" value="Unidad"></div>
                    </div>
                    <div class="form-group"><label>Imagen del Producto</label><input type="file" name="imagen_nueva" class="form-control-file"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar Producto</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- FIN MODAL NUEVO -->