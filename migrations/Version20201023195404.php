<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201023195404 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, old_id INT DEFAULT NULL, title VARCHAR(145) DEFAULT NULL, details VARCHAR(255) DEFAULT NULL, start DATETIME DEFAULT NULL, end DATETIME DEFAULT NULL, allday TINYINT(1) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, color VARCHAR(20) DEFAULT NULL, customer_name VARCHAR(100) DEFAULT NULL, street VARCHAR(100) DEFAULT NULL, street_number VARCHAR(10) DEFAULT NULL, plz VARCHAR(10) DEFAULT NULL, ort VARCHAR(100) DEFAULT NULL, ansprechpartner VARCHAR(100) DEFAULT NULL, anrede VARCHAR(20) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, bermerkungen LONGTEXT DEFAULT NULL, termin_type VARCHAR(100) DEFAULT NULL, INDEX IDX_6EA9A146A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE calendar');
    }
}
