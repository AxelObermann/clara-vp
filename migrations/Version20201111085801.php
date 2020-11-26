<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201111085801 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, adress_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, street_number VARCHAR(255) DEFAULT NULL, zip VARCHAR(255) DEFAULT NULL, town VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, adresstype VARCHAR(255) NOT NULL, INDEX IDX_5CECC7BE8486F9AC (adress_id), INDEX IDX_5CECC7BE9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, full_name VARCHAR(255) DEFAULT NULL, contact_person VARCHAR(255) DEFAULT NULL, old_id INT NOT NULL, INDEX IDX_81398E09A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE8486F9AC FOREIGN KEY (adress_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE8486F9AC');
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE9395C3F3');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE customer');
    }
}
