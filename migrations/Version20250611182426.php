<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250611182426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE commune (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_E2E2D1EE98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commune ADD CONSTRAINT FK_E2E2D1EE98260155 FOREIGN KEY (region_id) REFERENCES region (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE site ADD region_id INT DEFAULT NULL, ADD adresse VARCHAR(255) DEFAULT NULL, ADD telephone VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE site ADD CONSTRAINT FK_694309E498260155 FOREIGN KEY (region_id) REFERENCES region (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_694309E498260155 ON site (region_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE site DROP FOREIGN KEY FK_694309E498260155
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commune DROP FOREIGN KEY FK_E2E2D1EE98260155
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commune
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE region
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_694309E498260155 ON site
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE site DROP region_id, DROP adresse, DROP telephone
        SQL);
    }
}
