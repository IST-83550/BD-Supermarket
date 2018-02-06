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
 * 
 *  Class to digest exceptions thrown by the database.
 * 
 */
class SupermarketException {
    
    const DEBUG = false;
    
    /*
     * SQL error codes
     */
    const FKEY_VIOLATION_CODE = "23503";
    const UNIQUE_VIOLATION_CODE = "23505";
    const CHECK_VIOLATION_CODE = "23514";
    
    
    /**
	 *  Retrieves error code from error message.
	 * 
	 *  @param error - PDOException error message
	 * 
	 *  @return SQL error code
	 * 
	 */
    private static function getErrorCode($error) {
        preg_match("/SQLSTATE\[(.*?)\]/", $error, $matches);
        return $matches[1];
    }
    
    /**
	 *  Digests message from a Foreign Key violation
	 * 
	 *  @param error - PDOException error message
	 * 
	 *  @return user-friendly error message
	 * 
	 */
    private static function digestForeignKeyViolation($error) {
        preg_match_all('/"(.*?)"/', $error, $matches);
        switch ($matches[1][1]) {
            case ("produto_categoria_fkey"):
                return "Não é possível remover uma categoria que tenha produtos.";
                break;
            case ("planograma_ean_fkey"):
                return "Não é possível remover um produto que esteja presente no planograma.";
                break;
            case ("produto_forn_primario_fkey"):
                return "Não é possível remover um fornecedor que seja fornecedor primário de um produto.";
                break;
            case ("fornece_sec_nif_fkey"):
                return "Não é possível remover um fornecedor que seja fornecedor secundário de um produto.";
                break;
            default:
                return self::DEBUG ? "Erro desconhecido: " . $error : "Erro interno.";
                break;
        }
    }
    
    /**
	 *  Digests message from a Unique violation
	 * 
	 *  @param error - PDOException error message
	 * 
	 *  @return user-friendly error message
	 * 
	 */
    private static function digestUniqueViolation($error) {
        preg_match('/"(.*?)"/', $error, $matches);
        switch ($matches[1]) {
            case ("categoria_pkey"):
                return "Já existe uma categoria com o nome inserido.";
                break;
            case ("constituida_pkey"):
                return "Já existe um relação de hierarquia entre as categorias selecionadas";
                break;
            case ("produto_pkey"):
                return "Já existe um produto com o EAN inserido.";
                break;
            case ("fornecedor_pkey"):
                return "Já existe um fornecedor com o NIF inserido.";
                break;
            default:
                return self::DEBUG ? "Erro desconhecido: " . $error : "Erro interno.";
                break;
        }
    }
    
    /**
	 *  Digests message from a Check violation
	 * 
	 *  @param error - PDOException error message
	 * 
	 *  @return user-friendly error message
	 * 
	 */
    private static function digestCheckViolation($error) {
        preg_match_all('/"(.*?)"/', $error, $matches);
        switch ($matches[1][1]) {
            case ("supercategoria_dif_categoria"):
                return "Não é possível estabelecer uma relação de hierarquia de uma categoria para si própria";
                break;
            case ("is_ean"):
                return "O EAN introduzido é inválido.";
                break;
            case ("is_nif"):
                return "O NIF introduzido é inválido.";
                break;
            case ("not_in_forn_prim"):
                return "Não é possível adicionar um fornecedor secundário que já seja fornecedor primário.";
            default:
                return self::DEBUG ? "Erro desconhecido: " . $error : "Erro interno.";
                break;
        }
    }
    
    /**
	 *  Digests exception thrown by the database
	 * 
	 *  @param error - PDOException
	 * 
	 *  @return user-friendly error message
	 * 
	 */
    public static function digest($exception) {
        $error = $exception->getMessage();
        switch (self::getErrorCode($error)) {
            
            case (self::FKEY_VIOLATION_CODE):
                return self::digestForeignKeyViolation($error);
                break;
                
            case (self::UNIQUE_VIOLATION_CODE):
                return self::digestUniqueViolation($error);
                break;
                
            case (self::CHECK_VIOLATION_CODE):
                return self::digestCheckViolation($error);
                break;
                
            default:
                return self::DEBUG ? "Erro desconhecido: " . $error : "Erro interno.";
                break;
            
        }
    }
    
}

?>