/*LOCAL USE
DROP DATABASE Iproject
GO
CREATE DATABASE Iproject
GO
USE Iproject
GO
*/

DROP TABLE PostVerificatie
GO
Drop TABLE Verkoper
GO
DROP TABLE Beheerder
GO
DROP TABLE Bestand
GO
DROP TABLE Bod
GO
DROP TABLE Feedback
GO
DROP TABLE VoorwerpInRubriek
GO
DROP TABLE Rubriek
GO
DROP TABLE Voorwerp
GO
DROP TABLE Gebruikerstelefoon
GO
DROP TABLE Gebruiker
GO
DROP TABLE Vraag
GO
DROP TABLE Land
GO
DROP TABLE Betalingswijzen
GO
DROP FUNCTION dbo.HoogsteBod
GO
DROP FUNCTION dbo.HoogsteBieder
GO

/*
======================================================================
	Table: Rubriek
	Status: Done
======================================================================
*/

CREATE TABLE Rubriek
(
	RubriekNummer INT NOT NULL, --Gekregen rubrieken gaan niet boven 200000 dus kan geen smallint dus moet int. miljoen is voldoende
	RubriekNaam VARCHAR(40) NOT NULL, --Gekregen rubrieknamen gaan niet boven 40 karakters dus zal wel lukken.
	VorigeRubriek INT NULL, --Zelfde als Rubrieknummer.
	Volgnummer TINYINT NOT NULL DEFAULT 1, --0 tot 255, zou genoeg moeten zijn. hoog nummer betekent populairder. Default op 1 zodat heel specifieke rubrieken toch nog onder anderen geplaatst kunnen worden. (rubriek "Overigen")

	CONSTRAINT PK_Rubriek_Rubrieknummer PRIMARY KEY (Rubrieknummer),
	CONSTRAINT FK_Rubriek_Rubrieknummer_Rubriek_Rubriek FOREIGN KEY (VorigeRubriek) REFERENCES Rubriek (Rubrieknummer),
	/*
	CONSTRAINT CHK_Rubriek_RubriekNaam CHECK (LEN(RTRIM(LTRIM(RubriekNaam))) >= 4), --Min 4 Characters, geen spaties.
	*/
)
GO

/*
======================================================================
	Table: Betalingswijzen
	Status: Done
======================================================================
*/

CREATE TABLE Betalingswijzen
(
	Betalingswijze VARCHAR(30) NOT NULL,

	CONSTRAINT PK_Betalingswijzen_Betalingswijze PRIMARY KEY (Betalingswijze),
	CONSTRAINT CHK_Betalingswijzen_Betalingswijze CHECK (LEN(RTRIM(LTRIM(Betalingswijze))) >= 3), --Min 3 karakters, geen spaties.
)
GO

/*
======================================================================
	Table: Land
	Status: Questions for product owner
======================================================================
*/

CREATE TABLE Land
(
	GBA_CODE CHAR(4) NOT NULL,
	Landnaam VARCHAR(40) NOT NULL,
	Begindatum DATE NULL,
	Einddatum DATE NULL,
	EER_Lid BIT NOT NULL DEFAULT 0,
	CONSTRAINT PK_Land_Landnaam PRIMARY KEY (Landnaam),
	CONSTRAINT UQ_Land_GBA_CODE UNIQUE (GBA_CODE),
	CONSTRAINT CHK_CODE CHECK ( LEN(GBA_CODE) = 4 ),
	CONSTRAINT CHK_DATUM CHECK ( Begindatum < Einddatum )
)
GO

/*
======================================================================
	Table: Vraag
	Status: Done
======================================================================
*/

CREATE TABLE Vraag
(
	Vraagnummer TINYINT NOT NULL, --maximaal 255 vragen.
	TekstVraag VARCHAR(70) NOT NULL, --Niet te lange vragen.

	CONSTRAINT PK_Vraag_Vraagnummer PRIMARY KEY (Vraagnummer)
)
GO

/*
======================================================================
	Table: Gebruiker
	Status: More constraints needed
======================================================================
*/

