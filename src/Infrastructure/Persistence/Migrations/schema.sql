CREATE TABLE users (
                       id VARCHAR(36) PRIMARY KEY,
                       email VARCHAR(255) UNIQUE NOT NULL,
                       password VARCHAR(255) NOT NULL,
                       name VARCHAR(100) NOT NULL,
                       role VARCHAR(20) NOT NULL,
                       created_at DATETIME NOT NULL,
                       last_login_at DATETIME NULL,
                       INDEX idx_email (email)
);

CREATE TABLE activities (
                            id VARCHAR(36) PRIMARY KEY,
                            user_id VARCHAR(36) NOT NULL,
                            type VARCHAR(50) NOT NULL,
                            metadata JSON,
                            ip_address VARCHAR(45) NOT NULL,
                            user_agent TEXT,
                            created_at DATETIME NOT NULL,
                            INDEX idx_user_id (user_id),
                            INDEX idx_type (type),
                            INDEX idx_created_at (created_at),
                            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
