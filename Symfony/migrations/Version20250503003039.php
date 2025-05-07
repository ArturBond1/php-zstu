<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250503003039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates core medical system tables';
    }

    public function up(Schema $schema): void
    {
        $this->createDoctorTable();
        $this->createPatientTable();
        $this->createDiagnosisTable();
        $this->createTreatmentTable();
        $this->createAppointmentTable();
        $this->createMessengerMessagesTable();
        $this->addForeignKeys();
    }

    public function down(Schema $schema): void
    {
        $this->dropForeignKeys();
        $this->dropTables();
    }

    private function createDoctorTable(): void
    {
        $this->addSql('
            CREATE TABLE doctor (
                id INT AUTO_INCREMENT NOT NULL,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                specialization VARCHAR(255) NOT NULL,
                license_number VARCHAR(50) NOT NULL,
                phone_number VARCHAR(20) DEFAULT NULL,
                email VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE INDEX UNIQ_DOCTOR_EMAIL (email),
                UNIQUE INDEX UNIQ_DOCTOR_LICENSE (license_number),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    private function createPatientTable(): void
    {
        $this->addSql('
            CREATE TABLE patient (
                id INT AUTO_INCREMENT NOT NULL,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                medical_record_number VARCHAR(50) NOT NULL,
                date_of_birth DATE NOT NULL,
                gender ENUM("male", "female", "other") DEFAULT NULL,
                phone_number VARCHAR(25) DEFAULT NULL,
                address TEXT DEFAULT NULL,
                blood_type ENUM("A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-") DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE INDEX UNIQ_PATIENT_MRN (medical_record_number),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    private function createDiagnosisTable(): void
    {
        $this->addSql('
            CREATE TABLE diagnosis (
                id INT AUTO_INCREMENT NOT NULL,
                icd_code VARCHAR(20) NOT NULL,
                name VARCHAR(255) NOT NULL,
                description LONGTEXT DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE INDEX UNIQ_DIAGNOSIS_ICD (icd_code),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    private function createTreatmentTable(): void
    {
        $this->addSql('
            CREATE TABLE treatment (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                type ENUM("medication", "procedure", "therapy", "surgery") NOT NULL,
                description LONGTEXT DEFAULT NULL,
                duration VARCHAR(50) DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    private function createAppointmentTable(): void
    {
        $this->addSql('
            CREATE TABLE appointment (
                id INT AUTO_INCREMENT NOT NULL,
                doctor_id INT NOT NULL,
                patient_id INT NOT NULL,
                diagnosis_id INT DEFAULT NULL,
                treatment_id INT DEFAULT NULL,
                appointment_date DATETIME NOT NULL,
                duration_minutes INT DEFAULT 30,
                status ENUM("scheduled", "completed", "canceled", "no_show") DEFAULT "scheduled",
                notes LONGTEXT DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY(id),
                INDEX IDX_APPOINTMENT_DOCTOR (doctor_id),
                INDEX IDX_APPOINTMENT_PATIENT (patient_id),
                INDEX IDX_APPOINTMENT_DIAGNOSIS (diagnosis_id),
                INDEX IDX_APPOINTMENT_TREATMENT (treatment_id),
                INDEX IDX_APPOINTMENT_DATE (appointment_date)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    private function createMessengerMessagesTable(): void
    {
        $this->addSql('
            CREATE TABLE messenger_messages (
                id BIGINT AUTO_INCREMENT NOT NULL,
                body LONGTEXT NOT NULL,
                headers LONGTEXT NOT NULL,
                queue_name VARCHAR(190) NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_75EA56E0FB7336F0 (queue_name),
                INDEX IDX_75EA56E0E3BD61CE (available_at),
                INDEX IDX_75EA56E016BA31DB (delivered_at),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    private function addForeignKeys(): void
    {
        $this->addSql('
            ALTER TABLE appointment 
            ADD CONSTRAINT FK_APPOINTMENT_DOCTOR 
            FOREIGN KEY (doctor_id) REFERENCES doctor (id) ON DELETE RESTRICT
        ');

        $this->addSql('
            ALTER TABLE appointment 
            ADD CONSTRAINT FK_APPOINTMENT_PATIENT 
            FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE
        ');

        $this->addSql('
            ALTER TABLE appointment 
            ADD CONSTRAINT FK_APPOINTMENT_DIAGNOSIS 
            FOREIGN KEY (diagnosis_id) REFERENCES diagnosis (id) ON DELETE SET NULL
        ');

        $this->addSql('
            ALTER TABLE appointment 
            ADD CONSTRAINT FK_APPOINTMENT_TREATMENT 
            FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE SET NULL
        ');
    }

    private function dropForeignKeys(): void
    {
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_APPOINTMENT_DOCTOR');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_APPOINTMENT_PATIENT');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_APPOINTMENT_DIAGNOSIS');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_APPOINTMENT_TREATMENT');
    }

    private function dropTables(): void
    {
        $tables = [
            'appointment',
            'diagnosis',
            'doctor',
            'patient',
            'treatment',
            'messenger_messages'
        ];

        foreach ($tables as $table) {
            $this->addSql("DROP TABLE IF EXISTS $table");
        }
    }
}