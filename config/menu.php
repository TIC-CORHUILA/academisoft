<?php
@session_start();

if(!$_SESSION){
  header("location: ../login/index.php");
}

//Se verifica el usuario para mostrar u ocultar menu
$sql="SELECT * FROM users WHERE id='$_SESSION[id]'";
$res=queryli($sql,1);

$tipo_usuario = $res['tipo_usuario'];

if($tipo_usuario!=3){
?>
  <!-- sidebar menu -->
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <h3>MENÚ PRINCIPAL</h3>
      <ul class="nav side-menu">

        <li>
          <a href="panel.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        </li>

      	<?php
      		if($tipo_usuario==1){
      	?>
				<li>
				  <a><i class="fa-solid fa-gear"></i> Configuración <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
				  <ul class="nav child_menu">
		        <li><a href="seguridad.php"><i class="fa-solid fa-user-shield me-2"></i> Usuarios</a></li>
		        <li><a href="personas.php"><i class="fa-solid fa-user-graduate me-2"></i> Estudiantes</a></li>
		        <li><a href="personas_cargue.php"><i class="fa-solid fa-cloud-arrow-up me-2"></i> Cargar</a></li>
		        <li><a href="../config/backup.php"><i class="fa-solid fa-database me-2"></i> Copia de seguridad</a></li>
				  </ul>
				</li>
				<li>
				  <a><i class="fa-solid fa-folder-open"></i> Documentación <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
				  <ul class="nav child_menu">
		        <li><a href="solicitar_paz_salvo.php"><i class="fa-solid fa-award me-2"></i> Solicitar paz y salvo</a></li>
		        <li><a href="gestionar_paz_salvo.php"><i class="fa-solid fa-list-check me-2"></i> Gestionar paz y salvo</a></li>
				  </ul>
				</li>
  			<?php
  				}
  			?>
				<li>
				  <a><i class="fa-solid fa-graduation-cap"></i> Práctica Extramuro <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
				  <ul class="nav child_menu">
				    <!-- Submódulo Planeación -->
				    <li>
				      <a><i class="fa-solid fa-table-list me-2"></i> Planeación <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.7rem;"></span></a>
				      <ul class="nav child_menu">
				        <li><a href="planeacion.php"><i class="fa-solid fa-pen-to-square me-2"></i> Registrar</a></li>
				        <li><a href="listado_planeacion.php"><i class="fa-solid fa-list-ol me-2"></i> Listado</a></li>
				      </ul>
				    </li>

				    <!-- Submódulo Gestión -->
				    <li>
				      <a href="listado_gestion.php"><i class="fa-solid fa-chart-column me-2"></i> Gestión</a>
				    </li>

				    <!-- Submódulo Informes -->
				    <li>
				      <a href="listado_informes.php"><i class="fa-solid fa-file-lines me-2"></i> Informe Prácticas</a>
				    </li>

				    <!-- Submódulo Manual -->
				    <li>
				      <a href="manual_practias.pdf" target="_blank"><i class="fa-solid fa-file-pdf me-2"></i> Manual de Usuario</a>
				    </li>

				    <!-- Submódulo Procesos (condicional) -->
				    <?php if($tipo_usuario>=14 && $tipo_usuario<=22 || $tipo_usuario==24){ ?>
				      <li>
				        <a><i class="fa-solid fa-star me-2"></i> Procesos <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.7rem;"></span></a>
				        <ul class="nav child_menu">
				          <li><a href="listado_procesos.php">Cambiar Estados</a></li>
				        </ul>
				      </li>
				    <?php } ?>

				  </ul>
				</li>
      <!-- EVALUACIÓN Y AGENDA -->
      	<?php

      	//DOCENTES
      	if($tipo_usuario==2 || ($tipo_usuario>=25 && $tipo_usuario<=35)) {

					//Se consulta la información del usuario logueado
					// Nombre del programa del usuario logueado
					$name = safe_utf8_encode($_SESSION['name']);

					// Validación: nombre → código
					if ($name == "Especialización en Gerencia Logística y Cadenas de Suministro") {
					    $programa = '22';
					} elseif ($name == "Especialización en Gestión de la Seguridad y Salud en el Trabajo") {
					    $programa = '18';
					} elseif ($name == "Especialización en Hidroinformática") {
					    $programa = '25'; // o '26A' si es virtual
					} elseif ($name == "Especialización en Sistemas Integrados de Gestión") {
					    $programa = '24';
					} elseif ($name == "Especialización en Marketing Digital") {
					    $programa = '28';
					} elseif ($name == "Especialización en Machine Learning para Internet de las Cosas (IoT)") {
					    $programa = '29';
					} elseif ($name == "Especialización en Ingeniería de Software") {
					    $programa = '34';
					} elseif ($name == "Especialización en Inteligencia Artificial") {
					    $programa = '35';
					} elseif ($name == "Especialización en Big Data y Analítica de Datos") {
					    $programa = '36';
					} elseif ($name == "Especialización en Arquitectura de Software") {
					    $programa = '56';
					} elseif ($name == "Especialización en Sanidad de Peces de Aguas Continentales") {
					    $programa = '37'; // o '37A' según modalidad
					} elseif ($name == "Especialización en Ciberseguridad") {
					    $programa = '73';
					} elseif ($name == "Especialización en Gestión de la Seguridad y Salud en el Trabajo") {
					    $programa = '74';
					} else {
					    $programa = null; // no encontrado
					}
      	?>
        <li>
        	<a><i class="fa-solid fa-calculator"></i> Evaluación Docente <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
          <ul class="nav child_menu">
            <?php if($programa!="" && $programa!=null){ ?>
            	<li><a href="listado_docentes.php">Director</a></li>
            	<li><a href="asignaturas.php">Evaluación Posgrado</a></li>
			        <li><a href="informe_evaluacion.php">Reporte docentes</a></li>

            <?php }else{
            	echo '<li><a href="autoevaluacion.php">Autoevaluación</a></li>';
            } ?>

          </ul>
        </li>

        <li>
        	<a><i class="fa-solid fa-calendar-days"></i> Agenda Profesoral <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
          <ul class="nav child_menu">
            <li><a href="crear_agenda.php">Crear agenda</a></li>
            <li><a href="agenda_profesoral.php">Informe de agenda profesoral</a></li>
          </ul>
        </li>

      	<?php
      	}
      	?>

      	<?php

      	//admin, Derechos Pecuniarios, Dirección de Curriculo
      	if($tipo_usuario==1 || $tipo_usuario==23 || $tipo_usuario==6){
      	?>
        <li>
          <a><i class="fa-solid fa-calculator"></i> Evaluación Docente <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
          <ul class="nav child_menu">
            <li><a href="programas.php">Evaluación Pregrado</a></li>
            <li><a href="asignaturas.php">Evaluación Posgrado</a></li>
            <li><a href="docentes.php">Docentes</a></li>
            <li><a href="modalidades.php">Modalidades</a></li>
            <li class="border-top my-1"></li>
            <li><a href="listado_docente_evaluacion.php">Evaluar docente</a></li>
            <li><a href="listado_docentes.php">Director</a></li>
            <li><a href="autoevaluacion.php">Autoevaluación</a></li>
            <li><a href="lista_reportes.php">Reportes</a></li>
          </ul>
        </li>
      	<?php
      	}

      	//Directores de programa
      	if(($tipo_usuario>=14 && $tipo_usuario<=22) || $tipo_usuario==24 ){
      		?>
        	<li>
        		<a><i class="fa-solid fa-calendar-days"></i> Agenda Profesoral <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
            <ul class="nav child_menu">
              <li><a href="agenda_profesoral.php">Informe de agenda profesoral</a></li>
        		</ul>
        	</li>

        	<li>
        		<a><i class="fa-solid fa-chalkboard-user"></i> Evaluación docente <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
            <ul class="nav child_menu">
			        <li><a href="listado_docentes.php">Evaluar docentes</a></li>
			        <li><a href="informe_evaluacion.php">Reporte docentes</a></li>
        		</ul>
        	</li>
		      <?php
      	}

      	//Decanos
      	if( $tipo_usuario>=11 && $tipo_usuario<=13 ){
      		?>
        	<li>
            <a><i class="fa-solid fa-calendar-days"></i> Agenda Profesoral <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
            <ul class="nav child_menu">
              <li><a href="agenda_profesoral.php">Informe de agenda profesoral</a></li>
        		</ul>
        	</li>
		      <?php
      	}

      	//Admin, derechos pecuniarios, Dirección de Curriculo
      	if($tipo_usuario==1 || $tipo_usuario==23 || $tipo_usuario==6){
      	?>
        <li>
          <a><i class="fa-solid fa-calendar-days"></i> Agenda Profesoral <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
          <ul class="nav child_menu">
            <li><a href="fechas_agenda.php">Fechas</a></li>
            <li><a href="crear_agenda.php">Crear</a></li>
            <li><a href="agenda_profesoral.php">Informe</a></li>
            <li><a href="reporte_agenda.php">Reporte de agenda</a></li>
            <li><a href="lista_reportes_agenda.php">Reportes</a></li>
          </ul>
        </li>
      	<?php
      	}
      	?>

        <!-- SECCIÓN BIENESTAR & MENÚ PASE -->
        <li>
          <a><i class="fa-solid fa-heart-pulse"></i> Bienestar <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
          <ul class="nav child_menu">
            <!-- Centro P.A.S.E & Bienestar (Hub General) -->
            <li>
              <a href="pase/index.php"><i class="fa-solid fa-layer-group me-2"></i> Centro P.A.S.E & Bienestar</a>
            </li>

            <!-- Menú P.A.S.E -->
            <li>
              <a><i class="fa-solid fa-user-shield me-2"></i> Menú P.A.S.E <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.7rem;"></span></a>
              <ul class="nav child_menu">
                <li><a href="pase/poblacion_riesgo.php"><i class="fa-solid fa-triangle-exclamation me-2"></i> Población en Riesgo</a></li>
                <li><a href="pase/alertas_docente.php"><i class="fa-solid fa-bell me-2"></i> Alertas Docentes (Manos Levantadas)</a></li>
                <li><a href="pase/seguimiento_buscar.php"><i class="fa-solid fa-magnifying-glass-chart me-2"></i> Buscar Estudiante 360°</a></li>
                <li><a href="pase/seguimiento_casos.php"><i class="fa-solid fa-folder-tree me-2"></i> Casos y Acompañamiento</a></li>
                <li><a href="pase/seguimiento_remisiones.php"><i class="fa-solid fa-paper-plane me-2"></i> Remisiones</a></li>
                <li><a href="pase/seguimiento_citas.php"><i class="fa-solid fa-calendar-check me-2"></i> Agenda de Citas</a></li>
                <li><a href="pase/seguimiento_actividades.php"><i class="fa-solid fa-people-group me-2"></i> Talleres y Actividades</a></li>
                <li><a href="pase/reportes.php"><i class="fa-solid fa-chart-line me-2"></i> Reportes e Indicadores</a></li>
                <li><a href="pase/caracterizacion_componentes.php"><i class="fa-solid fa-id-card-clip me-2"></i> Caracterización Estudiantil</a></li>
                <li><a href="pase/caracterizacion_evaluar.php"><i class="fa-solid fa-pen-ruler me-2"></i> Evaluar Caracterización</a></li>
                <li><a href="pase/seguimiento_solicitudes.php"><i class="fa-solid fa-hand-holding-hand me-2"></i> Solicitudes de Ayuda</a></li>
                <li><a href="pase/soporte_tickets.php"><i class="fa-solid fa-ticket me-2"></i> Tickets de Soporte</a></li>
                <li><a href="pase/configuracion.php"><i class="fa-solid fa-sliders me-2"></i> Configuración y Drivers</a></li>
                <li><a href="pase/seguimiento_tipos.php"><i class="fa-solid fa-gear me-2"></i> Tipos de Atención</a></li>
              </ul>
            </li>

            <!-- Asistencia -->
            <li>
              <a><i class="fa-solid fa-clipboard-user me-2"></i> Asistencia <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.7rem;"></span></a>
              <ul class="nav child_menu">
                <li><a href="eventos_index.php"><i class="fa-solid fa-calendar-days me-2"></i> Eventos y Asistencia</a></li>
                <li><a href="actividades_asistencia.php" target="_blank"><i class="fa-solid fa-qrcode me-2"></i> Asistencia QR</a></li>
              </ul>
            </li>
          </ul>
        </li>

      </ul>
    </div>
  </div>
  <!-- /sidebar menu -->
<?php
}
else{
	?>
  <!-- sidebar menu -->
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <h3>MENÚ ESTUDIANTE</h3>
      <ul class="nav side-menu">
        <li>
          <a href="panel.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        </li>
        <li>
          <a><i class="fa-solid fa-list-check"></i> Practica Extramuro <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
          <ul class="nav child_menu">
        		<li><a href="listado_evaluacion.php">Evaluación</a></li>
          </ul>
        </li>
        <li>
        	<a><i class="fa-solid fa-chalkboard-user"></i> Evaluación Docente <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
          <ul class="nav child_menu">
        		<li><a href="listado_docente_evaluacion.php">Evaluar</a></li>
          </ul>
        </li>
        <!-- Bienestar & PASE Estudiante -->
        <li>
          <a><i class="fa-solid fa-heart-pulse"></i> Bienestar Estudiantil <span class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.75rem;"></span></a>
          <ul class="nav child_menu">
            <li><a href="pase/index.php"><i class="fa-solid fa-layer-group me-2"></i> Centro Bienestar</a></li>
            <li><a href="pase/seguimiento_citas.php"><i class="fa-solid fa-calendar-check me-2"></i> Mis Citas / Agendar</a></li>
            <li><a href="pase/seguimiento_solicitudes.php"><i class="fa-solid fa-hand-holding-hand me-2"></i> Solicitar Ayuda</a></li>
            <li><a href="pase/caracterizacion_evaluar.php"><i class="fa-solid fa-pen-ruler me-2"></i> Caracterización</a></li>
            <li><a href="pase/soporte_tickets.php"><i class="fa-solid fa-ticket me-2"></i> Soporte y Orientación</a></li>
            <li><a href="actividades_asistencia.php" target="_blank"><i class="fa-solid fa-qrcode me-2"></i> Registrar Asistencia QR</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <!-- /sidebar menu -->
	<?php
}
?>

