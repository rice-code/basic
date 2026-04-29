<?php

namespace Rice\Basic\Tests\Domain\Mapper;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Domain\Mapper\ObjectMapper;

class ObjectMapperTest extends TestCase
{
    public function testArrayToObject()
    {
        $mapper = new ObjectMapper();

        $data = [
            'name' => 'Test',
            'age' => 25,
            'email' => 'test@example.com'
        ];

        $result = $mapper->fromArrayToObject(ObjectMapperTestUser::class, $data);

        $this->assertInstanceOf(ObjectMapperTestUser::class, $result);
        $this->assertEquals('Test', $result->name);
        $this->assertEquals(25, $result->age);
        $this->assertEquals('test@example.com', $result->email);
    }

    public function testObjectToArray()
    {
        $mapper = new ObjectMapper();

        $user = new ObjectMapperTestUser();
        $user->name = 'Test';
        $user->age = 25;
        $user->email = 'test@example.com';

        $result = $mapper->toArray($user);

        $this->assertIsArray($result);
        $this->assertEquals('Test', $result['name']);
        $this->assertEquals(25, $result['age']);
        $this->assertEquals('test@example.com', $result['email']);
    }

    public function testMapWithNullValues()
    {
        $mapper = new ObjectMapper();

        $data = [
            'name' => null,
            'age' => null,
            'email' => null
        ];

        $result = $mapper->fromArrayToObject(ObjectMapperTestUser::class, $data);

        $this->assertInstanceOf(ObjectMapperTestUser::class, $result);
        $this->assertNull($result->name);
        $this->assertNull($result->age);
        $this->assertNull($result->email);
    }

    public function testExtractWithPrivateProperties()
    {
        $mapper = new ObjectMapper();

        $user = new ObjectMapperTestUserWithPrivate();
        $user->setName('Test');
        $user->setAge(25);

        $result = $mapper->toArray($user, null, true);

        $this->assertIsArray($result);
        $this->assertEquals('Test', $result['name']);
        $this->assertEquals(25, $result['age']);
    }
}

class ObjectMapperTestUser
{
    public $name;
    public $age;
    public $email;
}

class ObjectMapperTestUserWithPrivate
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
