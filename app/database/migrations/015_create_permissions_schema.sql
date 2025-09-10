-- Create new tables for a bitmask-based permission system
PRAGMA foreign_keys=ON;
BEGIN TRANSACTION;

CREATE TABLE Permission(
  ID      INTEGER PRIMARY KEY,
  Name    TEXT NOT NULL UNIQUE,
  BitPos  INTEGER NOT NULL UNIQUE,
  Value   INTEGER GENERATED ALWAYS AS (1 << BitPos) STORED,
  Hex     TEXT    GENERATED ALWAYS AS ('0x' || printf('%016X', Value)) STORED
);

CREATE TABLE "Group"(
  ID    INTEGER PRIMARY KEY,
  Name  TEXT NOT NULL UNIQUE
);

-- The "User" table is not created here as it already exists as "users".
-- We will alter the existing "users" table and insert into it separately if needed.
-- CREATE TABLE "User"(
--   ID    INTEGER PRIMARY KEY,
--   Name  TEXT NOT NULL UNIQUE
-- );

CREATE TABLE GroupPermission(
  ID            INTEGER PRIMARY KEY,
  ID_Group      INTEGER NOT NULL REFERENCES "Group"(ID) ON DELETE CASCADE,
  ID_Permission INTEGER NOT NULL REFERENCES Permission(ID) ON DELETE CASCADE,
  Allow         INTEGER NOT NULL CHECK (Allow IN (0,1)),
  UNIQUE(ID_Group, ID_Permission)
);

CREATE TABLE UserPermission(
  ID            INTEGER PRIMARY KEY,
  ID_User       INTEGER NOT NULL REFERENCES "users"(id) ON DELETE CASCADE,
  ID_Permission INTEGER NOT NULL REFERENCES Permission(ID) ON DELETE CASCADE,
  Allow         INTEGER NOT NULL CHECK (Allow IN (0,1)),
  UNIQUE(ID_User, ID_Permission)
);

CREATE TABLE UserGroup(
  ID       INTEGER PRIMARY KEY,
  ID_User  INTEGER NOT NULL REFERENCES "users"(id) ON DELETE CASCADE,
  ID_Group INTEGER NOT NULL REFERENCES "Group"(ID) ON DELETE CASCADE,
  UNIQUE(ID_User, ID_Group)
);

INSERT INTO Permission(Name,BitPos) VALUES
('PERM_READ',0),('PERM_WRITE',1),('PERM_EXECUTE',2),('PERM_DELETE',3),
('PERM_SHARE',4),('PERM_RENAME',5),('PERM_COPY',6),('PERM_MOVE',7),
('PERM_ARCHIVE',8),('PERM_ADMIN',9);

INSERT INTO "Group"(Name) VALUES ('Client'), ('Staff'), ('Vendor'), ('Viewer'),('Editor'),('Manager'),('Admin');

-- We will handle user inserts and group assignments separately.
-- The following are examples and need to be adapted to the existing users table.

-- Assign permissions to groups
WITH p AS (SELECT Name,ID FROM Permission)
INSERT INTO GroupPermission(ID_Group,ID_Permission,Allow)
SELECT (SELECT ID FROM "Group" WHERE Name='Viewer'),
       (SELECT ID FROM p WHERE Name='PERM_READ'), 1;

WITH p AS (SELECT Name,ID FROM Permission)
INSERT INTO GroupPermission(ID_Group,ID_Permission,Allow)
SELECT (SELECT ID FROM "Group" WHERE Name='Editor'), ID, 1
FROM p WHERE Name IN ('PERM_READ','PERM_WRITE','PERM_RENAME');

WITH p AS (SELECT Name,ID FROM Permission)
INSERT INTO GroupPermission(ID_Group,ID_Permission,Allow)
SELECT (SELECT ID FROM "Group" WHERE Name='Manager'), ID, 1
FROM p WHERE Name IN ('PERM_READ','PERM_WRITE','PERM_DELETE','PERM_SHARE','PERM_COPY','PERM_MOVE');

WITH p AS (SELECT Name,ID FROM Permission)
INSERT INTO GroupPermission(ID_Group,ID_Permission,Allow)
SELECT (SELECT ID FROM "Group" WHERE Name='Admin'), ID, 1 FROM p;

-- Insert new users and assign them to groups
INSERT OR IGNORE INTO users (username, email, password_hash, first_name, last_name, role, is_active) VALUES
('alice', 'alice@example.com', '', 'Alice', 'A', 'client', 1),
('bob', 'bob@example.com', '', 'Bob', 'B', 'staff', 1),
('carol', 'carol@example.com', '', 'Carol', 'C', 'staff', 1),
('dave', 'dave@example.com', '', 'Dave', 'D', 'admin', 1),
('eve', 'eve@example.com', '', 'Eve', 'E', 'staff', 1),
('frank', 'frank@example.com', '', 'Frank', 'F', 'vendor', 1);

