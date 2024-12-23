-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Dic 23, 2024 alle 16:48
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carinci_635710`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `accessi`
--

CREATE TABLE `accessi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pt_id` int(11) DEFAULT NULL,
  `user_tipo` enum('utente','personal_trainer') NOT NULL,
  `timestamp_accesso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `accessi`
--

INSERT INTO `accessi` (`id`, `user_id`, `pt_id`, `user_tipo`, `timestamp_accesso`) VALUES
(19, NULL, 6, 'personal_trainer', '2024-12-23 11:45:33'),
(20, 18, NULL, 'utente', '2024-12-23 11:46:16'),
(21, 18, NULL, 'utente', '2024-12-23 11:53:34'),
(22, NULL, 6, 'personal_trainer', '2024-12-23 11:58:13'),
(23, 18, NULL, 'utente', '2024-12-23 12:33:13'),
(24, NULL, 6, 'personal_trainer', '2024-12-23 12:34:28'),
(25, 18, NULL, 'utente', '2024-12-23 12:34:50'),
(26, NULL, 6, 'personal_trainer', '2024-12-23 12:35:15'),
(27, 18, NULL, 'utente', '2024-12-23 12:38:59'),
(28, NULL, 6, 'personal_trainer', '2024-12-23 12:40:54'),
(29, NULL, 6, 'personal_trainer', '2024-12-23 12:47:46'),
(30, 18, NULL, 'utente', '2024-12-23 12:48:44'),
(31, NULL, 6, 'personal_trainer', '2024-12-23 13:05:52'),
(32, NULL, 6, 'personal_trainer', '2024-12-23 13:09:05'),
(33, 18, NULL, 'utente', '2024-12-23 13:19:31');

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `timestamp_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `nome`, `timestamp_login`) VALUES
(1, 'admin@pulsecoach.it', '$2y$10$EdtBs5VaRh37Q/hGeWUtNuQlXpS7Jh1ex3.5fH4zBdjKaQLKhnqVW', 'Admin1', '2024-12-23 13:05:19');

-- --------------------------------------------------------

--
-- Struttura della tabella `appuntamento`
--

CREATE TABLE `appuntamento` (
  `id` int(11) NOT NULL,
  `utente_id` int(11) DEFAULT NULL,
  `personal_trainer_id` int(11) DEFAULT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `stato` enum('prenotato','confermato','cancellato') DEFAULT 'prenotato'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `appuntamento`
--

INSERT INTO `appuntamento` (`id`, `utente_id`, `personal_trainer_id`, `data`, `ora`, `stato`) VALUES
(11, 18, 6, '2024-12-23', '14:00:00', 'confermato');

-- --------------------------------------------------------

--
-- Struttura della tabella `obiettivi`
--

CREATE TABLE `obiettivi` (
  `id` int(11) NOT NULL,
  `appuntamento_id` int(11) NOT NULL,
  `tipo_obiettivo` varchar(50) NOT NULL,
  `obiettivo` varchar(255) NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `ripetizioni` int(11) DEFAULT NULL,
  `serie` int(11) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `progresso1` int(11) DEFAULT NULL,
  `progresso2` int(11) DEFAULT NULL,
  `progresso3` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `obiettivi`
--

INSERT INTO `obiettivi` (`id`, `appuntamento_id`, `tipo_obiettivo`, `obiettivo`, `descrizione`, `ripetizioni`, `serie`, `peso`, `progresso1`, `progresso2`, `progresso3`) VALUES
(31, 11, 'quantitativo', 'Panca Piana', 'Usare il bilanciere o in alternativa fare la Chest Press', 8, 3, 35.00, 6, 2, 30.00),
(33, 11, 'continuativo', 'Bere 2 litri d\'acqua al giorno per 3 giorni', 'Segnare quanti giorni si è rispettato questo obiettivo giornaliero', NULL, NULL, NULL, 3, 3, -1.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `personal_trainer`
--

CREATE TABLE `personal_trainer` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `data_nascita` date NOT NULL,
  `genere` char(1) NOT NULL,
  `cellulare` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `curriculum` varchar(255) NOT NULL,
  `timestamp_login` datetime DEFAULT NULL,
  `timestamp_logout` datetime DEFAULT NULL,
  `timestamp_creazione` datetime DEFAULT current_timestamp(),
  `timestamp_aggiornamento` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `risposta1` varchar(255) NOT NULL,
  `risposta2` varchar(255) NOT NULL,
  `attivo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `personal_trainer`
