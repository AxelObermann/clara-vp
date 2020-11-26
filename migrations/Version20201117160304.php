<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201117160304 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_place ADD customer_id INT DEFAULT NULL, ADD zaehlernummer VARCHAR(255) DEFAULT NULL, ADD malo_id VARCHAR(255) DEFAULT NULL, ADD melo_id VARCHAR(255) DEFAULT NULL, ADD zaehlertyp VARCHAR(255) DEFAULT NULL, ADD seperater_zaehler VARCHAR(255) DEFAULT NULL, ADD verbrauch VARCHAR(255) DEFAULT NULL, ADD verbrauch_ht VARCHAR(255) DEFAULT NULL, ADD verbrauch_nt VARCHAR(255) DEFAULT NULL, ADD ap VARCHAR(255) DEFAULT NULL, ADD apbrutto VARCHAR(255) DEFAULT NULL, ADD apht VARCHAR(255) DEFAULT NULL, ADD aphtbrutto VARCHAR(255) DEFAULT NULL, ADD apnt VARCHAR(255) DEFAULT NULL, ADD apntbrutto VARCHAR(255) DEFAULT NULL, ADD gp VARCHAR(255) DEFAULT NULL, ADD gp_brutto VARCHAR(255) DEFAULT NULL, ADD abschlussprovision VARCHAR(255) DEFAULT NULL, ADD lifetimeprov_m VARCHAR(255) DEFAULT NULL, ADD folgeprovision_j VARCHAR(255) DEFAULT NULL, ADD folgeprov_m VARCHAR(255) DEFAULT NULL, ADD bonus_provision VARCHAR(255) DEFAULT NULL, ADD bonus_provision_verl VARCHAR(255) DEFAULT NULL, ADD status VARCHAR(255) DEFAULT NULL, ADD bonus_code VARCHAR(255) DEFAULT NULL, ADD spanne_pkw_h VARCHAR(255) DEFAULT NULL, ADD old_id INT NOT NULL, ADD deleted TINYINT(1) DEFAULT NULL, ADD inbelieferung TINYINT(1) NOT NULL, ADD belieferungsstart DATE DEFAULT NULL, ADD vers_kd_nr VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE delivery_place ADD CONSTRAINT FK_6416B55F9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_6416B55F9395C3F3 ON delivery_place (customer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_place DROP FOREIGN KEY FK_6416B55F9395C3F3');
        $this->addSql('DROP INDEX IDX_6416B55F9395C3F3 ON delivery_place');
        $this->addSql('ALTER TABLE delivery_place DROP customer_id, DROP zaehlernummer, DROP malo_id, DROP melo_id, DROP zaehlertyp, DROP seperater_zaehler, DROP verbrauch, DROP verbrauch_ht, DROP verbrauch_nt, DROP ap, DROP apbrutto, DROP apht, DROP aphtbrutto, DROP apnt, DROP apntbrutto, DROP gp, DROP gp_brutto, DROP abschlussprovision, DROP lifetimeprov_m, DROP folgeprovision_j, DROP folgeprov_m, DROP bonus_provision, DROP bonus_provision_verl, DROP status, DROP bonus_code, DROP spanne_pkw_h, DROP old_id, DROP deleted, DROP inbelieferung, DROP belieferungsstart, DROP vers_kd_nr');
    }
}
