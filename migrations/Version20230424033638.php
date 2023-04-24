<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230424033638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143497DD634');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0497DD634');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7497DD634');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE categorie_blog');
        $this->addSql('DROP TABLE categorie_donation');
        $this->addSql('DROP TABLE categorie_event');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143497DD634');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('DROP INDEX NomCategorie ON categorie');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0497DD634');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7497DD634');
        $this->addSql('ALTER TABLE event ADD type_event VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE role CHANGE role role VARCHAR(20) DEFAULT \'guest\'');
        $this->addSql('DROP INDEX email ON user');
        $this->addSql('ALTER TABLE user CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE banned banned TINYINT(1) DEFAULT 0 NOT NULL, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_blog (id INT AUTO_INCREMENT NOT NULL, NomCategorie VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX NomCategorie (NomCategorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categorie_donation (id INT AUTO_INCREMENT NOT NULL, NomCategorie VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX NomCategorie (NomCategorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categorie_event (id INT AUTO_INCREMENT NOT NULL, NomCategorie VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX NomCategorie (NomCategorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143497DD634');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143497DD634 FOREIGN KEY (categorie) REFERENCES categorie_blog (id)');
        $this->addSql('CREATE UNIQUE INDEX NomCategorie ON categorie (NomCategorie)');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0497DD634');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0497DD634 FOREIGN KEY (categorie) REFERENCES categorie_donation (id)');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7497DD634');
        $this->addSql('ALTER TABLE event DROP type_event');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7497DD634 FOREIGN KEY (categorie) REFERENCES categorie_event (id)');
        $this->addSql('ALTER TABLE role CHANGE role role VARCHAR(20) DEFAULT \'\'\'guest\'\'\'');
        $this->addSql('ALTER TABLE user CHANGE image image VARCHAR(255) DEFAULT \'NULL\', CHANGE banned banned TINYINT(1) DEFAULT NULL, CHANGE first_name first_name VARCHAR(255) DEFAULT \'NULL\', CHANGE last_name last_name VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('CREATE UNIQUE INDEX email ON user (email)');
    }
}
