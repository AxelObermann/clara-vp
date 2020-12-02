<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130085354 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deliver_place_check ADD updated_from_id INT NOT NULL, ADD created DATE DEFAULT NULL, ADD updated DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE deliver_place_check ADD CONSTRAINT FK_1FFAAE7464C49DD8 FOREIGN KEY (updated_from_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1FFAAE7464C49DD8 ON deliver_place_check (updated_from_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deliver_place_check DROP FOREIGN KEY FK_1FFAAE7464C49DD8');
        $this->addSql('DROP INDEX UNIQ_1FFAAE7464C49DD8 ON deliver_place_check');
        $this->addSql('ALTER TABLE deliver_place_check DROP updated_from_id, DROP created, DROP updated');
    }
}
