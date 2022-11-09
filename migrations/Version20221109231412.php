<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221109231412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_product_detail_value (product_id INT NOT NULL, product_detail_value_id INT NOT NULL, INDEX IDX_F37B02854584665A (product_id), INDEX IDX_F37B02853D7557E4 (product_detail_value_id), PRIMARY KEY(product_id, product_detail_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_detail_title (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_detail_value (id INT AUTO_INCREMENT NOT NULL, product_detail_title_id INT NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_ED23B106CECA6BFB (product_detail_title_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_8D93D6499395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_product_detail_value ADD CONSTRAINT FK_F37B02854584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_product_detail_value ADD CONSTRAINT FK_F37B02853D7557E4 FOREIGN KEY (product_detail_value_id) REFERENCES product_detail_value (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_detail_value ADD CONSTRAINT FK_ED23B106CECA6BFB FOREIGN KEY (product_detail_title_id) REFERENCES product_detail_title (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_product_detail_value DROP FOREIGN KEY FK_F37B02854584665A');
        $this->addSql('ALTER TABLE product_product_detail_value DROP FOREIGN KEY FK_F37B02853D7557E4');
        $this->addSql('ALTER TABLE product_detail_value DROP FOREIGN KEY FK_ED23B106CECA6BFB');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499395C3F3');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_product_detail_value');
        $this->addSql('DROP TABLE product_detail_title');
        $this->addSql('DROP TABLE product_detail_value');
        $this->addSql('DROP TABLE user');
    }
}
