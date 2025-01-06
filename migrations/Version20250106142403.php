<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250106142403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, logo_text VARCHAR(255) NOT NULL, block1_title VARCHAR(255) DEFAULT NULL, block1_text LONGTEXT DEFAULT NULL, block2_title VARCHAR(255) DEFAULT NULL, block2_text LONGTEXT DEFAULT NULL, block3_title VARCHAR(255) DEFAULT NULL, block3_text LONGTEXT DEFAULT NULL, footer_text LONGTEXT DEFAULT NULL, footer_link1 VARCHAR(255) DEFAULT NULL, footer_link1_label VARCHAR(255) DEFAULT NULL, footer_link2 VARCHAR(255) DEFAULT NULL, footer_link2_label VARCHAR(255) DEFAULT NULL, footer_link3 VARCHAR(255) DEFAULT NULL, footer_link3_label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE config');
    }
}
