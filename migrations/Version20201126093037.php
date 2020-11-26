<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201126093037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_place DROP vnfirma, DROP vnanrede, DROP vntitel, DROP vnvorname, DROP vnnachname, DROP vnstrasse, DROP vnhnr, DROP vnplz, DROP vnort, DROP vntelefon, DROP vnemail, DROP vngeburtstag, CHANGE geburtstag geburtstag VARCHAR(20) DEFAULT NULL, CHANGE re_geburtstag re_geburtstag VARCHAR(20) DEFAULT NULL, CHANGE vertragsbeginn vertragsbeginn VARCHAR(20) DEFAULT NULL, CHANGE belieferungsstart belieferungsstart VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_place ADD vnfirma VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vnanrede VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vntitel VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vnvorname VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vnnachname VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vnstrasse VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vnhnr VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vnplz VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vnort VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vntelefon VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vnemail VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vngeburtstag DATE DEFAULT NULL, CHANGE geburtstag geburtstag DATE DEFAULT NULL, CHANGE re_geburtstag re_geburtstag DATE DEFAULT NULL, CHANGE vertragsbeginn vertragsbeginn DATETIME DEFAULT NULL, CHANGE belieferungsstart belieferungsstart DATE DEFAULT NULL');
    }
}
