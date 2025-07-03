<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250620203607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'fix project table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project CHANGE start_date start_date DATE DEFAULT NULL, CHANGE deadline deadline DATE DEFAULT NULL, CHANGE archived archived TINYINT(1) DEFAULT 0 NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project CHANGE start_date start_date DATE NOT NULL, CHANGE deadline deadline DATE NOT NULL, CHANGE archived archived TINYINT(1) NOT NULL
        SQL);
    }
}
