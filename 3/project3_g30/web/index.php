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
 
require_once('inc/header.php');
require_once('inc/footer.php');

// Render header HTML
render_header("index"); ?>

<main role="main">
	<br>
	
	<div class="jumbotron">
		<div class="row">
			<div class="col-md-6">
				<h1>Bem vindo!</h1>
				<p class="lead">Bem vindo ao sistema de gestão do seu supermercado. As acções possíveis encontram-se listadas abaixo.</p>
			</div>
			<div class="col-md-6" style="text-align: center;">
				<img src="assets/img/store.svg" style="max-height: 200px;">
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-3">
			<h3 class="sections">Categorias</h3>
			<p><a class="btn btn-primary btn-block" href="manage_categories.php" role="button">Gerir Categorias</a></p>
			<p><a class="btn btn-primary btn-block" href="manage_sub_categories.php" role="button">Gerir Hierarquias de Categorias</a></p>
			<p><a class="btn btn-primary btn-block" href="show_categories_hierarchy.php" role="button">Listar Hierarquias de Categorias</a></p>
		</div>
		<div class="col-lg-3">
			<h3 class="sections">Produtos</h3>
			<p><a class="btn btn-primary btn-block" href="manage_products.php" role="button">Gerir Produtos</a></p>
			<p><a class="btn btn-primary btn-block" href="update_product.php" role="button">Alterar Designações de Produtos</a></p>
			<p><a class="btn btn-primary btn-block" href="manage_product_providers.php" role="button">Gerir Fornecedores de Produtos</a></p>
		</div>
		<div class="col-lg-3">
			<h3 class="sections">Fornecedores</h3>
			<p><a class="btn btn-primary btn-block" href="manage_providers.php" role="button">Gerir Fornecedores</a></p>
		</div>
		<div class="col-lg-3">
			<h3 class="sections">Reposições</h3>
			<p><a class="btn btn-primary btn-block" href="reposition_event.php" role="button">Listar Reposições de Produtos</a></p>
		</div>
	</div>

</main>

<?php 
// Render footer HTML
render_footer(); 
?>