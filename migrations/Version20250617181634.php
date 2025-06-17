<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617181634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE domaine_entreprise_entreprise (domaine_entreprise_id INT NOT NULL, entreprise_id INT NOT NULL, INDEX IDX_290A6790971D9803 (domaine_entreprise_id), INDEX IDX_290A6790A4AEAFEA (entreprise_id), PRIMARY KEY(domaine_entreprise_id, entreprise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE domaine_entreprise_entreprise ADD CONSTRAINT FK_290A6790971D9803 FOREIGN KEY (domaine_entreprise_id) REFERENCES domaine_entreprise (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE domaine_entreprise_entreprise ADD CONSTRAINT FK_290A6790A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA60971D9803
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D19FA60971D9803 ON entreprise
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE entreprise DROP domaine_entreprise_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE domaine_entreprise_entreprise DROP FOREIGN KEY FK_290A6790971D9803
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE domaine_entreprise_entreprise DROP FOREIGN KEY FK_290A6790A4AEAFEA
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE domaine_entreprise_entreprise
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE entreprise ADD domaine_entreprise_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA60971D9803 FOREIGN KEY (domaine_entreprise_id) REFERENCES domaine_entreprise (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D19FA60971D9803 ON entreprise (domaine_entreprise_id)
        SQL);
    }
}
