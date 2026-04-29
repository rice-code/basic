<?php

namespace Rice\Basic\Tests\Domain\Assembler;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Domain\Assembler\AbstractAssembler;
use Rice\Basic\Domain\DTO\BaseDTO;
use Rice\Basic\Domain\Entity\BaseEntity;

class AssemblerTest extends TestCase
{
    public function testDtoAssemblerInterface()
    {
        $assembler = new TestAssembler();

        $dto = new TestDTO();
        $dto->name = 'Test';
        $dto->age = 25;

        $entity = $assembler->toEntity($dto);

        $this->assertInstanceOf(TestEntity::class, $entity);
        $this->assertEquals('Test', $entity->getName());
        $this->assertEquals(25, $entity->getAge());
    }

    public function testEntityAssemblerInterface()
    {
        $assembler = new TestAssembler();

        $entity = new TestEntity();
        $entity->setName('Test');
        $entity->setAge(25);

        $dto = $assembler->toDTO($entity);

        $this->assertInstanceOf(TestDTO::class, $dto);
        $this->assertEquals('Test', $dto->name);
        $this->assertEquals(25, $dto->age);
    }

    public function testArrayConverterInterface()
    {
        $assembler = new TestAssembler();

        $data = ['name' => 'Test', 'age' => 25];

        $entity = $assembler->fromArrayToEntity($data);

        $this->assertInstanceOf(TestEntity::class, $entity);
        $this->assertEquals('Test', $entity->getName());
        $this->assertEquals(25, $entity->getAge());

        $result = $assembler->toArray($entity);

        $this->assertIsArray($result);
        $this->assertEquals('Test', $result['name']);
        $this->assertEquals(25, $result['age']);
    }

    public function testFromArrayToDTO()
    {
        $assembler = new TestAssembler();

        $data = ['name' => 'Test', 'age' => 25];

        $dto = $assembler->fromArrayToDTO($data);

        $this->assertInstanceOf(TestDTO::class, $dto);
        $this->assertEquals('Test', $dto->name);
        $this->assertEquals(25, $dto->age);
    }
}

class TestDTO extends BaseDTO
{
    public $name;
    public $age;
}

class TestEntity extends BaseEntity
{
    private $name;
    private $age;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }
}

class TestAssembler extends AbstractAssembler
{
    public function getEntityClass(): string
    {
        return TestEntity::class;
    }

    public function getDTOClass(): string
    {
        return TestDTO::class;
    }

    public function toDTO(object $entity): object
    {
        $dto = new TestDTO();
        $dto->name = $entity->getName();
        $dto->age = $entity->getAge();
        return $dto;
    }

    public function toEntity(object $dto): object
    {
        $entity = new TestEntity();
        $entity->setName($dto->name);
        $entity->setAge($dto->age);
        return $entity;
    }
}
