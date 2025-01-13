<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241224105412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artifact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, reference_number VARCHAR(255) DEFAULT NULL, build_num VARCHAR(255) DEFAULT NULL, build_date_time DATETIME DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artifacts_releases (artifact_id INT NOT NULL, release_id INT NOT NULL, INDEX IDX_FB7D9113E28B07AC (artifact_id), INDEX IDX_FB7D9113B12A727D (release_id), PRIMARY KEY(artifact_id, release_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deployment (id INT AUTO_INCREMENT NOT NULL, release_id INT NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_EB1255BEB12A727D (release_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `release` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) DEFAULT NULL, production_date DATETIME DEFAULT NULL, qa_date DATETIME DEFAULT NULL, stage_date DATETIME DEFAULT NULL, main_release_ticket VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, fullname VARCHAR(255) DEFAULT NULL, contact BIGINT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artifacts_releases ADD CONSTRAINT FK_FB7D9113E28B07AC FOREIGN KEY (artifact_id) REFERENCES artifact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artifacts_releases ADD CONSTRAINT FK_FB7D9113B12A727D FOREIGN KEY (release_id) REFERENCES `release` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deployment ADD CONSTRAINT FK_EB1255BEB12A727D FOREIGN KEY (release_id) REFERENCES `release` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artifacts_releases DROP FOREIGN KEY FK_FB7D9113E28B07AC');
        $this->addSql('ALTER TABLE artifacts_releases DROP FOREIGN KEY FK_FB7D9113B12A727D');
        $this->addSql('ALTER TABLE deployment DROP FOREIGN KEY FK_EB1255BEB12A727D');
        $this->addSql('DROP TABLE artifact');
        $this->addSql('DROP TABLE artifacts_releases');
        $this->addSql('DROP TABLE deployment');
        $this->addSql('DROP TABLE `release`');
        $this->addSql('DROP TABLE user');
    }
}