CREATE TABLE Gebruiker
(
	Gebruikersnaam VARCHAR(40) NOT NULL,
	Voornaam VARCHAR(30) NOT NULL,
	Achternaam VARCHAR(30) NOT NULL,
	Adresregel1 VARCHAR(50) NOT NULL,
	Adresregel2 VARCHAR(50) NULL,
	Postcode VARCHAR (7) NOT NULL,
	Plaatsnaam VARCHAR(85) NOT NULL,
	Land VARCHAR(40) NOT NULL,
	Datum DATE NOT NULL,
	Mailbox VARCHAR(50) NOT NULL, --Normaal e-mailadres heeft niet meer dan 50 karakters.
	Wachtwoord VARCHAR(255) NOT NULL,
	Vraagnummer TINYINT NOT NULL, --Zie tabel Vraag(vraagnummer)
	Antwoordtekst VARCHAR(255) NOT NULL,
	Verkoper BIT NOT NULL DEFAULT 0, --Bij registratie is een gebruiker nog geen verkoper.

	CONSTRAINT PK_Gebruiker_Gebruikersnaam PRIMARY KEY (Gebruikersnaam),
	CONSTRAINT FK_Gebruiker_Vraagnummer_Vraag_Vraagnummer FOREIGN KEY (Vraagnummer) REFERENCES Vraag(Vraagnummer),
	CONSTRAINT FK_Gebruiker_Land_Land_Landnaam FOREIGN KEY (Land) REFERENCES Land(Landnaam),
	CONSTRAINT CHK_Gebruiker_Gebruikersnaam CHECK (LEN(RTRIM(LTRIM(Gebruikersnaam))) >= 3), --Min 4 Characters, geen spaties.
)
GO

/*
======================================================================
	Table: Gebruikerstelefoon
	Status: Done. Waarom niet bij de gebruiker zelf bijvoegen?
======================================================================
*/

CREATE TABLE Gebruikerstelefoon
(
	Volgnr INT NOT NULL UNIQUE,
	Gebruikersnaam VARCHAR(40) NOT NULL UNIQUE,
	Telefoonnummer VARCHAR(11) NOT NULL UNIQUE,
	
	CONSTRAINT PK_Gebruikerstelefoon_Volgnr_Gebruikersnaam PRIMARY KEY (volgnr, gebruikersnaam),
	CONSTRAINT FK_Gebruiker_gebruikersnaam_Gebruikerstelefoon FOREIGN KEY (gebruikersnaam) REFERENCES Gebruiker (gebruikersnaam)
)
GO

/*
======================================================================
	Table: Beheerder
	Status: Done. constraints op minimum lengte wachtwoord?
======================================================================
*/

CREATE TABLE Beheerder
(
	Gebruikersnaam VARCHAR(40) NOT NULL, --Zie tabel Gebruiker(Gebruikersnaam).
	BeheerWachtwoord VARCHAR(20) NOT NULL

	CONSTRAINT PK_Beheerder_Gebruikersnaam PRIMARY KEY (Gebruikersnaam)
	CONSTRAINT FK_Gebruiker_gebruikersnaam_Gebruiker FOREIGN KEY (Gebruikersnaam) REFERENCES Gebruiker(Gebruikersnaam)
)
GO

/*
======================================================================
	Table: Voorwerp
	Status: Done.
======================================================================
*/

