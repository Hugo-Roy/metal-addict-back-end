<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210127214347 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE band CHANGE musicbrainz_id musicbrainz_id VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_48DFA2EB2A80BE12 ON band (musicbrainz_id)');
        $this->addSql('CREATE INDEX search_idx ON band (musicbrainz_id)');
        $this->addSql('CREATE INDEX search_idx ON country (country_code)');
        $this->addSql('ALTER TABLE review ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX user_event_idx ON review (user_id, event_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_48DFA2EB2A80BE12 ON band');
        $this->addSql('DROP INDEX search_idx ON band');
        $this->addSql('ALTER TABLE band CHANGE musicbrainz_id musicbrainz_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX search_idx ON country');
        $this->addSql('ALTER TABLE review MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX user_event_idx ON review');
        $this->addSql('ALTER TABLE review DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE review DROP id');
        $this->addSql('ALTER TABLE review ADD PRIMARY KEY (user_id, event_id)');
    }
}