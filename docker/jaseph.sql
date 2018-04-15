--
-- Datenbank: `jaseph`
--

--
-- Tabellenstruktur für Tabelle `draft`
--

CREATE TABLE `draft` (
  `DRAFTID` int(11) NOT NULL,
  `USERID` int(11) NOT NULL,
  `TITLE` varchar(200) NOT NULL,
  `CONTENT` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Tabellenstruktur für Tabelle `images`
--

CREATE TABLE `images` (
  `PICID` int(11) NOT NULL,
  `USERID` int(11) NOT NULL,
  `PATH` varchar(255) DEFAULT NULL,
  `TEMP_PATH` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tabellenstruktur für Tabelle `post`
--

CREATE TABLE `post` (
  `POSTID` int(11) NOT NULL,
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `USERID` int(11) NOT NULL,
  `TITLE` varchar(200) NOT NULL,
  `CONTENT` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Tabellenstruktur für Tabelle `saved`
--

CREATE TABLE `saved` (
  `USERID` int(11) NOT NULL,
  `POSTID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `USERID` int(11) NOT NULL,
  `USERNAME` varchar(20) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `PASSWORD` varchar(255) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `EMAIL` varchar(100) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `REGISTERED` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LASTSEEN` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LASTCHANGED` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IDENTIFIER` varchar(255) DEFAULT NULL,
  `TOKEN` varchar(255) DEFAULT NULL,
  `ROLE` varchar(255) NOT NULL DEFAULT 'COMMON',
  `VISIBILITY` varchar(30) NOT NULL DEFAULT 'VISIBLE'
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `draft`
--
ALTER TABLE `draft`
  ADD PRIMARY KEY (`DRAFTID`);

--
-- Indizes für die Tabelle `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`PICID`);

--
-- Indizes für die Tabelle `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`POSTID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USERID`);
