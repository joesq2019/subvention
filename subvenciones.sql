-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 14-11-2022 a las 14:50:04
-- Versión del servidor: 5.7.33
-- Versión de PHP: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `subvenciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accountability`
--

CREATE TABLE `accountability` (
  `id` int(11) NOT NULL,
  `id_subvention` int(11) NOT NULL,
  `name_represent` varchar(255) NOT NULL,
  `number_invoices` varchar(100) NOT NULL,
  `amount_delivered` varchar(100) NOT NULL,
  `amount_yielded` varchar(100) NOT NULL,
  `amount_refunded` varchar(100) NOT NULL,
  `balance` int(100) NOT NULL,
  `date_admission` varchar(100) NOT NULL,
  `status` varchar(50) DEFAULT '' COMMENT '0: Pendiente - 1: Aprobada - 2: observada - 3: Devolucion',
  `message` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `accountability`
--

INSERT INTO `accountability` (`id`, `id_subvention`, `name_represent`, `number_invoices`, `amount_delivered`, `amount_yielded`, `amount_refunded`, `balance`, `date_admission`, `status`, `message`) VALUES
(1, 1, 'Pedro Martinez', '2', '60', '50', '0', 10, '25-08-2022', '1', 'aprrobada por el jefe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accountability_files`
--

CREATE TABLE `accountability_files` (
  `id` int(11) NOT NULL,
  `id_accountability` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `type` varchar(250) NOT NULL COMMENT '1: beneficiarios\r\n2: Documentacion\r\n3: Fotografia\r\n4: reintegro',
  `related` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `accountability_files`
--

INSERT INTO `accountability_files` (`id`, `id_accountability`, `name`, `path`, `url`, `type`, `related`) VALUES
(1, 1, 'refresco2litros.jpg', 'listabeneficiarios/1.jpg', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/listabeneficiarios%2F1.jpg?alt=media&token=5b5ff91f-d137-46b0-86d2-5d6aa1688886', '1', NULL),
(2, 1, 'pastaallegri.jpg', 'fotografias/1-0-21625.jpg', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/fotografias%2F1-0-21625.jpg?alt=media&token=bc0f631f-5cc8-41ee-a681-7ef30695248e', '3', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `approval_subsidy`
--

CREATE TABLE `approval_subsidy` (
  `id` int(11) NOT NULL,
  `id_subvention` int(11) NOT NULL,
  `no_mayor_decree` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `agreement_date` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `no_payment_decree` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `payment_date` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `no_session` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `session_date` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `no_payment_installments` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `approval_subsidy`
--

INSERT INTO `approval_subsidy` (`id`, `id_subvention`, `no_mayor_decree`, `agreement_date`, `no_payment_decree`, `payment_date`, `no_session`, `session_date`, `no_payment_installments`, `status`) VALUES
(1, 1, '11111', '2022-08-01', '22222', '2022-08-02', '33333', '2022-08-03', '3', 1),
(2, 1, '1', '2022-09-26', '2', '2022-10-11', '3', '2022-10-26', '11', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `budge_information`
--

CREATE TABLE `budge_information` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `budget_certificate_number` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `emision_date` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `amount_available` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `name_document` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `path_document` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `url_document` text COLLATE utf8_spanish_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `budge_information`
--

INSERT INTO `budge_information` (`id`, `id_user`, `budget_certificate_number`, `emision_date`, `amount_available`, `name_document`, `status`, `path_document`, `url_document`, `created_at`) VALUES
(1, 1, '1919', '2022-09-21', '2222', 'campinha.png', 1, 'financing_role/1_1312038122_.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/financing_role%2F1_1312038122_.png?alt=media&token=2d48ab0d-f80b-4dfb-9aee-364386fd91c9', '2022-09-21 17:01:20'),
(2, 1, '111', '2022-09-22', '5', NULL, 1, NULL, NULL, '2022-09-21 17:04:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `data`
--

CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `accumulated_amount` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `data`
--

INSERT INTO `data` (`id`, `accumulated_amount`) VALUES
(1, '1755');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `financing_details`
--

CREATE TABLE `financing_details` (
  `id` int(11) NOT NULL,
  `id_subvention` int(11) NOT NULL,
  `details` varchar(255) NOT NULL,
  `unit_price` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `total_price` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `financing_details`
--

INSERT INTO `financing_details` (`id`, `id_subvention`, `details`, `unit_price`, `quantity`, `total_price`) VALUES
(1, 1, 'Detalle 1', '20', '3', '600'),
(2, 1, 'Detalle 2', '30', '3', '90'),
(3, 2, 'd1', '2', '4', '8'),
(4, 2, 'd2', '3', '6', '18'),
(7, 5, 'd1', '4', '4', '16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `financing_files`
--

CREATE TABLE `financing_files` (
  `id` int(11) NOT NULL,
  `id_subvention` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `financing_files`
--

INSERT INTO `financing_files` (`id`, `id_subvention`, `name`, `path`, `url`) VALUES
(1, 1, 'arroz primor.png', 'financing/1-0-88598.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/financing%2F1-0-88598.png?alt=media&token=7d46f051-1ac8-4081-be5e-78f033d3e50c'),
(2, 2, 'harinapan 2.png', 'financing/2-0-67494.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/financing%2F2-0-67494.png?alt=media&token=1c6b5bd8-3f89-4698-b1e5-9923b37fcb76');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `id_accountability` int(11) NOT NULL,
  `date` varchar(100) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `x` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `invoices`
--

INSERT INTO `invoices` (`id`, `id_accountability`, `date`, `detail`, `amount`, `x`) VALUES
(1, 1, '24-08-2022', 'detalle factura 1', '20', NULL),
(2, 1, '25-08-2022', 'detalle factura 2', '30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `id_subvention` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rut` varchar(50) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `members`
--

INSERT INTO `members` (`id`, `type`, `id_subvention`, `name`, `rut`, `address`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Presidente', 1, 'Jose Alaya', '2452', 'Calle 291 la victoria', '042129134814', '2022-08-23 20:54:23', NULL),
(2, 'Vicepresidente', 1, 'Mauricio Restrepo', '45245', 'Calle 191', '041234141414', '2022-08-23 20:54:23', NULL),
(3, 'Secretario', 1, 'Manuel Palacios', '21213', 'calle 19191', '04244141414', '2022-08-23 20:54:23', NULL),
(4, 'Tesorero', 1, 'Patricia Perez', '78979', 'calle 8118181', '042424824882', '2022-08-23 20:54:23', NULL),
(5, 'Presidente', 5, 'Samuel carrillo', '111', 'calle 2929', '919191', '2022-09-22 15:50:40', NULL),
(6, 'Vicepresidente', 5, 'manuel perez', '222', 'calel 18', '199191', '2022-09-22 15:50:40', NULL),
(7, 'Secretario', 5, 'isaias baduel', '333', 'calle 1881', '919191', '2022-09-22 15:50:40', NULL),
(8, 'Tesorero', 5, 'maria perez', '444', 'calle 28', '8545454', '2022-09-22 15:50:40', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organitation`
--

CREATE TABLE `organitation` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `rut` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `organitation`
--

INSERT INTO `organitation` (`id`, `name`, `rut`, `address`, `email`, `phone`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Sonrisas Unidas', '111-1', 'CAlle 25 estado aragua', 'sonrisas@gmail.com', '04242842848', 1, '2022-08-23 20:54:23', NULL),
(2, 'Ayuda para todos', '111-2', 'calle 91919', 'ayudatodos@gmail.com', '1818181', 1, '2022-09-22 15:50:40', NULL),
(5, 'opahans', '111-4', 'dddddd', 'dddd@gmail.com', '54546546', 1, '2022-09-30 03:26:37', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `permissions` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `role`
--

INSERT INTO `role` (`id`, `name`, `permissions`) VALUES
(1, 'SUPERADMIN', '{\"user_list\": \"true\",\"user_add\": \"true\",\"user_edit\": \"true\",\"user_delete\": \"true\",\"roles_list\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\",\"subvention_list\": \"true\",\"subvention_add\": \"true\",\"subvention_edit\": \"true\",\"subvention_delete\": \"true\",\"subvention_approve\": \"true\",\"accountability_list\": \"true\",\"accountability_add\": \"true\",\"accountability_approve\": \"true\",\"accountability_edit\": \"true\",\"accountability_delete\": \"true\",\"accountability_view_list\": \"true\",\"budget_information_list\": \"true\",\"budget_information_add\": \"true\",\"budget_information_delete\": \"true\",\"organization_list\": \"true\"}'),
(2, 'ADMINISTRADOR', '{\"user_list\": \"true\",\"user_edit\": \"true\",\"user_delete\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\",\"subvention_list\": \"true\",\"subvention_add\": \"true\",\"subvention_edit\": \"true\",\"subvention_delete\": \"true\",\"accountability_list\": \"true\",\"accountability_add\": \"true\",\"accountability_edit\": \"true\",\"accountability_delete\": \"true\",\"approval_subsidy_list\": \"true\",\"approval_subsidy_add\": \"true\",\"approval_subsidy_edit\": \"true\",\"approval_subsidy_delete\": \"true\"}'),
(3, 'JEFATURA DIDECO', '{\"user_list\": \"true\",\"user_edit\": \"true\",\"user_delete\": \"true\",\"roles_list\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\"}'),
(4, 'DIDECO', '{\"user_list\": \"true\",\"user_add\": \"true\",\"roles_list\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\"}'),
(5, 'FINANZAS', '{\"subvention_list\": \"true\",\"subvention_add\": \"true\",\"subvention_edit\": \"true\",\"subvention_delete\": \"true\",\"budget_information_list\": \"true\",\"budget_information_add\": \"true\",\"budget_information_edit\": \"true\",\"budget_information_delete\": \"true\"}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `id_subvention` int(11) NOT NULL,
  `activities` varchar(255) NOT NULL,
  `month` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `schedule`
--

INSERT INTO `schedule` (`id`, `id_subvention`, `activities`, `month`) VALUES
(1, 1, 'actividad 1', '08-2022'),
(2, 1, 'actividad 2', '09-2022'),
(3, 2, 'd a', '09-2022'),
(4, 2, 'd a', '10-2022'),
(8, 5, 'da', '06-2022'),
(9, 5, 'da2', '11-2022');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subvention`
--

CREATE TABLE `subvention` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_organitation` int(11) NOT NULL,
  `year` varchar(50) NOT NULL,
  `name_proyect` varchar(200) DEFAULT NULL,
  `objetive_proyect` text,
  `quantity_purchases` int(11) DEFAULT NULL,
  `amount_purchases` varchar(100) NOT NULL,
  `organization_contribution` varchar(100) NOT NULL,
  `amount_direct` varchar(100) DEFAULT NULL,
  `amount_indirect` varchar(100) DEFAULT NULL,
  `total_beneficiaries` varchar(100) DEFAULT NULL,
  `quantity_activities` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0: Error\r\n1: En evaluacion\r\n2: Pre-aprobada\r\n3: Aprobada\r\n4: Rechazada',
  `message` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `subvention`
--

INSERT INTO `subvention` (`id`, `id_user`, `id_organitation`, `year`, `name_proyect`, `objetive_proyect`, `quantity_purchases`, `amount_purchases`, `organization_contribution`, `amount_direct`, `amount_indirect`, `total_beneficiaries`, `quantity_activities`, `status`, `message`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2022-08-23', 'Una ayuda para todos', 'Ayudar a los mas necesitados', 2, '690', '20', '25', '35', '60', 2, 3, '', '2022-08-23 20:54:23', NULL),
(2, 1, 2, '2021-09-21', 'ayudar a los pobres', 'ayudar a los pobres', 2, '26', '5', '6', '8', '14', 2, 1, NULL, '2021-09-22 15:50:40', NULL),
(5, 1, 5, '2022-09-14', 'awdaw', 'dawdawd', 1, '16', '4', '5', '5', '10', 1, 1, NULL, '2022-09-30 03:26:37', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subvention_files`
--

CREATE TABLE `subvention_files` (
  `id` int(11) NOT NULL,
  `id_subvention` int(11) NOT NULL,
  `type` varchar(250) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `subvention_files`
--

INSERT INTO `subvention_files` (`id`, `id_subvention`, `type`, `name`, `path`, `url`) VALUES
(1, 1, 'antecedentes', 'arroz primor.png', 'documents/1_antecedentes.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F1_antecedentes.png?alt=media&token=c879a27f-4bac-436a-a4b2-65c83a268e4c'),
(2, 1, 'fotocopia_rut', 'harinapan.jpeg', 'documents/1_fotocopia_rut.jpeg', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F1_fotocopia_rut.jpeg?alt=media&token=78bc3c8b-f8fe-4b4a-8b21-72afb557bad0'),
(3, 1, 'fotocopia_caratula', 'refresco2litros.jpg', 'documents/1_fotocopia_caratula.jpg', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F1_fotocopia_caratula.jpg?alt=media&token=e3f6800a-b025-40a8-925d-d38b20786a03'),
(4, 1, 'fotocopia_libreta', 'mantequilla mevesa.jpg', 'documents/1_fotocopia_libreta.jpg', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F1_fotocopia_libreta.jpg?alt=media&token=08cb689c-7633-47b8-8ea7-4c53682172c9'),
(5, 1, 'fotocopia_registro', 'pastaallegri.jpg', 'documents/1_fotocopia_registro.jpg', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F1_fotocopia_registro.jpg?alt=media&token=91471e68-a36d-4b75-bac0-09b58a739ef1'),
(6, 1, 'certificado_persolidad_juridica', 'arroz primor.png', 'documents/1_certificado_persolidad_juridica.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F1_certificado_persolidad_juridica.png?alt=media&token=03e7c7cf-d1de-4f90-833e-dd67cbeb0958'),
(7, 1, 'estatutos', 'campinha.png', 'documents/1_estatutos.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F1_estatutos.png?alt=media&token=e5c133fc-eaa2-4c63-89bc-4c171ffbfffd'),
(8, 1, 'certificado_inscripcion', 'harinapan 2.png', 'documents/1_certificado_inscripcion.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F1_certificado_inscripcion.png?alt=media&token=e4239c24-1dd4-42c8-9ef5-393f3b3cd572'),
(9, 1, 'fotocopia_cedula', 'harinapan3.png', 'documents/1_fotocopia_cedula.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F1_fotocopia_cedula.png?alt=media&token=ca92cad8-842d-4ba4-b2e7-9baceb21d068'),
(10, 1, 'certificado_directorio', 'azucarmontalban.png', 'documents/1_certificado_directorio.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F1_certificado_directorio.png?alt=media&token=3c6408ef-08f1-4f42-b258-2f5f64b759ac'),
(11, 2, 'certificado_persolidad_juridica', 'arroz primor.png', 'documents/2_certificado_persolidad_juridica.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F2_certificado_persolidad_juridica.png?alt=media&token=081c0442-db3f-468d-b3be-f59754ada720'),
(12, 2, 'antecedentes', 'arroz primor.png', 'documents/2_antecedentes.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F2_antecedentes.png?alt=media&token=a86d2ff0-1ad0-404f-938d-cf35d9941ba1'),
(13, 2, 'fotocopia_caratula', 'refresco2litros.jpg', 'documents/2_fotocopia_caratula.jpg', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F2_fotocopia_caratula.jpg?alt=media&token=f51b6f59-25dd-4db4-b40c-f06d970d5b6d'),
(14, 2, 'fotocopia_libreta', 'mantequilla mevesa.jpg', 'documents/2_fotocopia_libreta.jpg', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F2_fotocopia_libreta.jpg?alt=media&token=f66b22d7-86e6-4b35-9b7b-c264e3976d8c'),
(15, 2, 'fotocopia_rut', 'harinapan.jpeg', 'documents/2_fotocopia_rut.jpeg', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F2_fotocopia_rut.jpeg?alt=media&token=9b8611d4-75d5-4512-bc63-1ddee7b9905b'),
(16, 2, 'certificado_inscripcion', 'harinapan 2.png', 'documents/2_certificado_inscripcion.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F2_certificado_inscripcion.png?alt=media&token=827bbe65-f875-4358-b394-f62359284b16'),
(17, 2, 'estatutos', 'campinha.png', 'documents/2_estatutos.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F2_estatutos.png?alt=media&token=be968d93-af7a-4e45-97d4-81f6b39c23f1'),
(18, 2, 'fotocopia_registro', 'pastaallegri.jpg', 'documents/2_fotocopia_registro.jpg', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F2_fotocopia_registro.jpg?alt=media&token=97c5657b-11bd-4802-97eb-9ee031562641'),
(19, 2, 'fotocopia_cedula', 'harinapan3.png', 'documents/2_fotocopia_cedula.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F2_fotocopia_cedula.png?alt=media&token=fec2b849-7112-4d5e-87d0-88352ab7ee91'),
(20, 2, 'certificado_directorio', 'azucarmontalban.png', 'documents/2_certificado_directorio.png', 'https://firebasestorage.googleapis.com/v0/b/subvention10.appspot.com/o/documents%2F2_certificado_directorio.png?alt=media&token=0ea9e062-63d0-4a5f-8132-db75ff4d542e');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `rut` varchar(100) NOT NULL,
  `id_role` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  `date_token` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `theme` varchar(10) NOT NULL DEFAULT 'light',
  `created_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL,
  `url` text,
  `path` text,
  `amount_final` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `rut`, `id_role`, `name`, `last_name`, `phone`, `email`, `username`, `password`, `token`, `date_token`, `status`, `theme`, `created_at`, `update_at`, `url`, `path`, `amount_final`) VALUES
(1, '54546546-1', 1, 'john', 'testx', '52112', 'joespruebas@gmail.com', 'admin', '7c222fb2927d828af22f592134e8932480637c0d', NULL, NULL, 1, 'light', '2020-09-15 19:21:38', '2021-08-21 04:00:00', NULL, NULL, NULL),
(2, '21212-1', 2, 'mariax', '', '6656', 'maria@gmail.com', 'maria', '7c222fb2927d828af22f592134e8932480637c0d', NULL, NULL, 1, 'light', '2021-07-28 04:00:00', '2021-08-21 04:00:00', NULL, NULL, NULL),
(3, '879879-1', 2, 'test', '', '546546', 'test@gmail.com', 'test', 'a642a77abd7d4f51bf9226ceaf891fcbb5b299b8', NULL, NULL, 1, 'light', '2021-08-04 04:00:00', NULL, NULL, NULL, NULL),
(4, '12414-1', 3, 'awdawdf', 'awfafa', '444546', '123@gmail.com', 'abc', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', NULL, NULL, 1, 'light', NULL, NULL, NULL, NULL, NULL),
(5, '555555-4', 5, 'aldrha', 'petit', '5331943', 'aldrha@test.com', 'aldrha', '7c222fb2927d828af22f592134e8932480637c0d', NULL, NULL, 1, 'light', NULL, '2021-09-16 04:00:00', NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accountability`
--
ALTER TABLE `accountability`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `accountability_files`
--
ALTER TABLE `accountability_files`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `approval_subsidy`
--
ALTER TABLE `approval_subsidy`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `budge_information`
--
ALTER TABLE `budge_information`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `financing_details`
--
ALTER TABLE `financing_details`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `financing_files`
--
ALTER TABLE `financing_files`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `organitation`
--
ALTER TABLE `organitation`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subvention`
--
ALTER TABLE `subvention`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subvention_files`
--
ALTER TABLE `subvention_files`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accountability`
--
ALTER TABLE `accountability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `accountability_files`
--
ALTER TABLE `accountability_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `approval_subsidy`
--
ALTER TABLE `approval_subsidy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `budge_information`
--
ALTER TABLE `budge_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `financing_details`
--
ALTER TABLE `financing_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `financing_files`
--
ALTER TABLE `financing_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `organitation`
--
ALTER TABLE `organitation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `subvention`
--
ALTER TABLE `subvention`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `subvention_files`
--
ALTER TABLE `subvention_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
