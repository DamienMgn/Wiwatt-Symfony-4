<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191007101606 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649515A8525');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B6A0B582');
        $this->addSql('DROP INDEX IDX_8D93D649B6A0B582 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649515A8525 ON user');
        $this->addSql('ALTER TABLE user DROP owner_booking_id, DROP renter_booking_id');
        $this->addSql('ALTER TABLE booking ADD owner_id INT NOT NULL, ADD renter_id INT NOT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEE289A545 FOREIGN KEY (renter_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE7E3C61F9 ON booking (owner_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDEE289A545 ON booking (renter_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE7E3C61F9');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEE289A545');
        $this->addSql('DROP INDEX IDX_E00CEDDE7E3C61F9 ON booking');
        $this->addSql('DROP INDEX IDX_E00CEDDEE289A545 ON booking');
        $this->addSql('ALTER TABLE booking DROP owner_id, DROP renter_id');
        $this->addSql('ALTER TABLE user ADD owner_booking_id INT DEFAULT NULL, ADD renter_booking_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649515A8525 FOREIGN KEY (owner_booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B6A0B582 FOREIGN KEY (renter_booking_id) REFERENCES booking (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B6A0B582 ON user (renter_booking_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649515A8525 ON user (owner_booking_id)');
    }
}
