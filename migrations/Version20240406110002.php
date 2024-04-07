<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240406110002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant CHANGE disabilty disability TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE mentor CHANGE avaibility availability VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE user_tutorat ADD doc_path VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mentor CHANGE availability avaibility VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE etudiant CHANGE disability disabilty TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user_tutorat DROP doc_path');
    }
}
