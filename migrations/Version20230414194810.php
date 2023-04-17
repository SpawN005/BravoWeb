<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414194810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artwork (id INT AUTO_INCREMENT NOT NULL, categorie INT DEFAULT NULL, owner INT DEFAULT NULL, title VARCHAR(30) NOT NULL, description TEXT NOT NULL, url VARCHAR(200) NOT NULL, INDEX artwork_fk (owner), INDEX FK_artwork (categorie), UNIQUE INDEX title (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, categorie INT DEFAULT NULL, author INT DEFAULT NULL, title VARCHAR(30) NOT NULL, description VARCHAR(100) NOT NULL, url VARCHAR(10000) NOT NULL, content VARCHAR(1000) NOT NULL, INDEX FK_blogcat (categorie), INDEX fk_user (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, NomCategorie VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(1000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaireblog (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_blog INT DEFAULT NULL, content VARCHAR(1000) NOT NULL, INDEX fk_CommBlog (id_blog), INDEX fk_CommUser (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentsoeuvre (id INT AUTO_INCREMENT NOT NULL, oeuvre_id INT DEFAULT NULL, user_id INT DEFAULT NULL, comment VARCHAR(255) NOT NULL, timestamp DATETIME DEFAULT \'current_timestamp()\' NOT NULL, INDEX oeuvre_id (oeuvre_id), INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donater (id INT AUTO_INCREMENT NOT NULL, id_donation INT DEFAULT NULL, id_user INT DEFAULT NULL, amount INT NOT NULL, INDEX fk_user (id_user), INDEX IDX_4BD61C33613E6D35 (id_donation), UNIQUE INDEX idx_donation (id_donation, id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donation (id INT AUTO_INCREMENT NOT NULL, owner INT DEFAULT NULL, categorie INT DEFAULT NULL, title VARCHAR(30) NOT NULL, description VARCHAR(100) NOT NULL, date_creation DATE NOT NULL, date_expiration DATE NOT NULL, amount INT NOT NULL, INDEX owner (owner), INDEX FK_donationcat (categorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, categorie INT DEFAULT NULL, title VARCHAR(30) NOT NULL, description VARCHAR(100) NOT NULL, nb_placeMax INT NOT NULL, date_beg DATE NOT NULL, date_end DATE NOT NULL, type_event VARCHAR(30) NOT NULL, url VARCHAR(255) NOT NULL, INDEX FK_eventcat (categorie), UNIQUE INDEX uk_title (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE noteblog (id INT AUTO_INCREMENT NOT NULL, id_blog INT DEFAULT NULL, note INT NOT NULL, INDEX id_blog (id_blog), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE noteoeuvre (id INT AUTO_INCREMENT NOT NULL, id_oeuvre INT DEFAULT NULL, id_user INT DEFAULT NULL, note INT NOT NULL, INDEX fk_user (id_user), INDEX IDX_DAD661B513C99B13 (id_oeuvre), UNIQUE INDEX idx_noteoeuvre_id (id_oeuvre, id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(30) NOT NULL, description VARCHAR(100) NOT NULL, date_creation DATE NOT NULL, etat VARCHAR(30) NOT NULL, date_treatment DATE NOT NULL, note INT NOT NULL, ownerID INT DEFAULT NULL, INDEX ownerID (ownerID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_event INT DEFAULT NULL, id_participant INT DEFAULT NULL, isConfirmed TINYINT(1) NOT NULL, nb_place INT NOT NULL, INDEX fk_id_participant (id_participant), INDEX fk_event (id_event), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (user_id INT DEFAULT NULL, role_ID INT AUTO_INCREMENT NOT NULL, role VARCHAR(20) DEFAULT \'guest\', INDEX user_id (user_id), PRIMARY KEY(role_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typereclamation (id INT NOT NULL, typeReclamation VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, phone INT NOT NULL, banned TINYINT(1) NOT NULL, is_verified TINYINT(1) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artwork ADD CONSTRAINT FK_881FC576497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE artwork ADD CONSTRAINT FK_881FC576CF60E67C FOREIGN KEY (owner) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143BDAFD8C8 FOREIGN KEY (author) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaireblog ADD CONSTRAINT FK_161F2FBF6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaireblog ADD CONSTRAINT FK_161F2FBF4B354D41 FOREIGN KEY (id_blog) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE commentsoeuvre ADD CONSTRAINT FK_F7F0250488194DE8 FOREIGN KEY (oeuvre_id) REFERENCES artwork (id)');
        $this->addSql('ALTER TABLE commentsoeuvre ADD CONSTRAINT FK_F7F02504A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donater ADD CONSTRAINT FK_4BD61C33613E6D35 FOREIGN KEY (id_donation) REFERENCES donation (id)');
        $this->addSql('ALTER TABLE donater ADD CONSTRAINT FK_4BD61C336B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0CF60E67C FOREIGN KEY (owner) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE noteblog ADD CONSTRAINT FK_FD8D68594B354D41 FOREIGN KEY (id_blog) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE noteoeuvre ADD CONSTRAINT FK_DAD661B513C99B13 FOREIGN KEY (id_oeuvre) REFERENCES artwork (id)');
        $this->addSql('ALTER TABLE noteoeuvre ADD CONSTRAINT FK_DAD661B56B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404DB30DDED FOREIGN KEY (ownerID) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D52B4B97 FOREIGN KEY (id_event) REFERENCES event (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955CF8DA6E6 FOREIGN KEY (id_participant) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE typereclamation ADD CONSTRAINT FK_B0284259BF396750 FOREIGN KEY (id) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artwork DROP FOREIGN KEY FK_881FC576497DD634');
        $this->addSql('ALTER TABLE artwork DROP FOREIGN KEY FK_881FC576CF60E67C');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143497DD634');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143BDAFD8C8');
        $this->addSql('ALTER TABLE commentaireblog DROP FOREIGN KEY FK_161F2FBF6B3CA4B');
        $this->addSql('ALTER TABLE commentaireblog DROP FOREIGN KEY FK_161F2FBF4B354D41');
        $this->addSql('ALTER TABLE commentsoeuvre DROP FOREIGN KEY FK_F7F0250488194DE8');
        $this->addSql('ALTER TABLE commentsoeuvre DROP FOREIGN KEY FK_F7F02504A76ED395');
        $this->addSql('ALTER TABLE donater DROP FOREIGN KEY FK_4BD61C33613E6D35');
        $this->addSql('ALTER TABLE donater DROP FOREIGN KEY FK_4BD61C336B3CA4B');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0CF60E67C');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0497DD634');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7497DD634');
        $this->addSql('ALTER TABLE noteblog DROP FOREIGN KEY FK_FD8D68594B354D41');
        $this->addSql('ALTER TABLE noteoeuvre DROP FOREIGN KEY FK_DAD661B513C99B13');
        $this->addSql('ALTER TABLE noteoeuvre DROP FOREIGN KEY FK_DAD661B56B3CA4B');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404DB30DDED');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D52B4B97');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955CF8DA6E6');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6AA76ED395');
        $this->addSql('ALTER TABLE typereclamation DROP FOREIGN KEY FK_B0284259BF396750');
        $this->addSql('DROP TABLE artwork');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE commentaireblog');
        $this->addSql('DROP TABLE commentsoeuvre');
        $this->addSql('DROP TABLE donater');
        $this->addSql('DROP TABLE donation');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE noteblog');
        $this->addSql('DROP TABLE noteoeuvre');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE typereclamation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
