
<DIV id="header-content">
	<P class="logo"><A href="index.php">caffeina</A></P>
	
	<UL class="nav">
		
		
		<LI class="drop">
			<A  href=".">Facturas</A>
		</LI>
					
		<LI class="drop"><A href="../proxy.php?action=2002">SALIR</A></LI>
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


    

