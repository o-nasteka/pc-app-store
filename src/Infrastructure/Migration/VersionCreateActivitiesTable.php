<?php

declare(strict_types=1);

namespace App\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class VersionCreateActivitiesTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
        CREATE TABLE activities (
            id VARCHAR(64) NOT NULL,
            user_id VARCHAR(64) DEFAULT NULL,
            type VARCHAR(255) NOT NULL,
            page VARCHAR(255) DEFAULT NULL,
            button VARCHAR(255) DEFAULT NULL,
            ip_address VARCHAR(45) DEFAULT NULL,
            user_agent TEXT,
            started_at DATETIME DEFAULT NULL,
            ended_at DATETIME DEFAULT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            INDEX IDX_USER_ID (user_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
    ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE activities');
    }
}
