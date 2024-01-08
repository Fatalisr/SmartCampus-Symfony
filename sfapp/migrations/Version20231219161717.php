<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219161717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention ADD type_i VARCHAR(15) NOT NULL check (type_I in (\'INSTALLATION\',\'MAINTENANCE\')), DROP type, CHANGE state state VARCHAR(8) DEFAULT NULL check (state in (\'EN_COURS\',\'FINIE\',\'ANNULEE\'))');
        $this->addSql('ALTER TABLE room CHANGE facing facing VARCHAR(1) NOT NULL check (facing in (\'N\',\'S\',\'E\',\'W\'))');
        $this->addSql('ALTER TABLE sa CHANGE state state VARCHAR(15) NOT NULL check (state in (\'INACTIF\',\'ACTIF\',\'MAINTENANCE\',\'A_INSTALLER\'))');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE intervention ADD type VARCHAR(15) NOT NULL, DROP type_i, CHANGE state state VARCHAR(8) DEFAULT NULL');
        $this->addSql('ALTER TABLE room CHANGE facing facing VARCHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE sa CHANGE state state VARCHAR(15) NOT NULL');
    }
}