<script>
(function() {
  function setupSidebarAccordion() {
    // 1. Elementos de primer nivel (.side-menu > li > a)
    var parentLinks = document.querySelectorAll('#sidebar-menu .side-menu > li > a');
    parentLinks.forEach(function(link) {
      var li = link.parentElement;
      var child = li.querySelector(':scope > .child_menu');
      if (child) {
        link.onclick = function(e) {
          e.preventDefault();
          var isOpen = li.classList.contains('active');
          document.querySelectorAll('#sidebar-menu .side-menu > li').forEach(function(otherLi) {
            if (otherLi !== li) {
              otherLi.classList.remove('active');
              var otherChild = otherLi.querySelector(':scope > .child_menu');
              if (otherChild) otherChild.style.display = 'none';
            }
          });
          if (!isOpen) {
            li.classList.add('active');
            child.style.display = 'block';
          } else {
            li.classList.remove('active');
            child.style.display = 'none';
          }
        };
      }
    });

    // 2. Elementos de subnivel anidados (.child_menu > li > a con submenú)
    var subLinks = document.querySelectorAll('#sidebar-menu .child_menu > li > a');
    subLinks.forEach(function(link) {
      var li = link.parentElement;
      var subChild = li.querySelector(':scope > .child_menu');
      if (subChild) {
        link.onclick = function(e) {
          e.preventDefault();
          e.stopPropagation();
          var isSubOpen = li.classList.contains('active');
          var siblings = li.parentElement.querySelectorAll(':scope > li');
          siblings.forEach(function(sib) {
            if (sib !== li) {
              sib.classList.remove('active');
              var sc = sib.querySelector(':scope > .child_menu');
              if (sc) sc.style.display = 'none';
            }
          });
          if (!isSubOpen) {
            li.classList.add('active');
            subChild.style.display = 'block';
          } else {
            li.classList.remove('active');
            subChild.style.display = 'none';
          }
        };
      }
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupSidebarAccordion);
  } else {
    setupSidebarAccordion();
  }
})();
</script>