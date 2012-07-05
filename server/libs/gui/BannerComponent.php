<?php


class BannerComponent implements GuiComponent{
	
	private $_title;
	private $_subtitle;
	private $_image;	
	
	public function __construct( $title, $subtitle, $image ){
		$this->_title = $title;
		$this->_subtitle = $subtitle;
		$this->_image = $image;
	}
	
	public function renderCmp(){
		?>
		<div class="uiWashLayout">
			<div class="ptm uiWashLayoutGradientWash">
				<div class="mainContent uiWashLayoutWashContent">
					<div class="widgets_central_header" style="background: url(<?php echo $this->_image; ?>) right 0 no-repeat;">
						<h1 class="marketingHeadline marketingHeadlineHuge"><?php echo $this->_title; ?></h1>
						<h2 class="marketingSubheader marketingSubheaderMedium"><?php echo $this->_subtitle; ?></h2>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	
	
}


	