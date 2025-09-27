<!-- ARCHIVO: view/frmnuevaventa.php -->

<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid"><div class="row"><div class="col-lg-12"><div class="breadcome-list shadow-reset"><div class="row">
        <div class="col-lg-6"><h3>Crear Nueva Venta</h3></div>
        <div class="col-lg-6"><ul class="breadcome-menu">
            <li><a href="index.php?c=ventas&a=IndexPage">Ventas</a> <span class="bread-slash">/</span></li>
            <li><span class="bread-blod">Nueva Venta</span></li>
        </ul></div>
    </div></div></div></div></div>
</div>

<div class="container-fluid">
    <form action="index.php?c=ventas&a=Guardar" method="post" id="frm-venta">
        <div class="row">
            <div class="col-lg-8">
                <!-- SECCIÓN PRODUCTOS -->
                <div class="sparkline8-list shadow-reset mg-b-15"><div class="sparkline8-graph">
                    <label>Añadir Producto</label>
                    <div class="input-group">
                        <select id="select-producto" class="form-control"><option value="">Buscar producto...</option>
                            <?php foreach($productos as $p): ?><option value="<?php echo $p->id_producto; ?>" data-precio="<?php echo $p->precio_venta_unit; ?>"><?php echo htmlspecialchars($p->nombre_producto); ?></option><?php endforeach; ?>
                        </select>
                        <span class="input-group-btn"><button type="button" class="btn btn-primary" onclick="agregarProducto()">Añadir</button></span>
                    </div><hr>
                    <h5>Productos Añadidos</h5>
                    <div class="table-responsive"><table class="table"><thead><tr><th>Producto</th><th>Cant.</th><th>Precio</th><th>Desc.</th><th>Subtotal</th><th></th></tr></thead><tbody id="tbody-productos"></tbody></table></div>
                </div></div>
                <!-- SECCIÓN SERVICIOS -->
                <div class="sparkline8-list shadow-reset"><div class="sparkline8-graph">
                    <label>Añadir Servicio</label>
                    <div class="input-group">
                        <select id="select-servicio" class="form-control"><option value="">Buscar servicio...</option>
                            <?php foreach($servicios as $s): ?><option value="<?php echo $s->id_servicio; ?>" data-precio="<?php echo $s->precio_base; ?>"><?php echo htmlspecialchars($s->nombre_servicio); ?></option><?php endforeach; ?>
                        </select>
                        <span class="input-group-btn"><button type="button" class="btn btn-primary" onclick="agregarServicio()">Añadir</button></span>
                    </div><hr>
                    <h5>Servicios Añadidos</h5>
                    <div class="table-responsive"><table class="table"><thead><tr><th>Servicio</th><th>Cant.</th><th>Precio</th><th>Subtotal</th><th></th></tr></thead><tbody id="tbody-servicios"></tbody></table></div>
                </div></div>
            </div>
            <div class="col-lg-4"><div class="sparkline8-list shadow-reset"><div class="sparkline8-graph">
                <h4>Cierre de Venta</h4>
                <div class="form-group"><label>Cliente</label><select name="id_cliente" class="form-control" required><option value="">Seleccione...</option>
                    <?php foreach($clientes as $c): ?>
                        <!-- ========================================================== -->
                        <!-- CORRECCIÓN CLAVE AQUÍ: de 'Nombre_completo' a 'nombre_completo' -->
                        <!-- ========================================================== -->
                        <option value="<?php echo $c->id_cliente; ?>"><?php echo htmlspecialchars($c->nombre_completo); ?></option>
                    <?php endforeach; ?>
                </select></div>
                <div class="form-group"><label>Vendedor Asignado</label><select name="id_usuario_vendedor" class="form-control" required><option value="">Seleccione...</option>
                    <?php foreach($usuarios as $u): ?><option value="<?php echo $u->id_usuario; ?>"><?php echo htmlspecialchars($u->nombre_empleado ?? $u->nombre_usuario); ?></option><?php endforeach; ?>
                </select></div>
                <div class="form-group"><label>Etapa Inicial</label><select name="etapa" class="form-control">
                    <option value="Cotizacion">Cotización</option><option value="Confirmada" selected>Confirmada</option>
                </select></div>
                <div class="form-group"><label>Notas / Observaciones</label><textarea name="notas_obs" class="form-control" rows="3"></textarea></div><hr>
                <div style="display: flex; justify-content: space-between;">
                    <h3><strong>Total:</strong></h3>
                    <h3><strong id="span-total">0.00</strong> Bs.</h3>
                </div><hr>
                <button type="submit" class="btn btn-success btn-block btn-lg"><i class="fa fa-save"></i> Guardar Venta</button>
            </div></div></div>
        </div>
    </form>
</div>
<script>
function agregarProducto(){
    const select=document.getElementById('select-producto'),o=select.options[select.selectedIndex];if(!o.value)return;
    const id=o.value,nombre=o.text,precio=parseFloat(o.getAttribute('data-precio'))||0;if(document.getElementById(`prod-${id}`))return alert('Producto ya añadido.');
    const fila=`<tr id="prod-${id}" class="item-venta"><td><input type="hidden" name="productos_id[]" value="${id}">${nombre}</td><td><input type="number" name="productos_cantidad[]" value="1" class="form-control cant" style="width:70px" min="1" oninput="calcularTotales()"></td><td><input type="number" step="0.01" name="productos_precio[]" value="${precio.toFixed(2)}" class="form-control precio" style="width:90px" oninput="calcularTotales()"></td><td><input type="number" step="0.01" name="productos_descuento[]" value="0.00" class="form-control desc" style="width:90px" oninput="calcularTotales()"></td><td class="subtotal text-right">${precio.toFixed(2)}</td><td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();calcularTotales();">&times;</button></td></tr>`;
    document.getElementById('tbody-productos').insertAdjacentHTML('beforeend',fila);calcularTotales();
}
function agregarServicio(){
    const select=document.getElementById('select-servicio'),o=select.options[select.selectedIndex];if(!o.value)return;
    const id=o.value,nombre=o.text,precio=parseFloat(o.getAttribute('data-precio'))||0;if(document.getElementById(`serv-${id}`))return alert('Servicio ya añadido.');
    const fila=`<tr id="serv-${id}" class="item-venta"><td><input type="hidden" name="servicios_id[]" value="${id}">${nombre}</td><td><input type="number" name="servicios_cantidad[]" value="1" class="form-control cant" style="width:70px" min="1" oninput="calcularTotales()"></td><td><input type="number" step="0.01" name="servicios_precio[]" value="${precio.toFixed(2)}" class="form-control precio" style="width:90px" oninput="calcularTotales()"></td><td class="subtotal text-right">${precio.toFixed(2)}</td><td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();calcularTotales();">&times;</button></td></tr>`;
    document.getElementById('tbody-servicios').insertAdjacentHTML('beforeend',fila);calcularTotales();
}
function calcularTotales(){
    let total=0;document.querySelectorAll('.item-venta').forEach(fila=>{
        const cant=parseFloat(fila.querySelector('.cant').value)||0,precio=parseFloat(fila.querySelector('.precio').value)||0;
        let desc=0;if(fila.querySelector('.desc'))desc=parseFloat(fila.querySelector('.desc').value)||0;
        const sub=(cant*precio)-desc;
        fila.querySelector('.subtotal').textContent=sub.toFixed(2);total+=sub;
    });
    document.getElementById('span-total').textContent=total.toFixed(2);
}
</script>