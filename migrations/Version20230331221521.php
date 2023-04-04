<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230331221521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
       
        $this->addSql('ALTER TABLE reclamation ADD typereclamation INT DEFAULT NULL, DROP typereclamation_id, CHANGE date_treatment date_treatment DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404B0284259 FOREIGN KEY (typereclamation) REFERENCES typereclamation (id)');
        $this->addSql('CREATE INDEX IDX_CE606404B0284259 ON reclamation (typereclamation)');
        
       
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
       
        
        $this->addSql('ALTER TABLE reclamation ADD typereclamation_id INT NOT NULL, DROP typereclamation, CHANGE date_treatment date_treatment DATE DEFAULT \'NULL\'');
       
    }
}
