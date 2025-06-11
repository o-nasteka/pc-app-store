<?php

declare(strict_types=1);

namespace App\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class VersionSyncSchema extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // --- USERS TABLE ---
        // Add updated_at column if not exists
        $this->addSql("ALTER TABLE users ADD COLUMN updated_at DATETIME DEFAULT NULL");
        // Rename last_login_at to last_login if needed
        $this->addSql("ALTER TABLE users CHANGE last_login_at last_login DATETIME DEFAULT NULL");

        // --- ACTIVITIES TABLE ---
        // Change id and user_id to VARCHAR(64)
        $this->addSql("ALTER TABLE activities MODIFY id VARCHAR(64) NOT NULL");
        $this->addSql("ALTER TABLE activities MODIFY user_id VARCHAR(64) DEFAULT NULL");
        // Add missing columns if not exist
        $this->addSql("ALTER TABLE activities ADD COLUMN page VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE activities ADD COLUMN button VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE activities ADD COLUMN ip_address VARCHAR(45) DEFAULT NULL");
        $this->addSql("ALTER TABLE activities ADD COLUMN user_agent TEXT");
        // Allow NULL for started_at and updated_at
        $this->addSql("ALTER TABLE activities MODIFY started_at DATETIME DEFAULT NULL");
        $this->addSql("ALTER TABLE activities MODIFY updated_at DATETIME DEFAULT NULL");
    }

    public function down(Schema $schema): void
    {
        // --- USERS TABLE ---
        $this->addSql("ALTER TABLE users DROP COLUMN updated_at");
        $this->addSql("ALTER TABLE users CHANGE last_login last_login_at DATETIME DEFAULT NULL");

        // --- ACTIVITIES TABLE ---
        $this->addSql("ALTER TABLE activities MODIFY id INT AUTO_INCREMENT NOT NULL");
        $this->addSql("ALTER TABLE activities MODIFY user_id INT NOT NULL");
        $this->addSql("ALTER TABLE activities DROP COLUMN page");
        $this->addSql("ALTER TABLE activities DROP COLUMN button");
        $this->addSql("ALTER TABLE activities DROP COLUMN ip_address");
        $this->addSql("ALTER TABLE activities DROP COLUMN user_agent");
        $this->addSql("ALTER TABLE activities MODIFY started_at DATETIME NOT NULL");
        $this->addSql("ALTER TABLE activities MODIFY updated_at DATETIME NOT NULL");
    }
}
