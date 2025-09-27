<!-- ARCHIVO: view/frmnuevacompra.php -->

<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid"><div class="row"><div class="col-lg-12"><div class="breadcome-list shadow-reset"><div class="row">
        <div class="col-lg-6"><h3>Registrar Nueva Compra</h3></div>
        <div class="col-lg-6"><ul class="breadcome-menu">
            <li><a href="index.php?c=compras&a=IndexPage">Compras</a> <span class="bread-slash">/</span></li>
            <li><span class="bread-blod">Nueva Compra</span></li>
        </ul></div>
    </div></div></div></div></div>
</div>

<div class="container-fluid">
    <form action="index.php?c=compras&a=Guardar" method="post" id="frm-compra">
        <div class="row">
            <div class="col-lg-8"><div class="sparkline8-list shadow-reset"><div class="sparkline8-graph">
                <label>Añadir Producto</label>
                <div class="input-group">
                    <select id="select-producto" class="form-control"><option value="">Buscar producto...</option>
                        <?php foreach($productos as $p): ?>
                            <option value="<?php echo $p->id_producto; ?>" data-costo="<?php echo $p->costo_unit_adquisicion; ?>"><?php echo htmlspecialchars($p->nombre_producto); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="input-group-btn"><button type="button" class="btn btn-primary" onclick="agregarProducto()">Añadir</button></span>
                </div><hr>
                <h5>Productos a Comprar</h5>
                <div class="table-responsive"><table class="table"><thead><tr><th>Producto</th><th>Cant.</th><th>Costo</th><th>Subtotal</th><th></th></tr></thead><tbody id="tbody-productos"></tbody></table></div>
            </div></div></div>
            <div class="col-lg-4"><div class="sparkline8-list shadow-reset"><div class="sparkline8-graph">
                <div class="form-group"><label>Proveedor</label><select name="id_proveedor" class="form-control" required><option value="">Seleccione...</option>
                    <?php foreach($proveedores as $p): ?><option value="<?php echo $p->id_proveedor; ?>"><?php echo htmlspecialchars($p->nombre_proveedor); ?></option><?php endforeach; ?>
                </select></div>
                <div class="form-group"><label>Comprador (Usuario)</label><select name="id_usuario_comprador" class="form-control" required><option value="">Seleccione...</option>
                    <?php foreach($usuarios as $u): ?><option value="<?php echo $u->id_usuario; ?>"><?php echo htmlspecialchars($u->nombre_empleado ?? $u->nombre_usuario); ?></option><?php endforeach; ?>
                </select></div>
                <div class="form-group"><label>Notas</label><textarea name="notas_obs" class="form-control" rows="3"></textarea></div>
                <div class="form-group"><label>Etapa del Proceso</label><input type="text" name="etapa" class="form-control" value="Solicitada" placeholder="Ej: Crédito 30 días, OC #123"></div><hr>
                <div style="display: flex; justify-content: space-between;"><h3><strong>Total:</strong></h3><h3><strong id="span-total">0.00</strong> Bs.</h3></div>
                <input type="hidden" name="total_final" id="total_final" value="0"><hr>
                <button type="submit" class="btn btn-success btn-block btn-lg"><i class="fa fa-save"></i> Guardar Compra</button>
            </div></div></div>
        </div>
    </form>
</div>
<script>
    function agregarProducto(){
        const select=document.getElementById('select-producto'),option=select.options[select.selectedIndex];if(!option.value)return;
        const id=option.value,nombre=option.text,costo=parseFloat(option.getAttribute('data-costo'))||0;if(document.getElementById(`prod-row-${id}`))return alert('Producto ya añadido.');
        const fila=`<tr id="prod-row-${id}"><td><input type="hidden" name="productos_id[]" value="${id}">${nombre}</td><td><input type="number" name="productos_cantidad[]" value="1" class="form-control" style="width:70px" min="1" oninput="calcularTotales()"></td><td><input type="number" step="0.01" name="productos_costo[]" value="${costo.toFixed(2)}" class="form-control" style="width:90px" oninput="calcularTotales()"></td><td class="subtotal-producto text-right">${costo.toFixed(2)}</td><td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();calcularTotales();">&times;</button></td></tr>`;
        document.getElementById('tbody-productos').insertAdjacentHTML('beforeend',fila);calcularTotales();
    }
    function calcularTotales(){
        let total=0;document.querySelectorAll('#tbody-productos tr').forEach(fila=>{const cant=parseFloat(fila.querySelector('[name="productos_cantidad[]"]').value)||0,costo=parseFloat(fila.querySelector('[name="productos_costo[]"]').value)||0,sub=cant*costo;fila.querySelector('.subtotal-producto').textContent=sub.toFixed(2);total+=sub;});
        document.getElementById('span-total').textContent=total.toFixed(2);document.getElementById('total_final').value=total.toFixed(2);
    }
</script>