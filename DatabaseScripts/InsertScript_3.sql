DELETE FROM Voorwerp
GO
DELETE FROM Rubriek
GO
DELETE FROM Gebruiker
GO

/*
======================================================================
	Table: Rubriek
======================================================================
*/

INSERT INTO Rubriek (RubriekNummer, RubriekNaam, VorigeRubriek, Volgnummer)
SELECT Categorieen.ID,		-- AS Rubrieknummer,
	   Categorieen.Name,	-- AS Rubrieknaam,
	   Categorieen.Parent,	-- AS VorigeRubriek,
	   ''					-- AS Volgnummer
FROM Categorieen
GO

/*
======================================================================
	Table: Gebruiker
======================================================================
*/

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
======================================================================
	Table: Voorwerp
======================================================================
*/

ALTER TABLE Voorwerp
ADD BerekeningWisselkoers VARCHAR(5)
GO

SET IDENTITY_INSERT Voorwerp ON
GO

INSERT INTO Voorwerp (Voorwerpnummer, Titel, Beschrijving, Startprijs, Betalingswijze, Plaatsnaam, Land, Looptijd, Verkoper, Thumbnail, BerekeningWisselkoers)
SELECT  Items.ID,																										--AS Voorwerpnummer
		LEFT(Items.Titel, 100),																							--AS Titel
		CASE WHEN LEN(RTRIM(LTRIM(Beschrijving))) <=10 THEN 'Geen beschrijving' ELSE LEFT(Items.Beschrijving, 5000) END, --AS Beschrijving
		CASE WHEN CAST(Items.Prijs AS NUMERIC(18,2)) >= 1.00 THEN CAST(Items.Prijs AS NUMERIC(18,2)) ELSE 1 END,		--AS Startprijs
		'Creditcard',																									--AS Betalingswijze
		'Arnhem',																										--AS Plaatsnaam
		'Nederland',																									--AS Land
		CASE Items.ID % 5																								--AS Looptijd
			WHEN 0 THEN 1
			WHEN 1 THEN 3
			WHEN 2 THEN 5
			WHEN 3 THEN 7
			WHEN 4 THEN 10
			ELSE '' END,
		Items.Verkoper,																									--AS Verkoper
		Thumbnail,																										--AS Thumbnail
		Items.Valuta
FROM Items
GO

SET IDENTITY_INSERT Voorwerp OFF
GO

UPDATE Voorwerp
SET Startprijs = CASE WHEN Startprijs * 0.659396239 < 1.00 THEN 1.00 ELSE Startprijs * 0.659396239 END
WHERE BerekeningWisselkoers = 'CAD'
GO

UPDATE Voorwerp
SET Startprijs = CASE WHEN Startprijs * 1.11459573 < 1.00 THEN 1.00 ELSE Startprijs * 1.11459573 END
WHERE BerekeningWisselkoers = 'GBP'
GO

UPDATE Voorwerp
SET Startprijs = CASE WHEN Startprijs * 0.0122573426 < 1.00 THEN 1.00 ELSE Startprijs * 0.0122573426 END
WHERE BerekeningWisselkoers = 'INR'
GO

UPDATE Voorwerp
SET Startprijs = CASE WHEN Startprijs * 0.880374687 < 1.00 THEN 1.00 ELSE Startprijs * 0.880374687 END
WHERE BerekeningWisselkoers = 'USD'
GO

ALTER TABLE Voorwerp
DROP COLUMN BerekeningWisselkoers
GO

/*
======================================================================
	Table: Bestand 8
======================================================================
*/

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

/*
======================================================================
	Table: VoorwerpInRubriek
======================================================================
*/

INSERT INTO VoorwerpInRubriek (Voorwerp, RubriekOpLaagsteNiveau)
SELECT  ID,			--AS Voorwerp
		Categorie	--AS RubriekOpLaagsteNiveau
FROM Items
GO

/*
======================================================================
	Table: Bod
======================================================================
*/

INSERT INTO Bod (Bodbedrag, Voorwerp, Gebruikersnaam) VALUES
(2.00, 390649125841, 'artpie2013'),
(3.00, 390649125841, 'joost706'),
(4.00, 390649125841, 'koeneloen'),
(5.00, 390649125841, 'pokeroy'),
(8.00, 390649125841, 'artpie2013'),
(13.00, 390649125841, 'joost706'),
(17.00, 390649125841, 'koeneloen'),
(30.00, 390649125841, 'pokeroy'),

(2.00, 331042226206, 'artpie2013'),
(3.00, 331042226206, 'joost706'),
(4.00, 331042226206, 'koeneloen'),
(5.00, 331042226206, 'pokeroy'),
(8.00, 331042226206, 'artpie2013'),
(13.00, 331042226206, 'joost706'),
(17.00, 331042226206, 'koeneloen'),
(30.00, 331042226206, 'pokeroy'),

(2.00, 161487877417, 'artpie2013'),
(3.00, 161487877417, 'joost706'),
(4.00, 161487877417, 'koeneloen'),
(5.00, 161487877417, 'pokeroy'),
(8.00, 161487877417, 'artpie2013'),
(13.00, 161487877417, 'joost706'),
(17.00, 161487877417, 'koeneloen'),
(30.00, 161487877417, 'pokeroy'),

(2.00, 261651216354, 'artpie2013'),
(3.00, 261651216354, 'joost706'),
(4.00, 261651216354, 'koeneloen'),
(5.00, 261651216354, 'pokeroy'),
(8.00, 261651216354, 'artpie2013'),
(13.00, 261651216354, 'joost706'),
(17.00, 261651216354, 'koeneloen'),
(30.00, 261651216354, 'pokeroy'),

(2.00, 390870076829, 'artpie2013'),
(3.00, 390870076829, 'joost706'),
(4.00, 390870076829, 'koeneloen'),
(5.00, 390870076829, 'pokeroy'),
(8.00, 390870076829, 'artpie2013'),
(13.00, 390870076829, 'joost706'),
(17.00, 390870076829, 'koeneloen'),
(30.00, 390870076829, 'pokeroy'),

(2.00, 371184585326, 'artpie2013'),
(3.00, 371184585326, 'joost706'),
(4.00, 371184585326, 'koeneloen'),
(5.00, 371184585326, 'pokeroy'),
(8.00, 371184585326, 'artpie2013'),
(13.00, 371184585326, 'joost706'),
(17.00, 371184585326, 'koeneloen'),
(30.00, 371184585326, 'pokeroy')

/*

Minimale verhoging
Start = 1.00

1.00 tot 49.99 = 0.50 verhoging
50.00 tot 499.99 = 1.00 verhoging
500.00 tot 99.99 = 5.00 verhoging
1000.00 tot 4999.99 = 10.00 verhoging
5000.00 < = 50.00 verhoging

390649125841 = 3
331042226206 = 3
161487877417 = 4
261651216354 = 4
390870076829 = 2
371184585326 = 2

*/

DROP TABLE Illustraties
GO
DROP TABLE Items
GO
DROP TABLE Users
GO
DROP TABLE Categorieen
GO