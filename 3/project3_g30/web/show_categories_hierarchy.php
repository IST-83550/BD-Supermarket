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
 
// Category to be listed.
if (isset($_POST) && isset($_POST['category'])) {
	if ($_POST['category']) {
		// Fetch sub-categories from database
		try {
			$db = new SupermarketService();
			$category = $_POST['category'];	
			$children = $db->listSubCategories($category);
			$db->close();
			$db = null;
		}
		catch (PDOException $e) {
			$error = SupermarketException::digest($e);
		}
	}
	else {
		$error = "Precisa de especificar a categoria a listar.";
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

<!-- Choose category to list form -->
<h1>Listar Hierarquias Super/Sub-Categoria</h1>

<form action="show_categories_hierarchy.php" method="post">
	<div class="row">
		<div class="col-md-3 form-group">
			<select class='category form-control' name="category" onchange="if(this.value) { this.form.submit(); }">
				<option disabled selected value="">Escolher Super Categoria</option>
				<?php
					// Fetch super-categories from database
					try {
						$db = new SupermarketService();
						$categories = $db->getSuperCategories();
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
	</div>
</form>
<p></p>
<?php 

// Show table if appropriate
if ($_POST['category']) {
	if (isset($children) && sizeof($children) > 0) {
	
?>
<!-- Sub-categories tables -->
<div class="row">
	<div class="col-md-4">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Sub-Categorias</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($children as $category) { ?>
				<tr>
					<td><?php echo($category['categoria']) ?></td>
				</tr>
				<?php } // end foreach ?>
			</tbody>
		</table>
	</div>
</div>			

<?php

	} // end children if
	else {
		// If there are no sub-categories (precaution only, should not occur)
		error("Esta categoria não tem sub-categorias.");
	}
} // end $_POST if

// Render footer HTML
render_footer(); ?>

<script>
    <?php
		// Script to select category previously selected
	    if(isset($_POST['category']) && $_POST['category']) {
	?>
	    $('select.category').val("<?php echo $_POST['category'] ?>");
	    
	<?php
	
	    } // end isset
	
	?>
</script>