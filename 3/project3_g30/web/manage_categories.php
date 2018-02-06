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
	
// Insert new category.
if (isset($_POST['submit'])) {
			
	try {
		if (isset($_POST['category']) && $_POST['category']) {
			$db = new SupermarketService();
			$category = $_POST['category'];
			$db->insertCategory($category);
			$db->close();
			$db = null;

			$success = "Categoria " . $category . " inserida com sucesso.";
		}
		else {
			$error = "Precisa de inserir um nome para a nova categoria.";
		}
			
	} 
	catch (PDOException $e) {
		$error = SupermarketException::digest($e);
	}
}
	
// Remove category.
if(isset($_POST['remove_category'])) {
			
	try {
		if (isset($_POST['category']) && $_POST['category']) {
			$db = new SupermarketService();
			$category = $_POST['category'];
			$db->removeCategory($category);
			$db->close();
			$db = null;
			
			$success = "Categoria " . $category . " removida com sucesso.";
		}
		else {
			$error = "Precisa de especificar a categoria a remover.";
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
render_header("category", "Gerir Categorias");

// Display success/error alerts
if (isset($success)) {
	success($success);
}
	
else if (isset($error)) {
	error($error);
}
?>

<!-- New category form -->
<h1>Inserir Nova Categoria</h1>
<form class="form-inline" action="manage_categories.php" method="post">
	<div class="form-group">
		<input name="category" type="text" class="form-control" placeholder="Nome da categoria" required>
	</div>
	<button type="submit" name="submit" class="btn btn-primary">
		<span class="icon icon-plus-circled" aria-hidden="true"></span>  Adicionar
	</button>
</form>

<!-- Existing categories table -->
<h2>Categorias Existentes</h2>

<?php
// Fetch categories from database
try {
	$db = new SupermarketService();
	$categories = $db->getCategories();
	$db->close();
	$db = null;
	
	// Render table if there are categories
	if (sizeof($categories) > 0) {
?>
<div class="row">
	<div class="col-md-4">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Categoria</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($categories as $category) { ?>
				<tr>
					<td><?php echo($category['nome']) ?></td>
					<td>
						<form action="manage_categories.php" method="post" onSubmit="return confirm('Tem a certeza que pretende eliminar esta categoria?');">
							<input type="hidden" value="<?php echo($category['nome']) ?>" name="category"/>
							<button class="btn btn-danger" type="submit" name="remove_category">
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
		error("Ainda não existem categorias no sistema.");
	}
} // end try
catch (PDOException $e) {
	error(SupermarketException::digest($e));
}

// Render footer HTML
render_footer();
?>