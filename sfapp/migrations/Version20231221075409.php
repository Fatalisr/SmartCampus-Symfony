<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231221075409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention DROP INDEX UNIQ_D11814AB62CAE146, ADD INDEX IDX_D11814AB62CAE146 (sa_id)');
        $this->addSql('ALTER TABLE intervention CHANGE type_i type_i VARCHAR(15) NOT NULL check (type_i in (\'INSTALLATION\',\'MAINTENANCE\')), CHANGE state state VARCHAR(8) DEFAULT NULL check (state in (\'EN_COURS\',\'FINIE\',\'ANNULEE\'))');
        $this->addSql('ALTER TABLE room CHANGE facing facing VARCHAR(1) NOT NULL check (facing in (\'N\',\'S\',\'E\',\'W\'))');
        $this->addSql('ALTER TABLE sa ADD intervention_id INT DEFAULT NULL, CHANGE state state VARCHAR(15) NOT NULL check (state in (\'INACTIF\',\'ACTIF\',\'MAINTENANCE\',\'A_INSTALLER\'))');
        $this->addSql('ALTER TABLE sa ADD CONSTRAINT FK_7F7E69048EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id)');
        $this->addSql('CREATE INDEX IDX_7F7E69048EAE3863 ON sa (intervention_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sa DROP FOREIGN KEY FK_7F7E69048EAE3863');
        $this->addSql('DROP INDEX IDX_7F7E69048EAE3863 ON sa');
        $this->addSql('ALTER TABLE sa DROP intervention_id, CHANGE state state VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE intervention DROP INDEX IDX_D11814AB62CAE146, ADD UNIQUE INDEX UNIQ_D11814AB62CAE146 (sa_id)');
        $this->addSql('ALTER TABLE intervention CHANGE type_i type_i VARCHAR(15) NOT NULL, CHANGE state state VARCHAR(8) DEFAULT NULL');
        $this->addSql('ALTER TABLE room CHANGE facing facing VARCHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
