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

-----------------------
-- Populate database --
-----------------------

-- Populate table 'categoria'
INSERT INTO categoria (nome) VALUES
    ('Peixe'),
    ('Carne'),
    ('Sumos'),
    ('Comida'),
    ('Panados'),
    ('Panedos'),
    ('Panidos'),
    ('Panodos'),
    ('Panudos'),
    ('Congelados'),
    ('Fruta'),
    ('Fruta de Verao'),
    ('Fruta de Inverno'),
    ('Fruta de Outono'),
    ('Fruta de Primavera'),
    ('Vegetais'),
    ('Vegetais de Sopa'),
    ('Vegetais de Salada')
;

-- Populate table 'categoria_simples'
INSERT INTO categoria_simples (nome) VALUES
    ('Peixe'),
    ('Sumos'),
    ('Panudos'),
    ('Congelados'),
    ('Fruta de Verao'),
    ('Fruta de Inverno'),
    ('Fruta de Outono'),
    ('Fruta de Primavera'),
    ('Vegetais de Sopa'),
    ('Vegetais de Salada')
;

-- Populate table 'super_categoria'
INSERT INTO super_categoria (nome) VALUES
    ('Comida'),
    ('Carne'),
    ('Panados'),
    ('Panedos'),
    ('Panidos'),
    ('Panodos'),
    ('Fruta'),
    ('Vegetais')
;

-- Populate table 'constituida'
INSERT INTO constituida (super_categoria, categoria) VALUES
    ('Comida', 'Peixe'),
    ('Comida', 'Carne'),
    ('Comida', 'Fruta'),
    ('Comida', 'Vegetais'),
    ('Comida', 'Congelados'),
    ('Carne', 'Panados'),
    ('Panados', 'Panedos'),
    ('Panedos', 'Panidos'),
    ('Panidos', 'Panodos'),
    ('Panodos', 'Panudos'),
    ('Fruta', 'Fruta de Verao'),
    ('Fruta', 'Fruta de Inverno'),
    ('Fruta', 'Fruta de Primavera'),
    ('Fruta', 'Fruta de Outono'),
    ('Vegetais', 'Vegetais de Sopa'),
    ('Vegetais', 'Vegetais de Salada')
;

-- Populate table 'fornecedor'
INSERT INTO fornecedor (nif, nome) VALUES
    ('572513766', 'Sonae'),
    ('189046381', 'Alfredo Costa'),
    ('222399408', 'Maria Jesus'),
    ('571781108', 'Sumos e Companhia'),
    ('527350911', 'Suminhos e Companhia'),
    ('529362436', 'Frutaria de Lisboa'),
    ('573218425', 'Frutaria Joana'),
    ('568048110', 'MARL'),
    ('515557568', 'Fruta Fresca'),
    ('529153942', 'Talho e Companhia'),
    ('575850866', 'Talho do Bruno'),
    ('586935003', 'Talho do Carlos')
;

-- Populate table 'produto'
INSERT INTO produto (ean, design, categoria, forn_primario, data) VALUES
    ('3735116265600', 'Carapau', 'Peixe', '572513766', '2017-11-01'),
    ('8692435020394', 'Dourada', 'Peixe', '572513766', '2017-11-01'),
    ('9821245614546', 'Filete de Pescada', 'Peixe', '568048110', '2007-12-15'),
    ('9054295716603', 'Coca-Cola', 'Sumos', '571781108', '2017-10-19'),
    ('2894194365224', 'Fanta', 'Sumos', '571781108', '2017-10-22'),
    ('1677986565035', 'Sumol', 'Sumos', '572513766', '2017-11-19'),
    ('9166381380326', 'Bife de Vaca', 'Carne', '572513766', '2017-10-19'),
    ('6943222691011', 'Lombo de Porco', 'Carne', '529153942', '2017-10-19'),
    ('2876464987780', 'Panudos Fritos', 'Panudos', '572513766', '2017-12-15'),
    ('8864108111028', 'Morango', 'Fruta de Primavera', '515557568', '2017-10-11'),
    ('6098893416477', 'Cereja', 'Fruta de Primavera', '572513766', '2017-10-11'),
    ('5592654287374', 'Coco', 'Fruta de Verao', '515557568', '2017-02-03'),
    ('1468246121310', 'Melancia', 'Fruta de Verao', '572513766', '2017-02-03'),
    ('4390887768164', 'Diospiro', 'Fruta de Outono', '515557568', '2017-10-13'),
    ('3606473094197', 'Tangerina', 'Fruta de Outono', '572513766', '2017-10-13'),
    ('6928834576162', 'Romã', 'Fruta de Inverno', '515557568', '2017-10-13'),
    ('2113049537762', 'Limão', 'Fruta de Inverno', '572513766', '2017-10-13'),
    ('3426689964487', 'Espinafre', 'Vegetais de Sopa', '529362436', '2017-10-19'),
    ('3072968720262', 'Nabiça', 'Vegetais de Sopa', '529362436', '2017-10-19'),
    ('3696452353278', 'Alho Francês', 'Vegetais de Sopa', '572513766', '2017-10-19'),
    ('9819240162507', 'Alface', 'Vegetais de Salada', '573218425', '2017-10-19'),
    ('5695405925155', 'Cenoura', 'Vegetais de Salada', '573218425', '2017-10-19'),
    ('2322867665713', 'Pepino', 'Vegetais de Salada', '572513766', '2017-10-19'),
    ('6018241219817', 'Douradinhos', 'Congelados', '572513766', '2017-10-19')
