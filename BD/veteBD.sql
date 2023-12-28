-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-12-2023 a las 18:45:50
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `exposicion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

CREATE TABLE `mascotas` (
  `Codigo_Animal` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Especie` varchar(50) DEFAULT NULL,
  `Raza` varchar(50) DEFAULT NULL,
  `Edad` int(11) NOT NULL,
  `Dni` varchar(10) NOT NULL,
  `FechaNacimiento` date DEFAULT NULL,
  `Peso` decimal(5,2) NOT NULL,
  `Codigo_Consulta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`Codigo_Animal`, `Nombre`, `Especie`, `Raza`, `Edad`, `Dni`, `FechaNacimiento`, `Peso`, `Codigo_Consulta`) VALUES
(100001, 'Fido', 'Perro', 'Labrador', 3, '123456789A', '2021-06-15', 25.50, NULL),
(100002, 'Whiskers', 'Gato', 'Siames', 2, '987654321B', '2022-01-20', 8.20, NULL),
(100003, 'Rex', 'Perro', 'Golden Retriever', 4, '567890123C', '2019-05-10', 30.00, NULL),
(100004, 'Bella', 'Gato', 'Persa', 6, '345678901D', '2017-03-25', 12.30, NULL),
(100005, 'Lucky', 'Perro', 'Poodle', 2, '210987654E', '2021-11-05', 10.80, NULL),
(100006, 'Simba', 'Gato', 'Bengal', 4, '456789012F', '2018-09-30', 14.60, NULL),
(100007, 'Rocky', 'Perro', 'Bulldog', 5, '789012345G', '2017-07-12', 28.30, NULL),
(100008, 'Molly', 'Perro', 'Labrador', 2, '123456789A', '2022-03-01', 22.10, NULL),
(100009, 'Oliver', 'Gato', 'Siames', 3, '987654321B', '2021-08-14', 9.70, NULL),
(100010, 'Luna', 'Gato', 'Persa', 5, '567890123C', '2017-04-19', 11.50, NULL),
(100011, 'Cooper', 'Perro', 'German Shepherd', 4, '345678901D', '2018-12-03', 32.50, NULL),
(100012, 'Charlie', 'Perro', 'Bulldog', 1, '210987654E', '2023-02-10', 15.40, NULL),
(100013, 'Lucy', 'Gato', 'Ragdoll', 4, '456789012F', '2018-07-25', 10.90, NULL),
(100014, 'Bailey', 'Perro', 'Golden Retriever', 2, '789012345G', '2021-10-05', 29.80, NULL),
(100015, 'Tiger', 'Gato', 'Maine Coon', 3, '123456789A', '2021-12-20', 13.60, NULL),
(100016, 'Max', 'Perro', 'Labrador', 6, '987654321B', '2016-04-02', 34.20, NULL),
(100017, 'Chloe', 'Gato', 'British Shorthair', 7, '567890123C', '2015-08-15', 11.00, NULL),
(100018, 'Rocky', 'Perro', 'Boxer', 3, '345678901D', '2019-09-22', 27.70, NULL),
(100019, 'Daisy', 'Perro', 'Beagle', 2, '210987654E', '2021-02-18', 17.90, NULL),
(100020, 'Lily', 'Gato', 'Siames', 4, '456789012F', '2018-06-10', 9.50, NULL),
(100021, 'Milo', 'Perro', 'Dachshund', 5, '789012345G', '2017-12-14', 13.80, NULL),
(100022, 'Lola', 'Gato', 'Persa', 2, '123456789A', '2022-05-05', 8.70, NULL),
(100023, 'Sophie', 'Gato', 'Maine Coon', 1, '987654321B', '2023-04-01', 12.40, NULL),
(100024, 'Duke', 'Perro', 'Great Dane', 3, '567890123C', '2020-11-30', 40.20, NULL),
(100025, 'Penny', 'Perro', 'Cocker Spaniel', 4, '345678901D', '2019-03-08', 19.60, NULL),
(100026, 'Leo', 'Gato', 'Ragdoll', 6, '210987654E', '2017-10-22', 14.10, NULL),
(100027, 'Zoe', 'Gato', 'British Shorthair', 3, '456789012F', '2020-12-02', 11.80, NULL),
(100028, 'Koda', 'Perro', 'Siberian Husky', 2, '789012345G', '2022-08-10', 27.30, NULL),
(100029, 'Mia', 'Gato', 'Siames', 3, '123456789A', '2021-09-19', 10.20, NULL),
(100030, 'Benji', 'Perro', 'Shih Tzu', 5, '987654321B', '2017-05-15', 14.50, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `DNI` varchar(10) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `FechaNacimiento` date DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `Direccion` varchar(100) DEFAULT NULL,
  `Rol` int(11) NOT NULL,
  `Clave` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`DNI`, `Nombre`, `Apellido`, `FechaNacimiento`, `Email`, `Telefono`, `Direccion`, `Rol`, `Clave`) VALUES
('1111111111', 'javier', 'Perez', '2023-12-01', 'ewphgtw@gmail.com', '210-987-0000', 'Calle C, Ciudad C', 0, 'nnnn'),
('123456789A', 'Juan', 'Pérez', '1985-03-15', 'juan.perez@email.com', '123-456-7890', 'Calle A, Ciudad A', 0, '9af15b336e6a9619928537df30b2e6a2376569fcf9d7e773eccede65606529a0'),
('210987654E', 'Laura', 'Torres', '1992-04-05', 'laura.torres@email.com', '210-987-6540', 'Calle E, Ciudad E', 0, '79f06f8fde333461739f220090a23cb2a79f6d714bee100d0e4b4af249294619'),
('234567890J', 'Luis', 'Rodríguez', '1982-11-15', 'luis.rodriguez@email.com', '234-567-8900', 'Calle J, Ciudad J', 1, '793a84a351bd364d2f0323b67b39407711e54bc4748c439fb32734538ef8dd15'),
('345678901D', 'David', 'Martínez', '1988-08-25', 'david.martinez@email.com', '345-678-9010', 'Calle D, Ciudad D', 0, '318aee3fed8c9d040d35a7fc1fa776fb31303833aa2de885354ddf3d44d8fb69'),
('456789012F', 'Pedro', 'Sánchez', '1975-09-30', 'pedro.sanchez@email.com', '456-789-0120', 'Calle F, Ciudad F', 0, 'c1f330d0aff31c1c87403f1e4347bcc21aff7c179908723535f2b31723702525'),
('567890123C', 'María', 'García', '1980-12-10', 'maria.garcia@email.com', '567-890-1230', 'Calle C, Ciudad C', 0, 'edee29f882543b956620b26d0ee0e7e950399b1c4222f5de05e06425b4c995e9'),
('654321098H', 'Roberto', 'Hernández', '1983-01-05', 'roberto.hernandez@email.com', '654-321-0980', 'Calle H, Ciudad H', 1, 'fe91a760983d401d9b679fb092b689488d1f46d92f3af5e9e93363326f3e8aa4'),
('66666666T', 'Tania', 'Robledo', '2023-12-22', 'tania@gmail.com', '210-987-0000', 'Calle C, Ciudad C', 0, '888df25ae35772424a560c7152a1de794440e0ea5cfee62828333a456a506e05'),
('789012345G', 'Sofía', 'Ramírez', '1987-07-12', 'sofia.ramirez@email.com', '789-012-3450', 'Calle G, Ciudad G', 0, 'd7697570462f7562b83e81258de0f1e41832e98072e44c36ec8efec46786e24e'),
('88888888G', 'Hector', 'Rola', '2023-12-21', 'hector@gmail.com', '210-987-8888', 'Calle C, Ciudad C', 0, 'd7697570462f7562b83e81258de0f1e41832e98072e44c36ec8efec46786e24e'),
('890123456I', 'Isabel', 'Gómez', '1995-06-20', 'isabel.gomez@email.com', '890-123-4560', 'Calle I, Ciudad I', 1, 'b7e307660e1611cb42bcb28e4bb4a6465ccb5ec2e028ca4be8b84e8787929a38'),
('987654321B', 'Ana', 'López', '1990-05-20', 'ana.lopez@email.com', '987-654-3210', 'Calle B, Ciudad B', 0, '0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacunas_perro`
--

