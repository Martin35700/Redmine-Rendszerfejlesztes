-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2024. Ápr 19. 10:23
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `redmine`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`) VALUES
(1, 'Admin Isztrátor', 'admin@admin.hu', 'b7757eeba8adb25e0145e3300ba9d8e09978ee5e90825e7b8776104dbabfcd3d');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `developers`
--

CREATE TABLE `developers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `developers`
--

INSERT INTO `developers` (`id`, `name`, `email`) VALUES
(1, 'Harnos Adrián', 'harnos.adrian@gmail.com'),
(2, 'Dömök Martin', 'martin.domok2002@gmail.com');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `managers`
--

CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `managers`
--

INSERT INTO `managers` (`id`, `name`, `email`, `password`) VALUES
(1, 'Heller Benedek', 'heller.benedek@gmail.com', '6bbaaeb9febabd5f14ee0b8f769ab069a9f4eecb23db563fd3baa07611b4399a'),
(2, 'Ferencz Kristóf', 'ferencz.kristof@gmail.com', 'e59ccefccb0aba4ded85708549bede11fd5cc22ec47c065a914eb26deb4c9fa5');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `type_id`) VALUES
(1, 'Platformer x', 'The best platformer game ever', 2),
(2, 'Vanenet.hu', 'Checks if you have internet connection', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `project_developers`
--

CREATE TABLE `project_developers` (
  `id` int(11) NOT NULL,
  `developer_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `project_developers`
--

INSERT INTO `project_developers` (`id`, `developer_id`, `project_id`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `project_types`
--

CREATE TABLE `project_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `project_types`
--

INSERT INTO `project_types` (`id`, `name`) VALUES
(1, 'Web Development'),
(2, 'Game Development');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `deadline` datetime NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `description`, `deadline`, `project_id`, `user_id`) VALUES
(1, 'Test automatization', 'auto tests', '2024-04-25 00:00:00', 1, 1),
(2, 'Backend', 'Backend with php', '2024-04-30 00:00:00', 2, 1),
(3, 'Frontend', 'Frontend for the website', '2024-05-12 00:00:00', 2, 2);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `developers`
--
ALTER TABLE `developers`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5C93B3A4C54C8C93` (`type_id`);

--
-- A tábla indexei `project_developers`
--
ALTER TABLE `project_developers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_131735E864DD9267` (`developer_id`),
  ADD KEY `IDX_131735E8166D1F9C` (`project_id`);

--
-- A tábla indexei `project_types`
--
ALTER TABLE `project_types`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_50586597166D1F9C` (`project_id`),
  ADD KEY `IDX_50586597A76ED395` (`user_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `developers`
--
ALTER TABLE `developers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `managers`
--
ALTER TABLE `managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `project_developers`
--
ALTER TABLE `project_developers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `project_types`
--
ALTER TABLE `project_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `FK_5C93B3A4C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `project_types` (`id`);

--
-- Megkötések a táblához `project_developers`
--
ALTER TABLE `project_developers`
  ADD CONSTRAINT `FK_131735E8166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `FK_131735E864DD9267` FOREIGN KEY (`developer_id`) REFERENCES `developers` (`id`);

--
-- Megkötések a táblához `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `FK_50586597166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `FK_50586597A76ED395` FOREIGN KEY (`user_id`) REFERENCES `managers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