CREATE TABLE Voorwerp
(
	Voorwerpnummer BIGINT IDENTITY NOT NULL, --App c genereert zelf een nummer.
	Titel VARCHAR(100) NOT NULL, --Minder dan marktplaats: 60 Moet 45 worden.
	Beschrijving VARCHAR(5000) NOT NULL, --Moet 800 worden
	Startprijs NUMERIC(18,2) NOT NULL, --Bedragen tot 100 miljoen.
	Betalingswijze VARCHAR(30) NOT NULL, --Zie tabel Betalingswijzen(Betalingswijze)
	Betalingsinstructie VARCHAR(400) NULL, --Helft van een beschrijving zou genoeg moeten zijn.
	Plaatsnaam VARCHAR(85) NOT NULL,
	Land VARCHAR(40) NOT NULL, --Zie tabel Land(Landnaam)
	Looptijd TINYINT NOT NULL Default 7, --Casus App C
	BeginMoment DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	EindMoment AS DATEADD(DAY, Looptijd, BeginMoment),
	VeilingGesloten AS CASE WHEN GETDATE() > DATEADD(DAY, Looptijd, BeginMoment)
					THEN 1
					ELSE 0 END,
	Verzendkosten NUMERIC(7,2) NULL, --Tot 99999 euro verzendkosten (Grote dingen, schepen? auto's? tanks?)
	Verzendinstructies VARCHAR(400) NULL, --Helft van een beschrijving zou genoeg moeten zijn.
	Verkoper VARCHAR(40) NOT NULL, --Zie tabel Gebruiker(Gebruikersnaam)
	Thumbnail VARCHAR(50) NOT NULL,

	CONSTRAINT PK_Voorwerp_Voorwerpnummer PRIMARY KEY (Voorwerpnummer),
	CONSTRAINT FK_Voorwerp_Land_Land_Landnaam FOREIGN KEY (Land) REFERENCES Land(Landnaam),
	CONSTRAINT FK_Voorwerp_Verkoper_Gebruiker_Gebruikersnaam FOREIGN KEY (Verkoper) REFERENCES Gebruiker(Gebruikersnaam),
	Constraint FK_Voorwerp_Betalingswijze_Betalingswijzen_BetalingswijzeNummer FOREIGN KEY (Betalingswijze) REFERENCES Betalingswijzen(Betalingswijze),

	CONSTRAINT CHK_Voorwerp_Titel CHECK (LEN(RTRIM(LTRIM(Titel))) >= 4), --Min 4 Characters, geen spaties.
	CONSTRAINT CHK_Voorwerp_Startprijs CHECK (Startprijs >= 1.00), --App B: Minimale verhoging.
	CONSTRAINT CHK_Voorwerp_Looptijd CHECK (Looptijd IN (1,3,5,7,10))
)
GO

/*
======================================================================
	Table: Bestand
	Status: Done.
======================================================================
*/

CREATE TABLE Bestand
(
	Filenaam VARCHAR(50) NOT NULL, --Bestand namen gaan waarschijnlijk niet boven 50 karakters. 
	Voorwerp BIGINT NOT NULL, --Zie tabel Voorwerp(Voorwerpnummer).

	CONSTRAINT PK_Bestand_Filenaam PRIMARY KEY(Filenaam),
	CONSTRAINT FK_Bestand_Voorwerp_Voorwerp_Voorwerpnummer FOREIGN KEY (Voorwerp) REFERENCES Voorwerp(Voorwerpnummer)
)
GO

/*
======================================================================
	Table: Bod
	Status: Done.
======================================================================
*/

CREATE TABLE Bod
(
	Bodbedrag NUMERIC(18,2) NOT NULL, --Zie tabel Voorwerp(Startprijs).
	Voorwerp BIGINT NOT NULL, --Zie tabel Voorwerp(Voorwerpnummer).
	Gebruikersnaam VARCHAR(40) NOT NULL, --Zie tabel Gebruiker(Gebruikersnaam).
	Tijd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT PK_Bod_Bodbedrag_Voorwerp PRIMARY KEY(Bodbedrag, Voorwerp),
	CONSTRAINT FK_Bod_Voorwerp_Voorwerp_Voorwerpnummer FOREIGN KEY (Voorwerp) REFERENCES Voorwerp(Voorwerpnummer),
	CONSTRAINT FK_Bod_Gebruikersnaam_Gebruiker_Gebruikersnaam FOREIGN KEY (Gebruikersnaam) REFERENCES Gebruiker(gebruikersnaam)
)
GO

/*
======================================================================
	Table: Feedback
	Status: Questions for product owner.
======================================================================
*/

CREATE TABLE Feedback
(
	Voorwerp BIGINT NOT NULL, --Zie tabel Voorwerp(Voorwerpnummer).
	SoortGebruiker BIT DEFAULT 1 NOT NULL,
	Feedbacksoortnaam BIT DEFAULT 1 NOT NULL,
	KoperOfVerkoper BIT DEFAULT 1 NOT NULL,
	Tijd DATETIME NOT NULL,
	Commentaar VARCHAR(100) NOT NULL,
	CONSTRAINT PK_Feedback_Voorwerp_SoortGebruiker PRIMARY KEY(Voorwerp, SoortGebruiker),
	CONSTRAINT FK_Feedback_Voorwerp_Voorwerp_Voorwerpnummer FOREIGN KEY (Voorwerp) REFERENCES Voorwerp(Voorwerpnummer)
)
GO

/*
======================================================================
	Table: VoorwerpInRubriek
	Status: Done.
======================================================================
*/

CREATE TABLE VoorwerpInRubriek
(
	Voorwerp BIGINT NOT NULL, --Zie tabel Voorwerp(Voorwerpnummer).
	RubriekOpLaagsteNiveau INT NOT NULL, --Zie tabel Rubriek(Rubrieknummer).

	CONSTRAINT PK_VoorwerpInRubriek_Voorwerp_RubriekOpLaagsteNiveau PRIMARY KEY(Voorwerp, RubriekOpLaagsteNiveau),
	CONSTRAINT FK_VoorwerpInRubriek_Voorwerp_Voorwerp_Voorwerpnummer FOREIGN KEY (Voorwerp) REFERENCES Voorwerp(Voorwerpnummer)
)
GO

/*
======================================================================
	Table: Verkoper
	Status: Done.
======================================================================
*/

CREATE TABLE Verkoper 
(
	Gebruikersnaam VARCHAR(40) NOT NULL,
	Banknaam VARCHAR(20) NULL,
	Rekeningnummer VARCHAR(30) NULL,
	Controleoptienaam VARCHAR(15) NOT NULL,
	Creditcardnummer VARCHAR(20) NULL

	CONSTRAINT PK_Verkoper PRIMARY KEY (Gebruikersnaam)
	CONSTRAINT FK_Verkoper_Gebruikersnaam FOREIGN KEY (Gebruikersnaam) REFERENCES Gebruiker(Gebruikersnaam)
)

CREATE TABLE PostVerificatie
(
	Gebruikersnaam VARCHAR(40) NOT NULL,
	VerificatieCode VARCHAR(40) NOT NULL,
	BeginMoment DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	EindMoment AS DATEADD(DAY, 7, BeginMoment),
	Geldig AS CASE WHEN GETDATE() > DATEADD(DAY, 7, BeginMoment)
					THEN 1
					ELSE 0 END,
	CONSTRAINT PK_PostVerificatie PRIMARY KEY (Gebruikersnaam, VerificatieCode),
	CONSTRAINT FK_PostVerificatie_Gebruikersnaam FOREIGN KEY (Gebruikersnaam) REFERENCES Gebruiker(Gebruikersnaam)
)

GO

CREATE FUNCTION dbo.HoogsteBod(@Voorwerpnummer BIGINT)
RETURNS NUMERIC(18,2)
AS
BEGIN
RETURN
	(
		Select TOP 1 Bodbedrag
		FROM Bod 
		WHERE Bod.Voorwerp = @Voorwerpnummer
		ORDER BY Bodbedrag DESC
	)
END

GO

CREATE FUNCTION dbo.HoogsteBieder (@Voorwerpnummer BIGINT)
RETURNS VARCHAR(40)
AS 
BEGIN
RETURN 
	(
		SELECT TOP 1 Gebruikersnaam
		FROM Bod
		WHERE Bod.Voorwerp = @Voorwerpnummer
		ORDER BY Bodbedrag DESC
	)
END
GO

ALTER TABLE Voorwerp
	ADD 
		Verkoopprijs AS CASE WHEN (CURRENT_TIMESTAMP > DATEADD(DAY, looptijd, BeginMoment)) then dbo.HoogsteBod(Voorwerpnummer) end,
		Koper AS CASE WHEN	(CURRENT_TIMESTAMP > DATEADD(DAY, looptijd, BeginMoment)) then dbo.HoogsteBieder(Voorwerpnummer) end
GO