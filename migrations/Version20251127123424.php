<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251127123424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE direction ADD direction_parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE direction ADD CONSTRAINT FK_3E4AD1B3195A0E07 FOREIGN KEY (direction_parent_id) REFERENCES direction (id)');
        $this->addSql('CREATE INDEX IDX_3E4AD1B3195A0E07 ON direction (direction_parent_id)');
        $this->addSql('ALTER TABLE service ADD service_parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD22AEBF03A FOREIGN KEY (service_parent_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD22AEBF03A ON service (service_parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE direction DROP FOREIGN KEY FK_3E4AD1B3195A0E07');
        $this->addSql('DROP INDEX IDX_3E4AD1B3195A0E07 ON direction');
        $this->addSql('ALTER TABLE direction DROP direction_parent_id');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD22AEBF03A');
        $this->addSql('DROP INDEX IDX_E19D9AD22AEBF03A ON service');
        $this->addSql('ALTER TABLE service DROP service_parent_id');
    }
}
