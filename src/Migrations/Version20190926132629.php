<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190926132629 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vehicle ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_1B80E486C54C8C93 ON vehicle (type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486C54C8C93');
        $this->addSql('DROP INDEX IDX_1B80E486C54C8C93 ON vehicle');
        $this->addSql('ALTER TABLE vehicle DROP type_id');
    }
}
