<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250701123921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE info_perso (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, situation_matrimoniale VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agent ADD cadre_statuaire VARCHAR(255) NOT NULL, ADD grade VARCHAR(255) NOT NULL, ADD classe VARCHAR(255) NOT NULL, ADD echelon VARCHAR(255) NOT NULL, ADD date_recrutement DATE NOT NULL, ADD numero_decision_contrat VARCHAR(255) NOT NULL, ADD decision_contrat VARCHAR(255) NOT NULL, ADD date_nomination_grade VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE info_perso
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agent DROP cadre_statuaire, DROP grade, DROP classe, DROP echelon, DROP date_recrutement, DROP numero_decision_contrat, DROP decision_contrat, DROP date_nomination_grade
        SQL);
    }
}
