-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 21-08-2021 a las 19:27:46
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `id_subvention` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1, 'SUPERADMIN', '{\"user_list\": \"true\",\"user_add\": \"true\",\"user_edit\": \"true\",\"user_delete\": \"true\",\"roles_list\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\"}'),
(2, 'ADMINISTRADOR', '{\"user_list\": \"true\",\"user_edit\": \"true\",\"user_delete\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\"}'),
(3, 'JEFATURA DIDECO', '{\"user_list\": \"true\",\"user_edit\": \"true\",\"user_delete\": \"true\",\"roles_list\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\"}'),
(4, 'DIDECO', '{\"user_list\": \"true\",\"user_add\": \"true\",\"roles_list\": \"true\",\"roles_edit\": \"true\",\"roles_delete\": \"true\",\"roles_add\": \"true\",\"roles_edit\": \"true\"}'),
(5, 'abc', '{\"user_add\": \"true\",\"roles_edit\": \"true\",\"roles_editx\": \"true\"}'),
(6, 'avawf', '{\"user_list\": \"true\"}');

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subvention`
--

CREATE TABLE `subvention` (
  `id` int(11) NOT NULL,
  `year` varchar(50) NOT NULL,
  `name_organitation` varchar(255) NOT NULL,
  `rut_organitation` varchar(100) NOT NULL,
  `phone_organitation` varchar(100) DEFAULT NULL,
  `email_organitation` varchar(100) DEFAULT NULL,
  `amount_purchases` varchar(100) NOT NULL,
  `organization_contribution` varchar(100) NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subvention_files`
--

CREATE TABLE `subvention_files` (
  `id` int(11) NOT NULL,
  `id_subvention` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `path` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `rut`, `id_role`, `name`, `last_name`, `phone`, `email`, `username`, `password`, `token`, `date_token`, `status`, `theme`, `created_at`, `update_at`, `url`, `path`) VALUES
(1, '54546546-1', 1, 'john', 'testx', '52112', 'john@gmail.com', 'admin', '7c222fb2927d828af22f592134e8932480637c0d', NULL, NULL, 1, 'light', '2020-09-15 19:21:38', '2021-08-21 04:00:00', NULL, NULL),
(2, '21212-1', 2, 'mariax', '', '6656', 'maria@gmail.com', 'maria', '7c222fb2927d828af22f592134e8932480637c0d', NULL, NULL, 1, 'light', '2021-07-28 04:00:00', '2021-08-21 04:00:00', NULL, NULL),
(3, '879879-1', 2, 'test', '', '546546', 'test@gmail.com', 'test', 'a642a77abd7d4f51bf9226ceaf891fcbb5b299b8', NULL, NULL, 1, 'light', '2021-08-04 04:00:00', NULL, NULL, NULL),
(4, '12414-1', 3, 'awdawdf', 'awfafa', '444546', '123@gmail.com', 'abc', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', NULL, NULL, 1, 'light', NULL, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

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
-- Indices de la tabla `members`
--
ALTER TABLE `members`
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
-- AUTO_INCREMENT de la tabla `financing_details`
--
ALTER TABLE `financing_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `financing_files`
--
ALTER TABLE `financing_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subvention`
--
ALTER TABLE `subvention`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subvention_files`
--
ALTER TABLE `subvention_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
