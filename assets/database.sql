CREATE SCHEMA IF NOT EXISTS receiteme;
USE receiteme;

-- Tabela de Usuário
CREATE TABLE Usuario(
    idUsuario int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    email varchar(100) NOT NULL, 
    senha varchar(50) NOT NULL,
    nome varchar(100) NOT NULL, 
    img varchar(200) NOT NULL,
    adm char(1) NOT NULL
);

-- Tabela de Categoria
CREATE TABLE Categoria(
    idCategoria int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nomeCategoria varchar(50) NOT NULL,
    imgCategoria varchar(100) NOT NULL
);

-- Tabela de Receita
CREATE TABLE Receita(
    idReceita int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nomeReceita varchar(150) NOT NULL, 
    ingredientes varchar(1500) NOT NULL,
    modoPreparo varchar(2500) NOT NULL, 
    img varchar(200) NOT NULL,
    categoria int(11),
    status varchar(15) NOT NULL,
    FOREIGN KEY (categoria) REFERENCES Categoria(idCategoria) ON DELETE CASCADE
);

-- Tabela de Doença Crônica
CREATE TABLE Doenca_Cronica(
    idDoenca int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nomeDoenca varchar(50) NOT NULL
);

-- Tabela de Relacionamento entre Receita e Doença Crônica
CREATE TABLE Receita_DoencaCronica(
    idReceita_Doenca int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    idReceita int(11) NOT NULL,
    idDoenca int(11) NOT NULL,
    FOREIGN KEY (idReceita) REFERENCES Receita(idReceita) ON DELETE CASCADE,
    FOREIGN KEY (idDoenca) REFERENCES Doenca_Cronica(idDoenca) ON DELETE CASCADE
);

-- Tabela de Relacionamento entre Usuário e Doença Crônica
CREATE TABLE Usuario_DoencaCronica(
    idUsuario_Doenca int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    idUsuario int(11) NOT NULL,
    idDoenca int(11) NOT NULL,
    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario) ON DELETE CASCADE,
    FOREIGN KEY (idDoenca) REFERENCES Doenca_Cronica(idDoenca) ON DELETE CASCADE
);


-- Criação das Categorias
INSERT INTO Categoria (nomeCategoria, imgCategoria)
VALUES ('Sobremesa', 'images/site/categorias/Sobremesa.png');

INSERT INTO Categoria (nomeCategoria, imgCategoria)
VALUES ('Sopa', 'images/site/categorias/Sopa.png');

INSERT INTO Categoria (nomeCategoria, imgCategoria)
VALUES ('Lanches', 'images/site/categorias/Lanche.png');

INSERT INTO Categoria (nomeCategoria, imgCategoria)
VALUES ('Saladas', 'images/site/categorias/Saladas.png');

INSERT INTO Categoria (nomeCategoria, imgCategoria)
VALUES ('Refeições', 'images/site/categorias/Refeicao.png');

-- Criação das Doenças Cronicas
INSERT INTO Doenca_Cronica (nomeDoenca)
VALUES ('Hipertensão');

INSERT INTO Doenca_Cronica (nomeDoenca)
VALUES ('Diabetes');

INSERT INTO Doenca_Cronica (nomeDoenca)
VALUES ('Colesterol');

INSERT INTO Doenca_Cronica (nomeDoenca)
VALUES ('Intolerantes a Lactose e Glúten');

-- Inserção em receita
INSERT INTO Receita (nomeReceita, ingredientes, modoPreparo, img, categoria, status)
VALUES ('Bolo de Chocolate', 'Farinha& açúcar& chocolate& ovos', 'Misture todos os ingredientes.&Asse no forno.', 'images/receitas/Bolo_chocolate.png', '1', 'ativo');

INSERT INTO Receita (nomeReceita, ingredientes, modoPreparo, img, categoria, status)
VALUES ('Sopa de Legumes', 'Legumes variados&caldo de galinha', 'Cozinhe os legumes no caldo de galinha.&Sirva quente.', 'images/receitas/Sopa_legumes.png', '2', 'ativo');

INSERT INTO Receita (nomeReceita, ingredientes, modoPreparo, img, categoria, status)
VALUES ('Salada de Verão', 'Alface&tomate&cenoura&azeitonas&queijo feta', 'Lave e corte os ingredientes.&Misture tudo em uma tigela.&Tempere a gosto.', 'images/receitas/salada_verao.png', '4', 'ativo');

INSERT INTO Receita (nomeReceita, ingredientes, modoPreparo, img, categoria, status)
VALUES ('Espaguete à Bolonhesa', 'Espaguete&carne moída&molho de tomate&cebola&alho', 'Cozinhe o espaguete conforme as instruções.&Refogue a carne, cebola e alho.&Adicione o molho de tomate.&Sirva sobre o espaguete.', 'images/receitas/espaguete_bolonhesa.png', '2', 'ativo');

INSERT INTO Receita (nomeReceita, ingredientes, modoPreparo, img, categoria, status)
VALUES ('Hambúrguer Caseiro', '250 gramas de alcatra&fraldinha ou contrafilé&Sal grosso a gosto&Pimenta a gosto&2 fatias de queijo da sua preferência&1 pão de hambúrguer&Tomate&Pepino e cebola fatiada (opcional)', 'Comece limpando bem a carne e retirando os nervos;%Corte a carne em cubos e passe em moedor. Caso você não tenha um em casa, corte e deixe a carne no congelador até que fique bem rígida;&Em seguida, bata a carne em um processador de alimentos, com a lâmina e o copo resfriados - isso faz com que a gordura não se misture muito à carne;&Depois, molde o hambúrguer com a ajuda de um aro e deixe por 30 minutos a 1 hora na geladeira;&Tempere o hambúrguer com bastante sal e pimenta;&Aqueça óleo em uma frigideira e adicione o hambúrguer para fritar! Vire a cada 1 minuto para que cozinhe uniformemente e fique bem dourado;&Depois, abaixe o fogo e adicione uma fatia de queijo sobre ele. Tampe e deixe que o queijo derreta o pão de hambúrguer no meio e leve-o para assar com o miolo para baixo por 5 minutos, até que fique dourado;&Monte o sanduíche e sirva acompanhado de batatas fritas!', 'images/receitas/lanche_artesanal.png', '3', 'ativo');
    
    --Receitas para Colesterol alto
    INSERT INTO Receita (nomeReceita, ingredientes, modoPreparo, img, categoria, status)
    VALUES ('Guacamole', '1 abacate&Suco de 1 limão&1 cebola picada&2 tomates sem semente&1 pimenta-dedo-de-moça&Folhas de ½ maço de coentro&3 colheres de sopa de azeite de oliva&Sal a gosto', 'Pegue o abacate, retire a casca e o caroço, coloque a polpa em uma tigela e amasse com um garfo, deixando apenas alguns pedaços. Feito isso, reserve. Corte os tomates, de preferência em cubos bem pequenos e aproveite para também picar o coentro. Abra a pimenta, retire as sementes brancas e pique em pedacinhos. Em seguida, misture os ingredientes cortados ao abacate e regue com suco de limão, azeite e sal. Depois disso, é só degustar.', 'images/receitas/guacamole.png', '5', 'ativo');
    INSERT INTO Receita_DoencaCronic (idReceita, idDoenca)
    VALUES ('', '');   











