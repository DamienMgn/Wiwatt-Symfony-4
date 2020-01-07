<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191007083855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD owner_booking_id INT DEFAULT NULL, ADD renter_booking_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649515A8525 FOREIGN KEY (owner_booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B6A0B582 FOREIGN KEY (renter_booking_id) REFERENCES booking (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649515A8525 ON user (owner_booking_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B6A0B582 ON user (renter_booking_id)');
        $this->addSql('ALTER TABLE date DROP status');
        $this->addSql('ALTER TABLE booking ADD vehicle_id INT NOT NULL, ADD vehicle_name VARCHAR(255) NOT NULL, ADD date VARCHAR(255) NOT NULL, ADD status INT NOT NULL, DROP day_number, DROP total_price, DROP created_at');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE545317D1 ON booking (vehicle_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE545317D1');
        $this->addSql('DROP INDEX IDX_E00CEDDE545317D1 ON booking');
        $this->addSql('ALTER TABLE booking ADD day_number INT NOT NULL, ADD total_price INT NOT NULL, ADD created_at DATETIME NOT NULL, DROP vehicle_id, DROP vehicle_name, DROP date, DROP status');
        $this->addSql('ALTER TABLE date ADD status INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649515A8525');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B6A0B582');
        $this->addSql('DROP INDEX IDX_8D93D649515A8525 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649B6A0B582 ON user');
        $this->addSql('ALTER TABLE user DROP owner_booking_id, DROP renter_booking_id');
    }
}
