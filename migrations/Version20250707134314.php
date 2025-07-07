<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250707134314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE dossier_afournir (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE dossier_afournir_type_demande (dossier_afournir_id INT NOT NULL, type_demande_id INT NOT NULL, INDEX IDX_37269B4E8F793D80 (dossier_afournir_id), INDEX IDX_37269B4E9DEA883D (type_demande_id), PRIMARY KEY(dossier_afournir_id, type_demande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_afournir_type_demande ADD CONSTRAINT FK_37269B4E8F793D80 FOREIGN KEY (dossier_afournir_id) REFERENCES dossier_afournir (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_afournir_type_demande ADD CONSTRAINT FK_37269B4E9DEA883D FOREIGN KEY (type_demande_id) REFERENCES type_demande (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_afournir_type_demande DROP FOREIGN KEY FK_37269B4E8F793D80
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_afournir_type_demande DROP FOREIGN KEY FK_37269B4E9DEA883D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE dossier_afournir
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE dossier_afournir_type_demande
        SQL);
    }
}
