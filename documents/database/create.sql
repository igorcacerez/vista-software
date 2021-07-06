CREATE TABLE usuario(
    id_usuario INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL,
    PRIMARY KEY (id_usuario)
);


CREATE TABLE token(
  id_token INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  token TEXT NOT NULL,
  ip VARCHAR(100) NOT NULL,
  data_expira TIMESTAMP NOT NULL,
  data TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_token),
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);


CREATE TABLE locatario(
    id_locatario INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    PRIMARY KEY (id_locatario)
);


CREATE TABLE locador(
    id_locador INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    diasRepasse INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id_locador)
);


CREATE TABLE contrato(
    id_contrato INT NOT NULL AUTO_INCREMENT,
    id_locatario INT NOT NULL,
    id_locador INT NOT NULL,
    imovel INT NOT NULL,

    taxaAdministracao DOUBLE NOT NULL DEFAULT 0,
    valorAluguel FLOAT NOT NULL DEFAULT 0,
    valorCondominio FLOAT NOT NULL DEFAULT 0,
    valorIptu FLOAT NOT NULL DEFAULT 0,

    cep VARCHAR(10) NULL DEFAULT NULL,
    bairro VARCHAR(150) NULL DEFAULT NULL,
    cidade VARCHAR(150) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    endereco VARCHAR(150) NOT NULL,
    numero VARCHAR(10) NULL DEFAULT NULL,

    dataInicio DATE NOT NULL,
    dataFim DATE NOT NULL,

    PRIMARY KEY (id_contrato),
    FOREIGN KEY (id_locatario) REFERENCES locatario(id_locatario),
    FOREIGN KEY (id_locador) REFERENCES locador(id_locador)
);


CREATE TABLE mensalidaderepasse(
    id_mensalidadeRepasse INT NOT NULL AUTO_INCREMENT,
    id_contrato INT NOT NULL,
    valorTotal FLOAT NOT NULL DEFAULT 0,
    valorRepasse FLOAT NOT NULL DEFAULT 0,
    dataVencimento DATE NOT NULL,
    dataRepasse DATE NOT NULL,
    pago BOOLEAN NOT NULL DEFAULT false,
    repasse BOOLEAN NOT NULL DEFAULT false,
    PRIMARY KEY (id_mensalidadeRepasse),
    FOREIGN KEY (id_contrato) REFERENCES contrato(id_contrato)
);