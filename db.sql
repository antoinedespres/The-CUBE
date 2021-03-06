CREATE TABLE if not exists User (
    UserID integer PRIMARY KEY autoincrement, 
    FirstName text not null, 
    LastName text not null, 
    Email text unique not null, 
    Password text not null, 
    BirthDate text not null, 
    LastLoginTime text, 
    AccountCreationTime text DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE if not exists PasswordResetTemp (
    Email text not null primary key references User(Email) ON DELETE CASCADE, 
    Key text not null, 
    ExpiryDate text
);

CREATE TABLE if not exists File (
    FileID integer PRIMARY KEY AUTOINCREMENT,
    FileName text not null,
    UploadDateTime numeric DEFAULT CURRENT_TIMESTAMP,
    Category text,
    UserID integer,
    FOREIGN KEY (UserID)
        references User (UserID) ON DELETE CASCADE
);

CREATE TABLE if not exists HasAccessTo(
    FileID integer,
    UserID integer,

    FOREIGN KEY (FileID)
        references File (FileID) ON DELETE CASCADE,
    FOREIGN KEY (UserID)
        references User (UserID) ON DELETE CASCADE,
    PRIMARY KEY (FileID, UserID)
);

