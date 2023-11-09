<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109094907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sa ADD current_room_id INT DEFAULT NULL, ADD old_room_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sa ADD CONSTRAINT FK_7F7E6904FE1AF516 FOREIGN KEY (current_room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE sa ADD CONSTRAINT FK_7F7E69042782A9C3 FOREIGN KEY (old_room_id) REFERENCES room (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7F7E6904FE1AF516 ON sa (current_room_id)');
        $this->addSql('CREATE INDEX IDX_7F7E69042782A9C3 ON sa (old_room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sa DROP FOREIGN KEY FK_7F7E6904FE1AF516');
        $this->addSql('ALTER TABLE sa DROP FOREIGN KEY FK_7F7E69042782A9C3');
        $this->addSql('DROP INDEX UNIQ_7F7E6904FE1AF516 ON sa');
        $this->addSql('DROP INDEX IDX_7F7E69042782A9C3 ON sa');
        $this->addSql('ALTER TABLE sa DROP current_room_id, DROP old_room_id');
    }
}
