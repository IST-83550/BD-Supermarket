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

----------------------------------------------
-- Rebuild schema, dropping existing tables --
----------------------------------------------

DROP TABLE IF EXISTS reposicao;
DROP TABLE IF EXISTS evento_reposicao;
DROP TABLE IF EXISTS planograma;
DROP TABLE IF EXISTS prateleira;
DROP TABLE IF EXISTS corredor;
DROP TABLE IF EXISTS fornece_sec;
DROP TABLE IF EXISTS produto;
DROP TABLE IF EXISTS fornecedor;
DROP TABLE IF EXISTS constituida;
DROP TABLE IF EXISTS super_categoria;
DROP TABLE IF EXISTS categoria_simples;
DROP TABLE IF EXISTS categoria;

----------------------------
-- Create database schema --
----------------------------

-- Table structure for 'categoria'.

CREATE TABLE categoria (
	nome char(70) NOT NULL,
	PRIMARY KEY (nome)
);

-- Table structure for 'categoria_simples'.

CREATE TABLE categoria_simples (
	nome char(70) NOT NULL,
	PRIMARY KEY (nome),
	FOREIGN KEY (nome) REFERENCES categoria(nome) ON DELETE CASCADE
);

-- Table structure for 'super_categoria'.

CREATE TABLE super_categoria (
	nome char(70) NOT NULL,
	PRIMARY KEY (nome),
	FOREIGN KEY (nome) REFERENCES categoria(nome) ON DELETE CASCADE
);

-- Table structure for 'constituida'.

CREATE TABLE constituida (
	super_categoria char(70) NOT NULL,
	categoria       char(70) NOT NULL,
	PRIMARY KEY (super_categoria, categoria),
	FOREIGN KEY (super_categoria) REFERENCES super_categoria(nome) ON DELETE CASCADE,
	FOREIGN KEY (categoria) REFERENCES categoria(nome) ON DELETE CASCADE,
	CONSTRAINT supercategoria_dif_categoria CHECK (super_categoria != categoria)
);

-- Function to check if it is a nif
CREATE OR REPLACE FUNCTION check_nif(_nif char(9))
RETURNS BOOLEAN
AS $$
DECLARE result BOOLEAN;
BEGIN
	RETURN _nif ~ '^[1-9][0-9]{8}';
END;
$$ LANGUAGE plpgsql;

-- Table structure for 'fornecedor'.

CREATE TABLE fornecedor (
	nif  char(9)  NOT NULL,
	nome char(70) NOT NULL,
	PRIMARY KEY (nif),
	CONSTRAINT is_nif CHECK (check_nif(nif))
);

-- Function to check if it is an ean
CREATE OR REPLACE FUNCTION check_ean(_ean char(13))
RETURNS BOOLEAN
AS $$
DECLARE result BOOLEAN;
BEGIN
	RETURN _ean ~ '^[0-9]{13}';
END;
$$ LANGUAGE plpgsql;

-- Table structure for 'produto'.

CREATE TABLE produto (
	ean           char(13)  NOT NULL,
	design        char(400) NOT NULL,
	categoria     char(70)  NOT NULL,
	forn_primario char(70)  NOT NULL,
	data          date      NOT NULL,
	PRIMARY KEY (ean),
	FOREIGN KEY (categoria) REFERENCES categoria(nome),
	FOREIGN KEY (forn_primario) REFERENCES fornecedor(nif),
	CONSTRAINT is_ean CHECK (check_ean(ean))
);

-- Function to check if forn_sec isnt forn_prim aswell
CREATE OR REPLACE FUNCTION forn_prim_sec(_ean char(13), _nif char(9))
RETURNS BOOLEAN
AS $$
DECLARE result BOOLEAN;
BEGIN
  SELECT EXISTS (
	SELECT 1
	FROM produto
	WHERE produto.ean = _ean AND produto.forn_primario = _nif
	) INTO result;
	RETURN result;
END;
$$ LANGUAGE plpgsql;

-- Table structure for 'fornece_sec'.

CREATE TABLE fornece_sec (
	nif char(9) NOT NULL,
	ean char(13) NOT NULL,
	PRIMARY KEY (nif, ean),
	FOREIGN KEY (nif) REFERENCES fornecedor(nif),
	FOREIGN KEY (ean) REFERENCES produto(ean) ON DELETE CASCADE,
	CONSTRAINT not_in_forn_prim CHECK (NOT forn_prim_sec(ean, nif)),
	CONSTRAINT is_nif CHECK (check_nif(nif)),
	CONSTRAINT is_ean CHECK (check_ean(ean))
);

-- Table structure for 'corredor'.

CREATE TABLE corredor (
	nro     integer NOT NULL,
	largura real    NOT NULL,
	PRIMARY KEY (nro)
);

-- Table structure for 'prateleira'.

CREATE TABLE prateleira (
	nro     integer  NOT NULL,
	lado    char(20) NOT NULL,
	altura  char(20) NOT NULL,
	PRIMARY KEY (nro, lado, altura),
	FOREIGN KEY (nro) REFERENCES corredor(nro)
);

-- Table structure for 'planograma'.

CREATE TABLE planograma (
	ean      char(13) NOT NULL,
	nro      integer  NOT NULL,
	lado     char(20) NOT NULL,
	altura   char(20) NOT NULL,
	face     integer  NOT NULL,
	unidades integer  NOT NULL,
	loc      integer  NOT NULL,
	PRIMARY KEY (ean, nro, lado, altura),
	FOREIGN KEY (ean) REFERENCES produto(ean),
	FOREIGN KEY (nro, lado, altura) REFERENCES prateleira(nro, lado, altura),
	CONSTRAINT is_ean CHECK (check_ean(ean))
);

-- Table structure for 'evento_reposicao'.

CREATE TABLE evento_reposicao (
	operador char(70)   NOT NULL,
	instante timestamp  NOT NULL CHECK (instante <= localtimestamp),
	PRIMARY KEY (operador, instante)
);

-- Table structure for 'reposicao'.

CREATE TABLE reposicao (
	ean      char(13)   NOT NULL,
	nro      integer    NOT NULL,
	lado     char(20)   NOT NULL,
	altura   char(20)   NOT NULL,
	operador char(70)   NOT NULL,
	instante timestamp  NOT NULL,
	unidades integer    NOT NULL,
	PRIMARY KEY (ean, nro, lado, altura, operador, instante),
	FOREIGN KEY (ean, nro, lado, altura) REFERENCES planograma(ean, nro, lado, altura),
	FOREIGN KEY (operador, instante)     REFERENCES evento_reposicao(operador, instante),
	CONSTRAINT is_ean CHECK (check_ean(ean))
);