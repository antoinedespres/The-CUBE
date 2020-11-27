CREATE TABLE if not exists User (
    UserID integer PRIMARY KEY autoincrement, 
    FirstName text not null, 
    LastName text not null, 
    Email text unique not null, 
    Password text not null, 
    RecoveryEmail text, 
    PhoneNumber text, 
    BirthDate text not null, 
    LastLoginTime text, 
    AccountCreationTime text DEFAULT CURRENT_TIMESTAMP, 
    DarkMode numeric default 0
);

CREATE TABLE if not exists PasswordResetTemp (
    Email text not null primary key references User(Email), 
    Key text not null, 
    ExpiryDate text
);

CREATE TABLE if not exists File (
    FileID integer PRIMARY KEY AUTOINCREMENT,
    FileName text not null,
    UploadDateTime numeric DEFAULT CURRENT_TIMESTAMP,
    SuppressionDateTime numeric,
    Category text,
    UserID integer,
    FOREIGN KEY (UserID)
        references User (UserID)
);

CREATE TABLE if not exists HasAccessTo(
    Path text not null,
    FileID integer,
    UserID integer,

    FOREIGN KEY (FileID)
        references File (FileID),
    FOREIGN KEY (UserID)
        references User (UserID),
    PRIMARY KEY (FileID, UserID)
)
