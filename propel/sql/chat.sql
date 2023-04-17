
-----------------------------------------------------------------------
-- user
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [user];

CREATE TABLE [user]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [username] VARCHAR(100) NOT NULL,
    [password] VARCHAR(100) NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- group
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [group];

CREATE TABLE [group]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(255) NOT NULL,
    [creator_id] INTEGER,
    UNIQUE ([id]),
    FOREIGN KEY ([creator_id]) REFERENCES [user] ([id])
);

-----------------------------------------------------------------------
-- message
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [message];

CREATE TABLE [message]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [sender_id] INTEGER NOT NULL,
    [receiver_id] INTEGER,
    [group_id] INTEGER,
    [text] VARCHAR(255) NOT NULL,
    [created_at] TIMESTAMP NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([sender_id]) REFERENCES [user] ([id]),
    FOREIGN KEY ([receiver_id]) REFERENCES [user] ([id]),
    FOREIGN KEY ([group_id]) REFERENCES [group] ([id])
);

-----------------------------------------------------------------------
-- membership
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [membership];

CREATE TABLE [membership]
(
    [user_id] INTEGER NOT NULL,
    [group_id] INTEGER NOT NULL,
    PRIMARY KEY ([user_id],[group_id]),
    UNIQUE ([user_id],[group_id]),
    FOREIGN KEY ([user_id]) REFERENCES [user] ([id]),
    FOREIGN KEY ([group_id]) REFERENCES [group] ([id])
);
