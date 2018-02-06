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
 
-------------
-- Queries --
-------------

-- a)  Qual o nome do fornecedor que forneceu o maior número de categorias?
--	   Note que pode ser mais do que um fornecedor.

WITH counts AS (
	SELECT COUNT(categoria), nif FROM (
		SELECT nif, ean, categoria FROM (
			SELECT forn_primario AS nif, ean FROM produto
			UNION 
			SELECT nif, ean FROM fornece_sec
		) AS f
		JOIN produto USING (ean)
	) AS c
	GROUP BY nif
)

SELECT nome FROM fornecedor
JOIN counts USING (nif)
JOIN (
	SELECT MAX(count) AS count FROM counts
) AS max USING (count);


-- b) Quais os fornecedores primarios (nome e nif) que forneceram
--    produtos de todas as categorias simples.

SELECT DISTINCT nome, nif FROM fornecedor JOIN (
	SELECT forn_primario AS nif 
	FROM produto AS prod
	WHERE NOT EXISTS (
		SELECT nome AS categoria FROM categoria_simples
		EXCEPT 
		SELECT categoria FROM (
			produto JOIN categoria_simples
			ON produto.categoria = categoria_simples.nome
		) AS p
		WHERE forn_primario = prod.forn_primario
	)
) AS f USING (nif);


-- c) Quais os produtos (ean) que nunca foram repostos.

SELECT ean FROM (
	SELECT ean FROM produto
	EXCEPT
	SELECT DISTINCT ean FROM reposicao
) AS eans;


-- d) Quais os produtos (ean) com um numero de fornecedores
--    secundarios superior a 10.

SELECT ean FROM (
	SELECT COUNT(ean), ean FROM fornece_sec
	GROUP BY ean
) AS counts
WHERE count > 10;


-- e) Quais os produtos (ean) que foram repostos sempre
--    pelo mesmo operador.

SELECT ean FROM (
	SELECT COUNT(ean), ean FROM (
		SELECT DISTINCT ean, operador FROM reposicao
	) AS reposicoes GROUP BY ean
) AS counts
WHERE count = 1;