--

INSERT INTO `personal_trainer` (`id`, `nome`, `cognome`, `email`, `data_nascita`, `genere`, `cellulare`, `password`, `curriculum`, `timestamp_login`, `timestamp_logout`, `timestamp_creazione`, `timestamp_aggiornamento`, `risposta1`, `risposta2`, `attivo`) VALUES
(6, 'Luigi', 'Verdi', 'luigi.verdi@gmail.com', '2000-12-10', 'M', '1231231234', '$2y$10$Dg/L2SNsbWKP9p5s8AFwyurympzyRJHR/84dld/0n34M6yYcs.aZC', 'curriculum1.pdf', '2024-12-23 13:09:05', '2024-12-23 13:19:04', '2024-12-23 11:44:27', '2024-12-23 13:19:04', '$2y$10$ungBO5NziEtNSx9UPyTBmO0EgMVNwIFBUNjtKQsKtu53MNz4OfePa', '$2y$10$0/WLVAtGkMInCLDB8JAPL.TSVsLJfTwXA4fBMBTRfyudrZe/ppooi', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `data_nascita` date DEFAULT NULL,
  `genere` char(1) DEFAULT NULL,
  `altezza` int(11) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `informazioni_mediche` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `risposta1` varchar(255) NOT NULL,
  `risposta2` varchar(255) NOT NULL,
  `certificato` varchar(255) NOT NULL,
  `data_emissione_certificato` date NOT NULL,
  `timestamp_login` datetime DEFAULT NULL,
  `timestamp_logout` datetime DEFAULT NULL,
  `timestamp_creazione` datetime DEFAULT current_timestamp(),
  `timestamp_aggiornamento` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `nome`, `cognome`, `email`, `password`, `data_nascita`, `genere`, `altezza`, `peso`, `informazioni_mediche`, `note`, `risposta1`, `risposta2`, `certificato`, `data_emissione_certificato`, `timestamp_login`, `timestamp_logout`, `timestamp_creazione`, `timestamp_aggiornamento`) VALUES
(18, 'Mario', 'Rossi', 'mario.rossi@gmail.com', '$2y$10$66.MVMKJz/LLeGPsHp89LOTzkVQD1MlSDzhZdUyZsEsHxVvMUwxpS', '2002-10-20', 'M', 183, 75.00, '', '', '$2y$10$yeffto2JrSbMabF.aCtuIO6NGKti4S0wquZM9dSUO9q10nmEgQPi.', '$2y$10$kpj57wtCq6ILPZF3Xb7IpO1pP56ugN1RMU/luqPE7Kq5kaj3Blh.y', 'certificato1.pdf', '2024-01-20', '2024-12-23 13:19:31', '2024-12-23 16:46:09', '2024-12-23 11:41:04', '2024-12-23 16:46:09');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accessi`
--
ALTER TABLE `accessi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pt_id` (`pt_id`);

--
-- Indici per le tabelle `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `appuntamento`
--
ALTER TABLE `appuntamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utente_id` (`utente_id`),
  ADD KEY `personal_trainer_id` (`personal_trainer_id`);

--
-- Indici per le tabelle `obiettivi`
--
ALTER TABLE `obiettivi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appuntamento_id` (`appuntamento_id`);

--
-- Indici per le tabelle `personal_trainer`
--
ALTER TABLE `personal_trainer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `accessi`
--
ALTER TABLE `accessi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT per la tabella `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `appuntamento`
--
ALTER TABLE `appuntamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `obiettivi`
--
ALTER TABLE `obiettivi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT per la tabella `personal_trainer`
--
ALTER TABLE `personal_trainer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `accessi`
--
ALTER TABLE `accessi`
  ADD CONSTRAINT `accessi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utente` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accessi_ibfk_2` FOREIGN KEY (`pt_id`) REFERENCES `personal_trainer` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `appuntamento`
--
ALTER TABLE `appuntamento`
  ADD CONSTRAINT `appuntamento_ibfk_1` FOREIGN KEY (`utente_id`) REFERENCES `utente` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appuntamento_ibfk_2` FOREIGN KEY (`personal_trainer_id`) REFERENCES `personal_trainer` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `obiettivi`
--
ALTER TABLE `obiettivi`
  ADD CONSTRAINT `obiettivi_ibfk_1` FOREIGN KEY (`appuntamento_id`) REFERENCES `appuntamento` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
