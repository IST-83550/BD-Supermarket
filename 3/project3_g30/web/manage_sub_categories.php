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
	
// Insert new relation.
if(isset($_POST['submit'])) { 

	try {
		if (isset($_POST['category']) && isset($_POST['sub_category']) && $_POST['category'] && $_POST['sub_category']) {
			$db = new SupermarketService();
			$category = $_POST['category'];
			$sub_category = $_POST['sub_category'];
			$db->insertCategoryRelation($category, $sub_category);
			$db->close();
			$db = null;
			
			$success = "Sub categoria " . $sub_category . " da categoria " . $category . " inserida com sucesso.";
					 
		}
		else {
			$error = "Precisa de selecionar duas categorias para estabelecer a sua relação.";
		}
			
	}
	catch (PDOException $e) {
		$error = SupermarketException::digest($e);
	}
}    

// Remove relation.
if(isset($_POST['remove_category_relation'])) {   
	try {
		if (isset($_POST['category']) && isset($_POST['sub_category']) && $_POST['category'] && $_POST['sub_category']) {
			$db = new SupermarketService();
			$category = $_POST['category'];
			$sub_category = $_POST['sub_category'];
			$db->removeCategoryRelation($category, $sub_category);
			$db->close();
			$db = null;
			
			$success = "Sub categoria " . $sub_category . " da categoria " . $category . " removida com sucesso.";
		}
		else {
			$error = "Precisa de especificar ambas as categorias da relação a remover.";
		}
			
	} catch (PDOException $e) {
		$error = SupermarketException::digest($e);
	}
}

/*
 * HTML page
 */

// Render header HTML
render_header("category", "Gerir Relações entre Categorias");

// Display success/error alerts
if (isset($success)) {
	success($success);
}
	
else if (isset($error)) {
	error($error);
}
	
?>

<!-- New relation form -->
<h1>Inserir Relação de Categorias</h1>

<form class="form-inline" action="manage_sub_categories.php" method="post">
	<input type="hidden" name="category_type" value="super_categoria"/>
	<div class="form-group">
		<select class="form-control" name="category" required>
			<option selected disabled value="">Escolher Categoria</option>
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
		<select class="form-control" name="sub_category" required>
			<option selected disabled value="">Escolher Sub-Categoria</option>
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
					
				} catch (PDOException $e) {
					error(SupermarketException::digest($e));
				}
			?>
		</select>
	</div>
	<button type="submit" class="btn btn-primary" name="submit">
		<span class="icon icon-plus-circled" aria-hidden="true"></span>  Adicionar
	</button>
</form>

<!-- Existing relations table -->
<h2>Relações de Categorias Existentes</h2>

<?php
// Fetch categories relations from database
try {
	$db = new SupermarketService();
	$relations = $db->getCategoryRelations();
	$db->close();
	$db = null;
	
	// Render table if there are relations
	if (sizeof($relations) > 0) {
?>
	
<div class="row">
	<div class="col-md-6">	
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Super Categoria</th>
					<th>Sub Categoria</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($relations as $relation) { ?>
				<tr>
					<td><?php echo($relation['super_categoria']) ?></td>
					<td><?php echo($relation['categoria']) ?></td>
					<td>
						<form action="manage_sub_categories.php" method="post" onSubmit="return confirm('Tem a certeza que pretende eliminar a relação?');">
							<input type="hidden" value="<?php echo($relation['super_categoria']) ?>" name="category"/>
							<input type="hidden" value="<?php echo($relation['categoria']) ?>" name="sub_category"/>
							<button class="btn btn-danger" type="submit" name="remove_category_relation">
								<span class="icon icon-trash" aria-hidden="true"></span>  Remover Relação
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
		error("Ainda não existem relações de categorias no sistema.");
	}

} // end try
catch (PDOException $e) {
	error(SupermarketException::digest($e));
}

// Render footer HTML
render_footer();
?>