<?php
	require_once('codigos/conexion.inc'); 
	
	$IDCarpetaActual = $_GET["IDCarpeta"] ?? null;

	//Inicio la sesión
	session_start();
	
	//Utiliza los datos de sesion comprueba que el usuario este autenticado
	if($_SESSION["autenticado"] != "SI") {
		header("Location: index.php");
		exit(); //fin del scrip
	}
	$usuarioActivo = $_SESSION["nombre"];
	
	if (empty($IDCarpetaActual)) {
		$auxSql = sprintf("SELECT b.* FROM carpetas a INNER JOIN carpetas b ON a.IDCarpeta = b.CarpetaPadreID WHERE a.NombreCarpeta = '%s' AND a.IsRoot = 1", $_SESSION["usuario"]);
		$directorio = mysqli_query($conex,$auxSql);
		
		$auxSql = sprintf("SELECT IDCarpeta FROM carpetas WHERE NombreCarpeta = '%s' AND IsRoot = 1", $_SESSION["usuario"]);
		$res = mysqli_query($conex, $auxSql);
		$fila = mysqli_fetch_assoc($res);
		$IDCarpetaActual = $fila["IDCarpeta"] ?? null;
	} else {
		// Si hay IDCarpeta, buscar sus subcarpetas
		$auxSql = sprintf("SELECT * FROM carpetas WHERE CarpetaPadreID = %d", $IDCarpetaActual);
		$directorio = mysqli_query($conex, $auxSql);
	}

	// Consultar archivos de la carpeta actual
	$auxSql = sprintf("SELECT * FROM archivos WHERE IDCarpeta = %d", $IDCarpetaActual);
	$archivos = mysqli_query($conex, $auxSql);
?>
<!doctype html>
<html>
<head>
	<?php include_once('partes/encabe.inc'); ?>
    <title>Ingreso al Sitio</title>
</head>
<body class="container cuerpo">
	<header class="row">
        <div class="row">
        	<div class="col-lg-6 col-sm-6">
        		<img  src="imagenes/encabe.png" alt="logo institucional" width="100%">
            </div>
        </div>
        <div class="row">
            <?php include_once('partes/menu.inc'); ?>
        </div>
        <br />
    </header>

    <main class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<strong>Almacen de Archivos de <?php echo $usuarioActivo; ?></strong>
			</div>
			<div class="panel-body">
				<?php
					$conta = 0;
					echo '<a href=./agregarcarpeta.php?IDCarpeta=' . $IDCarpetaActual . '>'.'Agregar directorio</a>';
					echo '<br><br>';
					echo '<table class="table table-striped">';
						echo '<tr>';
							echo '<th>Nombre</th>';
							echo '<th>Borrar</th>';
						echo '</tr>';
						if ($directorio && mysqli_num_rows($directorio) > 0) {
							while ($elem = mysqli_fetch_assoc($directorio)) {
								echo '<tr>';
								echo '<td><a href="carpetas.php?IDCarpeta=' . $elem['IDCarpeta'] . '">' . htmlspecialchars($elem['NombreCarpeta']) . '</a></td>';
								echo '<td><a href="./codigos/borcarpeta.php?id=' . $elem['IDCarpeta'] . '">Borrar</a></td>';
								echo '</tr>';
								$conta++;
							} // fin del while
						} // fin del if
					echo '</table>';
					echo '<br><br>';
					if($conta == 0) {
						echo 'La carpeta se encuentra vac&iacute;a';
						echo '<br><br>';
					}	
				?>
				<?php
					$conta = 0;
					echo '<a href=./agrearchi.php?IDCarpeta=' . $IDCarpetaActual . '>Agregar archivo</a>';
					echo '<br><br>';
					echo '<table class="table table-striped">';
						echo '<tr>';
							echo '<th>Nombre</th>';
							echo '<th>Tama&ntilde;o</th>';
							echo '<th>Tipo documento</th>';
							echo '<th>Extensi&oacute;n</th>';
							echo '<th>Fecha almacenado</th>';
							echo '<th>Comentario</th>';
							echo '<th>Borrar</th>';
						echo '</tr>';
						if ($archivos && mysqli_num_rows($archivos) > 0) {
							while ($elem = mysqli_fetch_assoc($archivos)) {
								// Convertir peso de bytes a MB
								$pesoMB = number_format($elem['peso'] / 1048576, 2);
								
								echo '<tr>';
								echo '<td><a href="verarchi.php?id=' . $elem['IDArchivo'] . '">' . htmlspecialchars($elem['nombre_archivo']) . '</a></td>';
								echo '<td>' . $pesoMB . ' MB</td>';
								echo '<td>' . htmlspecialchars($elem['tipo_documento']) . '</td>';
								echo '<td>' . htmlspecialchars($elem['extension']) . '</td>';
								echo '<td>' . $elem['fecha_almacenado'] . '</td>';
								echo '<td>' . htmlspecialchars($elem['comentario']) . '</td>';
								echo '<td><a href="./codigos/borarchi.php?id=' . $elem['IDArchivo'] . '">Borrar</a></td>';
								echo '</tr>';
								$conta++;
							} // fin del while
						} // fin del if
					echo '</table>';
					echo '<br><br>';
					if($conta == 0)
						echo 'No se encontraron archivos en la carpeta';
					else{
						echo 'Cantidad de archivos: ' . $conta;
					}
				?>
			</div>
		</div>
    </main>

    <footer class="row">

    </footer>
	<?php include_once('partes/final.inc'); ?>
</body>
</html>