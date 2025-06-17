<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617165957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE type_demande_domaine_entreprise (type_demande_id INT NOT NULL, domaine_entreprise_id INT NOT NULL, INDEX IDX_F56AA57F9DEA883D (type_demande_id), INDEX IDX_F56AA57F971D9803 (domaine_entreprise_id), PRIMARY KEY(type_demande_id, domaine_entreprise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande_domaine_entreprise ADD CONSTRAINT FK_F56AA57F9DEA883D FOREIGN KEY (type_demande_id) REFERENCES type_demande (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande_domaine_entreprise ADD CONSTRAINT FK_F56AA57F971D9803 FOREIGN KEY (domaine_entreprise_id) REFERENCES domaine_entreprise (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande DROP FOREIGN KEY FK_EB5B850971D9803
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EB5B850971D9803 ON type_demande
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande DROP domaine_entreprise_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande_domaine_entreprise DROP FOREIGN KEY FK_F56AA57F9DEA883D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande_domaine_entreprise DROP FOREIGN KEY FK_F56AA57F971D9803
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE type_demande_domaine_entreprise
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande ADD domaine_entreprise_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande ADD CONSTRAINT FK_EB5B850971D9803 FOREIGN KEY (domaine_entreprise_id) REFERENCES domaine_entreprise (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EB5B850971D9803 ON type_demande (domaine_entreprise_id)
        SQL);
    }
}
