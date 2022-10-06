<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006164703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE config_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE wallet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE config (id INT NOT NULL, draw_begins_at_hour TIME(0) WITHOUT TIME ZONE NOT NULL, draw_begins_at_day_no SMALLINT NOT NULL, draw_begins_at_concrete_day SMALLINT NOT NULL, draw_is_concrete_day_set BOOLEAN NOT NULL, lottery_ticket_cost INT NOT NULL, lottery_required_tickets_sum INT NOT NULL, fee_basic SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN config.draw_begins_at_hour IS \'(DC2Type:time_immutable)\'');
        $this->addSql('CREATE TABLE draw (id UUID NOT NULL, tickets_quantity INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, lauched_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_done BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN draw.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN draw.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN draw.lauched_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ticket (id UUID NOT NULL, wallet_id INT NOT NULL, draw_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97A0ADA3712520F3 ON ticket (wallet_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA36FC5C1B8 ON ticket (draw_id)');
        $this->addSql('COMMENT ON COLUMN ticket.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket.draw_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE wallet (id INT NOT NULL, address VARCHAR(128) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA36FC5C1B8 FOREIGN KEY (draw_id) REFERENCES draw (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE config_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE wallet_id_seq CASCADE');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3712520F3');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA36FC5C1B8');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE draw');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
