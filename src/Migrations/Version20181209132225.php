<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181209132225 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE business_customer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(10) NOT NULL, city VARCHAR(255) NOT NULL, auth_key VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE connexion (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manufacturer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE os (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone (id INT AUTO_INCREMENT NOT NULL, screen_size_id INT NOT NULL, manufacturer_id INT NOT NULL, category_id INT NOT NULL, os_id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, memory INT NOT NULL, warranty INT NOT NULL, INDEX IDX_444F97DDE6F67FE9 (screen_size_id), INDEX IDX_444F97DDA23B42D (manufacturer_id), INDEX IDX_444F97DD12469DE2 (category_id), INDEX IDX_444F97DD3DCA04D1 (os_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE screen_size (id INT AUTO_INCREMENT NOT NULL, size DOUBLE PRECISION NOT NULL, unit VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, business_customer_id INT NOT NULL, firstname VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(10) NOT NULL, city VARCHAR(200) NOT NULL, date_create DATETIME NOT NULL, INDEX IDX_8D93D649D2511A9B (business_customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DDE6F67FE9 FOREIGN KEY (screen_size_id) REFERENCES screen_size (id)');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DDA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DD12469DE2 FOREIGN KEY (category_id) REFERENCES phone_category (id)');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DD3DCA04D1 FOREIGN KEY (os_id) REFERENCES os (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D2511A9B FOREIGN KEY (business_customer_id) REFERENCES business_customer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D2511A9B');
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DDA23B42D');
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DD3DCA04D1');
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DD12469DE2');
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DDE6F67FE9');
        $this->addSql('DROP TABLE business_customer');
        $this->addSql('DROP TABLE connexion');
        $this->addSql('DROP TABLE manufacturer');
        $this->addSql('DROP TABLE os');
        $this->addSql('DROP TABLE phone');
        $this->addSql('DROP TABLE phone_category');
        $this->addSql('DROP TABLE screen_size');
        $this->addSql('DROP TABLE user');
    }
}
