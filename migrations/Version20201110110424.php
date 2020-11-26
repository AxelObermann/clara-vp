<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201110110424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress ADD customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_5CECC7BE9395C3F3 ON adress (customer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE9395C3F3');
        $this->addSql('DROP INDEX IDX_5CECC7BE9395C3F3 ON adress');
        $this->addSql('ALTER TABLE adress DROP customer_id');
    }
}
