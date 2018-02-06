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
	
// Update product primary provider.
if (isset($_POST['submit-forn-prim'])) { 

	try {
		if (isset($_POST['prod']) && isset($_POST['forn']) && $_POST['prod'] && $_POST['forn']) {
			$db = new SupermarketService();
			$ean = $_POST['prod'];
			$nif = $_POST['forn'];
			$db->updateProductPrimProvider($ean, $nif);
			$db->close();
			$db = null;
			
			$success = "Fornecedor primário (NIF: " . $nif . ") alterado para o produto (EAN: " . $ean . ") com sucesso.";
		}
		else {
			$error = "Precisa de especificar o produto e o novo fornecedor primário.";
		}
		
	} 
	catch (PDOException $e) {
		$error = SupermarketException::digest($e);
	}
}    

// Insert product secondary provider
if (isset($_POST['submit-forn-sec'])) {   
	
	try {
		if (isset($_POST['prod']) && isset($_POST['forn-sec']) && $_POST['prod'] && $_POST['forn-sec']) {
			$db = new SupermarketService();
			$ean = $_POST['prod'];
			$nif = $_POST['forn-sec'];
			$db->insertProductSecProvider($ean, $nif);
			$db->close();
			$db = null;
			
			$success = "Fornecedor secundário (NIF: " . $nif . ") adicionado para o produto (EAN: " . $ean . ") com sucesso.";
		}
		else {
			$error = "Precisa de inserir o produto e o fornecedor secundário.";
		}
			
	}
	catch (PDOException $e) {
		$error = SupermarketException::digest($e);
	}
	
}

