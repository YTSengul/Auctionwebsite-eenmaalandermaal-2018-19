drop table Rubriek


CREATE TABLE Rubriek
(
	Rubrieknummer INT NOT NULL, --Gekregen rubrieken gaan niet boven 200000 dus kan geen smallint dus moet int. miljoen is voldoende
	Rubrieknaam VARCHAR(40) NOT NULL, --Gekregen rubrieknamen gaan niet boven 40 karacters dus zal wel lukken.
	Rubriek INT NULL, --Ditto.
	Volgnr TINYINT NOT NULL DEFAULT 0, --Tot 0 tot 255, zou genoeg moeten zijn.

	CONSTRAINT PK_Rubriek PRIMARY KEY (rubrieknummer),
	CONSTRAINT FK_Rubriek_rubrieknummer_Rubriek_Rubriek FOREIGN KEY (Rubriek) REFERENCES Rubriek (rubrieknummer)
)
