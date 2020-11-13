CREATE TABLE if not exists User (UserID integer PRIMARY KEY autoincrement, 
FirstName text not null, 
LastName text not null, 
Email text unique not null, 
Password text not null, 
RecoveryEmail text, 
PhoneNumber text unique, 
BirthDate numeric not null, 
LastLoginTime numeric, 
AccountCreationTime text DEFAULT CURRENT_TIMESTAMP, 
DarkMode numeric default 0);