;

-- Populate table 'fornece_sec'
INSERT INTO fornece_sec (nif, ean) VALUES
    ('572513766', '8864108111028'),
    ('572513766', '5592654287374'),
    ('572513766', '4390887768164'),
    ('572513766', '6928834576162'),
    ('572513766', '3426689964487'),
    ('572513766', '3072968720262'),
    ('572513766', '9819240162507'),
    ('568048110', '3735116265600'),
    ('189046381', '3735116265600'),
    ('568048110', '8692435020394'),
    ('189046381', '9821245614546'),
    ('586935003', '9166381380326'),
    ('575850866', '6943222691011'),
    ('575850866', '2876464987780'),
    ('573218425', '8864108111028'),
    ('573218425', '5592654287374'),
    ('529362436', '4390887768164'),
    ('529362436', '6928834576162'),
    ('222399408', '3426689964487'),
    ('222399408', '3072968720262'),
    ('222399408', '9819240162507'),
    ('222399408', '5695405925155'),
    ('568048110', '6018241219817'),
    ('527350911', '9054295716603'),
    ('527350911', '2894194365224'),
    ('527350911', '1677986565035'),
    ('568048110', '6098893416477'),
    ('568048110', '1468246121310'),
    ('568048110', '3606473094197'),
    ('568048110', '2113049537762'),
    ('568048110', '3696452353278'),
    ('568048110', '2322867665713')
;

-- Populate table 'corredor'
INSERT INTO corredor (nro, largura) VALUES
    ('1', '4'),
    ('2', '4'),
    ('3', '3'),
    ('4', '5'),
    ('5', '3'),
    ('6', '5')
;

-- Populate table 'prateleira'
INSERT INTO prateleira (nro, lado, altura) VALUES
    ('1', 'Direito', 'Baixo'),
    ('1', 'Direito', 'Alto'),
    ('1', 'Esquerdo', 'Baixo'),
    ('1', 'Esquerdo', 'Alto'),
    ('2', 'Direito', 'Baixo'),
    ('2', 'Direito', 'Alto'),
    ('2', 'Esquerdo', 'Baixo'),
    ('2', 'Esquerdo', 'Alto'),
    ('3', 'Direito', 'Baixo'),
    ('3', 'Direito', 'Alto'),
    ('3', 'Esquerdo', 'Baixo'),
    ('3', 'Esquerdo', 'Alto'),
    ('4', 'Direito', 'Baixo'),
    ('4', 'Direito', 'Alto'),
    ('4', 'Esquerdo', 'Baixo'),
    ('4', 'Esquerdo', 'Alto'),
    ('5', 'Direito', 'Baixo'),
    ('5', 'Direito', 'Alto'),
    ('5', 'Esquerdo', 'Baixo'),
    ('5', 'Esquerdo', 'Alto'),
    ('6', 'Direito', 'Baixo'),
    ('6', 'Direito', 'Alto'),
    ('6', 'Esquerdo', 'Baixo'),
    ('6', 'Esquerdo', 'Alto')
;

