<DIV id="header-content">
	<P class="logo"><A href="">caffeina</A></P>
	
	<UL class="nav">
	
			<LI class="drop">
			<A href="configuracion.php?action=actual">configuracion</A>
			</LI>
	
		
			<LI class="drop">
			<A href="base.php?action=dbkiss">base de datos</A>
			</LI>
			
				
			<LI class="drop">
			<A href="logs.php?action=ver">logs</A>
			</LI>
	
			<LI class="drop">
			<A href="equipos.php?action=lista">equipos</A>
				<!-- <UL  class="nav sub" >
				<LI><A >Ver equipos</A></LI>
				<LI class="last"><A href="equipos.php?action=nuevo">Nuevo equipo</A></LI>
				</UL> -->			
			</LI>
			
						
			<LI class="drop">
			<a href="../proxy.php?action=2002" >SALIR</a>
			</LI>		
				

	</UL>
	
</DIV> <!-- header-content -->

<script>
	var __current = [];
	
	function toolbar(){
		for( a = 0; a < jQuery(".nav.sub").length; a++){ jQuery(jQuery(".nav.sub")[a]).hide(); }
		
		jQuery("#header-content").hover(
			function(){
				if(__current) {
					__current.hide();
				}
			},
			function(){
			
			});
		
		jQuery(".nav>.drop>a").hover( 
			function(e){ 

				//in
				while(__current.length > 0) {
					__current.pop().hide();
				}
				
				__currentP = jQuery(this).next();
				__currentP.css("left", "0px");
				__currentP.show();
				__current.push( __currentP );

			},
			function(e){
				//out
			});
	}
	toolbar();

</script>


    

