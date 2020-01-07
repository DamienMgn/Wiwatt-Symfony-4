<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191004145116 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE545317D1');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEE289A545');
        $this->addSql('DROP INDEX IDX_E00CEDDE545317D1 ON booking');
        $this->addSql('DROP INDEX IDX_E00CEDDEE289A545 ON booking');
        $this->addSql('ALTER TABLE booking DROP vehicle_id, DROP renter_id, DROP updated_at');
        $this->addSql('ALTER TABLE user CHANGE avatar avatar VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377A3301C60');
        $this->addSql('DROP INDEX IDX_AA9E377A3301C60 ON date');
        $this->addSql('ALTER TABLE date DROP booking_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking ADD vehicle_id INT NOT NULL, ADD renter_id INT NOT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEE289A545 FOREIGN KEY (renter_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE545317D1 ON booking (vehicle_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDEE289A545 ON booking (renter_id)');
        $this->addSql('ALTER TABLE date ADD booking_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377A3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('CREATE INDEX IDX_AA9E377A3301C60 ON date (booking_id)');
        $this->addSql('ALTER TABLE user CHANGE avatar avatar VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
