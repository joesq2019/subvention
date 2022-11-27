-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 27-11-2022 a las 17:41:56
-- Versión del servidor: 8.0.30
-- Versión de PHP: 7.4.33

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
  `id` int NOT NULL,
  `id_subvention` int NOT NULL,
  `name_represent` varchar(255) NOT NULL,
  `number_invoices` varchar(100) NOT NULL,
  `amount_delivered` varchar(100) NOT NULL,
  `amount_yielded` varchar(100) NOT NULL,
  `amount_refunded` varchar(100) NOT NULL,
  `balance` int NOT NULL,
  `date_admission` varchar(100) NOT NULL,
  `status` varchar(50) DEFAULT '' COMMENT '0: Pendiente - 1: Aprobada - 2: observada - 3: Devolucion',
  `message` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accountability_files`
--

CREATE TABLE `accountability_files` (
  `id` int NOT NULL,
  `id_accountability` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `type` varchar(250) NOT NULL COMMENT '1: beneficiarios\r\n2: Documentacion\r\n3: Fotografia\r\n4: reintegro',
  `related` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `approval_subsidy`
--

CREATE TABLE `approval_subsidy` (
  `id` int NOT NULL,
  `id_subvention` int NOT NULL,
  `no_mayor_decree` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `agreement_date` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `no_payment_decree` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `payment_date` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `no_session` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `session_date` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `no_payment_installments` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `budge_information`
--

CREATE TABLE `budge_information` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `budget_certificate_number` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `emision_date` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `amount_available` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `name_document` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `path_document` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `url_document` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `data`
--

CREATE TABLE `data` (
  `id` int NOT NULL,
  `accumulated_amount` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `id_subvention` int NOT NULL,
  `details` varchar(255) NOT NULL,
  `unit_price` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `total_price` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `financing_files`
--

CREATE TABLE `financing_files` (
  `id` int NOT NULL,
  `id_subvention` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `id_accountability` int NOT NULL,
  `date` varchar(100) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `x` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `members`
--

CREATE TABLE `members` (
  `id` int NOT NULL,
  `type` varchar(50) NOT NULL,
  `id_subvention` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `rut` varchar(50) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organitation`
--

CREATE TABLE `organitation` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `rut` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `permissions` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `role`
--

INSERT INTO `role` (`id`, `name`, `permissions`) VALUES
(1, 'SUPERADMIN', '{\"user_list\": \"true\",\"user_add\": \"true\",\"user_edit\": \"true\",\"user_delete\": \"true\",\"roles_list\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\",\"subvention_list\": \"true\",\"subvention_add\": \"true\",\"subvention_edit\": \"true\",\"subvention_delete\": \"true\",\"accountability_list\": \"true\",\"accountability_add\": \"true\",\"accountability_edit\": \"true\",\"accountability_delete\": \"true\",\"accountability_view_list\": \"true\",\"accountability_view_add\": \"true\",\"accountability_view_delete\": \"true\",\"budget_information_list\": \"true\",\"budget_information_add\": \"true\",\"budget_information_edit\": \"true\",\"budget_information_delete\": \"true\",\"approval_subsidy_list\": \"true\",\"approval_subsidy_add\": \"true\",\"approval_subsidy_edit\": \"true\",\"approval_subsidy_delete\": \"true\"}'),
(2, 'ADMINISTRADOR', '{\"user_list\": \"true\",\"user_edit\": \"true\",\"user_delete\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\",\"subvention_list\": \"true\",\"subvention_add\": \"true\",\"subvention_edit\": \"true\",\"subvention_delete\": \"true\",\"accountability_list\": \"true\",\"accountability_add\": \"true\",\"accountability_edit\": \"true\",\"accountability_delete\": \"true\",\"approval_subsidy_list\": \"true\",\"approval_subsidy_add\": \"true\",\"approval_subsidy_edit\": \"true\",\"approval_subsidy_delete\": \"true\"}'),
(3, 'JEFATURA DIDECO', '{\"user_list\": \"true\",\"user_edit\": \"true\",\"user_delete\": \"true\",\"roles_list\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\"}'),
(4, 'DIDECO', '{\"user_list\": \"true\",\"user_add\": \"true\",\"roles_list\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\"}'),
(5, 'FINANZAS', '{\"subvention_list\": \"true\",\"subvention_add\": \"true\",\"subvention_edit\": \"true\",\"subvention_delete\": \"true\",\"budget_information_list\": \"true\",\"budget_information_add\": \"true\",\"budget_information_edit\": \"true\",\"budget_information_delete\": \"true\"}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `schedule`
--

CREATE TABLE `schedule` (
  `id` int NOT NULL,
  `id_subvention` int NOT NULL,
  `activities` varchar(255) NOT NULL,
  `month` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subvention`
--

CREATE TABLE `subvention` (
  `id` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_organitation` int NOT NULL,
  `year` varchar(50) NOT NULL,
  `name_proyect` varchar(200) DEFAULT NULL,
  `objetive_proyect` text,
  `quantity_purchases` int DEFAULT NULL,
  `amount_purchases` varchar(100) NOT NULL,
  `organization_contribution` varchar(100) NOT NULL,
  `amount_direct` varchar(100) DEFAULT NULL,
  `amount_indirect` varchar(100) DEFAULT NULL,
  `total_beneficiaries` varchar(100) DEFAULT NULL,
  `quantity_activities` int DEFAULT NULL,
  `status` int DEFAULT '1' COMMENT '0: Error\r\n1: En evaluacion\r\n2: Pre-aprobada\r\n3: Aprobada\r\n4: Rechazada',
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subvention_files`
--

CREATE TABLE `subvention_files` (
  `id` int NOT NULL,
  `id_subvention` int NOT NULL,
  `type` varchar(250) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `rut` varchar(100) NOT NULL,
  `id_role` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  `date_token` datetime DEFAULT NULL,
  `status` int DEFAULT '1',
  `theme` varchar(10) NOT NULL DEFAULT 'light',
  `created_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL,
  `url` text,
  `path` text,
  `amount_final` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `accountability_files`
--
ALTER TABLE `accountability_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `approval_subsidy`
--
ALTER TABLE `approval_subsidy`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `budge_information`
--
ALTER TABLE `budge_information`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `data`
--
ALTER TABLE `data`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `financing_details`
--
ALTER TABLE `financing_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `financing_files`
--
ALTER TABLE `financing_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `members`
--
ALTER TABLE `members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `organitation`
--
ALTER TABLE `organitation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subvention`
--
ALTER TABLE `subvention`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subvention_files`
--
ALTER TABLE `subvention_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
