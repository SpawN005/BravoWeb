<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501131659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artwork (id INT AUTO_INCREMENT NOT NULL, categorie INT DEFAULT NULL, owner INT DEFAULT NULL, title VARCHAR(30) NOT NULL, description TEXT NOT NULL, url VARCHAR(200) NOT NULL, INDEX artwork_fk (owner), INDEX FK_artwork (categorie), UNIQUE INDEX title (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, author_id INT DEFAULT NULL, title VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, content VARCHAR(1000) NOT NULL, INDEX IDX_C0155143BCF5E72D (categorie_id), INDEX IDX_C0155143F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, NomCategorie VARCHAR(100) NOT NULL, UNIQUE INDEX NomCategorie (NomCategorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_blog (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B73CD614DD8CA775 (nom_categorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_donation (id INT AUTO_INCREMENT NOT NULL, NomCategorie VARCHAR(100) NOT NULL, UNIQUE INDEX NomCategorie (NomCategorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(1000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire_blog (id INT AUTO_INCREMENT NOT NULL, blog_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_29ED9511DAE07E97 (blog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentsoeuvre (id INT AUTO_INCREMENT NOT NULL, oeuvre_id INT DEFAULT NULL, user_id INT DEFAULT NULL, comment VARCHAR(255) NOT NULL, timestamp DATETIME NOT NULL, INDEX oeuvre_id (oeuvre_id), INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donater (id INT AUTO_INCREMENT NOT NULL, id_donation INT DEFAULT NULL, id_user INT DEFAULT NULL, amount INT NOT NULL, INDEX fk_user (id_user), INDEX IDX_4BD61C33613E6D35 (id_donation), UNIQUE INDEX idx_donation (id_donation, id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donation (id INT AUTO_INCREMENT NOT NULL, owner INT DEFAULT NULL, categorie INT DEFAULT NULL, title VARCHAR(30) NOT NULL, description VARCHAR(100) NOT NULL, date_creation DATE NOT NULL, date_expiration DATE NOT NULL, amount INT NOT NULL, INDEX IDX_31E581A0CF60E67C (owner), INDEX IDX_31E581A0497DD634 (categorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, nb_place_max INT NOT NULL, date_beg DATE NOT NULL, date_end DATE NOT NULL, image VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3BAE0AA72B36786B (title), INDEX IDX_3BAE0AA7BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CFE8E8096C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_blog (id INT AUTO_INCREMENT NOT NULL, blog_id INT DEFAULT NULL, user_id INT DEFAULT NULL, note INT NOT NULL, INDEX IDX_606F401BDAE07E97 (blog_id), INDEX IDX_606F401BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE noteoeuvre (id INT AUTO_INCREMENT NOT NULL, id_oeuvre INT DEFAULT NULL, id_user INT DEFAULT NULL, note INT NOT NULL, INDEX fk_user (id_user), INDEX IDX_DAD661B513C99B13 (id_oeuvre), UNIQUE INDEX idx_noteoeuvre_id (id_oeuvre, id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, typereclamation INT DEFAULT NULL, title VARCHAR(30) NOT NULL, description VARCHAR(100) NOT NULL, date_creation DATE NOT NULL, etat VARCHAR(30) NOT NULL, date_treatment DATE DEFAULT NULL, note INT NOT NULL, ownerID INT DEFAULT NULL, INDEX IDX_CE606404B0284259 (typereclamation), INDEX ownerID (ownerID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_event_id INT DEFAULT NULL, id_participant_id INT DEFAULT NULL, is_confirmed TINYINT(1) NOT NULL, nb_place INT NOT NULL, INDEX IDX_42C84955212C041E (id_event_id), INDEX IDX_42C84955A07A8D1F (id_participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (user_id INT DEFAULT NULL, role_ID INT AUTO_INCREMENT NOT NULL, role VARCHAR(20) DEFAULT \'guest\', INDEX user_id (user_id), PRIMARY KEY(role_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typereclamation (id INT AUTO_INCREMENT NOT NULL, typeReclamation VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT \'\'\'aze.png\'\'\', phone INT NOT NULL, banned TINYINT(1) DEFAULT 0 NOT NULL, is_verified TINYINT(1) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artwork ADD CONSTRAINT FK_881FC576497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE artwork ADD CONSTRAINT FK_881FC576CF60E67C FOREIGN KEY (owner) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_blog (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire_blog ADD CONSTRAINT FK_29ED9511DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE commentsoeuvre ADD CONSTRAINT FK_F7F0250488194DE8 FOREIGN KEY (oeuvre_id) REFERENCES artwork (id)');
        $this->addSql('ALTER TABLE commentsoeuvre ADD CONSTRAINT FK_F7F02504A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donater ADD CONSTRAINT FK_4BD61C33613E6D35 FOREIGN KEY (id_donation) REFERENCES donation (id)');
        $this->addSql('ALTER TABLE donater ADD CONSTRAINT FK_4BD61C336B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0CF60E67C FOREIGN KEY (owner) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0497DD634 FOREIGN KEY (categorie) REFERENCES categorie_donation (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7BCF5E72D FOREIGN KEY (categorie_id) REFERENCES event_categorie (id)');
        $this->addSql('ALTER TABLE note_blog ADD CONSTRAINT FK_606F401BDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE note_blog ADD CONSTRAINT FK_606F401BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE noteoeuvre ADD CONSTRAINT FK_DAD661B513C99B13 FOREIGN KEY (id_oeuvre) REFERENCES artwork (id)');
        $this->addSql('ALTER TABLE noteoeuvre ADD CONSTRAINT FK_DAD661B56B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404DB30DDED FOREIGN KEY (ownerID) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404B0284259 FOREIGN KEY (typereclamation) REFERENCES typereclamation (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955212C041E FOREIGN KEY (id_event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artwork DROP FOREIGN KEY FK_881FC576497DD634');
        $this->addSql('ALTER TABLE artwork DROP FOREIGN KEY FK_881FC576CF60E67C');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143BCF5E72D');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143F675F31B');
        $this->addSql('ALTER TABLE commentaire_blog DROP FOREIGN KEY FK_29ED9511DAE07E97');
        $this->addSql('ALTER TABLE commentsoeuvre DROP FOREIGN KEY FK_F7F0250488194DE8');
        $this->addSql('ALTER TABLE commentsoeuvre DROP FOREIGN KEY FK_F7F02504A76ED395');
        $this->addSql('ALTER TABLE donater DROP FOREIGN KEY FK_4BD61C33613E6D35');
        $this->addSql('ALTER TABLE donater DROP FOREIGN KEY FK_4BD61C336B3CA4B');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0CF60E67C');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0497DD634');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7BCF5E72D');
        $this->addSql('ALTER TABLE note_blog DROP FOREIGN KEY FK_606F401BDAE07E97');
        $this->addSql('ALTER TABLE note_blog DROP FOREIGN KEY FK_606F401BA76ED395');
        $this->addSql('ALTER TABLE noteoeuvre DROP FOREIGN KEY FK_DAD661B513C99B13');
        $this->addSql('ALTER TABLE noteoeuvre DROP FOREIGN KEY FK_DAD661B56B3CA4B');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404DB30DDED');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404B0284259');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955212C041E');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A07A8D1F');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6AA76ED395');
        $this->addSql('DROP TABLE artwork');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_blog');
        $this->addSql('DROP TABLE categorie_donation');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE commentaire_blog');
        $this->addSql('DROP TABLE commentsoeuvre');
        $this->addSql('DROP TABLE donater');
        $this->addSql('DROP TABLE donation');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_categorie');
        $this->addSql('DROP TABLE note_blog');
        $this->addSql('DROP TABLE noteoeuvre');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE typereclamation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
