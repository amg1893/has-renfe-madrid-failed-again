<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181016121021 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE latest ADD id INT AUTO_INCREMENT NOT NULL, CHANGE last_id last_id BIGINT NOT NULL, CHANGE date_tweet date_tweet VARCHAR(255) NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE hashtag_status ADD id INT AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE hashtag hashtag VARCHAR(255) NOT NULL, CHANGE update_date update_time DATETIME DEFAULT NULL, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hashtag_status MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE hashtag_status DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE hashtag_status DROP id, CHANGE name name VARCHAR(100) NOT NULL COLLATE utf8mb4_general_ci, CHANGE hashtag hashtag VARCHAR(100) NOT NULL COLLATE utf8mb4_general_ci, CHANGE update_time update_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE latest MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE latest DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE latest DROP id, CHANGE last_id last_id BIGINT DEFAULT NULL, CHANGE date_tweet date_tweet VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci');
    }
}
