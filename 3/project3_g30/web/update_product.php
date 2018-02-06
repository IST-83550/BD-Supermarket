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

// Update product description
if (isset($_POST['update_product'])) {
	try {
				
		if (isset($_POST['ean']) && isset($_POST['design']) && isset($_POST['design_orig']) 
			&& $_POST['ean'] && $_POST['design'] && $_POST['design_orig']) {
				
			$db = new SupermarketService();
			$ean = $_POST['ean'];
			$design = $_POST['design'];
			$db->updateProductDesignation($ean, $design);
			$db->close();
			$db = null;
			
			$success = "Alterou a designação do produto (EAN: " . $ean . ") de " . $_POST['design_orig']. " para " . $design . " com sucesso.";
		}
		else {
			$error = "É necessário inserir uma nova designação para o produto.";
		}
			
	}
	catch (PDOException $e) {
		$error = SupermarketException::digest($e);
	}
}

/*
 * HTML page
 */
		
// Render header HTML
render_header("product", "Alterar Produtos"); 

// Display success/error alerts
if (isset($success)) {
	success($success);
}
	
else if (isset($error)) {
	error($error);
}
	
?>

<!-- Products designation update table -->
<h1>Alterar Designação de Produto</h1>

<?php
// Fetch products from database
try {
	$db = new SupermarketService();
	$products = $db->getProducts();
	$db->close();
	$db = null;
	
	// Render table if there are products
	if (isset($products) && sizeof($products) > 0) { 
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>EAN</th>
			<th>Categoria</th>
			<th>Designação</th>
			<th>Alterar Designação</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($products as $product) { ?>
		<tr>
			<td><?php echo($product['ean']) ?></td>
			<td><?php echo($product['categoria']) ?></td>
			<td><?php echo($product['design']) ?></td>
			<td>
				<form action="update_product.php" method="post" onSubmit="return confirm('Tem a certeza que pretende alterar a designação deste produto?');">
					<input type="hidden" value="<?php echo($product['ean']) ?>" name="ean"/>
					<input type="hidden" value="<?php echo($product['design']) ?>" name="design_orig"/>
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" name="design" placeholder="Nova designação" required />
							<div class="input-group-btn">
								<button class="btn btn-primary" type="submit" name="update_product">
									<span class="icon icon-arrows-ccw" aria-hidden="true"></span>  Alterar
								</button>
							</div>
						</div>
					</div>
				</form>
			</td>
		</tr>
		<?php } // end foreach ?>
	</tbody>
</table>

<?php
	} // end if
	else {
		error("Ainda não há produtos no sistema.");
	}

} // end try
catch (PDOException $e) {
	error(SupermarketException::digest($e));
}

// Render footer HTML
render_footer();
?>