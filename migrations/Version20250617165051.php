<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617165051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE type_demande (id INT AUTO_INCREMENT NOT NULL, domaine_entreprise_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_EB5B850971D9803 (domaine_entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande ADD CONSTRAINT FK_EB5B850971D9803 FOREIGN KEY (domaine_entreprise_id) REFERENCES domaine_entreprise (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande DROP FOREIGN KEY FK_EB5B850971D9803
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE type_demande
        SQL);
    }
}
