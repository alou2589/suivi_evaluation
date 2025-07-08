<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250707093412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE carte_professionnelle (id INT AUTO_INCREMENT NOT NULL, identite_id INT DEFAULT NULL, photo_agent VARCHAR(255) NOT NULL, date_delivrance DATE NOT NULL, date_expiration DATE NOT NULL, status_impression VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_157320EAE5F13C8F (identite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE carte_professionnelle ADD CONSTRAINT FK_157320EAE5F13C8F FOREIGN KEY (identite_id) REFERENCES affectation (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE carte_professionnelle DROP FOREIGN KEY FK_157320EAE5F13C8F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE carte_professionnelle
        SQL);
    }
}
