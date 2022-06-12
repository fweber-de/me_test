<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220612075921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_3BAE0AA764D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events_contacts (event_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_CE748D7071F7E88B (event_id), INDEX IDX_CE748D70E7A1254A (contact_id), PRIMARY KEY(event_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_location (id INT AUTO_INCREMENT NOT NULL, venue VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, zipcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA764D218E FOREIGN KEY (location_id) REFERENCES event_location (id)');
        $this->addSql('ALTER TABLE events_contacts ADD CONSTRAINT FK_CE748D7071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events_contacts ADD CONSTRAINT FK_CE748D70E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events_contacts DROP FOREIGN KEY FK_CE748D70E7A1254A');
        $this->addSql('ALTER TABLE events_contacts DROP FOREIGN KEY FK_CE748D7071F7E88B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA764D218E');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE events_contacts');
        $this->addSql('DROP TABLE event_location');
    }
}
