-- Create Users table
CREATE TABLE IF NOT EXISTS Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    TenantID INT,
    Username VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    PasswordHash VARCHAR(255) NOT NULL,
    Role VARCHAR(100) NOT NULL,
    Enabled TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (TenantID) REFERENCES Tenants(TenantID)
);

-- Create Logs table
CREATE TABLE IF NOT EXISTS Logs (
    LogID INT AUTO_INCREMENT PRIMARY KEY,
    TenantID INT,
    UserID INT,
    Action VARCHAR(255),
    LogDate DATETIME,
    IPAddress VARCHAR(45),
    UserAgent TEXT,
    AdditionalData TEXT,
    FOREIGN KEY (TenantID) REFERENCES Tenants(TenantID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Create Categories table
CREATE TABLE IF NOT EXISTS Categories (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY,
    TenantID INT,
    Name VARCHAR(255) NOT NULL,
    Description TEXT,
    FOREIGN KEY (TenantID) REFERENCES Tenants(TenantID)
);

-- Create Settings table
CREATE TABLE IF NOT EXISTS Settings (
    SettingID INT AUTO_INCREMENT PRIMARY KEY,
    TenantID INT,
    Key VARCHAR(255) NOT NULL,
    Value TEXT NOT NULL,
    FOREIGN KEY (TenantID) REFERENCES Tenants(TenantID)
);

-- Create Tags table
CREATE TABLE IF NOT EXISTS Tags (
    TagID INT AUTO_INCREMENT PRIMARY KEY,
    TenantID INT,
    Name VARCHAR(255) NOT NULL,
    FOREIGN KEY (TenantID) REFERENCES Tenants(TenantID)
);

-- Create Analytics table
CREATE TABLE IF NOT EXISTS Analytics (
    AnalyticID INT AUTO_INCREMENT PRIMARY KEY,
    TenantID INT,
    Page VARCHAR(255) NOT NULL,
    Visits INT NOT NULL,
    FOREIGN KEY (TenantID) REFERENCES Tenants(TenantID)
);

-- Create Posts table
CREATE TABLE IF NOT EXISTS Posts (
    PostID INT AUTO_INCREMENT PRIMARY KEY,
    TenantID INT,
    AuthorID INT,
    Title VARCHAR(255) NOT NULL,
    Content TEXT NOT NULL,
    PostDate DATETIME NOT NULL,
    CategoryID INT,
    Enabled TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (TenantID) REFERENCES Tenants(TenantID),
    FOREIGN KEY (AuthorID) REFERENCES Users(UserID),
    FOREIGN KEY (CategoryID) REFERENCES Categories(CategoryID)
);

-- Create Tenants table
CREATE TABLE IF NOT EXISTS Tenants (
    TenantID INT AUTO_INCREMENT PRIMARY KEY,
    Subdomain VARCHAR(255) UNIQUE NOT NULL,
    BlogName VARCHAR(255) NOT NULL,
    IsActive TINYINT(1) NOT NULL DEFAULT 1,
    Enabled TINYINT(1) NOT NULL DEFAULT 1
);

-- Create Comments table
CREATE TABLE IF NOT EXISTS Comments (
    CommentID INT AUTO_INCREMENT PRIMARY KEY,
    TenantID INT,
    PostID INT,
    UserID INT,
    Content TEXT NOT NULL,
    CommentDate DATETIME NOT NULL,
    FOREIGN KEY (TenantID) REFERENCES Tenants(TenantID),
    FOREIGN KEY (PostID) REFERENCES Posts(PostID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);
