<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250717104105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, programme_id INT DEFAULT NULL, responsable_action_id INT DEFAULT NULL, nom_action VARCHAR(255) NOT NULL, code_action VARCHAR(255) NOT NULL, cout_action VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_47CC8C9262BB7AEE (programme_id), INDEX IDX_47CC8C926074A123 (responsable_action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE affectation (id INT AUTO_INCREMENT NOT NULL, agent_id INT DEFAULT NULL, poste_id INT DEFAULT NULL, service_id INT DEFAULT NULL, date_debut DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_fin DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut_affectation VARCHAR(255) NOT NULL, INDEX IDX_F4DD61D33414710B (agent_id), INDEX IDX_F4DD61D3A0905086 (poste_id), INDEX IDX_F4DD61D3ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agent (id INT AUTO_INCREMENT NOT NULL, identification_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', cadre_statuaire VARCHAR(255) NOT NULL, matricule VARCHAR(255) NOT NULL, fonction VARCHAR(255) NOT NULL, grade VARCHAR(255) NOT NULL, echelon VARCHAR(255) NOT NULL, numero_decision_contrat VARCHAR(255) NOT NULL, decision_contrat VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, hierarchie VARCHAR(255) NOT NULL, date_recrutement DATE DEFAULT NULL, banque VARCHAR(255) NOT NULL, numero_compte VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_268B9C9D4DFE3A85 (identification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carte_professionnelle (id INT AUTO_INCREMENT NOT NULL, identite_id INT DEFAULT NULL, photo_agent VARCHAR(255) NOT NULL, date_delivrance DATE NOT NULL, date_expiration DATE NOT NULL, status_impression VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_157320EAE5F13C8F (identite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE direction (id INT AUTO_INCREMENT NOT NULL, nom_direction VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT NOT NULL, type_direction VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_administratif (id INT AUTO_INCREMENT NOT NULL, agent_id INT DEFAULT NULL, type_doc VARCHAR(255) NOT NULL, nom_doc VARCHAR(255) NOT NULL, document VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_33F0D1213414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE info_perso (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, situation_matrimoniale VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', lieu_naissance VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, qr_code LONGTEXT DEFAULT NULL, cin VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nature_depense (id INT AUTO_INCREMENT NOT NULL, action_id INT DEFAULT NULL, nom_nature VARCHAR(255) NOT NULL, budget_cp VARCHAR(255) NOT NULL, budget_ae VARCHAR(255) NOT NULL, INDEX IDX_2C86DC829D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, nom_poste VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, responsable_programme_id INT DEFAULT NULL, code_programme VARCHAR(255) NOT NULL, nom_programme VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', annee_programme VARCHAR(255) NOT NULL, cout_programme VARCHAR(255) NOT NULL, INDEX IDX_3DDCB9FF460F2D61 (responsable_programme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, structure_rattachee_id INT DEFAULT NULL, nom_service VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type_service VARCHAR(255) NOT NULL, INDEX IDX_E19D9AD2B9CF1BF3 (structure_rattachee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C9262BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C926074A123 FOREIGN KEY (responsable_action_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D33414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D4DFE3A85 FOREIGN KEY (identification_id) REFERENCES info_perso (id)');
        $this->addSql('ALTER TABLE carte_professionnelle ADD CONSTRAINT FK_157320EAE5F13C8F FOREIGN KEY (identite_id) REFERENCES affectation (id)');
        $this->addSql('ALTER TABLE document_administratif ADD CONSTRAINT FK_33F0D1213414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE nature_depense ADD CONSTRAINT FK_2C86DC829D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF460F2D61 FOREIGN KEY (responsable_programme_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2B9CF1BF3 FOREIGN KEY (structure_rattachee_id) REFERENCES direction (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C9262BB7AEE');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C926074A123');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D33414710B');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3A0905086');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3ED5CA9E6');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D4DFE3A85');
        $this->addSql('ALTER TABLE carte_professionnelle DROP FOREIGN KEY FK_157320EAE5F13C8F');
        $this->addSql('ALTER TABLE document_administratif DROP FOREIGN KEY FK_33F0D1213414710B');
        $this->addSql('ALTER TABLE nature_depense DROP FOREIGN KEY FK_2C86DC829D32F035');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF460F2D61');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2B9CF1BF3');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE affectation');
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE carte_professionnelle');
        $this->addSql('DROP TABLE direction');
        $this->addSql('DROP TABLE document_administratif');
        $this->addSql('DROP TABLE info_perso');
        $this->addSql('DROP TABLE nature_depense');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE programme');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
