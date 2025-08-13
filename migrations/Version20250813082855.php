<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250813082855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE about_section (id INT AUTO_INCREMENT NOT NULL, title_en VARCHAR(255) DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, title_fr VARCHAR(255) DEFAULT NULL, description_fr LONGTEXT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE categorie_domaine_entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category_of_permission (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE code_couleur (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, bg_color VARCHAR(255) DEFAULT NULL, text_color VARCHAR(255) DEFAULT NULL, btn_color VARCHAR(255) DEFAULT NULL, color_one VARCHAR(255) DEFAULT NULL, color_two VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, is_global TINYINT(1) DEFAULT NULL, is_default TINYINT(1) DEFAULT NULL, text_color_hover VARCHAR(255) DEFAULT NULL, btn_color_hover VARCHAR(255) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, INDEX IDX_4AAC379F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE commune (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, district VARCHAR(255) DEFAULT NULL, INDEX IDX_E2E2D1EE98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, demandeur_id INT DEFAULT NULL, site_id INT DEFAULT NULL, statut VARCHAR(255) DEFAULT NULL, objet VARCHAR(255) DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, fichier VARCHAR(255) DEFAULT NULL, INDEX IDX_2694D7A5C54C8C93 (type_id), INDEX IDX_2694D7A595A6EE59 (demandeur_id), INDEX IDX_2694D7A5F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, INDEX IDX_C1765B63CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE departement_site (departement_id INT NOT NULL, site_id INT NOT NULL, INDEX IDX_74650E2CCCF9E01E (departement_id), INDEX IDX_74650E2CF6BD1646 (site_id), PRIMARY KEY(departement_id, site_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE departement_rang (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, type_demande_id INT DEFAULT NULL, site_id INT DEFAULT NULL, rang INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9CE9A306CCF9E01E (departement_id), INDEX IDX_9CE9A3069DEA883D (type_demande_id), INDEX IDX_9CE9A306F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE domaine_entreprise (id INT AUTO_INCREMENT NOT NULL, categorie_domaine_entreprise_id INT DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, libelle_en VARCHAR(255) DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, INDEX IDX_966BE37E2D893D34 (categorie_domaine_entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE domaine_entreprise_entreprise (domaine_entreprise_id INT NOT NULL, entreprise_id INT NOT NULL, INDEX IDX_290A6790971D9803 (domaine_entreprise_id), INDEX IDX_290A6790A4AEAFEA (entreprise_id), PRIMARY KEY(domaine_entreprise_id, entreprise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE dossier_afournir (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, title_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE dossier_afournir_type_demande (dossier_afournir_id INT NOT NULL, type_demande_id INT NOT NULL, INDEX IDX_37269B4E8F793D80 (dossier_afournir_id), INDEX IDX_37269B4E9DEA883D (type_demande_id), PRIMARY KEY(dossier_afournir_id, type_demande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE hero_section (id INT AUTO_INCREMENT NOT NULL, title_fr VARCHAR(255) DEFAULT NULL, description_fr LONGTEXT DEFAULT NULL, bg_image VARCHAR(255) DEFAULT NULL, title_en VARCHAR(255) DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE langue (id INT AUTO_INCREMENT NOT NULL, title_fr VARCHAR(255) DEFAULT NULL, title_en VARCHAR(255) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, indice VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE niveau_hierarchique (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE niveau_hierarchique_departement (niveau_hierarchique_id INT NOT NULL, departement_id INT NOT NULL, INDEX IDX_3455158633918A39 (niveau_hierarchique_id), INDEX IDX_34551586CCF9E01E (departement_id), PRIMARY KEY(niveau_hierarchique_id, departement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE niveau_hierarchique_rang (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, niveau_hierarchique_id INT DEFAULT NULL, rang INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_EB4B664DCCF9E01E (departement_id), INDEX IDX_EB4B664D33918A39 (niveau_hierarchique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, category_of_permission_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_E04992AA3EDBBC5E (category_of_permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE permission_privilege (permission_id INT NOT NULL, privilege_id INT NOT NULL, INDEX IDX_A5D7B4EBFED90CCA (permission_id), INDEX IDX_A5D7B4EB32FB8AEA (privilege_id), PRIMARY KEY(permission_id, privilege_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE privilege (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, libelle_fr VARCHAR(255) DEFAULT NULL, libelle_en VARCHAR(255) DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, title_fr VARCHAR(255) DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, title_en VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, region_id INT DEFAULT NULL, commune_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, is_current TINYINT(1) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, INDEX IDX_694309E4A4AEAFEA (entreprise_id), INDEX IDX_694309E498260155 (region_id), INDEX IDX_694309E4131A4F72 (commune_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tutoriel (id INT AUTO_INCREMENT NOT NULL, title_fr VARCHAR(255) DEFAULT NULL, title_en VARCHAR(255) DEFAULT NULL, description_fr LONGTEXT DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, video VARCHAR(255) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, fichier VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE type_demande (id INT AUTO_INCREMENT NOT NULL, domaine_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, INDEX IDX_EB5B8504272FC9F (domaine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE type_demande_site (type_demande_id INT NOT NULL, site_id INT NOT NULL, INDEX IDX_89A5C1349DEA883D (type_demande_id), INDEX IDX_89A5C134F6BD1646 (site_id), PRIMARY KEY(type_demande_id, site_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, langue_id INT DEFAULT NULL, niveau_hierarchique_id INT DEFAULT NULL, departement_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, profile VARCHAR(255) DEFAULT NULL, is_super_admin TINYINT(1) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, INDEX IDX_8D93D649F6BD1646 (site_id), INDEX IDX_8D93D6492AADBACD (langue_id), INDEX IDX_8D93D64933918A39 (niveau_hierarchique_id), INDEX IDX_8D93D649CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_privilege (user_id INT NOT NULL, privilege_id INT NOT NULL, INDEX IDX_87C01763A76ED395 (user_id), INDEX IDX_87C0176332FB8AEA (privilege_id), PRIMARY KEY(user_id, privilege_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE code_couleur ADD CONSTRAINT FK_4AAC379F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commune ADD CONSTRAINT FK_E2E2D1EE98260155 FOREIGN KEY (region_id) REFERENCES region (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5C54C8C93 FOREIGN KEY (type_id) REFERENCES type_demande (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande ADD CONSTRAINT FK_2694D7A595A6EE59 FOREIGN KEY (demandeur_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)
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
        $this->addSql(<<<'SQL'
            ALTER TABLE departement_rang ADD CONSTRAINT FK_9CE9A306CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departement_rang ADD CONSTRAINT FK_9CE9A3069DEA883D FOREIGN KEY (type_demande_id) REFERENCES type_demande (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departement_rang ADD CONSTRAINT FK_9CE9A306F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE domaine_entreprise ADD CONSTRAINT FK_966BE37E2D893D34 FOREIGN KEY (categorie_domaine_entreprise_id) REFERENCES categorie_domaine_entreprise (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE domaine_entreprise_entreprise ADD CONSTRAINT FK_290A6790971D9803 FOREIGN KEY (domaine_entreprise_id) REFERENCES domaine_entreprise (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE domaine_entreprise_entreprise ADD CONSTRAINT FK_290A6790A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_afournir_type_demande ADD CONSTRAINT FK_37269B4E8F793D80 FOREIGN KEY (dossier_afournir_id) REFERENCES dossier_afournir (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_afournir_type_demande ADD CONSTRAINT FK_37269B4E9DEA883D FOREIGN KEY (type_demande_id) REFERENCES type_demande (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveau_hierarchique_departement ADD CONSTRAINT FK_3455158633918A39 FOREIGN KEY (niveau_hierarchique_id) REFERENCES niveau_hierarchique (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveau_hierarchique_departement ADD CONSTRAINT FK_34551586CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveau_hierarchique_rang ADD CONSTRAINT FK_EB4B664DCCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveau_hierarchique_rang ADD CONSTRAINT FK_EB4B664D33918A39 FOREIGN KEY (niveau_hierarchique_id) REFERENCES niveau_hierarchique (id)
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
            ALTER TABLE site ADD CONSTRAINT FK_694309E4A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE site ADD CONSTRAINT FK_694309E498260155 FOREIGN KEY (region_id) REFERENCES region (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE site ADD CONSTRAINT FK_694309E4131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande ADD CONSTRAINT FK_EB5B8504272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine_entreprise (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande_site ADD CONSTRAINT FK_89A5C1349DEA883D FOREIGN KEY (type_demande_id) REFERENCES type_demande (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande_site ADD CONSTRAINT FK_89A5C134F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D649F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D6492AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D64933918A39 FOREIGN KEY (niveau_hierarchique_id) REFERENCES niveau_hierarchique (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D649CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_privilege ADD CONSTRAINT FK_87C01763A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_privilege ADD CONSTRAINT FK_87C0176332FB8AEA FOREIGN KEY (privilege_id) REFERENCES privilege (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE code_couleur DROP FOREIGN KEY FK_4AAC379F6BD1646
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commune DROP FOREIGN KEY FK_E2E2D1EE98260155
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5C54C8C93
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A595A6EE59
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5F6BD1646
        SQL);
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
            ALTER TABLE departement_rang DROP FOREIGN KEY FK_9CE9A306CCF9E01E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departement_rang DROP FOREIGN KEY FK_9CE9A3069DEA883D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departement_rang DROP FOREIGN KEY FK_9CE9A306F6BD1646
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE domaine_entreprise DROP FOREIGN KEY FK_966BE37E2D893D34
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE domaine_entreprise_entreprise DROP FOREIGN KEY FK_290A6790971D9803
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE domaine_entreprise_entreprise DROP FOREIGN KEY FK_290A6790A4AEAFEA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_afournir_type_demande DROP FOREIGN KEY FK_37269B4E8F793D80
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_afournir_type_demande DROP FOREIGN KEY FK_37269B4E9DEA883D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveau_hierarchique_departement DROP FOREIGN KEY FK_3455158633918A39
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveau_hierarchique_departement DROP FOREIGN KEY FK_34551586CCF9E01E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveau_hierarchique_rang DROP FOREIGN KEY FK_EB4B664DCCF9E01E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveau_hierarchique_rang DROP FOREIGN KEY FK_EB4B664D33918A39
        SQL);
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
            ALTER TABLE site DROP FOREIGN KEY FK_694309E4A4AEAFEA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE site DROP FOREIGN KEY FK_694309E498260155
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE site DROP FOREIGN KEY FK_694309E4131A4F72
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande DROP FOREIGN KEY FK_EB5B8504272FC9F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande_site DROP FOREIGN KEY FK_89A5C1349DEA883D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_demande_site DROP FOREIGN KEY FK_89A5C134F6BD1646
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F6BD1646
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492AADBACD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D64933918A39
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CCF9E01E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_privilege DROP FOREIGN KEY FK_87C01763A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_privilege DROP FOREIGN KEY FK_87C0176332FB8AEA
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE about_section
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categorie_domaine_entreprise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category_of_permission
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE code_couleur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commune
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE demande
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE departement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE departement_site
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE departement_rang
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE domaine_entreprise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE domaine_entreprise_entreprise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE dossier_afournir
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE dossier_afournir_type_demande
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE entreprise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE hero_section
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE langue
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE niveau_hierarchique
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE niveau_hierarchique_departement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE niveau_hierarchique_rang
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
            DROP TABLE region
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE service
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE site
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tutoriel
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE type_demande
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE type_demande_site
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_privilege
        SQL);
    }
}
