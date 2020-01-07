<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191010125942 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment ADD owner_comment_id INT DEFAULT NULL, ADD renter_comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CAA03E456 FOREIGN KEY (owner_comment_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4DF9D4F1 FOREIGN KEY (renter_comment_id) REFERENCES booking (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9474526CAA03E456 ON comment (owner_comment_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9474526C4DF9D4F1 ON comment (renter_comment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CAA03E456');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4DF9D4F1');
        $this->addSql('DROP INDEX UNIQ_9474526CAA03E456 ON comment');
        $this->addSql('DROP INDEX UNIQ_9474526C4DF9D4F1 ON comment');
        $this->addSql('ALTER TABLE comment DROP owner_comment_id, DROP renter_comment_id');
    }
}
