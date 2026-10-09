<?php
/**
 * Script de insercion de usuarios docentes.
 * - Inserta solo si no existe un usuario con el mismo documento.
 * - La contrasena se genera con password_hash($documento, PASSWORD_DEFAULT).
 * - Los demas campos quedan en NULL o con el default de la tabla.
 *
 * Ejecucion: php insertar_usuarios_docentes.php
 */

include("config/conex.php");

$usuarios = [

		['name' => 	'CASTILLO PEREZ VLADIMIR','documento' => '12240507','email' => 'vladimir.castillo@corhuila.edu.co'],
		['name' => 	'TRUJILLO ORTIZ EDNA XIMENA','documento' => '1075312955','email' => 'ex_trujillo@corhuila.edu.co'],
		['name' => 	'REYES MAHECHA CARLOS ENRIQUE','documento' => '12120865','email' => 'carlos.reyes@corhuila.edu.co'],
		['name' => 	'LEMUS BOLAÑOS VIVIANA','documento' => '26431269','email' => 'viviana.lemus@corhuila.edu.co'],
		['name' => 	'PALOMINO QUERUZ CARLOS ARTURO','documento' => '79273562','email' => 'carlos.palomino@corhuila.edu.co'],
		['name' => 	'CIFUENTES CESPEDES CRISTIAN EDUARDO','documento' => '80799601','email' => 'cristian.cifuentes@corhuila.edu.co'],
		['name' => 	'NINCO CASTAÑEDA MARTHA XIMENA','documento' => '36347195','email' => 'martha.ninco@corhuila.edu.co'],
		['name' => 	'ZAMBRANO MONJE FELIX BERNARDO','documento' => '12282179','email' => 'felix.zambrano@corhuila.edu.co'],
		['name' => 	'CERQUERA AYALA LUIS ALFREDO','documento' => '12131209','email' => 'luis.cerquera@corhuila.edu.co'],
		['name' => 	'POLANIA CALDERON CRISTIAN ANDRES','documento' => '1075281361','email' => 'capolania@corhuila.edu.co'],
		['name' => 	'CHAVARRO ARIAS OSCAR EDUARDO','documento' => '7714478','email' => 'oscar.chavarro@corhuila.edu.co'],
		['name' => 	'ESTRADA TOLEDO HECTOR JAIME','documento' => '1117508100','email' => 'hectorj.estradat@corhuila.edu.co'],
		['name' => 	'GARZON PRECIADO JUAN SEBASTIAN','documento' => '1075544866','email' => 'juan.garzon@corhuila.edu.co'],
		['name' => 	'SAMBONI CHILITO NIXON FABIAN','documento' => '1084256399','email' => 'nixon.samboni@corhuila.edu.co'],
		['name' => 	'GONZALEZ SAPUY ANDERSON HARDY','documento' => '1075253765','email' => 'anderson.gonzalez@corhuila.edu.co'],
		['name' => 	'ROCHA MAJIN YILVER DUBAN','documento' => '1080933890','email' => 'mikirocha1411@gmail.com'],
		['name' => 	'GONZALEZ ALVAREZ JUAN MANUEL','documento' => '1075251056','email' => 'juan.gonzalez@corhuila.edu.co'],
		['name' => 	'GARCIA MURCIA ALEJANDRO','documento' => '7721014','email' => 'alejandro.garcia@corhuila.edu.co'],
		['name' => 	'ANGEL CHARRY JOAN FRANCISCO','documento' => '79856383','email' => 'j-angel@corhuila.edu.co'],
		['name' => 	'SALAZAR PEDREROS GIANCARLO','documento' => '94534143','email' => 'giancarlo.salazar@corhuila.edu.co'],
		['name' => 	'IBAÑEZ DIAZ OSVAL ARMANDO','documento' => '79690287','email' => 'oaibanez@corhuila.edu.co'],
		['name' => 	'SOTO FLOREZ JUAN PABLO','documento' => '1032429014','email' => 'j-soto@corhuila.edu.co'],
		['name' => 	'MARTINEZ ALVAREZ FREDY ALEJANDRO','documento' => '11224153','email' => ''],
		['name' => 	'FONSECA GRANDO GONZALO RAUL','documento' => '12524287','email' => 'gonzalo.raul@corhuila.edu.co'],
		['name' => 	'CAICEDO MACIAS EDUARDO','documento' => '1083903890','email' => 'eduardo.caicedo@corhuila.edu.co'],
		['name' => 	'CICERI BELTRAN LUZ ALEJANDRA','documento' => '1075234159','email' => 'luz.ciceri@corhuila.edu.co'],
		['name' => 	'PATIÑO ZAPATA JOHN FREDDY','documento' => '71173376','email' => 'jhon.patino@corhuila.edu.co'],
		['name' => 	'PERDOMO FUENTES CESAR AUGUSTO','documento' => '1075209543','email' => 'cesar.perdomo@corhuila.edu.co'],
		['name' => 	'OSORIO RAMIREZ STEFANY','documento' => '1075295086','email' => 'stefany.osorior@corhuila.edu.co'],
		['name' => 	'GARCIA HOLGUIN GERMAN ALEXANDER','documento' => '1022326164','email' => 'german.agarciah@corhuila.edu.co'],
		['name' => 	'CABRERA MEDINA CARLOS EDUARDO','documento' => '1081183127','email' => 'puyka2016@gmail.com'],
		['name' => 	'PUENTES VERU DANIELA ROCIO','documento' => '1083913436','email' => 'daniela.puentes@corhuila.edu.co'],
		['name' => 	'DUARTE BARRERA MONICA ROCIO','documento' => '1110501711','email' => 'monica.duarte@corhuila.edu.co'],
		['name' => 	'ULE DUQUE YANEIRY','documento' => '1075298757','email' => 'yaneiry.ule@corhuila.edu.co'],
		['name' => 	'GALINDO OSORIO SEBASTIAN DANILO','documento' => '1052413115','email' => 'sebastian.galindo@corhuila.edu.co'],
		['name' => 	'CLAROS TRUJILLO NIYERETH VANESSA','documento' => '1083883107','email' => 'niyereth.clarost@corhuila.edu.co'],
		['name' => 	'BECERRA ORDOÑEZ LUIS CARLOS','documento' => '1082128013','email' => 'luisc.becerra@corhuila.edu.co'],
		['name' => 	'TEJADA OSSA ALVARO','documento' => '19164556','email' => 'alvaro.tejada@corhuila.edu.co'],
		['name' => 	'PARDO HERRERA LILIANA','documento' => '55167541','email' => 'liliana.pardo@corhuila.edu.co'],
		['name' => 	'DIAZ TORRES CARLOS FERNANDO','documento' => '1083901400','email' => 'carlos.diaz@corhuila.edu.co'],
		['name' => 	'ROMERO CHARRY JUAN SEBASTIAN','documento' => '1016058775','email' => 'jsromero@corhuila.edu.co'],
		['name' => 	'GASCA TORRES LIGIA','documento' => '36296967','email' => 'ligia.gasca@corhuila.edu.co'],
		['name' => 	'SALAZAR SANCHEZ DIEGO MAURICIO','documento' => '1018484319','email' => 'diegom.salazars@corhuila.edu.co'],
		['name' => 	'CALDERON RAMOS MARTHA CECILIA','documento' => '52708259','email' => 'martha.calderon@corhuila.edu.co'],
		['name' => 	'BAQUERO YORDAN PAULO MARCEL','documento' => '80407763','email' => 'paulo.baquero@corhuila.edu.co'],
		['name' => 	'MONROY BARBOSA OSCAR ALEJANDRO','documento' => '80664293','email' => 'oscar.monroy@corhuila.edu.co'],
		['name' => 	'MOSQUERA ALVAREZ FABER','documento' => '18495436','email' => 'faber.mosquera@corhuila.edu.co'],
		['name' => 	'RAMOS POLANIA ALFREDO','documento' => '12111404','email' => 'aramos@corhuila.edu.co'],
		['name' => 	'DEL VALLE BORRAEZ CARLOS AUGUSTO','documento' => '80407724','email' => 'carlosa.delvalleb@corhuila.edu.co'],
		['name' => 	'CASTELAZO TORRES LUIS RODRIGO','documento' => '322266','email' => 'luisr.castelazo@corhuila.edu.co'],
		['name' => 	'AVILA LOPEZ JOSE ZAID','documento' => '1105683723','email' => 'jose.avila@corhuila.edu.co'],
		['name' => 	'GUTIERREZ BAHAMON JORGE ARMANDO','documento' => '1075262345','email' => ''],
		['name' => 	'GOETHE FLOREZ DILSON DAVID','documento' => '1143148155','email' => 'dilsond.goethe@corhuila.edu.co'],
		['name' => 	'RENDON GARCIA OLGA LUCIA','documento' => '41911962','email' => 'olga.rendon@corhuila.edu.co'],
		['name' => 	'OTERO PATERNINA RAMIRO DE JESUS','documento' => '92260320','email' => ''],
		['name' => 	'CHAVARRO DIAZ JORGE IVAN','documento' => '7726591','email' => 'jorge.chavarro@corhuila.edu.co'],
		['name' => 	'VANEGAS GRANADOS LUIS CARLOS','documento' => '1082841295','email' => 'luisvan@gmail.com'],
		['name' => 	'CUESTA MEJIA EDUARDO','documento' => 'CUME890630HPLSJ','email' => 'eduardo.cuestam@corhuila.edu.co'],
		['name' => 	'HIDALGO GAONA SANTIAGO FELIPE','documento' => '71260110','email' => 'santiago.hidalgog@corhuila.edu.co'],
		['name' => 	'DIAZ MERCADO VITALI','documento' => 'N20735397','email' => 'vitalidime@gmail.com'],
		['name' => 	'MARQUEZ DIAZ JAIRO EDUARDO','documento' => '79461682','email' => 'nanotechrd@gmail.com'],
		['name' => 	'CORZO PEREZ GERALD AUGUSTO','documento' => '909767','email' => 'gerald.corzop@corhuila.edu.co'],
		['name' => 	'SANCHEZ HERNANDEZ KAREL ALDRIN','documento' => '1070978519','email' => 'karel.sanchez@corhuila.edu.co'],
		['name' => 	'PULECIO SANTOS SANDY LORENA','documento' => '1026260515','email' => 'sandy.pulecio@corhuila.edu.co'],
		['name' => 	'RICO BAUTISTA NOLFER ALBERTO','documento' => '88030498','email' => 'na.ricobautista@corhuila.edu.co'],
		['name' => 	'NAVARRETE RAMOS ANDRES MAURICIO','documento' => '7712792','email' => 'andres.navarrete@corhuila.edu.co'],
		['name' => 	'GARCIA MUÑOZ LEYDA YOHANA','documento' => '55067367','email' => 'lgarcia@corhuila.edu.co'],
		['name' => 	'STERLING CALDERON CARLOS ANDRES','documento' => '1083896498','email' => 'carlos.sterling@corhuila.edu.co'],
		['name' => 	'MARIN USUGA ANGELICA NOHELY','documento' => '52900214','email' => 'angelica.marinusuga@corhuila.edu.co'],
		['name' => 	'MORALES SANCHEZ CARLOS FERNANDO','documento' => '80184292','email' => 'carlosf.morales@corhuila.edu.co'],
		['name' => 	'LARA FIGUEROA OSCAR FABIAN','documento' => '7725154','email' => 'oscar.lara@corhuila.edu.co'],
		['name' => 	'CUADRO MOGOLLON OMAR FERNANDO','documento' => '5478021','email' => 'omar.cuadro@corhuila.edu.co'],
		['name' => 	'CASTIBLANCO GONZÁLEZ LAURA CAROLINA','documento' => '52716003','email' => 'laura.castiblanco@corhuila.edu.co'],
		['name' => 	'RODRIGUEZ AVILA VICTOR HUGO','documento' => '89000864','email' => 'v-rodriguez@corhuila.edu.co'],
		['name' => 	'PERDOMO LOZADA MARIA DEL PILAR','documento' => '36305105','email' => 'maria.perdomo@corhuila.edu.co'],
		['name' => 	'SAAVEDRA MORA DAVID','documento' => '1117511820','email' => 'david.saavedra@corhuila.edu.co'],



];


try {
  foreach ($usuarios as $u) {

    $name = $u['name'];
    $email = $u['email'];
    $documento = $u['documento'];

  	$sql="SELECT * FROM users WHERE documento='$documento' AND tipo_usuario=2 "	;
  	$res=queryli($sql,1);

  	if($Filas==0){

      $password = password_hash($documento, PASSWORD_DEFAULT);

			$sql="
			    INSERT INTO users (name, email, cellphone, password, tipo_usuario, documento, firma, programa, facultad)
			    VALUES ('$name', '$email', NULL, '$password', 2, '$documento', NULL, NULL, NULL)";
			queryli($sql);
   	 $insertados++;
  	}
  	else{
  		continue;
  	}
  }

  echo $insertados;
} catch (Throwable $e) {
echo $e;
}
