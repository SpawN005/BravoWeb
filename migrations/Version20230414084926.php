<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414084926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_event_id INT DEFAULT NULL, id_participant_id INT DEFAULT NULL, is_confirmed TINYINT(1) NOT NULL, nb_place INT NOT NULL, INDEX IDX_42C84955212C041E (id_event_id), INDEX IDX_42C84955A07A8D1F (id_participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955212C041E FOREIGN KEY (id_event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955212C041E');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A07A8D1F');
        $this->addSql('DROP TABLE reservation');
    }
}
