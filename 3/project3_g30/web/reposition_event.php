<?php

/**
 * Bases de Dados 17/18
 * Turno BD2251795L09
 * Sexta-Feira 12h30
 * 
 * Grupo 30
 *
 * @author Francisco Neves (83467)
 * @author Francisco Catarrinho (83468)
 * @author Pedro Santos (83550)
 */
 
require_once('inc/db/SupermarketService.class.php');
require_once('inc/db/SupermarketException.class.php');
require_once('inc/alerts.php');
require_once('inc/header.php');
require_once('inc/footer.php');

/*
 * POST requests handling
 */
 
// Product to be listed
if (isset($_POST) && isset($_POST['ean'])) {
	if ($_POST['ean']) {
		// Fetch product reposition events
		try {
			$db = new SupermarketService();
			$ean = $_POST['ean'];
			$repositions = $db->listRepositionEvents($ean);
			$db->close();
			$db = null;
		}
		catch (PDOException $e) {
			$error = SupermarketException::digest($e);
		}
	}
	else {
		$error = "Precisa de especificar o produto a listar.";
	}
}

/*
 * HTML page
 */
		
// Render header HTML
render_header("reposition", "Eventos de Reposição"); 

// Display success/error alerts
if (isset($success)) {
	success($success);
}
	
else if (isset($error)) {
	error($error);
}
?>

<h1>Listar Reposições de Produto</h1>

<!-- Product to be listed form -->
<form action="reposition_event.php" method="post">
	<div class="row">
		<div class="col-md-3 form-group">
			<select class="form-control ean" name="ean" onchange="if(this.value) { this.form.submit(); }">
				<option value="" disabled selected>Escolher Produto</option>
				<?php
					// Fetch products from database
					try {
						$db = new SupermarketService();
						$products = $db->getProducts();
						$db->close();
						$db = null;
									
						foreach ($products as $product) {
							echo("<option value=\"" . $product["ean"] . "\">" . $product['ean'] . " - " . $product["design"] . "</option>");
						}
						
					} 
					catch (PDOException $e) {
						error(SupermarketException::digest($e));
					}
				?>
			</select>
		</div>
	</div>
	
</form>
<p></p>

<?php

// Show table if appropriate

if ($_POST['ean']) {
	if (isset($repositions) && sizeof($repositions) > 0) {

?>
<!-- Reposition events table -->
<table class="table table-bordered">
	<thead>
		<tr>
			<th>EAN</th>
			<th>Corredor</th>
			<th>Lado</th>
			<th>Altura</th>
			<th>Operador</th>
			<th>Instante</th>
			<th>Unidades</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($repositions as $reposition) { ?>
		<tr>
			<td><?php echo($reposition['ean']) ?></td>
			<td><?php echo($reposition['nro']) ?></td>
			<td><?php echo($reposition['lado']) ?></td>
			<td><?php echo($reposition['altura']) ?></td>
			<td><?php echo($reposition['operador']) ?></td>
			<td><?php echo($reposition['instante']) ?></td>
			<td><?php echo($reposition['unidades']) ?></td>
		</tr>
		<?php } // end foreach ?>
	</tbody>
</table>			
				
<?php

	} // end repositions if
	else {
		error("Este produto nunca foi reposto.");
	}
} // end $_POST if

// Render footer HTML
render_footer(); 

?>
<script>
    <?php
		// Script to select product previously selected
	    if(isset($_POST['ean']) && $_POST['ean']) {
	?>
	    $('select.ean').val("<?php echo $_POST['ean'] ?>");
	    
	<?php
	
	    } // end isset
	
	?>
</script>

