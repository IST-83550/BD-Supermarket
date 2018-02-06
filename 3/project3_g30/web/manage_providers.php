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
	
// Insert new provider.
if (isset($_POST['submit'])) {
	try {
		if (isset($_POST['nif']) && isset($_POST['name']) && $_POST['nif'] && $_POST['name']) {
			$db = new SupermarketService();
			$nif = $_POST['nif'];
			$name = $_POST['name'];
			$db->insertProvider($nif, $name);
			$db->close();
			$db = null;
						
			$success = "Fornecedor " . $name . " inserido com sucesso.";
		}
		else {
			$error = "Precisa de inserir um nif e um nome para o novo fornecedor.";
		}
			
	} 
	catch (PDOException $e) {
		$error = SupermarketException::digest($e);
	}
}   
	
// Remove provider.
if(isset($_POST['remove_provider'])){

	try {
		if (isset($_POST['nif']) && isset($_POST['name']) && $_POST['nif'] && $_POST['name']) {
			$db = new SupermarketService();
			$nif = $_POST['nif'];
			$db->removeProvider($nif);
			$db->close();
			$db = null;
			
			$success = "Fornecedor " . $_POST['name'] . " removido com sucesso.";
		}
		else {
			$error = "Precisa de especificar o fornecedor a remover.";
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
render_header("provider", "Gerir Fornecedores"); 

// Display success/error alerts
if (isset($success)) {
	success($success);
}
	
else if (isset($error)) {
	error($error);
}

?>

<!-- New provider form -->
<h1>Inserir Fornecedor</h1>

<form class="form-inline" action="manage_providers.php" method="post">
	<div class="form-group">
		<input type="text" name="nif" class="form-control" placeholder="NIF" maxlength="9" minlength="9" required>
	</div>
	<div class="form-group">
		<input type="text" name="name" class="form-control" placeholder="Nome" required>
	</div>
	<button class="btn btn-primary" type="submit" value="submit" name="submit">
		<span class="icon icon-plus-circled" aria-hidden="true"></span>  Adicionar
	</button>
</form>

<!-- Existing providers table -->
<h2>Fornecedores Existentes</h2>

<?php
// Fetch providers from database
try {
	$db = new SupermarketService();
	$providers = $db->getProviders();
	$db->close();
	$db = null;
	
	// Render table if there are providers
	if (sizeof($providers) > 0) {
?>

<div class="row">
	<div class="col-md-6">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>NIF</th>
					<th>Nome</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($providers as $provider) { ?>
				<tr>
					<td><?php echo($provider['nif']) ?></td>
					<td><?php echo($provider['nome']) ?></td>
					<td>
						<form action="manage_providers.php" method="post" onSubmit="return confirm('Tem a certeza que pretende eliminar este fornecedor?');">
							<input type="hidden" value="<?php echo($provider['nif']) ?>" name="nif"/>
							<input type="hidden" value="<?php echo($provider['nome']) ?>" name="name"/>
							<button class="btn btn-danger" type="submit" name="remove_provider">
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
		error("Ainda não há fornecedores no sistema.");
	}
		
} // end try
catch (PDOException $e) {
	error(SupermarketException::digest($e));
}

// Render footer HTML
render_footer();
?>