CREATE TABLE users (
                       id VARCHAR(36) PRIMARY KEY,
                       email VARCHAR(255) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       name VARCHAR(255) NOT NULL,
                       role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
                       last_login DATETIME,
                       created_at DATETIME NOT NULL,
                       updated_at DATETIME NOT NULL
);

CREATE TABLE activities (
                            id VARCHAR(36) PRIMARY KEY,
                            user_id VARCHAR(36) NOT NULL,
                            type ENUM('login', 'logout', 'registration', 'view_page', 'button_click') NOT NULL,
                            page VARCHAR(255),
                            button VARCHAR(255),
                            ip_address VARCHAR(45) NOT NULL,
                            user_agent TEXT,
                            created_at DATETIME NOT NULL,
                            FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE INDEX idx_activities_user_id ON activities(user_id);
CREATE INDEX idx_activities_type ON activities(type);
CREATE INDEX idx_activities_created_at ON activities(created_at);
