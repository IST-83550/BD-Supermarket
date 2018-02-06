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
 
require_once('includes.php');
    
/**
 *  Renders global header.
 * 
 *  @param menu - menu to highlight
 *  @param title - title to be appended to the page title (if set)
 *  @param css_specific - specific css to be included (if set)
 * 
 */
function render_header($menu, $title = false, $css_specific = false) {  
    global $CSS_INCLUDES;
?>
<!DOCTYPE html>
<html>
		
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" href="assets/img/favicon.ico"/>
		<title>Supermercado <?php if ($title) echo(" - " . $title) ?></title>
		<?php
            foreach($CSS_INCLUDES as $file) {
                echo "<link href=\"" . $file . "\" rel=\"stylesheet\"/>\n";
            }
            
            if ($css_specific) {
               foreach($css_specific as $file) {
                    echo "<link href=\"" . $file . "\" rel=\"stylesheet\"/>\n";
                } 
            }
        ?>
	</head>
	
	<body>
		<div class="container">
		    <header class="masthead">
				<div class="text-center">
					<br>
					<h1 class="title">Supermercado</h3>
					<br>
				</div>
				<!-- Navbar -->
				<ul class="nav nav-pills nav-tabs">
				 	<li role="presentation" <?php if ($menu == 'index') echo("class=\"active\"") ?>>
				 		<a href="index.php">Início</a>
				 	</li>
				  	<li role="presentation" class="dropdown <?php if ($menu == 'category') echo("active") ?>">
				    	<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
				    		Categorias <span class="caret"></span>
				    	</a>
				    	<ul class="dropdown-menu">
				      		<li>
				      			<a href="manage_categories.php">Gerir Categorias</a>
				      		</li>
				      		<li>
				      			<a href="manage_sub_categories.php">Gerir Sub-Categorias</a>
				      		</li>
				      		<li>
				      			<a href="show_categories_hierarchy.php">Listar Hierarquias</a>
				      		</li>
				    	</ul>
				  	</li>
					<li role="presentation" class="dropdown <?php if ($menu == 'product') echo("active") ?>">
				    	<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
				    		Produtos <span class="caret"></span>
				    	</a>
				    	<ul class="dropdown-menu">
				      		<li>
				      			<a href="manage_products.php">Gerir Produtos</a>
				      		</li>
				      		<li>
				      			<a href="update_product.php">Alterar Designação</a>
				      		</li>
				      		<li>
				      			<a href="manage_product_providers.php">Gerir Fornecedores de Produtos</a>
				      		</li>
				    	</ul>
				  	</li>
				  	<li role="presentation" class="dropdown <?php if ($menu == 'provider') echo("active") ?>">
				    	<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
				    		Fornecedores <span class="caret"></span>
				    	</a>
				    	<ul class="dropdown-menu">
				      		<li>
				      			<a href="manage_providers.php">Gerir Fornecedores</a>
				      		</li>
				    	</ul>
				  	</li>
				  	<li role="presentation" class="dropdown <?php if ($menu == 'reposition') echo("active") ?>">
				    	<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
				    		Eventos de Reposição <span class="caret"></span>
				    	</a>
				    	<ul class="dropdown-menu">
				      		<li>
				      			<a href="reposition_event.php">Listar Eventos de Reposição</a>
				      		</li>
				    	</ul>
				  	</li>
				</ul>
			</header>
<?php 
    } 
?>