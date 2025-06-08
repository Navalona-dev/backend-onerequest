<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250608190323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE category_of_permission (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, category_of_permission_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_E04992AA3EDBBC5E (category_of_permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE permission_privilege (permission_id INT NOT NULL, privilege_id INT NOT NULL, INDEX IDX_A5D7B4EBFED90CCA (permission_id), INDEX IDX_A5D7B4EB32FB8AEA (privilege_id), PRIMARY KEY(permission_id, privilege_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE privilege (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE privilege_user (privilege_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4D02837C32FB8AEA (privilege_id), INDEX IDX_4D02837CA76ED395 (user_id), PRIMARY KEY(privilege_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permission ADD CONSTRAINT FK_E04992AA3EDBBC5E FOREIGN KEY (category_of_permission_id) REFERENCES category_of_permission (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permission_privilege ADD CONSTRAINT FK_A5D7B4EBFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permission_privilege ADD CONSTRAINT FK_A5D7B4EB32FB8AEA FOREIGN KEY (privilege_id) REFERENCES privilege (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE privilege_user ADD CONSTRAINT FK_4D02837C32FB8AEA FOREIGN KEY (privilege_id) REFERENCES privilege (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE privilege_user ADD CONSTRAINT FK_4D02837CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE permission DROP FOREIGN KEY FK_E04992AA3EDBBC5E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permission_privilege DROP FOREIGN KEY FK_A5D7B4EBFED90CCA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permission_privilege DROP FOREIGN KEY FK_A5D7B4EB32FB8AEA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE privilege_user DROP FOREIGN KEY FK_4D02837C32FB8AEA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE privilege_user DROP FOREIGN KEY FK_4D02837CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category_of_permission
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE permission
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE permission_privilege
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE privilege
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE privilege_user
        SQL);
    }
}