CREATE TABLE `vacunas_perro` (
  `ID` int(11) NOT NULL,
  `Codigo_Animal` int(11) NOT NULL,
  `Nombre_Vacuna` varchar(50) DEFAULT NULL,
  `Enfermedades_Prevenidas` varchar(100) DEFAULT NULL,
  `Edad_de_Inicio` varchar(20) DEFAULT NULL,
  `Frecuencia` varchar(20) DEFAULT NULL,
  `Fecha_Vacunacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacunas_perro`
--

INSERT INTO `vacunas_perro` (`ID`, `Codigo_Animal`, `Nombre_Vacuna`, `Enfermedades_Prevenidas`, `Edad_de_Inicio`, `Frecuencia`, `Fecha_Vacunacion`) VALUES
(1, 100001, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100003, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100005, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100007, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100009, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100011, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100013, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100015, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100017, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100019, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100021, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100023, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100025, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100027, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(1, 100029, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
(2, 100001, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100003, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100005, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100007, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100009, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100011, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100013, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100015, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100017, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100019, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100021, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100023, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100025, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100027, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(2, 100029, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
(3, 100002, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100004, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100006, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100008, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100010, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100012, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100014, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100016, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100018, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100020, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100022, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100024, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100026, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100028, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(3, 100030, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
(4, 100002, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100004, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100006, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100008, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100010, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100012, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100014, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100016, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100018, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100020, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100022, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100024, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100026, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100028, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
(4, 100030, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD PRIMARY KEY (`Codigo_Animal`,`Dni`),
  ADD KEY `Dni` (`Dni`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`DNI`);

--
-- Indices de la tabla `vacunas_perro`
--
ALTER TABLE `vacunas_perro`
  ADD PRIMARY KEY (`ID`,`Codigo_Animal`),
  ADD KEY `Codigo_Animal` (`Codigo_Animal`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`Dni`) REFERENCES `personas` (`DNI`) ON DELETE CASCADE;

--
-- Filtros para la tabla `vacunas_perro`
--
ALTER TABLE `vacunas_perro`
  ADD CONSTRAINT `vacunas_perro_ibfk_1` FOREIGN KEY (`Codigo_Animal`) REFERENCES `mascotas` (`Codigo_Animal`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
