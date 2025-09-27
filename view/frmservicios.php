<!-- Breadcome start-->
<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcome-list shadow-reset">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="modal-area-button">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newServicioModal">
                                    Nuevo Servicio
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Inicio</a> <span class="bread-slash">/</span></li>
                                <li><span class="bread-blod">Servicios</span></li>
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
                    <h1>Lista de Servicios</h1>
                </div>
            </div>
            <div class="sparkline8-graph">
                <div class="static-table-list">
                    <table id="table" class="table table-striped" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Código</th>
                                <th>Nombre Servicio</th>
                                <th>Categoría</th>
                                <th>Tipo Cobro</th>
                                <th>Precio Base</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            foreach($this->model->Listar() as $s): 
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo htmlspecialchars($s->codigo_servicio); ?></td>
                                    <td><?php echo htmlspecialchars($s->nombre_servicio); ?></td>
                                    <td><?php echo htmlspecialchars($s->nombre_categoria_servicio); ?></td>
                                    <td><?php echo htmlspecialchars($s->tipo_cobro); ?></td>
                                    <td><?php echo number_format($s->precio_base, 2); ?> Bs.</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#edit_<?php echo $s->id_servicio; ?>" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
                                            <a href="#delete_<?php echo $s->id_servicio; ?>" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL EDITAR SERVICIO -->
                                <div class="modal fade" id="edit_<?php echo $s->id_servicio; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h4><i class="fa fa-edit"></i> Editar Servicio</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- CORRECCIÓN: La URL del action apunta al controlador en plural -->
                                                <form action="?c=servicios&a=InsEditar&ac=editar" method="post">
                                                    <input type="hidden" name="id_servicio" value="<?php echo $s->id_servicio; ?>">
                                                    
                                                    <div class="form-group">
                                                        <label>Nombre del Servicio</label>
                                                        <input type="text" name="nombre_servicio" class="form-control" value="<?php echo htmlspecialchars($s->nombre_servicio); ?>" required>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 form-group">
                                                            <label>Categoría</label>
                                                            <select name="id_categoria_servicio" class="form-control" required>
                                                                <?php foreach($categorias as $cat): ?>
                                                                    <option value="<?php echo $cat->id_categoria_servicio; ?>" <?php echo $cat->id_categoria_servicio == $s->id_categoria_servicio ? 'selected' : ''; ?>>
                                                                        <?php echo htmlspecialchars($cat->nombre_categoria_servicio); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label>Código del Servicio</label>
                                                            <input type="text" name="codigo_servicio" class="form-control" value="<?php echo htmlspecialchars($s->codigo_servicio); ?>" placeholder="Ej: INST-CAM-01">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label>Descripción</label>
                                                        <textarea name="descripcion" class="form-control"><?php echo htmlspecialchars($s->descripcion); ?></textarea>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <label>Tipo de Cobro</label>
                                                            <select name="tipo_cobro" class="form-control" required>
                                                                <option value="Por Hora" <?php echo $s->tipo_cobro == 'Por Hora' ? 'selected' : ''; ?>>Por Hora</option>
                                                                <option value="Fijo" <?php echo $s->tipo_cobro == 'Fijo' ? 'selected' : ''; ?>>Fijo</option>
                                                                <option value="Mensual" <?php echo $s->tipo_cobro == 'Mensual' ? 'selected' : ''; ?>>Mensual</option>
                                                                <option value="Anual" <?php echo $s->tipo_cobro == 'Anual' ? 'selected' : ''; ?>>Anual</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label>Precio Base</label>
                                                            <input type="number" step="0.01" name="precio_base" class="form-control" value="<?php echo $s->precio_base; ?>" required>
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label>Duración Estimada</label>
                                                            <input type="text" name="duracion_estimada" class="form-control" value="<?php echo htmlspecialchars($s->duracion_estimada); ?>" placeholder="Ej: 2 horas">
                                                        </div>
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
                                <div class="modal fade" id="delete_<?php echo $s->id_servicio; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h4 class="modal-title">Eliminar Servicio</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">	
                                                <p class="text-center">¿Está seguro de eliminar el servicio?</p>
                                                <h3 class="text-center"><?php echo htmlspecialchars($s->nombre_servicio); ?></h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                <!-- CORRECCIÓN: La URL del action apunta al controlador en plural -->
                                                <a href="?c=servicios&a=Eliminar&id_servicio=<?php echo $s->id_servicio; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN MODAL ELIMINAR -->
                            <?php 
                            $i++;
                            endforeach; 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL NUEVO SERVICIO -->
<div class="modal fade" id="newServicioModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4><i class="fa fa-plus"></i> Nuevo Servicio</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- CORRECCIÓN: La URL del action apunta al controlador en plural -->
                <form action="?c=servicios&a=InsEditar&ac=nuevo" method="post">
                    <div class="form-group">
                        <label>Nombre del Servicio</label>
                        <input type="text" name="nombre_servicio" class="form-control" placeholder="Ej: Instalación de Cámara (Unidad)" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Categoría</label>
                            <select name="id_categoria_servicio" class="form-control" required>
                                <option value="">Seleccione una categoría...</option>
                                <?php foreach($categorias as $cat): ?>
                                    <option value="<?php echo $cat->id_categoria_servicio; ?>"><?php echo htmlspecialchars($cat->nombre_categoria_servicio); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Código del Servicio</label>
                            <input type="text" name="codigo_servicio" class="form-control" placeholder="Ej: INST-CAM-01">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" placeholder="Breve descripción del servicio"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Tipo de Cobro</label>
                            <select name="tipo_cobro" class="form-control" required>
                                <option value="Fijo">Fijo</option>
                                <option value="Por Hora">Por Hora</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Anual">Anual</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Precio Base</label>
                            <input type="number" step="0.01" name="precio_base" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Duración Estimada</label>
                            <input type="text" name="duracion_estimada" class="form-control" placeholder="Ej: 2 horas">
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar Servicio</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- FIN MODAL NUEVO -->