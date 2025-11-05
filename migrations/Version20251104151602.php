<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251104151602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matos_informatique ADD marque_matos_id INT DEFAULT NULL, DROP marque_matos');
        $this->addSql('ALTER TABLE matos_informatique ADD CONSTRAINT FK_7D340BD6552AF01 FOREIGN KEY (marque_matos_id) REFERENCES marque_matos (id)');
        $this->addSql('CREATE INDEX IDX_7D340BD6552AF01 ON matos_informatique (marque_matos_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matos_informatique DROP FOREIGN KEY FK_7D340BD6552AF01');
        $this->addSql('DROP INDEX IDX_7D340BD6552AF01 ON matos_informatique');
        $this->addSql('ALTER TABLE matos_informatique ADD marque_matos VARCHAR(255) NOT NULL, DROP marque_matos_id');
    }
}
