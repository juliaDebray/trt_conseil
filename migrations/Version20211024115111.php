<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211024115111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B853C674EE');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B891BD8781');
        $this->addSql('ALTER TABLE offers DROP FOREIGN KEY FK_DA46042769CCBE9A');
        $this->addSql('DROP TABLE IF EXISTS candidature');
        $this->addSql('DROP TABLE IF EXISTS offers');
        $this->addSql('DROP TABLE IF EXISTS user');

        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, candidate_id INT NOT NULL, offer_id INT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_E33BD3B891BD8781 (candidate_id), INDEX IDX_E33BD3B853C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offers (id INT AUTO_INCREMENT NOT NULL, author_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, company_address VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_DA46042769CCBE9A (author_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, company_address VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, curriculum_vitae VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B891BD8781 FOREIGN KEY (candidate_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B853C674EE FOREIGN KEY (offer_id) REFERENCES offers (id)');
        $this->addSql('ALTER TABLE offers ADD CONSTRAINT FK_DA46042769CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id)');

        /**
         * admin id : admin@admin.fr / pwd : admin
         */
        $this->addSql('INSERT INTO users(id, email, roles, password, firstname, lastname, company_address, status, curriculum_vitae) VALUES ("1", "admin@admin.fr", "[\"ROLE_ADMIN\"]", "$2y$13$6isO0d6TewmVC01CgmvM5uCKF9XXTRaWfU5SF9VFwJjxzBsP2V0zK", NULL, NULL, NULL, "validated", NULL)');

        /**
         * consultant id : consultant@consultant.fr / pwd : consultant
         */
        $this->addSql('INSERT INTO users(id, email, roles, password, firstname, lastname, company_address, status, curriculum_vitae) VALUES ("2", "consultant@consultant.fr", "[\"ROLE_CONSULTANT\"]", "$2y$13$Pg06K5C5QXm0/tXTAUP7RuV8uY2cLcULn3UvMLPSZKsQ75sKqe7X2", NULL, NULL, NULL, "validated", NULL)');

        /**
         * recruiter id : recruteur@recruteur.fr / pwd : recruteur
         */
        $this->addSql('INSERT INTO users(id, email, roles, password, firstname, lastname, company_address, status, curriculum_vitae) VALUES ("3", "recruteur@recruteur.fr", "[\"ROLE_RECRUITER\"]", "$2y$13$Pg06K5C5QXm0/tXTAUP7RuV8uY2cLcULn3UvMLPSZKsQ75sKqe7X2", NULL, NULL, NULL, "validated", NULL)');

        /**
         * candidate id : candidat@candidat.fr / pwd : candidat
         */
        $this->addSql('INSERT INTO users(id, email, roles, password, firstname, lastname, company_address, status, curriculum_vitae) VALUES ("4", "candidate@candidate.fr", "[\"ROLE_CANDIDATE\"]", "$2y$13$bNjt1Hor6fRu1eQXjCJFp.vwkDbBlXkcHuEXj.MHSzfOn7AIc.1om", NULL, NULL, NULL, "validated", NULL)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B853C674EE');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B891BD8781');
        $this->addSql('ALTER TABLE offers DROP FOREIGN KEY FK_DA46042769CCBE9A');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE offers');
        $this->addSql('DROP TABLE user');
    }
}
