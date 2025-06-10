<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610163415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_privilege (user_id INT NOT NULL, privilege_id INT NOT NULL, INDEX IDX_87C01763A76ED395 (user_id), INDEX IDX_87C0176332FB8AEA (privilege_id), PRIMARY KEY(user_id, privilege_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_privilege ADD CONSTRAINT FK_87C01763A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_privilege ADD CONSTRAINT FK_87C0176332FB8AEA FOREIGN KEY (privilege_id) REFERENCES privilege (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE privilege_user DROP FOREIGN KEY FK_4D02837C32FB8AEA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE privilege_user DROP FOREIGN KEY FK_4D02837CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE privilege_user
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE privilege_user (privilege_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4D02837CA76ED395 (user_id), INDEX IDX_4D02837C32FB8AEA (privilege_id), PRIMARY KEY(privilege_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE privilege_user ADD CONSTRAINT FK_4D02837C32FB8AEA FOREIGN KEY (privilege_id) REFERENCES privilege (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE privilege_user ADD CONSTRAINT FK_4D02837CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_privilege DROP FOREIGN KEY FK_87C01763A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_privilege DROP FOREIGN KEY FK_87C0176332FB8AEA
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_privilege
        SQL);
    }
}
