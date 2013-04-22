<?php

	if (isset($_GET["l"])){
		switch($_GET["l"]){
			case "php":
			// We'll be outputting a PDF
			header('Content-type: text');

			// It will be called .php
			header('Content-Disposition: attachment; filename="pos-erp-sdk.php"');
	
			//insert header

			// The PDF source is in original.pdf
			readfile('../sdk/php/pos-erp-sdk.php');
			break;
		}
	}