// Remove product secondary provider
if (isset($_POST['remove_product_provider'])) {
	try {
		if (isset($_POST['prod']) && isset($_POST['nif']) && $_POST['prod'] && $_POST['nif']) {
			$db = new SupermarketService();
			$ean = $_POST['prod'];
			$nif = $_POST['nif'];
			$db->removeProductSecProvider($ean, $nif);
			$db->close();
			$db = null;
			
			$success = "Fornecedor secundário (NIF: " . $nif . ") removido para o produto (EAN: " . $ean . ") com sucesso.";
		}
		else {
			$error = "Precisa de especificar o produto e o fornecedor secundário a remover.";
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
render_header("product", "Gerir Fornecimentos de Produto"); 

// Display success/error alerts
if (isset($success)) {
	success($success);
}
	
else if (isset($error)) {
	error($error);
}
	
?>

<!-- Product selection form -->
<h1>Gerir Fornecimentos de Produto</h1>
<form action="manage_product_providers.php" method="post">
	<div class="row">
		<div class="col-md-3 form-group">
			<select class="form-control prod" name="prod" onchange="if(this.value) { this.form.submit(); }">
    			<option disabled selected value="">Escolher Produto</option>
    			<?php
    				// Fetch products from database
    				try {
    					$db = new SupermarketService();
    					$products = $db->getProducts();
    					$db->close();
    					$db = null;
    					
    					foreach ($products as $product) {
    						echo("<option value=\"" . $product["ean"] . "\">" . $product["ean"]  . " - " . $product["design"] . "</option>");
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

<?php 
// Render tables if product is specified
if(isset($_POST['prod']) && $_POST['prod']) {
?>
    
<br>
<!-- Primary provider table -->
<h2>Fornecedor Primário</h2>

<?php
// Fetch product primary provider
try {
	$db = new SupermarketService();
	$providers = $db->getProductPrimProvider($_POST['prod']);
	$otherProviders = $db->getProductPrimaryProviders($_POST['prod']);
	$db->close();
	$db = null;
	
	// Render table if there is a primary provider
	if (sizeof($providers) > 0) {
?>
<div class="row">
	<div class="col-md-10">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>NIF</th>
					<th>Nome</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo($providers[0]['nif']) ?></td>
					<td><?php echo($providers[0]['nome']) ?></td>
					<td>
						<form class="form-inline" action="manage_product_providers.php" method="post" onSubmit="return confirm('Tem a certeza que prende alterar o fornecedor primário deste produto?');">
							<div class="form-group">
								<input type="hidden" value="<?php echo($_POST['prod']) ?>" name="prod"/>
								<select class="form-control forn" name="forn" required>
									<option disabled selected value="">Alterar Fornecedor</option>
									<?php foreach($otherProviders as $oprovider) { 
										echo("<option value=\"" . $oprovider["nif"] . "\">" . $oprovider["nif"]  . " - " . $oprovider["nome"] . "</option>");
									} ?>
									<option value="new-forn">(+) Novo Fornecedor</option>
								</select>
							</div>
							&nbsp;&nbsp;&nbsp;
							<button class="btn btn-primary" type="submit" name="submit-forn-prim">
								<span class="icon icon-arrows-ccw" aria-hidden="true"></span>   Alterar Fornecedor Primário
							</button>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<?php 
	} // end if
	else {
		// This is for precaution only, as it can't happen in the database
		error("Este produto não tem um fornecedor primário.");
	}
} //end try 
catch (PDOException $e) {
	error(SupermarketException::digest($e));
}
?>
<br>

<p><h2>Fornecedores Secundários</h2></p>

<!-- Add secondary provider form -->
<form class="form-inline" action="manage_product_providers.php" method="post">
	<input type="hidden" value="<?php echo $_POST['prod'] ?>" name="prod" />
	<div class="form-group">
		<label for="forn-sec">Adicionar Fornecedor Secundário:&nbsp; &nbsp;</label>
		<select class="form-control forn-sec" name="forn-sec" required>
			<option selected disabled value="">Escolher Fornecedor</option>
			<?php
				// Fetch secondary providers candidates from database
				try {
					$db = new SupermarketService();
					$providers = $db->getProductSecondaryProviders($_POST['prod']);
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
			<option value="new-forn">(+) Novo Fornecedor</option>
		</select>
	</div>
	<button type="submit" name="submit-forn-sec" class="btn btn-primary">
		<span class="icon icon-plus-circled" aria-hidden="true"></span>  Adicionar
	</button>
</form>
<br>

<!-- Secondary providers table -->
<?php
// Fetch product secondary providers
try {
	$db = new SupermarketService();
	$sec_providers = $db->getProductSecProviders($_POST['prod']);
	$db->close();
	$db = null;
	
	// Render table if there are secondary providers
	if (sizeof($sec_providers) > 0) {
?>
<div class="row">
	<div class="col-md-8">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>NIF</th>
					<th>Nome</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($sec_providers as $sec_provider) { ?>
				<tr>
					<td><?php echo($sec_provider['nif']) ?></td>
					<td><?php echo($sec_provider['nome']) ?></td>
					<td>
						<form action="manage_product_providers.php" method="post" onSubmit="return confirm('Tem a certeza que pretende eliminar este fornecedor secundário?');">
							<input type="hidden" value="<?php echo($_POST['prod']) ?>" name="prod"/>
							<input type="hidden" value="<?php echo($sec_provider['nif']) ?>" name="nif"/>
							<button class="btn btn-danger" type="submit" name="remove_product_provider">
								<span class="icon icon-trash" aria-hidden="true"></span>  Remover
							</button>
						</form>
					</td>
				</tr>
				<?php } // end foreach ?>
			</tbody>
		</table>
	</div>
</div>

<?php
	} // end if
	else {
		error("Este produto não tem fornecedores secundários.");
	}

} // end try
catch (PDOException $e) {
	error(SupermarketException::digest($e));
}

} // end isset $_POST['prod']

// Render footer HTML
render_footer(Array("assets/js/manage_product_providers.js"));
?>
			
<script>
    <?php
		
	    if(isset($_POST['prod']) && $_POST['prod']) {
	?>
		// Script to select product previously selected
	    $('select.prod').val("<?php echo $_POST['prod'] ?>");
	    
	    // Hide current primary provider
	    $('select.forn option[value="<?php 
	    	// Fetch product primary provider
	    	try {
	    	    $db = new SupermarketService();
		    	$prod_prim_provider = $db->getProductPrimProvider($_POST['prod']);
		    	$db->close();
				$db = null;
				
				echo(trim($prod_prim_provider[0]['nif']));
	    	} 
	    	catch (PDOException $e) {
				error(SupermarketException::digest($e));
			}
	        
	    ?>"]').hide();
	   
	<?php
	
	    } // end isset $_POST['prod']
	
	?>
</script>