<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128073802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE deployments (id INT AUTO_INCREMENT NOT NULL, job_name VARCHAR(255) NOT NULL, parameters JSON NOT NULL, status VARCHAR(50) NOT NULL, build_number INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, triggered_by VARCHAR(255) DEFAULT NULL, release_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE deployment DROP FOREIGN KEY FK_EB1255BEB12A727D');
        $this->addSql('DROP TABLE deployment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE deployment (id INT AUTO_INCREMENT NOT NULL, release_id INT NOT NULL, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_EB1255BEB12A727D (release_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE deployment ADD CONSTRAINT FK_EB1255BEB12A727D FOREIGN KEY (release_id) REFERENCES `release` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE deployments');
    }
}
