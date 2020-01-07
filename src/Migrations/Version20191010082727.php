<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191010082727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vehicle CHANGE model model VARCHAR(30) DEFAULT NULL, CHANGE seat_number seat_number INT DEFAULT NULL, CHANGE max_speed max_speed INT DEFAULT NULL, CHANGE weight weight INT DEFAULT NULL, CHANGE power power INT DEFAULT NULL, CHANGE autonomy autonomy INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vehicle CHANGE model model VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE seat_number seat_number INT NOT NULL, CHANGE max_speed max_speed INT NOT NULL, CHANGE weight weight INT NOT NULL, CHANGE power power INT NOT NULL, CHANGE autonomy autonomy INT NOT NULL');
    }
}
