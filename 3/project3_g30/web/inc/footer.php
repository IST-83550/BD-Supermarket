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
 *  Renders global footer.
 * 
 *  @param js_specific - specific js to be included (if set)
 * 
 */
function render_footer($js_specific = false) {  
    global $JS_INCLUDES; 
?>
			<br><br>
			<footer class="page-footer text-center">
				<p>&copy; Grupo 30 BD2251795L09 2017/2018 - Instituto Superior Técnico</p>
			</footer>
			<br>

		</div>
		<?php
			foreach($JS_INCLUDES as $file) {
		        echo "<script src=\"" . $file . "\" type=\"text/javascript\"></script>\n";
		    }
		    
	        if ($js_specific) {
    			foreach($js_specific as $file) {
    		        echo "<script src=\"" . $file . "\" type=\"text/javascript\"></script>\n";
    		    }
	        }
	    ?>

<?php
    }
?>