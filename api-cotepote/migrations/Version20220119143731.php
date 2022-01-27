<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220119143731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_option (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, options_id INT NOT NULL, INDEX IDX_3FD41B8DA76ED395 (user_id), INDEX IDX_3FD41B8D3ADB05F1 (options_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_option ADD CONSTRAINT FK_3FD41B8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_option ADD CONSTRAINT FK_3FD41B8D3ADB05F1 FOREIGN KEY (options_id) REFERENCES `option` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_option');
    }
}
