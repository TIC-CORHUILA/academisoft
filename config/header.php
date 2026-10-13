<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('safe_utf8_encode')) {
    function safe_utf8_encode($string) {
        if (!is_string($string)) return (string)$string;
        return mb_check_encoding($string, 'UTF-8') ? $string : utf8_encode($string);
    }
}

$user_id_header = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
$user_name_header = isset($_SESSION['name']) ? $_SESSION['name'] : 'Usuario';
$registros_notas = 0;
$resNotas = [];
$fotoPerfil = "images/img.jpg";
$tipo_usuario_header = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : 0;

$is_in_subfolder = (strpos($_SERVER['PHP_SELF'] ?? '', '/production/pase/') !== false);
$base_prod = $is_in_subfolder ? '../' : '';
$base_root = $is_in_subfolder ? '../../' : '../';

if ($user_id_header > 0 && function_exists('queryli')) {
    // Se cuentan las notificaciones/notas pendientes
    $sql_notas = "SELECT p.*, c.name, c2.name as dirigido
                  FROM planeacion_notas p
                  INNER JOIN users c on p.id_usuario = c.id
                  INNER JOIN users c2 on p.id_dirigido = c2.id
                  WHERE p.id_dirigido='$user_id_header'
                  ORDER BY p.id DESC
                  LIMIT 5";
    $resNotas = queryli($sql_notas, 2);
    $registros_notas = isset($Filas) ? $Filas : 0;

    // Foto de Perfil
    $rutaFotoUsuario = $base_root . "adjuntos/fotos/" . $user_id_header . ".jpg";
    if (file_exists($rutaFotoUsuario)) {
        $fotoPerfil = $rutaFotoUsuario . "?v=" . time();
    }

    // Obtener tipo de usuario actualizado de la BD
    $sql_user = "SELECT tipo_usuario FROM users WHERE id='$user_id_header'";
    $res_user = queryli($sql_user, 1);
    if (isset($res_user['tipo_usuario'])) {
        $tipo_usuario_header = $res_user['tipo_usuario'];
    }
}
?>

<!-- Global Modern Assets & Inter Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link rel="stylesheet" href="<?= $base_root ?>build/css/academisoft-modern.css?v=<?= time() ?>">
<script src="<?= $base_root ?>build/js/academisoft-app.js?v=<?= time() ?>"></script>

