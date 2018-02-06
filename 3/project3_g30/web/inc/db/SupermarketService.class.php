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

require_once('db_config.php');

/**
 * 
 *  Class to encapsulate all database-related operations.
 * 
 */
class SupermarketService {
	
	private $db;

	/**
	 *  Constructor.
	 * 
	 */
	public function __construct() {
		global $DB_CONFIG;
		
		$dbhost = $DB_CONFIG['host'];
		$dbname = $DB_CONFIG['name'];
		$dbuser = $DB_CONFIG['user'];
		$dbpass = $DB_CONFIG['password'];
		
		$this->db = new PDO("pgsql:host=$dbhost;dbname=$dbname;", $dbuser, $dbpass);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	/**
	 *  Closes PDO connection.
	 *  Assigns PDO instance pointer a null reference.
	 * 
	 */
	public function close() {
		$this->db = null;
	}

	/**
	 *  Escapes HTML inputs.
	 * 
	 *  @param inputs
	 * 
	 *  @return escaped input
	 * 
	 */
	private function escapeHTML($inputs) {
		foreach ($inputs as $input_key => $input_value) {
			$inputs[$input_key] = htmlentities($input_value);
		}
		return $inputs;
	}
	
	/**
	 *  Prepares and executes queries.
	 * 
	 *  @param query
	 *  @param params
	 *  @param mode -- True -> Fetch results, False -> Just execute
	 * 
	 *  @return query result (array)
	 * 
	 */
	private function prepareAndExecuteQuery($query, $params, $mode = false) {
		$params = $this->escapeHTML($params);
		$statement = $this->db->prepare($query);
		$statement->execute($params);
		if ($mode) {
			return $statement->fetchAll(\PDO::FETCH_ASSOC);
		}
	}
	
	/**
	 *  Executes transaction on given queries.
	 * 
	 *  @param queries - Array(query => params / array of params)
	 * 
	 */
	private function makeTransaction($queries) {
		
		try {
			
			$this->db->beginTransaction();
			
			foreach($queries as $query => $params) {
				
				if (is_array($params[0])) {
					foreach($params as $param) {
						$this->prepareAndExecuteQuery($query, $param);
					}
				}
				else {
					$this->prepareAndExecuteQuery($query, $params);
				}
				
			}
			
			$this->db->commit();
			
		} catch (PDOException $e) {
			$this->db->rollBack();
			throw $e;
		}
		
	}
	
	/**
	 *  Check Super-category.
	 * 
	 *  @return query result (array)
	 * 
	 */
	private function isSuperCategory($category) {
		$query = "SELECT nome FROM super_categoria WHERE nome = :categoria";
		return $this->prepareAndExecuteQuery($query, Array(":categoria" => $category), true);
	}
	
	/**
	 *  Get all products.
	 * 
	 *  @return query result (array)
	 * 
	 */
	public function getProducts() {
		$query = $this->db->prepare("SELECT * FROM produto ORDER BY ean");
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	/**
	 *  Get all categories.
	 * 
	 *  @return query result (array)
	 * 
	 */
	public function getCategories() {
		$query = $this->db->prepare("SELECT * FROM categoria ORDER BY nome");
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	/**
	 *  Get all super categories.
	 * 
	 *  @return query result (array)
	 * 
	 */
	public function getSuperCategories() {
		$query = $this->db->prepare("SELECT * FROM super_Categoria ORDER BY nome");
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	/**
	 *  Get all providers/suppliers.
	 * 
	 *  @return query result (array)
	 * 
	 */
	public function getProviders() {
		$query = $this->db->prepare("SELECT * FROM fornecedor ORDER BY nif");
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	/**
	 *  Get all categories relations.
	 * 
	 *  @return query result (array)
	 * 
	 */
	public function getCategoryRelations() {
		$query = $this->db->prepare("SELECT * FROM constituida ORDER BY super_categoria");
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	/**
	 *  Get product primary providers/suppliers.
	 * 
	 *  @param ean - product EAN
	 * 
	 *  @return query result (array)
	 * 
	 */
	public function getProductPrimaryProviders($ean) {
		$query = "SELECT nif, nome FROM (
					SELECT nif
					FROM fornecedor
					EXCEPT 
					SELECT nif 
					FROM fornece_sec WHERE ean=:ean
				  ) AS f 
				  JOIN fornecedor USING (nif) ORDER BY nif";
		return $this->prepareAndExecuteQuery($query, Array(":ean" => $ean), true);
	}
	
	/**
	 *  Get product secondary providers.
	 * 
	 *  @param ean - product EAN
	 * 
	 *  @return query result (array)
	 * 
	 */
	public function getProductSecondaryProviders($ean) {
		$query = "SELECT nif, nome FROM (
					SELECT nif
					FROM fornecedor
					EXCEPT 
					SELECT forn_primario AS nif 
					FROM produto WHERE ean=:ean
				  ) AS f 
				  JOIN fornecedor USING (nif) ORDER BY nif";
		return $this->prepareAndExecuteQuery($query, Array(":ean" => $ean), true);
	}
	
	/**
	 *  Get the primary provider of a given product.
	 * 
	 *  @param ean - product EAN
	 * 
	 *  @return query result (array)
	 * 
	 */
	public function getProductPrimProvider($ean) {
		$query = "SELECT nif, nome FROM 
				  fornecedor JOIN (
				      SELECT forn_primario AS nif
				      FROM produto
				      WHERE ean = :ean
				  ) AS f
				  USING (nif)";
		return $this->prepareAndExecuteQuery($query, Array(":ean" => $ean), true);
	}
	
	/**
	 *  Get the secondarys providers of a given product.
	 * 
	 *  @param ean
	 * 
	 *  @return query result (array)
	 * 
	 */
	public function getProductSecProviders($ean) {
		$query = "SELECT * FROM fornece_sec JOIN fornecedor USING (nif) WHERE ean = :ean";
		return $this->prepareAndExecuteQuery($query, Array(":ean" => $ean), true);
	}
	
	/**
	 *  Insert product.
	 * 
	 *  @param ean - product EAN
	 *  @param category - product category
	 *  @param design - product designation
	 *  @param prim_provider - primary product supplier/provider
	 *	@param sec_providers - secundary product suppliers/providers (array)
	 *  @param date - supply start date
	 * 
	 */
	public function insertProduct($ean, $category, $design, $prim_provider, $sec_providers, $date) {
		$queries = Array(
				
					"INSERT INTO produto (ean, categoria, design, forn_primario, data) VALUES (:ean, :categoria, :design, :forn_primario, :data)"
					=> 
					Array(":ean" => $ean, ":categoria" => $category, ":design" => $design, ":forn_primario" => $prim_provider, ":data" => $date)
				
			);
			
		$sec_providers_params = Array();
		foreach ($sec_providers as $sec_provider) {
			$sec_providers_params[] = Array(":nif" => $sec_provider, ":ean" => $ean);
		}
		
		$queries["INSERT INTO fornece_sec (nif, ean) VALUES (:nif, :ean)"] = $sec_providers_params;
		
		$this->makeTransaction($queries);
	}
	
	/**
	 *  Insert category.
	 * 
	 *  @param category
	 * 
	 */
	public function insertCategory($category) {
		
		$queries = Array(
			
				"INSERT INTO categoria (nome) VALUES (:categoria)"
				=>
				Array(":categoria" => $category),
				"INSERT INTO categoria_simples (nome) VALUES (:categoria)"
				=>
				Array(":categoria" => $category)
				
			);
			
		$this->makeTransaction($queries);
		
	}
	
	/**
	 *  Insert provider/supplier.
	 * 
	 *  @param nif - provider/supplier NIF
	 *  @param name - provider/supplier name
	 * 
	 */
	public function insertProvider($nif, $name) {
		$query = "INSERT INTO fornecedor (nif, nome) VALUES (:nif, :nome)";
		$this->prepareAndExecuteQuery($query, Array(":nif" => $nif, ":nome" => $name));
	}
	
	/**
	 *  Insert secondary provider/supplier for a given product.
	 * 
	 *  @param ean - product EAN
	 *  @param nif - provider/supplier NIF
	 * 
	 */
	public function insertProductSecProvider($ean, $nif) {
		$query = "INSERT INTO fornece_sec (nif, ean) VALUES (:nif, :ean)";
		$this->prepareAndExecuteQuery($query, Array(":nif" => $nif, ":ean" => $ean));
	}
	
	/**
	 *  Insert sub category in given super category.
	 * 
	 *  @param category
	 *  @param sub_category
	 * 
	 */
	public function insertCategoryRelation($category, $sub_category) {
		if (!$this->isSuperCategory($category)) {
			
			$queries = Array(
				
					"DELETE FROM categoria_simples WHERE nome = :categoria"
					=>
					Array(":categoria" => $category),
					
					"INSERT INTO super_categoria (nome) VALUES (:categoria)"
					=>
					Array(":categoria" => $category),
					
					"INSERT INTO constituida (super_categoria, categoria) VALUES (:super_categoria, :categoria)"
					=>
					Array(":super_categoria" => $category, ":categoria" => $sub_category)
				
				);
			
			$this->makeTransaction($queries);
		}
		else {
			
			$query = "INSERT INTO constituida (super_categoria, categoria) VALUES (:super_categoria, :categoria)";
			$this->prepareAndExecuteQuery($query, Array(":super_categoria" => $category, ":categoria" => $sub_category));
			
		}
	}  
	
	/**
	 *  Remove category.
	 * 
	 *  @param category
	 * 
	 */
	public function removeCategory($category) {
		$query = "DELETE FROM categoria WHERE nome = :categoria";
		$this->prepareAndExecuteQuery($query, Array(":categoria" => $category));
	}
	
	/**
	 *  Remove provider/supplier.
	 * 
	 *  @param nif provider/supplier NIF
	 * 
	 */
	public function removeProvider($nif) {
		$query = "DELETE FROM fornecedor WHERE nif = :nif";
		$this->prepareAndExecuteQuery($query, Array(":nif" => $nif));
	}
	
	/**
	 *  Remove secondary provider/supplier for a given product.
	 * 
	 *  @param ean - product EAN
	 *  @param nif - provider/supplier NIF
	 * 
	 */
	public function removeProductSecProvider($ean, $nif) {
		$query = "DELETE FROM fornece_sec WHERE nif = :nif AND ean = :ean";
		$this->prepareAndExecuteQuery($query, Array(":nif" => $nif, ":ean" => $ean));
	}
	
	/**
	 *  Remove category relation.
	 * 
	 *  @param category
	 *  @param sub_category
	 * 
	 */
	public function removeCategoryRelation($category, $sub_category) {
		$query = "DELETE FROM constituida WHERE super_categoria = :super_categoria AND categoria = :categoria";
		$this->prepareAndExecuteQuery($query, Array(":super_categoria" => $category, ":categoria" => $sub_category));
	}
	
	/**
	 *  Remove product.
	 * 
	 *  @param ean - product EAN
	 * 
	 */
	public function removeProduct($ean) {
		$query = "DELETE FROM produto WHERE ean = :ean";
		$this->prepareAndExecuteQuery($query, Array(":ean" => $ean));
	}
	
	/**
	 *  Update product designation.
	 * 
	 *  @param ean - product EAN
	 *  @param design - new product designation
	 * 
	 */
	public function updateProductDesignation($ean, $design) {
		$query = "UPDATE produto SET design = :design WHERE ean = :ean";
		$this->prepareAndExecuteQuery($query, Array(":design" => $design, ":ean" => $ean));
	}
	
	/**
	 *  Update product primary provider.
	 * 
	 *  @param ean - product EAN
	 *  @param nif - provider nif
	 * 
	 */
	public function updateProductPrimProvider($ean, $nif) {
		$query = "UPDATE produto SET forn_primario = :forn_primario WHERE ean = :ean";
		$this->prepareAndExecuteQuery($query, Array(":forn_primario" => $nif, ":ean" => $ean));
	}
	
	/**
	 *  List reposition events given EAN.
	 * 
	 *  @param ean - product EAN
	 * 
	 *  @return query result (array)
	 * 
	 */
	public function listRepositionEvents($ean) {
		$query = "SELECT * FROM reposicao WHERE ean = :ean ORDER BY instante";
		return $this->prepareAndExecuteQuery($query, Array(":ean" => $ean), true);
	}
	
	/**
	 *  List all sub categories given super category.
	 * 
	 *  @param ean - product EAN
	 * 
	 *  @return query result (array)
	 * 
	 */
	public function listSubCategories($category) {
		$query = '	WITH RECURSIVE cte (super_categoria, categoria) AS
					(
						SELECT     super_categoria, categoria
						FROM       constituida
						WHERE      super_categoria = :category
						UNION ALL
						SELECT     c.super_categoria, c.categoria
						FROM       constituida AS c
						INNER JOIN cte
						ON c.super_categoria = cte.categoria
					)
					SELECT categoria FROM cte ORDER BY categoria;';
		return $this->prepareAndExecuteQuery($query, Array(":category" => $category), true);
	}

}

?>