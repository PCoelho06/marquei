<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401092659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service_resource (service_id INT NOT NULL, resource_id INT NOT NULL, INDEX IDX_45E7AF2BED5CA9E6 (service_id), INDEX IDX_45E7AF2B89329D25 (resource_id), PRIMARY KEY(service_id, resource_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service_resource ADD CONSTRAINT FK_45E7AF2BED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_resource ADD CONSTRAINT FK_45E7AF2B89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD show_tutorial TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service_resource DROP FOREIGN KEY FK_45E7AF2BED5CA9E6');
        $this->addSql('ALTER TABLE service_resource DROP FOREIGN KEY FK_45E7AF2B89329D25');
        $this->addSql('DROP TABLE service_resource');
        $this->addSql('ALTER TABLE user DROP show_tutorial');
    }
}
