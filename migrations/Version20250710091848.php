<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250710091848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C1765B63CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE departement_site (departement_id INT NOT NULL, site_id INT NOT NULL, INDEX IDX_74650E2CCCF9E01E (departement_id), INDEX IDX_74650E2CF6BD1646 (site_id), PRIMARY KEY(departement_id, site_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departement ADD CONSTRAINT FK_C1765B63CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departement_site ADD CONSTRAINT FK_74650E2CCCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departement_site ADD CONSTRAINT FK_74650E2CF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE departement DROP FOREIGN KEY FK_C1765B63CCF9E01E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departement_site DROP FOREIGN KEY FK_74650E2CCCF9E01E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departement_site DROP FOREIGN KEY FK_74650E2CF6BD1646
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE departement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE departement_site
        SQL);
    }
}
