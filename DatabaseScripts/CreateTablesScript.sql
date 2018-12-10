/*LOCAL USE
DROP DATABASE Iproject
GO
CREATE DATABASE Iproject
GO
USE Iproject
GO
*/

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
DROP TABLE emailconfiguratie
GO
DROP TABLE Land
GO
DROP TABLE Betalingswijzen
GO



CREATE TABLE Rubriek
(
	RubriekNummer INT NOT NULL, --Gekregen rubrieken gaan niet boven 200000 dus kan geen smallint dus moet int. miljoen is voldoende
	RubriekNaam VARCHAR(40) NOT NULL, --Gekregen rubrieknamen gaan niet boven 40 karakters dus zal wel lukken.
	VorigeRubriek INT NULL, --Zelfde als Rubrieknummer.
	Volgnummer TINYINT NOT NULL DEFAULT 1, --0 tot 255, zou genoeg moeten zijn. hoog nummer betekent populairder. Default op 1 zodat heel specifieke rubrieken toch nog onder anderen geplaatst kunnen worden. (rubriek "Overigen")

	CONSTRAINT PK_Rubriek PRIMARY KEY (Rubrieknummer),
	CONSTRAINT FK_Rubriek_Rubrieknummer_Rubriek_Rubriek FOREIGN KEY (VorigeRubriek) REFERENCES Rubriek (Rubrieknummer),
	/*
	CONSTRAINT CHK_Rubriek_RubriekNaam CHECK (LEN(RTRIM(LTRIM(RubriekNaam))) >= 4), --Min 4 Characters, geen spaties.
	*/
)
GO

CREATE TABLE Betalingswijzen
(
	BetalingswijzeNummer TINYINT IDENTITY NOT NULL, --Tot 255 soorten betalingswijzen.
	Betalingswijze VARCHAR(30) NOT NULL,

	CONSTRAINT PK_Betalingswijzen PRIMARY KEY (Betalingswijzenummer),
	CONSTRAINT CHK_Betalingswijzen_Betalingswijze CHECK (LEN(RTRIM(LTRIM(Betalingswijze))) >= 3), --Min 3 karakters, geen spaties.
)
GO

