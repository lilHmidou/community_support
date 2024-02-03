<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203222132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etudiant (id INT NOT NULL, level_studies VARCHAR(30) NOT NULL, disabilty TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mentor (id INT NOT NULL, level_experience VARCHAR(20) NOT NULL, avaibility VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3BF396750 FOREIGN KEY (id) REFERENCES user_tutorat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mentor ADD CONSTRAINT FK_801562DEBF396750 FOREIGN KEY (id) REFERENCES user_tutorat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tutorat DROP level_experience, DROP avaibility, DROP level_studies, DROP disabilty');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3BF396750');
        $this->addSql('ALTER TABLE mentor DROP FOREIGN KEY FK_801562DEBF396750');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE mentor');
        $this->addSql('ALTER TABLE user_tutorat ADD level_experience VARCHAR(20) DEFAULT NULL, ADD avaibility VARCHAR(50) DEFAULT NULL, ADD level_studies VARCHAR(30) DEFAULT NULL, ADD disabilty TINYINT(1) DEFAULT NULL');
    }
}
