-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-06-2025 a las 19:31:35
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `documentos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso_certificacion`
--

CREATE TABLE `curso_certificacion` (
  `id_curso_certificacion` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipo` enum('certificado','curso') COLLATE utf8_spanish_ci DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL,
  `unidad_duracion` enum('dias','meses','anios') COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preseleccionado`
--

CREATE TABLE `preseleccionado` (
  `id_preseleccionado` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_registro` date NOT NULL DEFAULT current_timestamp(),
  `apellidos_nombres` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `dni` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `exactian` varchar(32) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_ingreso_ultimo_proyecto` date DEFAULT NULL,
  `fecha_cese_ultimo_proyecto` date DEFAULT NULL,
  `nombre_ultimo_proyecto` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono_1` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono_2` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `departamento_residencia` varchar(32) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preseleccionado_curso_certificacion`
--

CREATE TABLE `preseleccionado_curso_certificacion` (
  `id_prese_curs_certi` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `id_preseleccionado` varchar(32) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_curs_certi` varchar(32) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preseleccionado_requerimiento`
--

CREATE TABLE `preseleccionado_requerimiento` (
  `id_prese_reque` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_registro` date NOT NULL DEFAULT current_timestamp(),
  `id_preseleccionado` varchar(32) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_reque_proy` varchar(32) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requerimiento_proyecto`
--

CREATE TABLE `requerimiento_proyecto` (
  `id_requerimiento` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_registro` date NOT NULL DEFAULT current_timestamp(),
  `id_proyecto` int(11) DEFAULT NULL,
  `fecha_requerimiento` date DEFAULT NULL,
  `numero_requerimiento` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipo_requerimiento` enum('INCREMENTO DE ACTIVIDAD','REEMPLAZO','INICIO DE ACTIVIDADES') COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_fase` int(11) DEFAULT NULL,
  `id_cargo` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `regimen` enum('CIVIL','COMÚN') COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `curso_certificacion`
--
ALTER TABLE `curso_certificacion`
  ADD PRIMARY KEY (`id_curso_certificacion`);

--
-- Indices de la tabla `preseleccionado`
--
ALTER TABLE `preseleccionado`
  ADD PRIMARY KEY (`id_preseleccionado`);

--
-- Indices de la tabla `preseleccionado_curso_certificacion`
--
ALTER TABLE `preseleccionado_curso_certificacion`
  ADD PRIMARY KEY (`id_prese_curs_certi`);

--
-- Indices de la tabla `preseleccionado_requerimiento`
--
ALTER TABLE `preseleccionado_requerimiento`
  ADD PRIMARY KEY (`id_prese_reque`);

--
-- Indices de la tabla `requerimiento_proyecto`
--
ALTER TABLE `requerimiento_proyecto`
  ADD PRIMARY KEY (`id_requerimiento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
