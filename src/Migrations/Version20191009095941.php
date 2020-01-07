<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191009095941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE phone_number phone_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7E3C61F9');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE289A545');
        $this->addSql('DROP INDEX IDX_9474526CE289A545 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C7E3C61F9 ON comment');
        $this->addSql('ALTER TABLE comment DROP owner_id, DROP renter_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment ADD owner_id INT NOT NULL, ADD renter_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE289A545 FOREIGN KEY (renter_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526CE289A545 ON comment (renter_id)');
        $this->addSql('CREATE INDEX IDX_9474526C7E3C61F9 ON comment (owner_id)');
        $this->addSql('ALTER TABLE user CHANGE phone_number phone_number VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
