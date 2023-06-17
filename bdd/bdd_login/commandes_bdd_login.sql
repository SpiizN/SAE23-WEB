CREATE TABLE login (
	"idUser"	INTEGER NOT NULL UNIQUE,
	"mail"	TEXT NOT NULL,
	"login"	TEXT NOT NULL,
	"password"	TEXT NOT NULL,
	"status_session" TEXT DEFAULT 'etu',
	PRIMARY KEY("idUser" AUTOINCREMENT)
);

INSERT INTO login (mail, login, password, status_session) VALUES ('test@test.fr', 'Test@_', 'lannion', 'admin') ;