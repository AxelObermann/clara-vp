<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130082941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification ADD customer_id INT DEFAULT NULL, ADD delvery_place_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA15C761CF FOREIGN KEY (delvery_place_id) REFERENCES delivery_place (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA9395C3F3 ON notification (customer_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA15C761CF ON notification (delvery_place_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA9395C3F3');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA15C761CF');
        $this->addSql('DROP INDEX IDX_BF5476CA9395C3F3 ON notification');
        $this->addSql('DROP INDEX IDX_BF5476CA15C761CF ON notification');
        $this->addSql('ALTER TABLE notification DROP customer_id, DROP delvery_place_id');
    }
}
