<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250614144138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE site ADD commune_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE site ADD CONSTRAINT FK_694309E4131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_694309E4131A4F72 ON site (commune_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE site DROP FOREIGN KEY FK_694309E4131A4F72
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_694309E4131A4F72 ON site
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE site DROP commune_id
        SQL);
    }
}
