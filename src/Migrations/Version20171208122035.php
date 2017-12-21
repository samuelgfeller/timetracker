<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171208122035 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY fk_contact_company');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY fk_log_contact1');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY fk_log_service1');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE service');
        $this->addSql('ALTER TABLE ort CHANGE ort ort VARCHAR(45) NOT NULL, CHANGE PLZ plz INT NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, ort_id INT NOT NULL, name VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci, adresse VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci, INDEX fk_company_ort1_idx (ort_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, ort_id INT DEFAULT NULL, name VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci, adresse VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci, taetigkeit VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci, INDEX fk_contact_company_idx (company_id), INDEX fk_contact_ort1_idx (ort_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, contact_id INT NOT NULL, service_id INT NOT NULL, von DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, bis DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, comment VARCHAR(100) DEFAULT NULL COLLATE utf8_general_ci, INDEX fk_log_contact1_idx (contact_id), INDEX fk_log_service1_idx (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL COLLATE utf8_general_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT fk_company_ort1 FOREIGN KEY (ort_id) REFERENCES ort (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT fk_contact_company FOREIGN KEY (company_id) REFERENCES company (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT fk_contact_ort1 FOREIGN KEY (ort_id) REFERENCES ort (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT fk_log_contact1 FOREIGN KEY (contact_id) REFERENCES contact (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT fk_log_service1 FOREIGN KEY (service_id) REFERENCES service (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE ort CHANGE ort ort VARCHAR(45) DEFAULT NULL COLLATE utf8_general_ci, CHANGE plz PLZ VARCHAR(6) DEFAULT NULL COLLATE utf8_general_ci');
    }
}