-- Populate table 'planograma'
INSERT INTO planograma (ean, nro, lado, altura, face, unidades, loc) VALUES
    ('3735116265600', '1', 'Direito', 'Baixo', '10', '20', '1'),
    ('3735116265600', '2', 'Direito', 'Alto', '10', '20', '1'),
    ('8864108111028', '3', 'Direito', 'Baixo', '7', '17', '4'),
    ('1677986565035', '1', 'Direito', 'Baixo', '7', '17', '2'),
    ('9166381380326', '2', 'Esquerdo', 'Baixo', '2', '20', '3'),
    ('2876464987780', '3', 'Direito', 'Alto', '2', '20', '4'),
    ('8864108111028', '4', 'Esquerdo', 'Baixo', '7', '17', '6'),
    ('3696452353278', '4', 'Esquerdo', 'Alto', '5', '17', '6'),
    ('2322867665713', '6', 'Esquerdo', 'Alto', '10', '18', '4'),
    ('6018241219817', '6', 'Direito', 'Alto', '2', '56', '1')
;

-- Populate table 'evento_reposicao'
INSERT INTO evento_reposicao (operador, instante) VALUES
    ('Manuel', '2017-10-19 10:23:54+00'),
    ('Joana', '2017-10-19 10:23:54+00'),
    ('Nuno', '2017-11-19 10:23:54+00'),
    ('Nuno', '2017-10-19 10:21:54+00'),
    ('Duarte', '2017-10-19 10:21:00+00'),
    ('Duarte', '2017-10-19 10:21:23+00'),
    ('Joao', '2017-11-19 10:21:54+00'),
    ('Joao', '2017-02-19 10:21:32+00'),
    ('Daniel', '2017-10-18 10:21:54+00'),
    ('Daniel', '2017-10-19 23:16:45+00'),
    ('Duarte', '2017-10-20 10:09:54+00'),
    ('Nuno', '2017-11-09 09:21:54+00'),
    ('Ana', '2017-10-09 22:20:32+00'),
    ('Ana', '2017-11-15 11:21:23+00')
;

-- Populate table 'reposicao'
INSERT INTO reposicao (ean, nro, lado, altura, operador, instante, unidades) VALUES
    ('3735116265600', '1', 'Direito', 'Baixo', 'Manuel', '2017-10-19 10:23:54+00', '20'),
    ('3735116265600', '2', 'Direito', 'Alto', 'Manuel', '2017-10-19 10:23:54+00', '20'),
    ('3735116265600', '1', 'Direito', 'Baixo', 'Joana', '2017-10-19 10:23:54+00', '11'),
    ('3735116265600', '2', 'Direito', 'Alto', 'Nuno', '2017-11-19 10:23:54+00', '2'),
    ('3735116265600', '1', 'Direito', 'Baixo', 'Duarte', '2017-10-19 10:21:00+00', '2'),
    ('3735116265600', '1', 'Direito', 'Baixo', 'Joao', '2017-11-19 10:21:54+00', '3'),
    ('9166381380326', '2', 'Esquerdo', 'Baixo', 'Duarte', '2017-10-19 10:21:23+00', '4'),
    ('9166381380326', '2', 'Esquerdo', 'Baixo', 'Joao', '2017-02-19 10:21:32+00', '5'),
    ('2876464987780', '3', 'Direito', 'Alto', 'Daniel', '2017-10-18 10:21:54+00', '6'),
    ('2876464987780', '3', 'Direito', 'Alto', 'Daniel', '2017-10-19 23:16:45+00', '7'),
    ('8864108111028', '4', 'Esquerdo', 'Baixo', 'Duarte', '2017-10-20 10:09:54+00', '8'),
    ('8864108111028', '4', 'Esquerdo', 'Baixo', 'Nuno', '2017-11-09 09:21:54+00', '9'),
    ('3696452353278', '4', 'Esquerdo', 'Alto', 'Ana', '2017-10-09 22:20:32+00', '10'),
    ('3696452353278', '4', 'Esquerdo', 'Alto', 'Ana', '2017-11-15 11:21:23+00', '10'),
    ('2322867665713', '6', 'Esquerdo', 'Alto', 'Daniel', '2017-10-18 10:21:54+00', '6'),
    ('2322867665713', '6', 'Esquerdo', 'Alto', 'Daniel', '2017-10-19 23:16:45+00', '7'),
    ('6018241219817', '6', 'Direito', 'Alto', 'Duarte', '2017-10-20 10:09:54+00', '8'),
    ('6018241219817', '6', 'Direito', 'Alto', 'Nuno', '2017-11-09 09:21:54+00', '9'),
    ('6018241219817', '6', 'Direito', 'Alto', 'Ana', '2017-10-09 22:20:32+00', '10'),
    ('6018241219817', '6', 'Direito', 'Alto', 'Ana', '2017-11-15 11:21:23+00', '10')
;