-- Assign groups to users
INSERT INTO UserGroup(ID_User,ID_Group)
SELECT (SELECT id FROM "users"  WHERE username='admin'),
       (SELECT ID FROM "Group" WHERE Name='Admin');
INSERT INTO UserGroup(ID_User,ID_Group)
SELECT (SELECT id FROM "users"  WHERE username='restore'),
       (SELECT ID FROM "Group" WHERE Name='Admin');
INSERT INTO UserGroup(ID_User,ID_Group)
SELECT (SELECT id FROM "users"  WHERE username='alice'),
       (SELECT ID FROM "Group" WHERE Name='Client');
INSERT INTO UserGroup(ID_User,ID_Group)
SELECT (SELECT id FROM "users"  WHERE username='bob'),
       (SELECT ID FROM "Group" WHERE Name='Editor');
INSERT INTO UserGroup(ID_User,ID_Group)
SELECT (SELECT id FROM "users"  WHERE username='carol'),
       (SELECT ID FROM "Group" WHERE Name='Manager');
INSERT INTO UserGroup(ID_User,ID_Group)
SELECT (SELECT id FROM "users"  WHERE username='dave'),
       (SELECT ID FROM "Group" WHERE Name='Admin');
INSERT INTO UserGroup(ID_User,ID_Group)
SELECT (SELECT id FROM "users"  WHERE username='eve'),
       (SELECT ID FROM "Group" WHERE Name='Editor');
INSERT INTO UserGroup(ID_User,ID_Group)
SELECT (SELECT id FROM "users"  WHERE username='frank'),
       (SELECT ID FROM "Group" WHERE Name='Vendor');

-- Assign specific user permissions
INSERT INTO UserPermission(ID_User,ID_Permission,Allow)
SELECT (SELECT id FROM "users" WHERE username='bob'),
       (SELECT ID FROM Permission WHERE Name='PERM_DELETE'),
       0;
INSERT INTO UserPermission(ID_User,ID_Permission,Allow)
SELECT (SELECT id FROM "users" WHERE username='eve'),
       (SELECT ID FROM Permission WHERE Name='PERM_ADMIN'),
       1;

-- Create views for effective permissions
CREATE VIEW V_UserEffective AS
WITH RoleAllow AS (
  SELECT ug.ID_User, SUM(p.Value) AS Mask
  FROM UserGroup ug
  JOIN GroupPermission gp ON gp.ID_Group=ug.ID_Group AND gp.Allow=1
  JOIN Permission p ON p.ID=gp.ID_Permission
  GROUP BY ug.ID_User
),
RoleDeny AS (
  SELECT ug.ID_User, SUM(p.Value) AS Mask
  FROM UserGroup ug
  JOIN GroupPermission gp ON gp.ID_Group=ug.ID_Group AND gp.Allow=0
  JOIN Permission p ON p.ID=gp.ID_Permission
  GROUP BY ug.ID_User
),
UserAllow AS (
  SELECT up.ID_User, SUM(p.Value) AS Mask
  FROM UserPermission up
  JOIN Permission p ON p.ID=up.ID_Permission
  WHERE up.Allow=1
  GROUP BY up.ID_User
),
UserDeny AS (
  SELECT up.ID_User, SUM(p.Value) AS Mask
  FROM UserPermission up
  JOIN Permission p ON p.ID=up.ID_Permission
  WHERE up.Allow=0
  GROUP BY up.ID_User
)
SELECT
  u.id            AS ID_User,
  u.username          AS UserName,
  COALESCE(ra.Mask,0) AS GroupAllowMask,
  COALESCE(rd.Mask,0) AS GroupDenyMask,
  COALESCE(ua.Mask,0) AS UserAllowMask,
  COALESCE(ud.Mask,0) AS UserDenyMask,
  ((COALESCE(ra.Mask,0) | COALESCE(ua.Mask,0))
   & ~ (COALESCE(rd.Mask,0) | COALESCE(ud.Mask,0))) AS EffectiveMask,
  '0x' || printf('%016X',
     ((COALESCE(ra.Mask,0) | COALESCE(ua.Mask,0))
      & ~ (COALESCE(rd.Mask,0) | COALESCE(ud.Mask,0)))
  ) AS EffectiveHex
FROM "users" u
LEFT JOIN RoleAllow ra ON ra.ID_User=u.id
LEFT JOIN RoleDeny  rd ON rd.ID_User=u.id
LEFT JOIN UserAllow ua ON ua.ID_User=u.id
LEFT JOIN UserDeny  ud ON ud.ID_User=u.id;

CREATE VIEW V_UserEffectiveNames AS
WITH eff AS (
  SELECT ID_User, UserName, EffectiveMask FROM V_UserEffective
)
SELECT
  e.ID_User,
  e.UserName,
  GROUP_CONCAT(p.Name, ',') AS EffectiveNames
FROM eff e
JOIN Permission p ON (e.EffectiveMask & p.Value) <> 0
GROUP BY e.ID_User, e.UserName;

COMMIT;
