<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230402163916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artwork CHANGE owner owner INT DEFAULT NULL, CHANGE categorie categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog CHANGE author author INT DEFAULT NULL, CHANGE categorie categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143BDAFD8C8 FOREIGN KEY (author) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaireblog CHANGE id_blog id_blog INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentsoeuvre CHANGE oeuvre_id oeuvre_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentsoeuvre ADD CONSTRAINT FK_F7F0250488194DE8 FOREIGN KEY (oeuvre_id) REFERENCES artwork (id)');
        $this->addSql('ALTER TABLE commentsoeuvre ADD CONSTRAINT FK_F7F02504A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donater CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_donation id_donation INT DEFAULT NULL');
        $this->addSql('ALTER TABLE donater ADD CONSTRAINT FK_4BD61C33613E6D35 FOREIGN KEY (id_donation) REFERENCES donation (id)');
        $this->addSql('ALTER TABLE donater ADD CONSTRAINT FK_4BD61C336B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4BD61C33613E6D35 ON donater (id_donation)');
        $this->addSql('ALTER TABLE donation CHANGE owner owner INT DEFAULT NULL, CHANGE categorie categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0CF60E67C FOREIGN KEY (owner) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE event CHANGE categorie categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE noteblog CHANGE id_blog id_blog INT DEFAULT NULL');
        $this->addSql('ALTER TABLE noteblog ADD CONSTRAINT FK_FD8D68594B354D41 FOREIGN KEY (id_blog) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE noteoeuvre CHANGE id_oeuvre id_oeuvre INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE noteoeuvre ADD CONSTRAINT FK_DAD661B513C99B13 FOREIGN KEY (id_oeuvre) REFERENCES artwork (id)');
        $this->addSql('ALTER TABLE noteoeuvre ADD CONSTRAINT FK_DAD661B56B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DAD661B513C99B13 ON noteoeuvre (id_oeuvre)');
        $this->addSql('ALTER TABLE reclamation CHANGE ownerID ownerID INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404DB30DDED FOREIGN KEY (ownerID) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation CHANGE id_participant id_participant INT DEFAULT NULL, CHANGE id_event id_event INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D52B4B97 FOREIGN KEY (id_event) REFERENCES event (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955CF8DA6E6 FOREIGN KEY (id_participant) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role CHANGE role role VARCHAR(20) DEFAULT \'guest\'');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE typereclamation CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE typereclamation ADD CONSTRAINT FK_B0284259BF396750 FOREIGN KEY (id) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE artwork CHANGE categorie categorie INT NOT NULL, CHANGE owner owner INT NOT NULL');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143497DD634');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143BDAFD8C8');
        $this->addSql('ALTER TABLE blog CHANGE categorie categorie INT NOT NULL, CHANGE author author INT NOT NULL');
        $this->addSql('ALTER TABLE commentaireblog CHANGE id_user id_user INT NOT NULL, CHANGE id_blog id_blog INT NOT NULL');
        $this->addSql('ALTER TABLE commentsoeuvre DROP FOREIGN KEY FK_F7F0250488194DE8');
        $this->addSql('ALTER TABLE commentsoeuvre DROP FOREIGN KEY FK_F7F02504A76ED395');
        $this->addSql('ALTER TABLE commentsoeuvre CHANGE oeuvre_id oeuvre_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE donater DROP FOREIGN KEY FK_4BD61C33613E6D35');
        $this->addSql('ALTER TABLE donater DROP FOREIGN KEY FK_4BD61C336B3CA4B');
        $this->addSql('DROP INDEX IDX_4BD61C33613E6D35 ON donater');
        $this->addSql('ALTER TABLE donater CHANGE id_donation id_donation INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0CF60E67C');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0497DD634');
        $this->addSql('ALTER TABLE donation CHANGE owner owner INT NOT NULL, CHANGE categorie categorie INT NOT NULL');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7497DD634');
        $this->addSql('ALTER TABLE event CHANGE categorie categorie INT NOT NULL');
        $this->addSql('ALTER TABLE noteblog DROP FOREIGN KEY FK_FD8D68594B354D41');
        $this->addSql('ALTER TABLE noteblog CHANGE id_blog id_blog INT NOT NULL');
        $this->addSql('ALTER TABLE noteoeuvre DROP FOREIGN KEY FK_DAD661B513C99B13');
        $this->addSql('ALTER TABLE noteoeuvre DROP FOREIGN KEY FK_DAD661B56B3CA4B');
        $this->addSql('DROP INDEX IDX_DAD661B513C99B13 ON noteoeuvre');
        $this->addSql('ALTER TABLE noteoeuvre CHANGE id_oeuvre id_oeuvre INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404DB30DDED');
        $this->addSql('ALTER TABLE reclamation CHANGE ownerID ownerID INT NOT NULL');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D52B4B97');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955CF8DA6E6');
        $this->addSql('ALTER TABLE reservation CHANGE id_event id_event INT NOT NULL, CHANGE id_participant id_participant INT NOT NULL');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6AA76ED395');
        $this->addSql('ALTER TABLE role CHANGE role role VARCHAR(20) DEFAULT \'\'\'guest\'\'\'');
        $this->addSql('ALTER TABLE typereclamation DROP FOREIGN KEY FK_B0284259BF396750');
        $this->addSql('ALTER TABLE typereclamation CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
