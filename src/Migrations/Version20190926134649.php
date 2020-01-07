<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190926134649 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vehicle ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1B80E486A76ED395 ON vehicle (user_id)');
        $this->addSql('ALTER TABLE date ADD renter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377AE289A545 FOREIGN KEY (renter_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AA9E377AE289A545 ON date (renter_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377AE289A545');
        $this->addSql('DROP INDEX IDX_AA9E377AE289A545 ON date');
        $this->addSql('ALTER TABLE date DROP renter_id');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486A76ED395');
        $this->addSql('DROP INDEX IDX_1B80E486A76ED395 ON vehicle');
        $this->addSql('ALTER TABLE vehicle DROP user_id');
    }
}
