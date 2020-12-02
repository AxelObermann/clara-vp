<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201202125708 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_place ADD facility_user_id INT DEFAULT NULL, ADD stab DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE delivery_place ADD CONSTRAINT FK_6416B55F17137FA0 FOREIGN KEY (facility_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6416B55F17137FA0 ON delivery_place (facility_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_place DROP FOREIGN KEY FK_6416B55F17137FA0');
        $this->addSql('DROP INDEX IDX_6416B55F17137FA0 ON delivery_place');
        $this->addSql('ALTER TABLE delivery_place DROP facility_user_id, DROP stab');
    }
}
