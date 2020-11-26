<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201109164011 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) DEFAULT NULL, contact_person VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adress ADD adress_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE8486F9AC FOREIGN KEY (adress_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_5CECC7BE8486F9AC ON adress (adress_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE8486F9AC');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP INDEX IDX_5CECC7BE8486F9AC ON adress');
        $this->addSql('ALTER TABLE adress DROP adress_id');
    }
}
