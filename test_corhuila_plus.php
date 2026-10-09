<?php

		define("MI_ROOT_TEMP_RELATIVA", $_SERVER['DOCUMENT_ROOT'].'/');


echo "ruta " . MI_ROOT_TEMP_RELATIVA;

		$target_dir = MI_ROOT_TEMP_RELATIVA.'adjuntos/1';

		//Validamos si la ruta de destino existe, en caso de no existir la creamos
		if(!file_exists($target_dir)){
			mkdir($target_dir, 0777) or die("No se puede crear el directorio de extracci&oacute;n 0 ");
		}

?>
