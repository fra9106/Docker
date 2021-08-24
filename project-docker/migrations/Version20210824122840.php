<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210824122840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, recipe_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, is_actif TINYINT(1) DEFAULT \'0\' NOT NULL, rating INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C59D8A214 (recipe_id), FULLTEXT INDEX IDX_9474526C2B36786BFEC530A9 (title, content), INDEX IDX_9474526C989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE keyword (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, region_id INT NOT NULL, content LONGTEXT NOT NULL, picture VARCHAR(255) NOT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, is_submited TINYINT(1) DEFAULT \'0\' NOT NULL, is_from_youtube_api TINYINT(1) DEFAULT \'0\' NOT NULL, url_video VARCHAR(255) DEFAULT NULL, description_video LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_DA88B137A76ED395 (user_id), INDEX IDX_DA88B13798260155 (region_id), FULLTEXT INDEX IDX_DA88B1375E237E06FEC530A9 (name, content), INDEX IDX_DA88B137989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_keyword (recipe_id INT NOT NULL, keyword_id INT NOT NULL, INDEX IDX_16777F7B59D8A214 (recipe_id), INDEX IDX_16777F7B115D4552 (keyword_id), PRIMARY KEY(recipe_id, keyword_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_category (recipe_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_70DCBC5F59D8A214 (recipe_id), INDEX IDX_70DCBC5F12469DE2 (category_id), PRIMARY KEY(recipe_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_like (id INT AUTO_INCREMENT NOT NULL, recipe_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D3781A6C59D8A214 (recipe_id), INDEX IDX_D3781A6CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, picture VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, name VARCHAR(255) NOT NULL, FULLTEXT INDEX IDX_F62F1765E237E066DE44026 (name, description), INDEX IDX_F62F176989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, is_rgpd TINYINT(1) DEFAULT \'1\' NOT NULL, is_banned TINYINT(1) DEFAULT \'0\' NOT NULL, is_verified TINYINT(1) DEFAULT \'0\' NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, download_token VARCHAR(255) DEFAULT NULL, biography LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), FULLTEXT INDEX IDX_8D93D64986CC499D (pseudo), INDEX IDX_8D93D649989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13798260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE recipe_keyword ADD CONSTRAINT FK_16777F7B59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_keyword ADD CONSTRAINT FK_16777F7B115D4552 FOREIGN KEY (keyword_id) REFERENCES keyword (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_category ADD CONSTRAINT FK_70DCBC5F59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_category ADD CONSTRAINT FK_70DCBC5F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_like ADD CONSTRAINT FK_D3781A6C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_like ADD CONSTRAINT FK_D3781A6CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_category DROP FOREIGN KEY FK_70DCBC5F12469DE2');
        $this->addSql('ALTER TABLE recipe_keyword DROP FOREIGN KEY FK_16777F7B115D4552');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C59D8A214');
        $this->addSql('ALTER TABLE recipe_keyword DROP FOREIGN KEY FK_16777F7B59D8A214');
        $this->addSql('ALTER TABLE recipe_category DROP FOREIGN KEY FK_70DCBC5F59D8A214');
        $this->addSql('ALTER TABLE recipe_like DROP FOREIGN KEY FK_D3781A6C59D8A214');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13798260155');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137A76ED395');
        $this->addSql('ALTER TABLE recipe_like DROP FOREIGN KEY FK_D3781A6CA76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE keyword');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_keyword');
        $this->addSql('DROP TABLE recipe_category');
        $this->addSql('DROP TABLE recipe_like');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE user');
    }
}
