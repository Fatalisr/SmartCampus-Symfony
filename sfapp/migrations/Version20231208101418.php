<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231208101418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention CHANGE message message VARCHAR(500) DEFAULT NULL, CHANGE type type VARCHAR(15) NOT NULL check (type in (\'INSTALLATION\',\'MAINTENANCE\'))');
        $this->addSql('ALTER TABLE member CHANGE role role VARCHAR(15) NOT NULL check (role in (\'REFERENT\',\'TECHNICIEN\'))');
        $this->addSql('ALTER TABLE room CHANGE facing facing VARCHAR(1) NOT NULL check (facing in (\'N\',\'S\',\'E\',\'W\'))');
        $this->addSql('ALTER TABLE sa CHANGE state state VARCHAR(15) NOT NULL check (state in (\'INACTIF\',\'ACTIF\',\'MAINTENANCE\',\'A_INSTALLER\'))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member CHANGE role role VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE sa CHANGE state state VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE room CHANGE facing facing VARCHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE intervention CHANGE message message VARCHAR(500) NOT NULL, CHANGE type type VARCHAR(15) NOT NULL');
    }
}
