<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221016182516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE config_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cron_job_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cron_report_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE config (id INT NOT NULL, draw_begins_at_hour TIME(0) WITHOUT TIME ZONE NOT NULL, draw_begins_at_day_no SMALLINT NOT NULL, draw_begins_at_concrete_day SMALLINT NOT NULL, draw_is_concrete_day_set BOOLEAN NOT NULL, lottery_ticket_cost BIGINT NOT NULL, lottery_required_tickets_sum BIGINT NOT NULL, fee_basic SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN config.draw_begins_at_hour IS \'(DC2Type:time_immutable)\'');
        $this->addSql('CREATE TABLE cron_job (id INT NOT NULL, name VARCHAR(191) NOT NULL, command VARCHAR(1024) NOT NULL, schedule VARCHAR(191) NOT NULL, description VARCHAR(191) NOT NULL, enabled BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX un_name ON cron_job (name)');
        $this->addSql('CREATE TABLE cron_report (id INT NOT NULL, job_id INT DEFAULT NULL, run_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, run_time DOUBLE PRECISION NOT NULL, exit_code INT NOT NULL, output TEXT NOT NULL, error TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6C6A7F5BE04EA9 ON cron_report (job_id)');
        $this->addSql('CREATE TABLE draw (id UUID NOT NULL, winner_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, launched_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, done_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_done BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70F2BD0F5DFCD4B8 ON draw (winner_id)');
        $this->addSql('COMMENT ON COLUMN draw.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN draw.winner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN draw.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN draw.launched_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN draw.done_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE transaction (id UUID NOT NULL, draw_id UUID NOT NULL, block_number VARCHAR(255) DEFAULT NULL, block_hash VARCHAR(255) DEFAULT NULL, timestamp VARCHAR(255) DEFAULT NULL, hash VARCHAR(255) NOT NULL, transaction_from VARCHAR(255) NOT NULL, transaction_to VARCHAR(255) NOT NULL, value BIGINT NOT NULL, gas BIGINT NOT NULL, gas_price BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_723705D1D1B862B8 ON transaction (hash)');
        $this->addSql('CREATE INDEX IDX_723705D16FC5C1B8 ON transaction (draw_id)');
        $this->addSql('COMMENT ON COLUMN transaction.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.draw_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.created_at IS \'(DC2Type:datetime_immutable)\'');
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
        $this->addSql('ALTER TABLE cron_report ADD CONSTRAINT FK_B6C6A7F5BE04EA9 FOREIGN KEY (job_id) REFERENCES cron_job (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE draw ADD CONSTRAINT FK_70F2BD0F5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES transaction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D16FC5C1B8 FOREIGN KEY (draw_id) REFERENCES draw (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE config_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cron_job_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cron_report_id_seq CASCADE');
        $this->addSql('ALTER TABLE cron_report DROP CONSTRAINT FK_B6C6A7F5BE04EA9');
        $this->addSql('ALTER TABLE draw DROP CONSTRAINT FK_70F2BD0F5DFCD4B8');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D16FC5C1B8');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE cron_job');
        $this->addSql('DROP TABLE cron_report');
        $this->addSql('DROP TABLE draw');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
