<h1>Reporte de ventas desde la vista</h1>
<p>Este reporte tiene [pagetotal] paginas</p>
<table class="simple-table">
    <thead>
        <tr>
            <th>NO.</th>
            <th>Nombre</th>
            <th>Precio</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $key => $producto): ?>
            <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $producto['nombre'] ?> </td>
                <td><?= $producto['precio'] ?> </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<pagebreak resetpagenum="1" pagenumstyle="a" suppress="off" />