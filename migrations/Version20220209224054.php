<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220209224054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product CHANGE date_creation date_creation DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(1000) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE coleur coleur VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE image image VARCHAR(500) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
