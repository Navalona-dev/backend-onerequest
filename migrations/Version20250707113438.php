<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250707113438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE demande ADD site_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2694D7A5F6BD1646 ON demande (site_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5F6BD1646
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2694D7A5F6BD1646 ON demande
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande DROP site_id
        SQL);
    }
}
