<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327123416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom_client VARCHAR(255) NOT NULL, prenom_client VARCHAR(255) NOT NULL, tel_fixe VARCHAR(255) DEFAULT NULL, tel_portable1 VARCHAR(255) DEFAULT NULL, tel_portable2 VARCHAR(255) DEFAULT NULL, ref_client VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, date_paiment DATE DEFAULT NULL, nom_gerant VARCHAR(45) DEFAULT NULL, email VARCHAR(55) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_probleme (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magasin (id INT AUTO_INCREMENT NOT NULL, nom_mag VARCHAR(255) NOT NULL, nom_social VARCHAR(255) NOT NULL, rue VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, fax VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, email VARCHAR(255) NOT NULL, nom_rue VARCHAR(255) NOT NULL, phone_fix VARCHAR(255) NOT NULL, dette VARCHAR(255) NOT NULL, white VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sav_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, type TINYINT(1) NOT NULL, date DATETIME NOT NULL, read_etat TINYINT(1) NOT NULL, INDEX IDX_B6BD307F4F726353 (sav_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prioritaire (id INT AUTO_INCREMENT NOT NULL, niveau INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE probleme (id INT AUTO_INCREMENT NOT NULL, sav_id INT DEFAULT NULL, probleme VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_7AB2D7144F726353 (sav_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsable (id INT AUTO_INCREMENT NOT NULL, nom_respo VARCHAR(255) NOT NULL, tel_respo1 VARCHAR(255) DEFAULT NULL, tel_respo2 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sav (id INT AUTO_INCREMENT NOT NULL, magasin_id INT DEFAULT NULL, client_id INT DEFAULT NULL, niveau_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, ref_sav VARCHAR(255) NOT NULL, date_appel DATE NOT NULL, date_intervantion DATE NOT NULL, nb_emplacement INT NOT NULL, etat INT NOT NULL, intervention TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', admin_creer INT DEFAULT NULL, solution VARCHAR(255) DEFAULT NULL, INDEX IDX_6C7681F420096AE3 (magasin_id), INDEX IDX_6C7681F419EB6921 (client_id), INDEX IDX_6C7681F4B3E9C81 (niveau_id), INDEX IDX_6C7681F49D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, username VARCHAR(255) NOT NULL, forgot_password_toke VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F4F726353 FOREIGN KEY (sav_id) REFERENCES sav (id)');
        $this->addSql('ALTER TABLE probleme ADD CONSTRAINT FK_7AB2D7144F726353 FOREIGN KEY (sav_id) REFERENCES sav (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE sav ADD CONSTRAINT FK_6C7681F420096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE sav ADD CONSTRAINT FK_6C7681F419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE sav ADD CONSTRAINT FK_6C7681F4B3E9C81 FOREIGN KEY (niveau_id) REFERENCES prioritaire (id)');
        $this->addSql('ALTER TABLE sav ADD CONSTRAINT FK_6C7681F49D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F4F726353');
        $this->addSql('ALTER TABLE probleme DROP FOREIGN KEY FK_7AB2D7144F726353');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE sav DROP FOREIGN KEY FK_6C7681F420096AE3');
        $this->addSql('ALTER TABLE sav DROP FOREIGN KEY FK_6C7681F419EB6921');
        $this->addSql('ALTER TABLE sav DROP FOREIGN KEY FK_6C7681F4B3E9C81');
        $this->addSql('ALTER TABLE sav DROP FOREIGN KEY FK_6C7681F49D86650F');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE list_probleme');
        $this->addSql('DROP TABLE magasin');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE prioritaire');
        $this->addSql('DROP TABLE probleme');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE responsable');
        $this->addSql('DROP TABLE sav');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
