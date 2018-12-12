DELETE FROM Voorwerp
GO
DELETE FROM Rubriek
GO
DELETE FROM Gebruiker
GO

INSERT INTO Rubriek (RubriekNummer, RubriekNaam, VorigeRubriek, Volgnummer)
SELECT Categorieen.ID,		-- AS Rubrieknummer,
	   Categorieen.Name,	-- AS Rubrieknaam,
	   Categorieen.Parent,	-- AS VorigeRubriek,
	   ''					-- AS Volgnummer
FROM Categorieen
GO

INSERT INTO Gebruiker (Gebruikersnaam, Voornaam, Achternaam, Adresregel1, Adresregel2, Postcode, Plaatsnaam, Land, Datum, Mailbox, Wachtwoord, Vraagnummer, Antwoordtekst, Verkoper)
SELECT Users.Username,		-- AS Gebruikersnaam,
	   'Bob',				-- AS Voornaam,
	   'Jones',				-- AS Achternaam,
	   '1234AB',			-- AS Adresregel1,
	   '',					-- AS Adresregel2,
	   Users.Postalcode,	-- AS Postcode,
	   'Arnhem',			-- AS Plaatsnaam,
	   'Nederland',			-- AS Land,
	   '12-13-1980',		-- AS Datum, --(American standard -> Month-day-year)
	   '1234@gmail.com',	-- AS Mailbox,
	   'test',				-- AS Wachtwoord,
	    1,					-- AS Vraagnummer,
	   'Bert',				-- AS Antwoordtekst,
	    1					-- AS Verkoper
FROM Users
GO

/*

DROP TABLE TestA
GO
DROP TABLE TestB
GO
DROP TABLE TestC
GO
DROP TABLE TestD
GO
DROP TABLE TestE
GO
DROP TABLE TestF
GO
DROP TABLE TestG
GO

CREATE TABLE TestA
(
	Titel VARCHAR(90) NOT NULL, --Minder dan marktplaats: 60 Moet 45 worden

	CONSTRAINT CHK_Voorwerp_Titela CHECK (LEN(RTRIM(LTRIM(Titel))) >= 4), --Min 4 Characters, geen spaties.
)
GO

INSERT INTO TestA
SELECT  Items.Titel as Titel
FROM Items
GO

CREATE TABLE TestB
(
	Beschrijving VARCHAR(MAX) NOT NULL, --Moet 800 worden
)
GO

INSERT INTO TestB
SELECT  Items.Beschrijving as Beschrijving
FROM Items
GO

CREATE TABLE TestC
(
	Startprijs NUMERIC(18,2) NOT NULL, --Bedragen tot 100 miljoen.

	CONSTRAINT CHK_Voorwerp_Startprijsa CHECK (Startprijs >= 1.00), --App B: Minimale verhoging.
)
GO

INSERT INTO TestC (Startprijs)
SELECT  CASE WHEN CAST(Items.Prijs AS NUMERIC(18,2)) >= 1.00 THEN CAST(Items.Prijs AS NUMERIC(18,2)) ELSE 1  END
FROM Items
GO

CREATE TABLE TestD
(
	Betalingswijze VARCHAR(30) NOT NULL, --Zie tabel Betalingswijzen(Betalingswijze)

	Constraint FK_Voorwerp_Betalingswijze_Betalinagswijzen_BetalingswijzeNummer FOREIGN KEY (Betalingswijze) REFERENCES Betalingswijzen(Betalingswijze),
)
GO

INSERT INTO TestD
SELECT  'Creditcard' as Betalingswijze
FROM Items
GO

CREATE TABLE TestE
(
	Plaatsnaam VARCHAR(85) NOT NULL,
)
GO

INSERT INTO TestE
SELECT  'Arnhem' as Plaatsnaam
FROM Items
GO

CREATE TABLE TestF
(
	Land VARCHAR(40) NOT NULL, --Zie tabel Land(Landnaam)

	CONSTRAINT FK_Voorwerp_Land_Laand_Landnaam FOREIGN KEY (Land) REFERENCES Land(Landnaam),
)
GO

INSERT INTO TestF
SELECT  'Nederland' as Land
FROM Items
GO

CREATE TABLE TestG
(
	Verkoper VARCHAR(40) NOT NULL, --Zie tabel Gebruiker(Gebruikersnaam)

	CONSTRAINT FK_Voorwerp_Verkoper_Geabruiker_Gebruikersnaam FOREIGN KEY (Verkoper) REFERENCES Gebruiker(Gebruikersnaam),
)
GO

INSERT INTO TestG
SELECT  Items.Verkoper as Verkoper
FROM Items
GO

*/

SET IDENTITY_INSERT Voorwerp ON

INSERT INTO Voorwerp (Voorwerpnummer, Titel, Beschrijving, Startprijs, Betalingswijze, Plaatsnaam, Land, Verkoper, Thumbnail)
SELECT  ID,																												--AS Voorwerpnummer
		Items.Titel,																									--AS Titel
		Items.Beschrijving,																								--AS Beschrijving
		CASE WHEN CAST(Items.Prijs AS NUMERIC(18,2)) >= 1.00 THEN CAST(Items.Prijs AS NUMERIC(18,2)) ELSE 1  END,		--AS Startprijs
		'Creditcard',																									--AS Betalingswijze
		'Arnhem',																										--AS Plaatsnaam
		'Nederland',																									--AS Land
		Items.Verkoper,																									--AS Verkoper
		Thumbnail																										--AS Thumbnail
FROM Items
GO

SET IDENTITY_INSERT Voorwerp OFF

INSERT INTO Bestand (Filenaam, Voorwerp)
SELECT	IllustratieFile,
		ItemID
FROM Items
CROSS APPLY
(
SELECT TOP 4 * 
FROM Illustraties
WHERE ItemID = Items.ID
) X
GO

INSERT INTO VoorwerpInRubriek (Voorwerp, RubriekOpLaagsteNiveau)
SELECT  ID,			--AS Voorwerp
		Categorie	--AS RubriekOpLaagsteNiveau
FROM Items
GO

DROP TABLE Illustraties
GO
DROP TABLE Items
GO
DROP TABLE Users
GO
DROP TABLE Categorieen
GO