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

// Insert new product.
if (isset($_POST['submit'])) { 
	try {
		if (isset($_POST['ean']) && isset($_POST['category']) && isset($_POST['design']) &&
			isset($_POST['forn']) && isset($_POST['forn-sec']) && isset($_POST['date']) &&
			$_POST['ean'] && $_POST['category'] && $_POST['design'] && $_POST['forn'] &&
			$_POST['forn-sec'] && $_POST['date']) {
				
			$db = new SupermarketService();
			
			$ean = $_POST['ean'];
			$category = $_POST['category'];
			$design = $_POST['design'];
			$prim_provider = $_POST['forn'];
			$sec_providers = array_filter($_POST['forn-sec']);
			$date = date('Y-m-d', strtotime($_POST['date']));
		
			$db->insertProduct($ean, $category, $design, $prim_provider, $sec_providers, $date);
			$db->close();
			$db = null;
			
			$success = "Produto " . $design . " inserido com sucesso.";
		}
		else {
			$error =  "Precisa de inserir todas as informações necessárias para o novo produto.";
		}
		
	} 
	catch (PDOException $e) {
		$error = SupermarketException::digest($e);
	}
}

/* Remove product */
if (isset($_POST['remove_product'])) {
	try {
		if (isset($_POST['ean']) && isset($_POST['design']) && $_POST['ean'] && $_POST['design']) {
			$db = new SupermarketService();
			$ean = $_POST['ean'];
			$db->removeProduct($ean);
			$db->close();
			$db = null;
			
			$success = "Produto " . $_POST['design'] . " removido com sucesso!";
		}
		else {
			$error =  "Precisa de especificar o produto a remover.";
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
render_header("product", "Gerir Produtos", Array("//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css")); 


// Display success/error alerts
if (isset($success)) {
	success($success);	
}
	
else if (isset($error)) {
	error($error);
}
	
?>

<!-- New product form -->
<h1>Inserir Novo Produto</h1>
<div class="row">
	<div class="col-md-5">
		<form action="manage_products.php" method="post">
			<div class="form-group">
			    <input type="text" name="ean" class="form-control" placeholder="EAN" maxlength="13" minlength="13" required>
	  		</div>
	  		<div class="form-group">
			    <input type="text" name="design" class="form-control" placeholder="Designação" required>
	  		</div>
	  		<div class="form-group">
		  		<select class="form-control" name="category" required>
				  	<option disabled selected value="">Escolher Categoria</option>
					<?php
						// Fetch categories from database
						try {
							$db = new SupermarketService();
							$categories = $db->getCategories();
							$db->close();
							$db = null;
							
							foreach ($categories as $category) {
								echo("<option value=\"" . $category["nome"] . "\">" . $category["nome"] . "</option>");
							}
							
						} 
						catch (PDOException $e) {
							error(SupermarketException::digest($e));
						}
					?>
				</select>
			</div>
			<div class="form-group">
				<select class="form-control forn" name="forn" required>
					<option disabled selected value="">Escolher Fornecedor Primário</option>
					<?php
						// Fetch providers from database
						try {
							$db = new SupermarketService();
							$providers = $db->getProviders();
							$db->close();
							$db = null;
							
							foreach ($providers as $provider) { 
								echo("<option value=\"" . $provider["nif"] . "\">" . $provider["nif"]  . " - " . $provider["nome"] . "</option>");
							}
						
						}
						catch (PDOException $e) {
							error(SupermarketException::digest($e));
						}
					?>
					<option value="new-forn">(+) Novo fornecedor</option>
				</select>
			</div>
			<div class="form-group">
				<input id="datepicker" class="form-control" type="text" name="date" placeholder="Data de Inicio de Fornecimento" required>
			</div>
			<div class="form-group forn-sec-wrapper">
				<div class="input-group">
					<select class="form-control forn-sec" name="forn-sec[]" required>
					  	<option disabled selected value="">Escolher Fornecedor Secundário</option>
						<?php
							// Fetch providers from database
							try {
								$db = new SupermarketService();
								$providers = $db->getProviders();
								$db->close();
								$db = null;
								
								foreach ($providers as $provider) { 
									echo("<option value=\"" . $provider["nif"] . "\">" . $provider["nif"]  . " - " . $provider["nome"] . "</option>");
								}
								
							} 
							catch (PDOException $e) {
								error(SupermarketException::digest($e));
							}
						?>
						<option value="new-forn">(+) Novo fornecedor</option>
					</select>
					<div class="input-group-btn">
						<button class="btn btn-danger" type="button" name="remove_product" style="line-height: 1.3;" disabled>
							<span class="icon icon-cancel" aria-hidden="true"></span>
						</button>
					</div>
				</div>
			</div>
			<button type="button" class="btn btn-default add-another" disabled>
				<span class="icon icon-plus" aria-hidden="true"></span>  Adicionar Outro Fornecedor Secundário
			</button>
			<button type="submit" name="submit" class="btn btn-primary">
				<span class="icon icon-plus-circled" aria-hidden="true"></span>  Adicionar
			</button>
		</form>
	</div>
</div>

<!-- Exisiting products table -->
<h2>Produtos Existentes</h2>

<?php
// Fetch products from database
try {
	$db = new SupermarketService();
	$products = $db->getProducts();
	$db->close();
	$db = null;
	
	// Render table if there are products
	if (sizeof($products) > 0) { 
?>
	
<table class="table table-bordered">
	<thead>
		<tr>
			<th>EAN</th>
			<th>Designação</th>
			<th>Fornecedor Primário (NIF)</th>
			<th>Data de Contrato</th>
			<th>Categoria</th>
			<th>Ações</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($products as $product) { ?>
		<tr>
			<td><?php echo($product['ean']) ?></td>
			<td><?php echo($product['design']) ?></td>
			<td><?php echo($product['forn_primario']) ?></td>
			<td><?php echo(date("d-m-Y", strtotime($product['data']))) ?></td>
			<td><?php echo($product['categoria']) ?></td>
			<td>
				<form action="manage_products.php" method="post" onSubmit="return confirm('Tem a certeza que pretende eliminar este produto?');">
					<input type="hidden" value="<?php echo($product['ean']) ?>" name="ean"/>
					<input type="hidden" value="<?php echo($product['design']) ?>" name="design"/>
					<button class="btn btn-danger" type="submit" name="remove_product">
						<span class="icon icon-trash" aria-hidden="true"></span>  Remover
					</button>
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
render_footer(Array("//code.jquery.com/ui/1.12.1/jquery-ui.min.js", "assets/js/manage_products.js"));
?>