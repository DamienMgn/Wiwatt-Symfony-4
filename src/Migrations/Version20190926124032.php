<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190926124032 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(30) NOT NULL, ADD lastname VARCHAR(30) NOT NULL, ADD avatar VARCHAR(255) NOT NULL, ADD department VARCHAR(255) DEFAULT NULL, ADD zip_code INT NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD adress VARCHAR(255) NOT NULL, ADD longitude DOUBLE PRECISION NOT NULL, ADD latitude DOUBLE PRECISION NOT NULL, ADD average_score INT DEFAULT NULL, ADD visible_status TINYINT(1) NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP firstname, DROP lastname, DROP avatar, DROP department, DROP zip_code, DROP city, DROP adress, DROP longitude, DROP latitude, DROP average_score, DROP visible_status, DROP created_at, DROP updated_at');
    }
}
