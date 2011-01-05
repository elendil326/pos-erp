<div class="g-section header">
<a href="./"><img alt="Caffeina Mexico" height=40 src="../media/logo_pos.png"></a>
<div id="custom-search">
  <form action="buscar.php">
      <input id="q" name="q" type="text"> <input class="cse_submit" id="cse-search-button" type="submit" value="Buscar">
   </form>
</div>

<div class="ga-container-nav-top">
  <ul id="toolbar">

    <li class="product">
      <a  class="drop">CLIENTES</a>
      <ul>
        <li class="ga-side-nav-off">
          <a href="clientes.php?action=lista" >Lista de Clientes</a>
        </li>

        <li class="ga-side-nav-off">
          <a  href="clientes.php?action=nuevo" >Nuevo Cliente</a>
        </li>

      </ul>
    </li>

    <li class="product">
      <a class="drop" >SUCURSALES</a>
      <ul>
        <li class="ga-side-nav-off">
          <a href="sucursales.php?action=lista">Lista de Sucursales</a>
        </li>
        <li class="ga-side-nav-off">
          <a href="sucursales.php?action=abrir">Abrir sucursal</a>
        </li>
      </ul>
    </li>

    <li class="product">
      <a class="drop" >GERENTES</a>
      <ul>
        <li class="ga-side-nav-off">
          <a href="gerentes.php?action=lista">Lista de gerentes</a>
        </li>
        <li class="ga-side-nav-off">
          <a href="gerentes.php?action=asignar">Asignar gerencias</a>
        </li>
        <li class="ga-side-nav-off">
          <a href="gerentes.php?action=nuevo">Nuevo gerente</a>
        </li>
      </ul>
    </li>



    <li class="product">
      <a class="drop" >VENTAS</a>
      <ul style="top: 33px; left: 246px; width: 200px; display: none; ">
        <li class="ga-side-nav-off">
          <a  href="ventas.php?action=lista">Ver Todas</a>
        </li>
        <li>
          <a  href="ventas.php?action=porProducto">Por producto</a>
        </li>
        <!--
        <li>
          <a  href="ventas.php?action=porEmpleado">Por empleado</a>
        </li>
        -->
		<!--
        <li>
          <a  href="ventas.php?action=proyecciones">Historial y Proyecciones</a>
        </li>
        -->

      </ul>
    </li>



    <li class="product">
      <a class="drop" >AUTORIZACIONES</a>
      <ul>
        <li class="ga-side-nav-off">
          <a href="autorizaciones.php?action=pendientes" >Pendientes</a>
        </li>
        <li>
          <a href="autorizaciones.php?action=historial" >Todas las autorizaciones</a>
        </li>
      </ul>
    </li>






    <li class="product">
      <a class="drop" >INVENTARIO</a>
      <ul>
        <li class="ga-side-nav-off">
          <a href="inventario.php?action=lista">Lista de Inventario</a>
        </li>
        <li>
          <a href="inventario.php?action=surtir">Surtir Sucursal</a>
        </li>
        <li>
          <a href="inventario.php?action=nuevo">Nuevo Producto</a>
        </li>
        <li>
          <a href="inventario.php?action=transit">En transito</a>
        </li>
      </ul>
    </li>






    <li class="product">
      <a href="../proxy.php?action=2002" >SALIR</a>
    </li>




    <li class="last">&nbsp;
    </li>
  </ul><script>var toolbar = new Toolbar('toolbar');</script>
</div>

</div>
