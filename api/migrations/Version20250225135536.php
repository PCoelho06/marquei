<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225135536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_E04992AA5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_salon (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, salon_id INT NOT NULL, role VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_10AA0F4FA76ED395 (user_id), INDEX IDX_10AA0F4F4C91BDE4 (salon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_salon_permission (id INT AUTO_INCREMENT NOT NULL, user_salon_id INT NOT NULL, permission_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3A88ED2FAC1C59E4 (user_salon_id), INDEX IDX_3A88ED2FFED90CCA (permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_salon ADD CONSTRAINT FK_10AA0F4FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_salon ADD CONSTRAINT FK_10AA0F4F4C91BDE4 FOREIGN KEY (salon_id) REFERENCES salon (id)');
        $this->addSql('ALTER TABLE user_salon_permission ADD CONSTRAINT FK_3A88ED2FAC1C59E4 FOREIGN KEY (user_salon_id) REFERENCES user_salon (id)');
        $this->addSql('ALTER TABLE user_salon_permission ADD CONSTRAINT FK_3A88ED2FFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id)');
        $this->addSql('ALTER TABLE refresh_token ADD salon_id INT DEFAULT NULL, ADD mode VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F21954C91BDE4 FOREIGN KEY (salon_id) REFERENCES salon (id)');
        $this->addSql('CREATE INDEX IDX_C74F21954C91BDE4 ON refresh_token (salon_id)');
        $this->addSql('ALTER TABLE salon DROP FOREIGN KEY FK_F268F4177E3C61F9');
        $this->addSql('DROP INDEX IDX_F268F4177E3C61F9 ON salon');
        $this->addSql('ALTER TABLE salon DROP owner_id');
        $this->addSql('ALTER TABLE user DROP roles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_salon DROP FOREIGN KEY FK_10AA0F4FA76ED395');
        $this->addSql('ALTER TABLE user_salon DROP FOREIGN KEY FK_10AA0F4F4C91BDE4');
        $this->addSql('ALTER TABLE user_salon_permission DROP FOREIGN KEY FK_3A88ED2FAC1C59E4');
        $this->addSql('ALTER TABLE user_salon_permission DROP FOREIGN KEY FK_3A88ED2FFED90CCA');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE user_salon');
        $this->addSql('DROP TABLE user_salon_permission');
        $this->addSql('ALTER TABLE refresh_token DROP FOREIGN KEY FK_C74F21954C91BDE4');
        $this->addSql('DROP INDEX IDX_C74F21954C91BDE4 ON refresh_token');
        $this->addSql('ALTER TABLE refresh_token DROP salon_id, DROP mode');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE salon ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE salon ADD CONSTRAINT FK_F268F4177E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F268F4177E3C61F9 ON salon (owner_id)');
    }
}
