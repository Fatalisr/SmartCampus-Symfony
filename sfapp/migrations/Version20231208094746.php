<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231208094746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(15) NOT NULL, password VARCHAR(30) NOT NULL, role VARCHAR(15) NOT NULL check (role in (\'REFERENT\',\'TECHNICIEN\')), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(15) NOT NULL, nb_computer INT NOT NULL, facing VARCHAR(1) NOT NULL check (facing in (\'N\',\'S\',\'E\',\'W\')), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sa (id INT AUTO_INCREMENT NOT NULL, current_room_id INT DEFAULT NULL, old_room_id INT DEFAULT NULL, name VARCHAR(15) NOT NULL, state VARCHAR(15) NOT NULL check (state in (\'INACTIF\',\'ACTIF\',\'MAINTENANCE\',\'A_INSTALLER\')), UNIQUE INDEX UNIQ_7F7E6904FE1AF516 (current_room_id), INDEX IDX_7F7E69042782A9C3 (old_room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sa ADD CONSTRAINT FK_7F7E6904FE1AF516 FOREIGN KEY (current_room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE sa ADD CONSTRAINT FK_7F7E69042782A9C3 FOREIGN KEY (old_room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE intervention CHANGE type type VARCHAR(15) NOT NULL check (type in (\'INSTALLATION\',\'MAINTENANCE\'))');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB62CAE146 FOREIGN KEY (sa_id) REFERENCES sa (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB62CAE146');
        $this->addSql('ALTER TABLE sa DROP FOREIGN KEY FK_7F7E6904FE1AF516');
        $this->addSql('ALTER TABLE sa DROP FOREIGN KEY FK_7F7E69042782A9C3');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE sa');
        $this->addSql('ALTER TABLE intervention CHANGE type type VARCHAR(15) NOT NULL');
    }
}
