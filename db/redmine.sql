-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2024. Ápr 11. 15:02
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
-- Tábla szerkezet ehhez a táblához `developers`
--

CREATE TABLE `developers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `developers`
--

INSERT INTO `developers` (`id`, `name`, `email`) VALUES
(1, 'Harnos Adrián', 'adrian@harnos.hu'),
(2, 'Dömök Martin', 'martin.domok2002@gmail.com');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `managers`
--

CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `managers`
--

INSERT INTO `managers` (`id`, `name`, `email`, `password`) VALUES
(1, 'Heller Benedek', 'heller.benedek@gmail.com', 'beni123'),
(2, 'Ferencz Kristóf', 'ferencz.kristof@gmail.com', 'kristof123');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `projects`
--

INSERT INTO `projects` (`id`, `name`, `type_id`, `description`) VALUES
(1, 'Platformer x', 2, 'The best platformer game ever'),
(2, 'Vanenet.hu', 1, 'Checks if you have internet connection');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `project_developers`
--

CREATE TABLE `project_developers` (
  `id` int(11) NOT NULL,
  `developer_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `project_developers`
--

INSERT INTO `project_developers` (`id`, `developer_id`, `project_id`) VALUES
(6, 1, 1),
(9, 2, 2);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `project_types`
--

CREATE TABLE `project_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

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
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `deadline` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `description`, `project_id`, `user_id`, `deadline`) VALUES
(2, 'Test automatization', 'auto tests', 1, 1, '2024-04-06 00:00:00'),
(3, 'Backend', 'Backend with php', 2, 1, '2024-04-15 00:00:00'),
(6, 'Frontend', 'Frontend for the website', 2, 2, '2024-04-12 00:00:00');

--
-- Indexek a kiírt táblákhoz
--

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
  ADD KEY `type_id` (`type_id`);

--
-- A tábla indexei `project_developers`
--
ALTER TABLE `project_developers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `developer_id` (`developer_id`);

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
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT a táblához `project_types`
--
ALTER TABLE `project_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `project_types` (`id`);

--
-- Megkötések a táblához `project_developers`
--
ALTER TABLE `project_developers`
  ADD CONSTRAINT `project_developers_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `project_developers_ibfk_2` FOREIGN KEY (`developer_id`) REFERENCES `developers` (`id`);

--
-- Megkötések a táblához `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `managers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
