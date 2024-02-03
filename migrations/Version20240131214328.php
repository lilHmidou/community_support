<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131214328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE config_module (id INT AUTO_INCREMENT NOT NULL, is_mentor_enabled TINYINT(1) NOT NULL, is_etudiant_enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_message (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at_cm DATETIME NOT NULL, topic VARCHAR(30) NOT NULL, content_cm VARCHAR(255) NOT NULL, INDEX IDX_2C9211FEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, domain VARCHAR(30) NOT NULL, learning_choice VARCHAR(30) NOT NULL, comments VARCHAR(255) NOT NULL, level_studies VARCHAR(30) NOT NULL, disabilty TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mentor (id INT AUTO_INCREMENT NOT NULL, domain VARCHAR(30) NOT NULL, learning_choice VARCHAR(30) NOT NULL, comments VARCHAR(255) NOT NULL, level_experience VARCHAR(20) NOT NULL, avaibility VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, send_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content_m VARCHAR(255) NOT NULL, INDEX IDX_B6BD307F4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, created_at_p DATETIME NOT NULL, location VARCHAR(50) NOT NULL, category VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_tutorat (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, domain VARCHAR(30) NOT NULL, learning_choice VARCHAR(30) NOT NULL, comments VARCHAR(255) NOT NULL, INDEX IDX_95455B99A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_message ADD CONSTRAINT FK_2C9211FEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE user_tutorat ADD CONSTRAINT FK_95455B99A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD config_module_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CE1482AC FOREIGN KEY (config_module_id) REFERENCES config_module (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649CE1482AC ON user (config_module_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CE1482AC');
        $this->addSql('ALTER TABLE contact_message DROP FOREIGN KEY FK_2C9211FEA76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F4B89032C');
        $this->addSql('ALTER TABLE user_tutorat DROP FOREIGN KEY FK_95455B99A76ED395');
        $this->addSql('DROP TABLE config_module');
        $this->addSql('DROP TABLE contact_message');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE mentor');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user_tutorat');
        $this->addSql('DROP INDEX UNIQ_8D93D649CE1482AC ON user');
        $this->addSql('ALTER TABLE user DROP config_module_id');
    }
}
