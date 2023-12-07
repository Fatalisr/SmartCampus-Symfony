<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207094529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, sa_id INT NOT NULL, starting_date DATE DEFAULT NULL, ending_date DATE DEFAULT NULL, message VARCHAR(500) NOT NULL, report VARCHAR(500) DEFAULT NULL, type VARCHAR(15) NOT NULL check (type in (\'INSTALLATION\',\'MAINTENANCE\')), INDEX IDX_D11814AB62CAE146 (sa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB62CAE146 FOREIGN KEY (sa_id) REFERENCES sa (id)');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E962CAE146');
        $this->addSql('DROP TABLE maintenance');
        $this->addSql('ALTER TABLE member CHANGE role role VARCHAR(15) NOT NULL check (role in (\'REFERENT\',\'TECHNICIEN\'))');
        $this->addSql('ALTER TABLE room CHANGE facing facing VARCHAR(1) NOT NULL check (facing in (\'N\',\'S\',\'E\',\'W\'))');
        $this->addSql('ALTER TABLE sa CHANGE state state VARCHAR(15) NOT NULL check (state in (\'INACTIF\',\'ACTIF\',\'MAINTENANCE\',\'A_INSTALLER\'))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE maintenance (id INT AUTO_INCREMENT NOT NULL, sa_id INT NOT NULL, starting_date DATE DEFAULT NULL, ending_date DATE DEFAULT NULL, message VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_2F84F8E962CAE146 (sa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E962CAE146 FOREIGN KEY (sa_id) REFERENCES sa (id)');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB62CAE146');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('ALTER TABLE sa CHANGE state state VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE member CHANGE role role VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE room CHANGE facing facing VARCHAR(1) NOT NULL');
    }
}
