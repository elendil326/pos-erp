<?php

	require_once("../../server/bootstrap.php");	
	require_once("controller/gui.controller.php");
	require_once("admin/includes/checkSession.php");
	require_once("admin/includes/static.php");	
	require_once('controller/autorizaciones.controller.php');
	

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >

<head><?php AdminGuiController::getHtmlHead(); ?></head>


<body class="sub">
  <div id="wrapper">

    <div id="header" class="clientes" <?php if( POS_STYLE_SUCURSALES_BANNER ) echo " style='background-image: url(". POS_STYLE_SUCURSALES_BANNER .")'"; ?> >
      
      <div id="top-bar">
        
        <?php include_once("admin/includes/mainMenu.php"); ?>
            
      </div> 
      <!-- /top-bar -->

      <div id="header-main">
		<h1 id="MAIN_TITLE">Centro de distribuci&oacute;n</h1> 
      </div>
    </div>
    
    <div id="content" >
		


	<div class="container">
	<?php /*
			<div class="document summary" >
			

				<!-- - - - - - - - - - - - - - - - - - - - - - - - -  Getting Started - - - - - - - - - - - - - - - - - - - - - - - - -->
				<div class="gs-container" style="" >
					<div class="get-started-title">
						<h2>Getting Started with the Demo d</h2>
						<em class="icons gs-close" >
						&nbsp; </em><a href="" class="gs-close" ><span>You can use the help menu to show Getting Started again.</span> Hide Getting Started </a>
					</div>
					<div class="notify get-started">
						<em>
						&nbsp; </em>
						<div class="message">
							<p>
								 Try out the features and get familiar with Xero using your own demo organization.
							</p>
							<ul>
								<li><a href=""onclick="getStarted.showMovie(&#39;Getting Started Tour&#39;, &#39;/movies/gettingstarted/scrnCastPlayer_inApp.swf&#39;, &#39;/movies/gettingstarted/data.xml&#39;, 610, 455); return false;">Watch the Getting Started tour</a>. </li>
								<li><a href=""target="_blank">Here's 10 things you can try out in Xero.</a></li>
							</ul>
							<p>
								 There is <a href=""target="_blank">full online help in Xero</a> and you can always contact Customer Care at anytime to ask us a question.
							</p>
						</div>
					</div>
				</div>



				<!-- - - - - - - - - - - - - - - - - - - - - - - - -  COLUMNA  - - - - - - - - - - - - - - - - - - - - - - - - -->
				<div class="col">

					<!-- - - - - - - - - - - - - - - - - - - - - - - - -  TITULO  - - - - - - - - - - - - - - - - - - - - - - - - -->
					<h2>
						<span>
						<a href="">
							Bank Accounts 
						</a>
						</span>

						<a href="" id="OG_Glossary_BankSummary" onclick="" class="icons tip">&nbsp;</a>
						<a href="">Go to Banking › </a>
					</h2>

					<!-- - - - - - - - - - - - - - - - - - - - - - - - -  CAJA  - - - - - - - - - - - - - - - - - - - - - - - - -->
					<div class="bank">
						<span class="logos global" style="width:auto;" >
							<em>
								&nbsp;
							</em>
						</span>
						<a  href="" class="bank-name global">Ridgeway Business A/C<span>090-8007-006543</span></a>



						<div class="content">
							<div class="balance">
								<div class="statement-balance">
									<label>Statement Balance </label>
									<span>7,827.22</span>
								</div>
								<div class="date-imported" >
									29 Jul 2011
								</div>
								<div class="no-padding-top" style="margin-bottom:5px;">
									<a class="xbtn blue" href=""  style="width: 105px; ">
									Reconcile <span >27</span> items</a>
								</div>
								<div class="bank-balance">
									<label>
									Balance in Xero </label><span>3,171.89</span>
								</div>
							</div>
						</div>
					</div> <!-- /bank -->



					<!-- - - - - - - - - - - - - - - - - - - - - - - - -  CAJA  - - - - - - - - - - - - - - - - - - - - - - - - -->
					<div class="bank">
							<span class="logos global" style="width:auto;" >
								<em>
									&nbsp;
								</em>
							</span>
							<a  href="" class="bank-name global">Ridgeway Business A/C<span>090-8007-006543</span></a>



							<div class="content">
								<div class="balance">
									<div class="statement-balance">
										<label>Statement Balance </label>
										<span>7,827.22</span>
									</div>
									<div class="date-imported" >
										29 Jul 2011
									</div>
									<div class="no-padding-top" style="margin-bottom:5px;">
										<a class="xbtn blue" href="" style="width: 105px; ">
										Reconcile <span >27</span> items</a>
									</div>


										<div class="authorize no-transactions">
											<h3>
											No transactions importegd </h3>
											<p>
												<a href="">Import a bank statement to get started</a>
											</p>
										</div>


									<div class="bank-balance">
										<label>
										Balance in Xero </label><span>3,171.89</span>
									</div>

								</div>
							</div>
						</div> <!-- /bank -->


				</div><!-- </div class="col"> -->



				<!-- - - - - - - - - - - - - - - - - - - - - - - - -  COLUMNA DERECHA  - - - - - - - - - - - - - - - - - - - - - - - - -->
				<div class="col right">

					<!-- - - - - - - - - - - - - - - - - - - - - - - - -  CAJA  - - - - - - - - - - - - - - - - - - - - - - - - -->
					<div class="bank">
						<span class="logos global" style="width:auto;" >
							<em>
								&nbsp;
							</em>
						</span>
						<a  href="" class="bank-name global">Ridgeway Business A/C<span>090-8007-006543</span></a>



						<div class="content">
							<div class="balance">
								<div class="statement-balance">
									<label>Statement Balance </label>
									<span>7,827.22</span>
								</div>
								<div class="date-imported" >
									29 Jul 2011
								</div>
								<div class="no-padding-top" style="margin-bottom:5px;">
									<a class="xbtn blue" href="" style="width: 105px; ">
									Reconcile <span >27</span> items</a>
								</div>
								<div class="bank-balance">
									<label>
									Balance in Xero </label><span>3,171.89</span>
								</div>
							</div>
						</div>
					</div> <!-- /bank -->



					<!-- - - - - - - - - - - - - - - - - - - - - - - - -  CAJA  - - - - - - - - - - - - - - - - - - - - - - - - -->
					<div class="bank">
							<span class="logos global" style="width:auto;" >
								<em>
									&nbsp;
								</em>
							</span>
							<a  href="" class="bank-name global">Ridgeway Business A/C<span>090-8007-006543</span></a>



							<div class="content">
								<div class="balance">
									<div class="statement-balance">
										<label>Statement Balance </label>
										<span>7,827.22</span>
									</div>
									<div class="date-imported" >
										29 Jul 2011
									</div>
									<div class="no-padding-top" style="margin-bottom:5px;">
										<a class="xbtn blue" href="" style="width: 105px; ">
										Reconcile <span >27</span> items</a>
									</div>


										<div class="authorize no-transactions">
											<h3>
											No transactions importegd </h3>
											<p>
												<a href="">Import a bank statement to get started</a>
											</p>
										</div>


									<div class="bank-balance">
										<label>
										Balance in Xero </label><span>3,171.89</span>
									</div>

								</div>
							</div>
						</div> <!-- /bank -->




				</div><!-- /col -->

			</div>



			<div id="good_bad" class="document feedback">
				<p>
					 Overall, how are you feeling about Xero?
				</p>
			</div>

			*/ ?>
		</div>

		<?php include_once("admin/includes/footer.php"); ?>
    </div> 
    <!-- /content -->
    
            
  </div> 
  <!-- /wrapper -->

</body></HTML>
