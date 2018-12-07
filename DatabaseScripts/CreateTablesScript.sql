/*LOCAL USE
DROP DATABASE Iproject
GO
CREATE DATABASE Iproject
GO
USE Iproject
GO
*/

drop table Rubriek
GO
drop table Voorwerp
GO
drop table Gebruikerstelefoon
GO
drop table Beheerder
GO
drop table Gebruiker
GO
drop table Vraag
GO

select Rubrieknaam from Rubriek order by LEN(Rubrieknaam) DESC

CREATE TABLE Rubriek
(
	RubriekNummer INT NOT NULL, --Gekregen rubrieken gaan niet boven 200000 dus kan geen smallint dus moet int. miljoen is voldoende
	RubriekNaam VARCHAR(40) NOT NULL, --Gekregen rubrieknamen gaan niet boven 40 karacters (Langste is 30) dus zal wel lukken.
	VorigeRubriek INT NULL, --Ditto.
	Populariteit TINYINT NOT NULL DEFAULT 0, --0 tot 255, zou genoeg moeten zijn. hoog nummer betekend populairder.

	CONSTRAINT PK_Rubriek PRIMARY KEY (rubrieknummer),
	CONSTRAINT FK_Rubriek_rubrieknummer_Rubriek_Rubriek FOREIGN KEY (VorigeRubriek) REFERENCES Rubriek (Rubrieknummer)
)
GO

CREATE TABLE Voorwerp
(
	Voorwerpnummer INT IDENTITY NOT NULL, --App c genereert zelf een nummer.
	Titel VARCHAR(45) NOT NULL, --Minder dan marktplaats :60
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
	Verzendkosten NUMERIC(7,2) NULL, --Tot 99000 euro verzendkosten (Grote dingen, schepen? auto's?)
	Verzendinstructies VARCHAR(400) NULL, --Helft van een beschrijving zou genoeg moeten zijn.
	Verkoper VARCHAR(30) NOT NULL,
	Koper VARCHAR(30) NULL,

	AfbeeldingNaam VARCHAR(200) NOT NULL,

	CONSTRAINT PK_Voorwerp PRIMARY KEY (Voorwerpnummer),
	--CONSTRAINT FK_Voorwerp_Betalingswijzen FOREIGN KEY (Betalingswijze) REFERENCES Betalingswijzen (Betaalwijze),

	CONSTRAINT CHK_Titel CHECK (LEN(RTRIM(LTRIM(Titel))) >= 4), --Min 4 Characters, geen spaties.
	CONSTRAINT CHK_Startprijs CHECK (Startprijs >= 1.00), --App B: Minimale verhoging.
	CONSTRAINT CHK_Looptijd CHECK (Looptijd IN (1,3,5,7,10))
)
GO

CREATE TABLE Vraag
(
	vraagnummer TINYINT NOT NULL,
	TekstVraag VARCHAR(50) NOT NULL,

	CONSTRAINT PK_vraagnummer PRIMARY KEY (vraagnummer)
)
GO

CREATE TABLE Gebruiker
(
	gebruikersnaam VARCHAR(30) NOT NULL UNIQUE,
	voornaam VARCHAR(20) NOT NULL,
	achternaam VARCHAR(30) NOT NULL,
	adresregel1 VARCHAR(50) NOT NULL,
	adresregel2 VARCHAR(50) NULL,
	postcode VARCHAR (7) NOT NULL,
	plaatsnaam VARCHAR(85) NOT NULL,
	land VARCHAR(50) NOT NULL,
	datum DATE NOT NULL,
	mailbox VARCHAR(50) NOT NULL UNIQUE,
	wachtwoord VARCHAR(255) NOT NULL,
	vraagnummer TINYINT NOT NULL, --0 tot 255 zou genoeg moeten.
	antwoordtekst VARCHAR(255) NOT NULL,
	Verkoper BIT NOT NULL DEFAULT 0, --Bij registratie is een gebruiker nog geen verkoper.

	CONSTRAINT PK_Gebruiker PRIMARY KEY (gebruikersnaam),
	CONSTRAINT FK_Gebruiker_vraagnummer_Vraag_vraagnummer FOREIGN KEY (vraagnummer) REFERENCES Vraag (vraagnummer),
)
GO

CREATE TABLE Gebruikerstelefoon
(
	volgnr INT NOT NULL UNIQUE,
	gebruikersnaam VARCHAR(30) NOT NULL UNIQUE,
	telefoonnummer VARCHAR(11) NOT NULL UNIQUE,
	
	CONSTRAINT PK_Gebruikerstelefoon PRIMARY KEY (volgnr, gebruikersnaam),
	CONSTRAINT FK_Gebruiker_gebruikersnaam_Gebruikerstelefoon FOREIGN KEY (gebruikersnaam) REFERENCES Gebruiker (gebruikersnaam)
)
GO

CREATE TABLE Beheerder
(
	gebruikersnaam VARCHAR(30) NOT NULL UNIQUE,
	BeheerWachtwoord VARCHAR(20) NOT NULL

	CONSTRAINT PK_Beheerder PRIMARY KEY (gebruikersnaam)
	CONSTRAINT FK_Gebruiker_gebruikersnaam_Gebruiker FOREIGN KEY (gebruikersnaam) REFERENCES Gebruiker (gebruikersnaam)
)
GO