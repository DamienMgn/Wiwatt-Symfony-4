<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190926134916 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE date ADD vehicle_id INT NOT NULL');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377A545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE INDEX IDX_AA9E377A545317D1 ON date (vehicle_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377A545317D1');
        $this->addSql('DROP INDEX IDX_AA9E377A545317D1 ON date');
        $this->addSql('ALTER TABLE date DROP vehicle_id');
    }
}
