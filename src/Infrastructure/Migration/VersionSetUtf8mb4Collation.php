<?php

declare(strict_types=1);

namespace App\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionSetUtf8mb4Collation extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set utf8mb4_0900_ai_ci collation for database and all tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER DATABASE activity_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci");
        $this->addSql("ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci");
        $this->addSql("ALTER TABLE activities CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER DATABASE activity_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE activities CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }
} 