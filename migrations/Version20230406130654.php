<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230406130654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX NomCategorie ON categorie (NomCategorie)');
        $this->addSql('CREATE UNIQUE INDEX NomCategorie ON categorie_blog (NomCategorie)');
        $this->addSql('ALTER TABLE categorie_donation ADD NomCategorie VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX NomCategorie ON categorie_donation (NomCategorie)');
        $this->addSql('CREATE UNIQUE INDEX NomCategorie ON categorie_event (NomCategorie)');
        $this->addSql('ALTER TABLE role CHANGE role role VARCHAR(20) DEFAULT \'guest\'');
        $this->addSql('ALTER TABLE user CHANGE image image VARCHAR(255) DEFAULT \'\'\'aze.png\'\'\', CHANGE checker checker VARCHAR(200) DEFAULT \'\'\'usable\'\'\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP INDEX NomCategorie ON categorie');
        $this->addSql('DROP INDEX NomCategorie ON categorie_blog');
        $this->addSql('DROP INDEX NomCategorie ON categorie_donation');
        $this->addSql('ALTER TABLE categorie_donation DROP NomCategorie');
        $this->addSql('DROP INDEX NomCategorie ON categorie_event');
        $this->addSql('ALTER TABLE role CHANGE role role VARCHAR(20) DEFAULT \'\'\'guest\'\'\'');
        $this->addSql('ALTER TABLE user CHANGE image image VARCHAR(255) DEFAULT \'\'\'\'\'\'\'aze.png\'\'\'\'\'\'\', CHANGE checker checker VARCHAR(200) DEFAULT \'\'\'\'\'\'\'usable\'\'\'\'\'\'\' NOT NULL');
    }
}