CREATE TABLE Voorwerp
(
	Voorwerpnummer INT IDENTITY NOT NULL, --App c genereert zelf een nummer.
	Titel VARCHAR(45) NOT NULL, --Minder dan marktplaats: 60
	Beschrijving VARCHAR(800) NOT NULL,
	Startprijs NUMERIC(18,2) NOT NULL, --Bedragen tot 100 miljoen.
	Betalingswijze VARCHAR(20) NOT NULL,
	Betalingsinstructie VARCHAR(400) NULL, --Helft van een beschrijving zou genoeg moeten zijn.
	Plaatsnaam VARCHAR(85) NOT NULL,
	Land VARCHAR(50) NOT NULL,
	Looptijd TINYINT NOT NULL Default 7, --Casus App C
	BeginMoment DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	EindMoment AS DATEADD(DAY, Looptijd, BeginMoment),
	VeilingGesloten AS CASE WHEN GETDATE() > DATEADD(DAY, Looptijd, BeginMoment)
					THEN 1
					ELSE 0 END,
	Verkoopprijs NUMERIC(18,2) NULL,--AS dbo.fnHoogsteBod(Voorwerpnummer),
	Verzendkosten NUMERIC(7,2) NULL, --Tot 99999 euro verzendkosten (Grote dingen, schepen? auto's? tanks?)
	Verzendinstructies VARCHAR(400) NULL, --Helft van een beschrijving zou genoeg moeten zijn.
	Verkoper VARCHAR(30) NOT NULL,
	Koper VARCHAR(30) NULL,

	AfbeeldingNaam VARCHAR(200) NOT NULL,

	CONSTRAINT PK_Voorwerp PRIMARY KEY (Voorwerpnummer),
	--CONSTRAINT FK_Voorwerp_Betalingswijzen FOREIGN KEY (Betalingswijze) REFERENCES Betalingswijzen (Betaalwijze),

	CONSTRAINT CHK_Voorwerp_Titel CHECK (LEN(RTRIM(LTRIM(Titel))) >= 4), --Min 4 Characters, geen spaties.
	CONSTRAINT CHK_Voorwerp_Startprijs CHECK (Startprijs >= 1.00), --App B: Minimale verhoging.
	CONSTRAINT CHK_Voorwerp_Looptijd CHECK (Looptijd IN (1,3,5,7,10))
)
GO

CREATE TABLE Land
(
	GBA_CODE CHAR(4) NOT NULL,
	Landnaam VARCHAR(40) NOT NULL,
	Begindatum DATE NULL,
	Einddatum DATE NULL,
	EER_Lid BIT NOT NULL DEFAULT 0,
	CONSTRAINT PK_tblIMAOLand PRIMARY KEY (Landnaam),
	CONSTRAINT UQ_tblIMAOLand UNIQUE (GBA_CODE),
	CONSTRAINT CHK_CODE CHECK ( LEN(GBA_CODE) = 4 ),
	CONSTRAINT CHK_DATUM CHECK ( Begindatum < Einddatum )
)
GO

CREATE TABLE Vraag
(
	Vraagnummer TINYINT NOT NULL, --maximaal 255 vragen.
	TekstVraag VARCHAR(50) NOT NULL, --korte vragen.

	CONSTRAINT PK_vraagnummer PRIMARY KEY (vraagnummer)
)
GO

CREATE TABLE Gebruiker
(
	Gebruikersnaam VARCHAR(30) NOT NULL UNIQUE,
	Voornaam VARCHAR(30) NOT NULL,
	Achternaam VARCHAR(30) NOT NULL,
	Adresregel1 VARCHAR(50) NOT NULL,
	Adresregel2 VARCHAR(50) NULL,
	Postcode VARCHAR (7) NOT NULL,
	Plaatsnaam VARCHAR(85) NOT NULL,
	Land int NOT NULL,
	Datum DATE NOT NULL,
	Mailbox VARCHAR(50) NOT NULL UNIQUE, --Normaal e-mailadres heeft niet meer dan 50 karakters.
	Wachtwoord VARCHAR(255) NOT NULL,
	Vraagnummer TINYINT NOT NULL, --Zie tabel Vraag(vraagnummer)
	Antwoordtekst VARCHAR(255) NOT NULL,
	Verkoper BIT NOT NULL DEFAULT 0, --Bij registratie is een gebruiker nog geen verkoper.

	CONSTRAINT PK_Gebruiker PRIMARY KEY (gebruikersnaam),
	CONSTRAINT FK_Gebruiker_vraagnummer_Vraag_vraagnummer FOREIGN KEY (vraagnummer) REFERENCES Vraag (vraagnummer),
	CONSTRAINT CHK_Gebruiker_Gebruikersnaam CHECK (LEN(RTRIM(LTRIM(Gebruikersnaam))) >= 4), --Min 4 Characters, geen spaties.
	/*Geen constraints op voornaam en achternaam, is hun eigen verantwoordelijkheid*/
)
GO

CREATE TABLE Gebruikerstelefoon
(
	Volgnr INT NOT NULL UNIQUE,
	Gebruikersnaam VARCHAR(30) NOT NULL UNIQUE,
	Telefoonnummer VARCHAR(11) NOT NULL UNIQUE,
	
	CONSTRAINT PK_Gebruikerstelefoon PRIMARY KEY (volgnr, gebruikersnaam),
	CONSTRAINT FK_Gebruiker_gebruikersnaam_Gebruikerstelefoon FOREIGN KEY (gebruikersnaam) REFERENCES Gebruiker (gebruikersnaam)
)
GO

CREATE TABLE Beheerder
(
	GebruikersNaam VARCHAR(30) NOT NULL UNIQUE,
	BeheerWachtwoord VARCHAR(20) NOT NULL

	CONSTRAINT PK_Beheerder PRIMARY KEY (gebruikersnaam)
	CONSTRAINT FK_Gebruiker_gebruikersnaam_Gebruiker FOREIGN KEY (gebruikersnaam) REFERENCES Gebruiker (gebruikersnaam)
)
GO

create table EmailConfiguratie (
	Mailbox varchar(50) NOT NULL,
	Verificatiecode varchar(10) NOT NULL,
	Geverifieerd bit DEFAULT 0 NOT NULL,

	CONSTRAINT PK_emailconfiguratie PRIMARY KEY (mailbox)
)
GO

CREATE TABLE Bestand
(
	Filenaam INT IDENTITY NOT NULL, --elke foto moet een andere naam hebben. Daarom deze keuze. 
	Voorwerp INT NOT NULL, --Zie tabel Voorwerp(Voorwerpnummer).

	CONSTRAINT PK_Bestand PRIMARY KEY(Filenaam),
	CONSTRAINT FK_Bestand_Voorwerp_Voorwerp_Voorwerpnummer FOREIGN KEY (Voorwerp) REFERENCES Voorwerp(Voorwerpnummer)
)
GO

CREATE TABLE Bod
(
	Bodbedrag NUMERIC(18,2) NOT NULL,
	Voorwerp INT NOT NULL, --Zie tabel Voorwerp(Voorwerpnummer).
	Gebruikersnaam VARCHAR(30) NOT NULL,
	Tijd DATETIME NOT NULL,

	CONSTRAINT PK_Bod PRIMARY KEY(Bodbedrag, Voorwerp),
	CONSTRAINT FK_Bod_Voorwerp_Voorwerp_Voorwerpnummer FOREIGN KEY (Voorwerp) REFERENCES Voorwerp(Voorwerpnummer),
	CONSTRAINT FK_Bod_Gebruikersnaam_Gebruiker_Gebruikersnaam FOREIGN KEY (Gebruikersnaam) REFERENCES Gebruiker(gebruikersnaam)
)
GO

CREATE TABLE Feedback
(
	Voorwerp INT NOT NULL, --Zie tabel Voorwerp(Voorwerpnummer).
	SoortGebruiker BIT DEFAULT 1 NOT NULL,
	Feedbacksoortnaam BIT DEFAULT 1 NOT NULL,
	KoperOfVerkoper BIT DEFAULT 1 NOT NULL,
	Tijd DATETIME NOT NULL,
	Commentaar VARCHAR(100) NOT NULL,
	CONSTRAINT PK_Feedback PRIMARY KEY(Voorwerp, SoortGebruiker),
	CONSTRAINT FK_Feedback_Voorwerp_Voorwerp_Voorwerpnummer FOREIGN KEY (Voorwerp) REFERENCES Voorwerp(Voorwerpnummer)
)
GO

CREATE TABLE VoorwerpInRubriek
(
	Voorwerp INT NOT NULL, --Zie tabel Voorwerp(Voorwerpnummer).
	RubriekOpLaagsteNiveau INT NOT NULL,

	CONSTRAINT PK_VoorwerpInRubriek PRIMARY KEY(Voorwerp, RubriekOpLaagsteNiveau),
	CONSTRAINT FK_VoorwerpInRubriek_Voorwerp_Voorwerp_Voorwerpnummer FOREIGN KEY (Voorwerp) REFERENCES Voorwerp(Voorwerpnummer)
)
GO