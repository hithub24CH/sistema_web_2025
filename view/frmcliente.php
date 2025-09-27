<!-- Breadcome start-->
<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcome-list shadow-reset">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="modal-area-button">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newClienteModal">
                                    Nuevo Cliente
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Inicio</a> <span class="bread-slash">/</span></li>
                                <li><span class="bread-blod">Clientes</span></li>
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
                    <h1>Lista de Clientes</h1>
                </div>
            </div>
            <div class="sparkline8-graph">
                <div class="static-table-list">
                    <table id="table" class="table table-striped" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Código</th>
                                <th>Nombre / Razón Social</th>
                                <th>Tipo</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            foreach($this->modelCliente->Listar() as $c): 
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($c->codigo_cliente); ?></td>
                                    <td><?php echo htmlspecialchars($c->nombre_completo); ?></td>
                                    <td><span class="label label-<?php echo ($c->tipo_cliente == 'Persona') ? 'info' : 'success'; ?>"><?php echo htmlspecialchars($c->tipo_cliente); ?></span></td>
                                    
                                    <!-- CORRECCIÓN: Usamos el operador '??' para evitar el error con valores NULL -->
                                    <td><?php echo htmlspecialchars($c->telefono ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($c->correo ?? ''); ?></td>
                                    
                                    <td>
                                        <div class="btn-group">
                                            <a href="#edit_<?php echo $c->id_cliente; ?>" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
                                            <a href="#delete_<?php echo $c->id_cliente; ?>" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL EDITAR CLIENTE -->
                                <div class="modal fade" id="edit_<?php echo $c->id_cliente; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h4><i class="fa fa-edit"></i> Editar Cliente</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="?c=cliente&a=InsEditar" method="post">
                                                    <input type="hidden" name="ac" value="editar">
                                                    <input type="hidden" name="id_cliente" value="<?php echo $c->id_cliente; ?>">
                                                    <input type="hidden" name="tipo_cliente" value="<?php echo $c->tipo_cliente; ?>">
                                                    
                                                    <?php if ($c->tipo_cliente == 'Persona'): ?>
                                                        <input type="hidden" name="id_persona" value="<?php echo $c->id_persona; ?>">
                                                    <?php else: ?>
                                                        <input type="hidden" name="id_empresa" value="<?php echo $c->id_empresa; ?>">
                                                    <?php endif; ?>

                                                    <h4>Datos de <?php echo $c->tipo_cliente; ?></h4>
                                                    <hr>

                                                    <div style="display: <?php echo ($c->tipo_cliente == 'Persona') ? 'block' : 'none'; ?>;">
                                                        <div class="row">
                                                            <div class="col-md-4 form-group"><label>Nombres</label><input type="text" name="nombres" class="form-control" value="<?php echo htmlspecialchars($c->nombres ?? ''); ?>"></div>
                                                            <div class="col-md-4 form-group"><label>Apellido Paterno</label><input type="text" name="apellido_paterno" class="form-control" value="<?php echo htmlspecialchars($c->apellido_paterno ?? ''); ?>"></div>
                                                            <div class="col-md-4 form-group"><label>Apellido Materno</label><input type="text" name="apellido_materno" class="form-control" value="<?php echo htmlspecialchars($c->apellido_materno ?? ''); ?>"></div>
                                                        </div>
                                                        <div class="form-group"><label>CI</label><input type="text" name="ci" class="form-control" value="<?php echo htmlspecialchars($c->ci ?? ''); ?>"></div>
                                                    </div>

                                                    <div style="display: <?php echo ($c->tipo_cliente == 'Empresa') ? 'block' : 'none'; ?>;">
                                                        <div class="form-group"><label>Razón Social</label><input type="text" name="razon_social" class="form-control" value="<?php echo htmlspecialchars($c->razon_social ?? ''); ?>"></div>
                                                        <div class="row">
                                                            <div class="col-md-6 form-group"><label>Nombre Comercial</label><input type="text" name="nombre_comercial" class="form-control" value="<?php echo htmlspecialchars($c->nombre_comercial ?? ''); ?>"></div>
                                                            <div class="col-md-6 form-group"><label>Identificador Fiscal (NIT/RUC)</label><input type="text" name="identificador_fiscal" class="form-control" value="<?php echo htmlspecialchars($c->identificador_fiscal ?? ''); ?>"></div>
                                                        </div>
                                                    </div>

                                                    <h4>Datos Comunes</h4>
                                                    <hr>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-4 form-group"><label>Código Cliente</label><input type="text" name="codigo_cliente" class="form-control" value="<?php echo htmlspecialchars($c->codigo_cliente ?? ''); ?>" required></div>
                                                        <div class="col-md-4 form-group"><label>Teléfono</label><input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($c->telefono ?? ''); ?>"></div>
                                                        <div class="col-md-4 form-group"><label>Correo</label><input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($c->correo ?? ''); ?>"></div>
                                                    </div>

                                                    <div class="form-group"><label>Dirección</label><textarea name="direccion" class="form-control"><?php echo htmlspecialchars($c->direccion ?? ''); ?></textarea></div>
                                                    <div class="form-group"><label>Notas</label><textarea name="notas" class="form-control"><?php echo htmlspecialchars($c->notas ?? ''); ?></textarea></div>
                                                    
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
                                <div class="modal fade" id="delete_<?php echo $c->id_cliente; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h4 class="modal-title">Eliminar Cliente</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">	
                                                <p class="text-center">¿Está seguro de eliminar al cliente?</p>
                                                <h3 class="text-center"><?php echo htmlspecialchars($c->nombre_completo ?? ''); ?></h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                                                <a href="?c=cliente&a=Eliminar&id_cliente=<?php echo $c->id_cliente; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
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

<!-- MODAL NUEVO CLIENTE -->
<div class="modal fade" id="newClienteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4><i class="fa fa-plus"></i> Nuevo Cliente</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="?c=cliente&a=InsEditar" method="post">
                    <input type="hidden" name="ac" value="nuevo">
                    <div class="form-group">
                        <label>Tipo de Cliente</label>
                        <select name="tipo_cliente" id="tipo_cliente_selector" class="form-control" required>
                            <option value="">Seleccione un tipo...</option>
                            <option value="Persona">Persona</option>
                            <option value="Empresa">Empresa</option>
                        </select>
                    </div>
                    <hr>
                    
                    <div id="campos_persona" style="display:none;">
                        <h4>Datos de la Persona</h4>
                        <div class="row">
                            <div class="col-md-4 form-group"><label>Nombres</label><input type="text" name="nombres" class="form-control"></div>
                            <div class="col-md-4 form-group"><label>Apellido Paterno</label><input type="text" name="apellido_paterno" class="form-control"></div>
                            <div class="col-md-4 form-group"><label>Apellido Materno</label><input type="text" name="apellido_materno" class="form-control"></div>
                        </div>
                        <div class="form-group"><label>CI</label><input type="text" name="ci" class="form-control"></div>
                    </div>
                    
                    <div id="campos_empresa" style="display:none;">
                        <h4>Datos de la Empresa</h4>
                        <div class="form-group"><label>Razón Social</label><input type="text" name="razon_social" class="form-control"></div>
                        <div class="row">
                            <div class="col-md-6 form-group"><label>Nombre Comercial</label><input type="text" name="nombre_comercial" class="form-control"></div>
                            <div class="col-md-6 form-group"><label>Identificador Fiscal (NIT/RUC)</label><input type="text" name="identificador_fiscal" class="form-control"></div>
                        </div>
                    </div>
                    
                    <h4>Datos Comunes</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 form-group"><label>Código Cliente</label><input type="text" name="codigo_cliente" class="form-control" required></div>
                        <div class="col-md-4 form-group"><label>Teléfono</label><input type="text" name="telefono" class="form-control"></div>
                        <div class="col-md-4 form-group"><label>Correo</label><input type="email" name="correo" class="form-control"></div>
                    </div>
                    <div class="form-group"><label>Dirección</label><textarea name="direccion" class="form-control"></textarea></div>
                    <div class="form-group"><label>Notas</label><textarea name="notas" class="form-control"></textarea></div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar Cliente</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- FIN MODAL NUEVO -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoClienteSelector = document.getElementById('tipo_cliente_selector');
    if (tipoClienteSelector) {
        tipoClienteSelector.addEventListener('change', function() {
            var tipo = this.value;
            document.getElementById('campos_persona').style.display = (tipo === 'Persona') ? 'block' : 'none';
            document.getElementById('campos_empresa').style.display = (tipo === 'Empresa') ? 'block' : 'none';
        });
    }
});
</script>