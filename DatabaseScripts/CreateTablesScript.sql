/*LOCAL USE
DROP DATABASE Iproject
GO
CREATE DATABASE Iproject
GO
USE Iproject
GO
*/

drop table Rubriek
drop table Gebruikerstelefoon
drop table Beheerder
drop table Gebruiker
drop table Vraag

CREATE TABLE Rubriek
(
	Rubrieknummer INT NOT NULL, --Gekregen rubrieken gaan niet boven 200000 dus kan geen smallint dus moet int. miljoen is voldoende
	Rubrieknaam VARCHAR(40) NOT NULL, --Gekregen rubrieknamen gaan niet boven 40 karacters dus zal wel lukken.
	Rubriek INT NULL, --Ditto.
	Volgnr TINYINT NOT NULL DEFAULT 0, --0 tot 255, zou genoeg moeten zijn.

	CONSTRAINT PK_Rubriek PRIMARY KEY (rubrieknummer),
	CONSTRAINT FK_Rubriek_rubrieknummer_Rubriek_Rubriek FOREIGN KEY (Rubriek) REFERENCES Rubriek (rubrieknummer)
)

CREATE TABLE Vraag
(
	vraagnummer TINYINT NOT NULL,
	TekstVraag VARCHAR(50) NOT NULL,

	CONSTRAINT PK_vraagnummer PRIMARY KEY (vraagnummer)
)

CREATE TABLE Gebruiker
(
	gebruikersnaam VARCHAR(10) NOT NULL UNIQUE,
	voornaam VARCHAR(10) NOT NULL,
	achternaam VARCHAR(20) NOT NULL,
	adresregel1 VARCHAR(50) NOT NULL,
	adresregel2 VARCHAR(50) NULL,
	postcode VARCHAR (7) NOT NULL,
	plaatsnaam VARCHAR(85) NOT NULL,
	land VARCHAR(50) NOT NULL,
	datum DATE NOT NULL,
	mailbox VARCHAR(50) NOT NULL UNIQUE,
	wachtwoord VARCHAR(20) NOT NULL,
	vraagnummer TINYINT NOT NULL, --0 tot 255 zou genoeg moeten.
	antwoordtekst VARCHAR(25) NOT NULL,
	Verkoper BIT NOT NULL DEFAULT 0, --Bij registratie is een gebruiker nog geen verkoper.

	CONSTRAINT PK_Gebruiker PRIMARY KEY (gebruikersnaam),
	CONSTRAINT FK_Gebruiker_vraagnummer_Vraag_vraagnummer FOREIGN KEY (vraagnummer) REFERENCES Vraag (vraagnummer),
)

CREATE TABLE Gebruikerstelefoon
(
	volgnr INT NOT NULL UNIQUE,
	gebruikersnaam VARCHAR(10) NOT NULL UNIQUE,
	telefoonnummer VARCHAR(11) NOT NULL UNIQUE,
	
	CONSTRAINT PK_Gebruikerstelefoon PRIMARY KEY (volgnr, gebruikersnaam),
	CONSTRAINT FK_Gebruiker_gebruikersnaam_Gebruikerstelefoon FOREIGN KEY (gebruikersnaam) REFERENCES Gebruiker (gebruikersnaam)
) 

CREATE TABLE Beheerder
(
	gebruikersnaam VARCHAR(10) NOT NULL UNIQUE,
	BeheerWachtwoord VARCHAR(20) NOT NULL

	CONSTRAINT PK_Beheerder PRIMARY KEY (gebruikersnaam)
	CONSTRAINT FK_Gebruiker_gebruikersnaam_Gebruiker FOREIGN KEY (gebruikersnaam) REFERENCES Gebruiker (gebruikersnaam)
)