<!-- FULL-WIDTH TOP NAVIGATION HEADER -->
<div class="top_nav"
  style="margin-left: 0 !important; width: 100% !important; position: sticky; top: 0; z-index: 1000; background: #FFFFFF; border-bottom: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
  <div class="nav_menu"
    style="display: flex; align-items: center; justify-content: space-between; height: 64px; padding: 0 28px; background: #FFFFFF;">

    <!-- LEFT: BRAND LOGO & MODULES NAV -->
    <div class="d-flex align-items-center" style="gap: 20px;">
      <!-- Brand Logo -->
      <a href="<?= $base_prod ?>panel.php" class="d-flex align-items-center gap-2 text-decoration-none"
        style="font-weight: 800; font-size: 1.25rem; color: var(--text-main);">
        <div
          style="width: 38px; height: 38px; border-radius: 10px; background: var(--primary-gradient); color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(22, 163, 74, 0.3);">
          <i class="fa-solid fa-graduation-cap" style="font-size: 1.2rem;"></i>
        </div>
        <span>Academi<span style="color: var(--primary);">Soft</span></span>
      </a>

      <!-- Divider -->
      <div style="height: 24px; width: 1px; background: var(--border-color);"></div>

      <!-- Quick Nav Links -->
      <div class="d-none d-lg-flex align-items-center" style="gap: 8px;">
        <a href="<?= $base_prod ?>panel.php" class="btn-top-link">
          <i class="fa-solid fa-house me-1"></i> Inicio
        </a>

        <!-- Módulos Dropdown (Compatible con Bootstrap 4 y 5) -->
        <div class="dropdown">
          <button class="btn-top-link dropdown-toggle d-flex align-items-center gap-1 border-0 bg-transparent"
            type="button" id="navbarModulesDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            style="font-weight: 600; color: var(--text-main); font-size: 0.88rem; padding: 8px 12px; border-radius: 8px; cursor: pointer;">
            <i class="fa-solid fa-cubes me-1 text-success"></i>
            <span>Módulos del Sistema</span>
            <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.7rem; color: var(--text-muted);"></i>
          </button>

          <div class="dropdown-menu shadow-lg p-3" aria-labelledby="navbarModulesDropdown"
            style="width: 390px; max-height: 540px; overflow-y: auto; border-radius: 14px; border: 1px solid var(--border-color);">
            <div class="px-2 pb-2 mb-2 border-bottom d-flex align-items-center justify-content-between">
              <strong style="color: var(--text-main); font-size: 0.88rem;">Menú de Módulos y Servicios</strong>
              <span class="badge bg-green">AcademiSoft</span>
            </div>

            <?php if ($tipo_usuario_header != 3) { ?>

              <!-- === MÓDULOS ADMINISTRATIVOS === -->
              <?php if ($tipo_usuario_header == 1) { ?>
                <h6 class="dropdown-header text-uppercase fw-bold mt-1 mb-2" style="font-size: 0.75rem; color: var(--primary); border-bottom: 1px solid var(--border-color); padding-bottom: 6px; padding-left: 0;">Módulos Administrativos</h6>

                <!-- Configuración (Admin / Usuario 1) -->
                <div class="mb-3">
                  <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem;"><i
                      class="fa-solid fa-gear me-1"></i> Configuración</small>
                  <div class="d-flex flex-column gap-1">
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>seguridad.php"><i
                        class="fa-solid fa-user-shield me-2 text-success"></i> Usuarios</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>personas.php"><i
                        class="fa-solid fa-user-graduate me-2 text-success"></i> Estudiantes</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>personas_cargue.php"><i
                        class="fa-solid fa-cloud-arrow-up me-2 text-success"></i> Cargar Datos (Cargue Masivo)</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>test_corhuila_plus.php"><i
                        class="fa-solid fa-cloud-arrow-up me-2 text-success"></i> CorhuilaPlus</a>
                  </div>
                </div>

                <!-- Documentación -->
                <div class="mb-3">
                  <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem;"><i
                      class="fa-solid fa-folder-open me-1"></i> Documentación</small>
                  <div class="d-flex flex-column gap-1">
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>solicitar_paz_salvo.php"><i
                        class="fa-solid fa-award me-2 text-success"></i> Solicitar Paz y Salvo</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>gestionar_paz_salvo.php"><i
                        class="fa-solid fa-list-check me-2 text-success"></i> Gestionar Paz y Salvo</a>
                  </div>
                </div>

                <!-- SECCIÓN BIENESTAR EN MÓDULOS ADMINISTRATIVOS -->
                <div class="mb-3">
                  <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem;"><i
                      class="fa-solid fa-heart-pulse me-1 text-success"></i> BIENESTAR</small>
                  <div class="d-flex flex-column gap-1">
                    
                    <!-- P.A.S.E con su menú interno -->
                    <div class="rounded p-1" style="background: rgba(0, 132, 61, 0.04); border: 1px solid rgba(0, 132, 61, 0.18);">
                      <div class="d-flex align-items-center justify-content-between px-2 py-1.5 border-bottom mb-1" 
                           style="cursor: pointer;" 
                           data-bs-toggle="collapse" 
                           data-toggle="collapse" 
                           data-bs-target="#menuInternoPaseAdmin" 
                           data-target="#menuInternoPaseAdmin"
                           aria-expanded="true">
                        <strong class="text-success" style="font-size: 0.82rem;">
                          <i class="fa-solid fa-user-shield me-1"></i> P.A.S.E
                        </strong>
                        <i class="fa-solid fa-chevron-down text-muted" style="font-size: 0.65rem;"></i>
                      </div>

                      <!-- Menú Interno de P.A.S.E -->
                      <div class="collapse show" id="menuInternoPaseAdmin">
                        <div class="d-flex flex-column gap-1">
                          <a class="dropdown-item py-1 px-2 rounded fw-bold text-success" href="<?= $base_prod ?>pase/index.php" style="background: rgba(0, 132, 61, 0.08); font-size: 0.82rem;">
                            <i class="fa-solid fa-house-chimney-medical me-2 text-success"></i> Centro P.A.S.E (Hub)
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_remisiones.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-paper-plane me-2 text-success"></i> Remisiones
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_casos.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-folder-tree me-2 text-success"></i> Casos y Bitácora
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_citas.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-calendar-check me-2 text-success"></i> Agenda de Citas
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_buscar.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-magnifying-glass-chart me-2 text-success"></i> Búsqueda 360°
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_actividades.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-people-group me-2 text-success"></i> Talleres y Actividades
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/poblacion_riesgo.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-triangle-exclamation me-2 text-warning"></i> Población en Riesgo
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/caracterizacion_componentes.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-id-card-clip me-2 text-success"></i> Caracterización
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/caracterizacion_evaluar.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-pen-ruler me-2 text-success"></i> Evaluar Caracterización
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_solicitudes.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-hand-holding-hand me-2 text-success"></i> Solicitudes de Ayuda
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/soporte_tickets.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-ticket me-2 text-info"></i> Tickets de Soporte
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_tipos.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-sliders me-2 text-success"></i> Tipos de Atención
                          </a>
                        </div>
                      </div>
                    </div>

                    <!-- Asistencia -->
                    <div class="mt-1 pt-1 border-top">
                      <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.68rem;">
                        <i class="fa-solid fa-clipboard-user me-1 text-success"></i> Asistencia
                      </small>
                      <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>eventos_index.php" style="font-size: 0.82rem;">
                        <i class="fa-solid fa-calendar-days me-2 text-success"></i> Eventos y Asistencia
                      </a>
                      <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>actividades.php" style="font-size: 0.82rem;">
                        <i class="fa-solid fa-calendar-plus me-2 text-success"></i> Administrar Actividades
                      </a>
                      <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>actividades_asistencia.php" target="_blank" style="font-size: 0.82rem;">
                        <i class="fa-solid fa-qrcode me-2 text-success"></i> Registro Asistencia QR (FO-BI-BN-27)
                      </a>
                    </div>

                  </div>
                </div>
              <?php } ?>

              <!-- === MÓDULOS ACADÉMICOS === -->
              <h6 class="dropdown-header text-uppercase fw-bold mt-1 mb-2" style="font-size: 0.75rem; color: var(--primary); border-bottom: 1px solid var(--border-color); padding-bottom: 6px; padding-left: 0;">Módulos Académicos</h6>

              <!-- Práctica Extramuro -->
              <div class="mb-3">
                <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem;"><i
                    class="fa-solid fa-graduation-cap me-1"></i> Práctica Extramuro</small>
                <div class="d-flex flex-column gap-1">
                  <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>planeacion.php"><i
                      class="fa-solid fa-pen-to-square me-2 text-success"></i> Registrar Planeación</a>
                  <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>listado_planeacion.php"><i
                      class="fa-solid fa-list-ol me-2 text-success"></i> Listado Planeaciones</a>
                  <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>listado_gestion.php"><i
                      class="fa-solid fa-chart-column me-2 text-success"></i> Gestión de Prácticas</a>
                  <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>listado_informes.php"><i
                      class="fa-solid fa-file-lines me-2 text-success"></i> Informe Prácticas</a>
                  <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>manual_practias.pdf" target="_blank"><i
                      class="fa-solid fa-file-pdf me-2 text-success"></i> Manual de Usuario</a>
                  <?php if (($tipo_usuario_header >= 14 && $tipo_usuario_header <= 22) || $tipo_usuario_header == 24 || $tipo_usuario_header == 1) { ?>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>listado_procesos.php"><i
                        class="fa-solid fa-star me-2 text-success"></i> Cambiar Estados Práctica</a>
                  <?php } ?>
                </div>
              </div>

              <!-- Evaluación Docente -->
              <div class="mb-3">
                <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem;"><i
                    class="fa-solid fa-calculator me-1"></i> Evaluación Docente</small>
                <div class="d-flex flex-column gap-1">
                  <?php if ($tipo_usuario_header == 1 || $tipo_usuario_header == 23 || $tipo_usuario_header == 6) { ?>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>programas.php"><i
                        class="fa-solid fa-book-bookmark me-2 text-success"></i> Pregrado</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>asignaturas.php"><i
                        class="fa-solid fa-graduation-cap me-2 text-success"></i> Posgrado</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>docentes.php"><i
                        class="fa-solid fa-users-gear me-2 text-success"></i> Docentes</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>modalidades.php"><i
                        class="fa-solid fa-sliders me-2 text-success"></i> Modalidades</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>listado_docente_evaluacion.php"><i
                        class="fa-solid fa-square-check me-2 text-success"></i> Evaluar Docente</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>listado_docentes.php"><i
                        class="fa-solid fa-user-tie me-2 text-success"></i> Director</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>autoevaluacion.php"><i
                        class="fa-solid fa-user-pen me-2 text-success"></i> Autoevaluación</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>lista_reportes.php"><i
                        class="fa-solid fa-chart-line me-2 text-success"></i> Reportes Evaluación</a>
                  <?php } else { ?>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>listado_docentes.php"><i
                        class="fa-solid fa-chalkboard-user me-2 text-success"></i> Evaluar Docente</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>informe_evaluacion.php"><i
                        class="fa-solid fa-file-chart-column me-2 text-success"></i> Reporte Docentes</a>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>autoevaluacion.php"><i
                        class="fa-solid fa-user-pen me-2 text-success"></i> Autoevaluación</a>
                  <?php } ?>
                </div>
              </div>

              <!-- Agenda Profesoral -->
              <div class="mb-3">
                <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem;"><i
                    class="fa-solid fa-calendar-days me-1"></i> Agenda Profesoral</small>
                <div class="d-flex flex-column gap-1">
                  <?php if ($tipo_usuario_header == 1 || $tipo_usuario_header == 23 || $tipo_usuario_header == 6) { ?>
                    <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>fechas_agenda.php"><i
                        class="fa-solid fa-calendar-check me-2 text-success"></i> Fechas Agenda</a>
                  <?php } ?>
                  <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>crear_agenda.php"><i
                      class="fa-solid fa-plus me-2 text-success"></i> Crear Agenda</a>
                  <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>agenda_profesoral.php"><i
                      class="fa-solid fa-file-lines me-2 text-success"></i> Informe de Agenda</a>
                  <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>reporte_agenda.php"><i
                      class="fa-solid fa-chart-pie me-2 text-success"></i> Reporte Agenda</a>
                  <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>lista_reportes_agenda.php"><i
                      class="fa-solid fa-file-chart-pie me-2 text-success"></i> Reportes Generales Agenda</a>
                </div>
              </div>

              <!-- Si no es admin 1, mostrar también BIENESTAR -> P.A.S.E -->
              <?php if ($tipo_usuario_header != 1) { ?>
                <div class="mb-2">
                  <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem;"><i
                      class="fa-solid fa-heart-pulse me-1 text-success"></i> BIENESTAR</small>
                  <div class="d-flex flex-column gap-1">
                    <div class="rounded p-1" style="background: rgba(0, 132, 61, 0.04); border: 1px solid rgba(0, 132, 61, 0.18);">
                      <div class="d-flex align-items-center justify-content-between px-2 py-1.5 border-bottom mb-1" 
                           style="cursor: pointer;" 
                           data-bs-toggle="collapse" 
                           data-toggle="collapse" 
                           data-bs-target="#menuInternoPaseDoc" 
                           data-target="#menuInternoPaseDoc"
                           aria-expanded="true">
                        <strong class="text-success" style="font-size: 0.82rem;">
                          <i class="fa-solid fa-user-shield me-1"></i> P.A.S.E
                        </strong>
                        <i class="fa-solid fa-chevron-down text-muted" style="font-size: 0.65rem;"></i>
                      </div>
                      <div class="collapse show" id="menuInternoPaseDoc">
                        <div class="d-flex flex-column gap-1">
                          <a class="dropdown-item py-1 px-2 rounded fw-bold text-success" href="<?= $base_prod ?>pase/index.php" style="background: rgba(0, 132, 61, 0.08); font-size: 0.82rem;">
                            <i class="fa-solid fa-house-chimney-medical me-2 text-success"></i> Centro P.A.S.E (Hub)
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_remisiones.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-paper-plane me-2 text-success"></i> Remitir Estudiante
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_casos.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-folder-tree me-2 text-success"></i> Casos y Acompañamiento
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_citas.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-calendar-check me-2 text-success"></i> Agenda de Citas
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_buscar.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-magnifying-glass-chart me-2 text-success"></i> Búsqueda 360°
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_actividades.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-people-group me-2 text-success"></i> Talleres y Actividades
                          </a>
                          <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/soporte_tickets.php" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-ticket me-2 text-info"></i> Tickets de Soporte
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="mt-1 pt-1 border-top">
                      <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.68rem;">
                        <i class="fa-solid fa-clipboard-user me-1 text-success"></i> Asistencia
                      </small>
                      <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>eventos_index.php" style="font-size: 0.82rem;">
                        <i class="fa-solid fa-calendar-days me-2 text-success"></i> Eventos y Asistencia
                      </a>
                      <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>actividades_asistencia.php" target="_blank" style="font-size: 0.82rem;">
                        <i class="fa-solid fa-qrcode me-2 text-success"></i> Asistencia QR
                      </a>
                    </div>
                  </div>
                </div>
              <?php } ?>

            <?php } else { ?>
              <!-- Mis Módulos Estudiante -->
              <div class="mb-3">
                <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem;">Mis Módulos
                  Estudiante</small>
                <div class="d-flex flex-column gap-1">
                  <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>listado_evaluacion.php"><i
                      class="fa-solid fa-list-check me-2 text-success"></i> Evaluación Práctica</a>
                  <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>listado_docente_evaluacion.php"><i
                      class="fa-solid fa-star me-2 text-success"></i> Evaluar Mis Docentes</a>
                </div>
              </div>

              <!-- SECCIÓN BIENESTAR ESTUDIANTIL -->
              <div class="mb-2">
                <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem;">
                  <i class="fa-solid fa-heart-pulse me-1 text-success"></i> BIENESTAR
                </small>
                <div class="rounded p-1" style="background: rgba(0, 132, 61, 0.04); border: 1px solid rgba(0, 132, 61, 0.18);">
                  <div class="px-2 py-1 border-bottom mb-1 fw-bold text-success" style="font-size: 0.82rem;">
                    <i class="fa-solid fa-user-shield me-1"></i> P.A.S.E
                  </div>
                  <div class="d-flex flex-column gap-1">
                    <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/index.php" style="font-size: 0.82rem;">
                      <i class="fa-solid fa-house-chimney-medical me-2 text-success"></i> Centro Bienestar
                    </a>
                    <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_citas.php" style="font-size: 0.82rem;">
                      <i class="fa-solid fa-calendar-check me-2 text-success"></i> Mis Citas / Agendar
                    </a>
                    <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_solicitudes.php" style="font-size: 0.82rem;">
                      <i class="fa-solid fa-hand-holding-hand me-2 text-success"></i> Solicitar Ayuda
                    </a>
                    <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/caracterizacion_evaluar.php" style="font-size: 0.82rem;">
                      <i class="fa-solid fa-pen-ruler me-2 text-success"></i> Encuesta de Caracterización
                    </a>
                    <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>pase/soporte_tickets.php" style="font-size: 0.82rem;">
                      <i class="fa-solid fa-ticket me-2 text-info"></i> Soporte y Orientación
                    </a>
                    <a class="dropdown-item py-1 px-2 rounded" href="<?= $base_prod ?>actividades_asistencia.php" target="_blank" style="font-size: 0.82rem;">
                      <i class="fa-solid fa-qrcode me-2 text-success"></i> Registrar Asistencia QR
                    </a>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>

        <!-- BIENESTAR DIRECT DROPDOWN EN TOPBAR -->
        <div class="dropdown">
          <button class="btn-top-link dropdown-toggle d-flex align-items-center gap-1 border-0 bg-transparent"
            type="button" id="navbarBienestarTop" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            style="font-weight: 600; color: var(--text-main); font-size: 0.88rem; padding: 8px 12px; border-radius: 8px; cursor: pointer;">
            <i class="fa-solid fa-heart-pulse me-1 text-success"></i>
            <span>Bienestar</span>
            <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.7rem; color: var(--text-muted);"></i>
          </button>
          <div class="dropdown-menu shadow-lg p-3" aria-labelledby="navbarBienestarTop"
            style="width: 330px; max-height: 490px; overflow-y: auto; border-radius: 14px; border: 1px solid var(--border-color);">
            <div class="px-2 pb-2 mb-2 border-bottom d-flex align-items-center justify-content-between">
              <strong style="color: var(--text-main); font-size: 0.88rem;"><i class="fa-solid fa-user-shield text-success me-1"></i> P.A.S.E</strong>
              <span class="badge bg-green">Bienestar</span>
            </div>
            <div class="d-flex flex-column gap-1">
              <a class="dropdown-item py-1.5 px-2 rounded fw-bold text-success" href="<?= $base_prod ?>pase/index.php" style="background: rgba(0, 132, 61, 0.08);">
                <i class="fa-solid fa-house-chimney-medical me-2 text-success"></i> Centro P.A.S.E (Hub)
              </a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_remisiones.php"><i class="fa-solid fa-paper-plane me-2 text-success"></i> Remisiones</a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_casos.php"><i class="fa-solid fa-folder-tree me-2 text-success"></i> Casos y Bitácora</a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_citas.php"><i class="fa-solid fa-calendar-check me-2 text-success"></i> Agenda de Citas</a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_buscar.php"><i class="fa-solid fa-magnifying-glass-chart me-2 text-success"></i> Búsqueda 360°</a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_actividades.php"><i class="fa-solid fa-people-group me-2 text-success"></i> Talleres y Actividades</a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>pase/poblacion_riesgo.php"><i class="fa-solid fa-triangle-exclamation me-2 text-warning"></i> Población en Riesgo</a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>pase/caracterizacion_componentes.php"><i class="fa-solid fa-id-card-clip me-2 text-success"></i> Caracterización</a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>pase/caracterizacion_evaluar.php"><i class="fa-solid fa-pen-ruler me-2 text-success"></i> Evaluar Caracterización</a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_solicitudes.php"><i class="fa-solid fa-hand-holding-hand me-2 text-success"></i> Solicitudes de Ayuda</a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>pase/soporte_tickets.php"><i class="fa-solid fa-ticket me-2 text-info"></i> Tickets de Soporte</a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>pase/seguimiento_tipos.php"><i class="fa-solid fa-sliders me-2 text-success"></i> Tipos de Atención</a>
              <div class="border-top my-1"></div>
              <small class="text-uppercase fw-bold text-muted px-2" style="font-size: 0.68rem;">Asistencia</small>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>eventos_index.php"><i class="fa-solid fa-calendar-days me-2 text-success"></i> Eventos y Asistencia</a>
              <a class="dropdown-item py-1.5 px-2 rounded" href="<?= $base_prod ?>actividades_asistencia.php" target="_blank"><i class="fa-solid fa-qrcode me-2 text-success"></i> Asistencia QR</a>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- CENTER: GLOBAL SEARCH -->
    <div class="topbar-search d-none d-md-block" style="width: 320px; position: relative;">
    </div>

    <!-- RIGHT: CONTROLS & USER PROFILE -->
    <div class="d-flex align-items-center" style="gap: 14px;">

      <!-- NOTIFICATIONS -->
      <?php if ($registros_notas > 0) { ?>
        <div class="dropdown">
          <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-bs-toggle="dropdown" data-toggle="dropdown"
            aria-expanded="false" style="position: relative; text-decoration: none;">
            <i class="fa-regular fa-envelope"></i>
            <span class="badge bg-green"
              style="position: absolute; top: -4px; right: -4px; background: var(--primary-gradient); color: white; font-size: 0.68rem; font-weight: 800; padding: 2px 6px; border-radius: 10px; border: 2px solid #FFF;">
              <?= $registros_notas ?>
            </span>
          </a>

          <div class="dropdown-menu shadow-lg p-0" aria-labelledby="navbarDropdown1"
            style="width: 320px; right: 0; left: auto; border-radius: 14px; border: 1px solid var(--border-color);">
            <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center"
              style="background: var(--bg-main);">
              <strong style="color: var(--text-main); font-size: 0.88rem;">Notificaciones</strong>
              <span class="badge bg-green"><?= $registros_notas ?> nuevas</span>
            </div>

            <?php
            foreach ($resNotas as $key => $value) {
              $ruta = ($value['tipo'] == 1) ? $base_prod . "planeacion.php?id=" . $value['id_planeacion'] : $base_prod . "gestionar.php?id=" . $value['id_planeacion'];
              $rutaFotoNota = $base_root . "adjuntos/fotos/" . $value['id_usuario'] . ".jpg";
              $fotoNota = file_exists($rutaFotoNota) ? $rutaFotoNota . "?v=" . time() : "images/img.jpg";
              ?>
              <a class="dropdown-item d-flex gap-3 align-items-start py-2 px-3 border-bottom" href="<?= $ruta ?>">
                <img src="<?= $fotoNota ?>" alt="Profile"
                  style="width:36px;height:36px;object-fit:cover;border-radius:50%;border: 1px solid var(--primary);">
                <div class="flex-grow-1" style="line-height: 1.3;">
                  <div class="d-flex justify-content-between align-items-center">
                    <strong
                      style="font-size: 0.82rem; color: var(--text-main);"><?= strtoupper(safe_utf8_encode($value['name'])) ?></strong>
                    <small style="font-size: 0.7rem; color: var(--text-muted);"><?= $value['fecha_registro'] ?></small>
                  </div>
                  <p class="mb-0 text-truncate" style="max-width: 200px; font-size: 0.78rem; color: var(--text-muted);">
                    <?= $value['nota'] ?>
                  </p>
                </div>
              </a>
            <?php } ?>
          </div>
        </div>
      <?php } ?>

      <!-- USER PROFILE DROPDOWN -->
      <div class="dropdown">
        <a href="javascript:;" class="user-profile dropdown-toggle d-flex align-items-center gap-2 text-decoration-none"
          id="navbarDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-expanded="false"
          style="padding: 6px 14px; border-radius: 24px; border: 1px solid var(--border-color); background: #FFFFFF;">
          <img src="<?= $fotoPerfil ?>" alt="Usuario"
            style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary);">
          <span
            style="font-weight: 600; color: var(--text-main); font-size: 0.88rem;"><?= safe_utf8_encode($user_name_header); ?></span>
          <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem; color: var(--text-muted);"></i>
        </a>

        <div class="dropdown-menu shadow-lg" aria-labelledby="navbarDropdown"
          style="right: 0; left: auto; border-radius: 12px; border: 1px solid var(--border-color);">
          <a class="dropdown-item py-2 px-3" href="<?= $base_prod ?>perfil.php">
            <i class="fa-regular fa-user me-2" style="color: var(--primary);"></i> Mi Perfil
          </a>
          <a class="dropdown-item py-2 px-3" href="<?= $base_prod ?>panel.php">
            <i class="fa-solid fa-house me-2" style="color: var(--primary);"></i> Dashboard Home
          </a>
          <div class="dropdown-divider my-1"></div>
          <a class="dropdown-item py-2 px-3 text-danger" href="<?= $base_root ?>login/index.php?cerrar">
            <i class="fa-solid fa-right-from-bracket me-2"></i> Cerrar Sesión
          </a>
        </div>
      </div>

    </div>

  </div>
</div>

<style>
  .btn-top-link {
    color: var(--text-main);
    font-weight: 600;
    font-size: 0.88rem;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none !important;
    transition: all 0.2s ease;
  }

  .btn-top-link:hover {
    background-color: var(--primary-light);
    color: var(--primary);
  }
</style>