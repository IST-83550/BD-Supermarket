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


/**
 *  Renders success alert.
 * 
 *  @param msg
 * 
 */
function success($msg) {
    echo("<br><div class=\"alert alert-success\" role=\"alert\"><span class=\"icon icon-check\" aria-hidden=\"true\"></span>   " . $msg . "</div>");
}

/**
 *  Renders error alert.
 * 
 *  @param msg
 * 
 */
function error($msg) {
    echo("<br><div class=\"alert alert-danger\" role=\"alert\"><span class=\"icon icon-attention\" aria-hidden=\"true\"></span>   " . $msg . "</div>");
}

?>