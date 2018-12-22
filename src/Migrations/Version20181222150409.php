<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181222150409 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE phonenumber phonenumber VARCHAR(35) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649EFF286D2 ON user (phonenumber)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_8D93D649EFF286D2 ON user');
        $this->addSql('ALTER TABLE user CHANGE phonenumber phonenumber VARCHAR(35) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:phone_number)\'');
    }
}
