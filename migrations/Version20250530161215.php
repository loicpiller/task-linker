<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530161215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, role INT NOT NULL, contract_type VARCHAR(50) NOT NULL, hire_date DATE NOT NULL, active TINYINT(1) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, start_date DATE NOT NULL, deadline DATE NOT NULL, archived TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, label VARCHAR(20) NOT NULL, INDEX IDX_7B00651C166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, label VARCHAR(20) NOT NULL, INDEX IDX_389B783166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, project_id INT NOT NULL, status_id INT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, deadline DATE DEFAULT NULL, INDEX IDX_527EDB258C03F15C (employee_id), INDEX IDX_527EDB25166D1F9C (project_id), INDEX IDX_527EDB256BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE task_tag (task_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_6C0B4F048DB60186 (task_id), INDEX IDX_6C0B4F04BAD26311 (tag_id), PRIMARY KEY(task_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE timeslot (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, employee_id INT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, INDEX IDX_3BE452F78DB60186 (task_id), INDEX IDX_3BE452F78C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status ADD CONSTRAINT FK_7B00651C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD CONSTRAINT FK_389B783166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD CONSTRAINT FK_527EDB258C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD CONSTRAINT FK_527EDB25166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD CONSTRAINT FK_527EDB256BF700BD FOREIGN KEY (status_id) REFERENCES status (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task_tag ADD CONSTRAINT FK_6C0B4F048DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task_tag ADD CONSTRAINT FK_6C0B4F04BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F78DB60186 FOREIGN KEY (task_id) REFERENCES task (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F78C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE status DROP FOREIGN KEY FK_7B00651C166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP FOREIGN KEY FK_389B783166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task DROP FOREIGN KEY FK_527EDB258C03F15C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task DROP FOREIGN KEY FK_527EDB25166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task DROP FOREIGN KEY FK_527EDB256BF700BD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task_tag DROP FOREIGN KEY FK_6C0B4F048DB60186
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task_tag DROP FOREIGN KEY FK_6C0B4F04BAD26311
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE timeslot DROP FOREIGN KEY FK_3BE452F78DB60186
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE timeslot DROP FOREIGN KEY FK_3BE452F78C03F15C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE employee
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE status
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE task
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE task_tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE timeslot
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
