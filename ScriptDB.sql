 CREATE DATABASE mybox;
 
 USE mybox;
 
CREATE TABLE carpetas
(
	IDCarpeta INT NOT NULL auto_increment,
    NombreCarpeta varchar (50) not null,
    CarpetaPadreID INT NULL,
    IsRoot bool not null,
    primary key (IDCarpeta)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

ALTER TABLE carpetas
ADD CONSTRAINT FK_carpeta_padre
FOREIGN KEY (CarpetaPadreID) REFERENCES carpetas(IDCarpeta) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE archivos (
    IDArchivo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_archivo VARCHAR(255) NOT NULL,
    comentario varchar(50),
    tipo_documento VARCHAR(100),
    peso BIGINT,
    fecha_almacenado DATETIME DEFAULT CURRENT_TIMESTAMP,
    IDCarpeta INT NOT NULL,
    extension VARCHAR(10),
	contenido_archivo LONGBLOB NOT NULL,
    FOREIGN KEY (IDCarpeta) REFERENCES carpetas(IDCarpeta) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE usuarios (  
	usuario varchar(15) unique,     
    contra varchar(80) not null,     
    nombre varchar(25) not null,     
    email varchar(20) unique,     
    primary key (usuario) 
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE archivos_compartidos (
    IDArchivoCompartido INT AUTO_INCREMENT PRIMARY KEY,
    ArchivoID INT NOT NULL,
    compartido_de varchar(15),
    compartido_usuario varchar(15),
    FOREIGN KEY (ArchivoID) REFERENCES archivos(IDArchivo) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (compartido_de) REFERENCES usuarios(usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (compartido_usuario) REFERENCES usuarios(usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE (ArchivoID, compartido_usuario)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE carpetas_compartidas (
    IDCarpetaCompartida INT AUTO_INCREMENT PRIMARY KEY,
    CarpetaID INT NOT NULL,
    compartido_de varchar(15),
    compartido_usuario varchar(15),
    FOREIGN KEY (CarpetaID) REFERENCES carpetas(IDCarpeta) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (compartido_de) REFERENCES usuarios(usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (compartido_usuario) REFERENCES usuarios(usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE (CarpetaID, compartido_usuario)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;