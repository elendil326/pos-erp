<script>
	function mmss(m){
		switch(m){
			case 'clientes' :{
				document.getElementById("subm1").innerHTML = "Lista de clientes";
				document.getElementById("subm2").innerHTML = "Nuevo Cliente";
				document.getElementById("subm3").innerHTML = "&nbsp;";
				document.getElementById("subm4").innerHTML = "&nbsp;";
				document.getElementById("subm5").innerHTML = "&nbsp;";
				document.getElementById("subm6").innerHTML = "&nbsp;";
				break;
			} 
			
			case 'sucursales' :{
				document.getElementById("subm1").innerHTML = "Lista de Sucursales";
				document.getElementById("subm2").innerHTML = "Abrir sucursal";
				document.getElementById("subm3").innerHTML = "Gerentes";
				document.getElementById("subm4").innerHTML = "Asignar gerencias";
				document.getElementById("subm5").innerHTML = "Nuevo gerente";
				document.getElementById("subm6").innerHTML = "";
				break;
			} 
			
			case 'ventas' :{
				document.getElementById("subm1").innerHTML = "Realizar venta";
				document.getElementById("subm2").innerHTML = "Ver ventas";
				document.getElementById("subm3").innerHTML = "Ventas por producto";
				document.getElementById("subm4").innerHTML = "&nbsp;";
				document.getElementById("subm5").innerHTML = "&nbsp;";
				document.getElementById("subm6").innerHTML = "&nbsp;";
				break;
			} 
		}
	}
</script>
<DIV id="header-content">
	<P class="logo"><A href="">caffeina</A></P>
	<UL class="nav">
		<LI><A onMouseOver="mmss('clientes')">CLIENTES</A></LI>
		<LI><A onMouseOver="mmss('sucursales')">SUCURSALES</A></LI>
		<LI><A onMouseOver="mmss('ventas')">VENTAS</A></LI>
		<LI><A >AUTORIZACIONES</A></LI>
		<LI><A >PROVEEDORES</A></LI>
		<LI><A >INVENTARIO</A></LI>				
		<LI class="last"><A href="../proxy.php?action=2002">SALIR</A></LI>
	</UL>
	<UL class="nav" style="top: 50px;">
		<LI><A id="subm1" >a</A></LI>
		<LI><A id="subm2" >a</A></LI>
		<LI><A id="subm3" >a</A></LI>
		<LI><A id="subm4" >a</A></LI>
		<LI><A id="subm5" >a</A></LI>
		<LI><A id="subm6" >a</A></LI>				
		<LI class="last"><A id="subm7"></A></LI>
	</UL>	
</DIV> <!-- header-content -->

