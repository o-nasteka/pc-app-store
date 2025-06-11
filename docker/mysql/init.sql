CREATE DATABASE IF NOT EXISTS activity_tracker
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

CREATE USER IF NOT EXISTS 'app_user'@'%' IDENTIFIED BY 'app_password';
GRANT ALL PRIVILEGES ON activity_tracker.* TO 'app_user'@'%';
FLUSH PRIVILEGES;
