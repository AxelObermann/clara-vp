<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201117160246 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE delivery_place (id INT AUTO_INCREMENT NOT NULL, system_id VARCHAR(20) DEFAULT NULL, tarifnummer VARCHAR(50) DEFAULT NULL, firmenname VARCHAR(100) DEFAULT NULL, unternehmensform VARCHAR(100) DEFAULT NULL, anrede VARCHAR(50) DEFAULT NULL, titel VARCHAR(50) DEFAULT NULL, vorname VARCHAR(100) DEFAULT NULL, nachname VARCHAR(100) DEFAULT NULL, strasse VARCHAR(100) DEFAULT NULL, hausnummer VARCHAR(100) DEFAULT NULL, plz VARCHAR(10) DEFAULT NULL, ort VARCHAR(10) DEFAULT NULL, telefon VARCHAR(20) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, geburtstag DATE DEFAULT NULL, contractadr_iis VARCHAR(100) DEFAULT NULL, vnfirma VARCHAR(100) DEFAULT NULL, vnanrede VARCHAR(100) DEFAULT NULL, vntitel VARCHAR(100) DEFAULT NULL, vnvorname VARCHAR(100) DEFAULT NULL, vnnachname VARCHAR(100) DEFAULT NULL, vnstrasse VARCHAR(100) DEFAULT NULL, vnhnr VARCHAR(100) DEFAULT NULL, vnplz VARCHAR(255) DEFAULT NULL, vnort VARCHAR(255) DEFAULT NULL, vntelefon VARCHAR(255) DEFAULT NULL, vnemail VARCHAR(255) DEFAULT NULL, vngeburtstag DATE DEFAULT NULL, billingadr_iis VARCHAR(255) DEFAULT NULL, re_firma VARCHAR(255) DEFAULT NULL, re_anrede VARCHAR(255) DEFAULT NULL, re_titel VARCHAR(255) DEFAULT NULL, re_vorname VARCHAR(255) DEFAULT NULL, re_nachname VARCHAR(255) DEFAULT NULL, re_strasse VARCHAR(255) DEFAULT NULL, re_hausnummer VARCHAR(255) DEFAULT NULL, re_plz VARCHAR(255) DEFAULT NULL, re_ort VARCHAR(255) DEFAULT NULL, re_telefon VARCHAR(255) DEFAULT NULL, re_email VARCHAR(255) DEFAULT NULL, re_geburtstag DATE NOT NULL, versorger VARCHAR(255) DEFAULT NULL, tarifname VARCHAR(255) DEFAULT NULL, vorversorger VARCHAR(255) DEFAULT NULL, vorversorger_code VARCHAR(255) DEFAULT NULL, kundennummer VARCHAR(255) DEFAULT NULL, auftragsdatum DATE DEFAULT NULL, vertragsbeginn DATETIME DEFAULT NULL, dauer VARCHAR(255) DEFAULT NULL, medium VARCHAR(255) DEFAULT NULL, medium_typ VARCHAR(255) DEFAULT NULL, kundenart VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE delivery_place');
    }
}
