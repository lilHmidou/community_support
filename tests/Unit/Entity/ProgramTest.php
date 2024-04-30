<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Program;
use App\Entity\Etudiant;
use App\Entity\Mentor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;


class ProgramTest extends TestCase
{
    private Program $program;

    protected function setUp(): void
    {
        $this->program = new Program();
    }

    public function testInitialValues(): void
    {
        $this->assertNull($this->program->getId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->program->getCreatedAtProgram());
        $this->assertCount(0, $this->program->getEtudiants());
    }

    public function testSetAndGetTitleP(): void
    {
        $this->program->setTitleP("New Title");
        $this->assertSame("New Title", $this->program->getTitleP());
    }

    public function testAddAndRemoveEtudiant(): void
    {
        $etudiant = new Etudiant();
        $this->program->addEtudiant($etudiant);
        $this->assertCount(1, $this->program->getEtudiants());

        $this->program->removeEtudiant($etudiant);
        $this->assertCount(0, $this->program->getEtudiants());
    }
}