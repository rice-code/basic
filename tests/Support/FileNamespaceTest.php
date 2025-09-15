<?php

namespace Tests\Support;

use Tests\Support\Entity\Cat;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\FileParser;

class FileNamespaceTest extends TestCase
{
    protected FileParser $fileParser;

    protected function setUp(): void
    {
        // 每次测试前重置单例实例
        $reflection = new \ReflectionClass(FileParser::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null);
        
        // 获取新的单例实例
        $this->fileParser = FileParser::getInstance();
    }

    /**
     * 测试FileParser的单例模式
     */
    public function testSingletonInstance(): void
    {
        $instance1 = FileParser::getInstance();
        $instance2 = FileParser::getInstance();
        
        $this->assertSame($instance1, $instance2, 'FileParser应该是单例模式，返回相同实例');
    }

    /**
     * 测试FileParser能否正确分析Cat类文件的命名空间
     */
    public function testExecuteAndAnalysis(): void
    {
        $namespace = Cat::class;
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'Entity' . DIRECTORY_SEPARATOR . 'Cat.php';
        
        $this->fileParser->execute($namespace, $filePath);
        $uses = $this->fileParser->getUses();
        $alias = $this->fileParser->getAlias();
        
        // 验证命名空间是否存在
        $this->assertArrayHasKey($namespace, $uses, '应该包含Cat类的命名空间信息');
        
        // 验证this键是否存在（当前命名空间）
        $this->assertArrayHasKey('this', $uses[$namespace], '应该包含当前命名空间的信息');
        
        // 验证使用的类是否被正确识别
        $this->assertArrayHasKey('Accessor', $uses[$namespace], '应该识别到Accessor trait');
        $this->assertArrayHasKey('AutoFillProperties', $uses[$namespace], '应该识别到AutoFillProperties trait');
    }

    /**
     * 测试FileParser对alias的处理
     */
    public function testAliasHandling(): void
    {
        $namespace = Cat::class;
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'Entity' . DIRECTORY_SEPARATOR . 'Cat.php';
        
        $this->fileParser->execute($namespace, $filePath);
        $alias = $this->fileParser->getAlias();
        $uses = $this->fileParser->getUses();
        
        // 验证alias是否正确处理
        $this->assertArrayHasKey($namespace, $alias, '应该包含Cat类的alias信息');
        $this->assertArrayHasKey('S', $alias[$namespace], '应该识别到Speak类的别名S');
        $this->assertEquals('Speak', $alias[$namespace]['S'], '别名S应该对应Speak类');
        
        // 验证使用了别名的原始类名是否在uses中
        $this->assertArrayHasKey('Speak', $uses[$namespace], '应该在uses中包含Speak类的信息');
    }

    /**
     * 测试FileParser的analysis方法对不同类型行的处理
     */
    public function testAnalysisWithDifferentLines(): void
    {
        $testNamespace = 'Test\Namespace';
        
        // 测试命名空间行
        $this->assertFalse($this->fileParser->analysis($testNamespace, 'namespace Test\\Project;'), '分析命名空间行应该返回false');
        $uses = $this->fileParser->getUses();
        $this->assertArrayHasKey($testNamespace, $uses, '应该包含测试命名空间');
        $this->assertArrayHasKey('this', $uses[$testNamespace], '应该包含this键');
        $this->assertEquals('Test\Project', $uses[$testNamespace]['this'], 'this键应该包含正确的命名空间');
        
        // 测试use语句行（无别名）
        $this->assertFalse($this->fileParser->analysis($testNamespace, 'use App\\Model;'), '分析use语句行（无别名）应该返回false');
        $uses = $this->fileParser->getUses();
        $this->assertArrayHasKey('Model', $uses[$testNamespace], '应该包含Model类');
        $this->assertEquals('App', $uses[$testNamespace]['Model'], 'Model类应该对应App命名空间');
        
        // 测试use语句行（有别名）
        $this->assertFalse($this->fileParser->analysis($testNamespace, 'use App\\Database as DB;'), '分析use语句行（有别名）应该返回false');
        $alias = $this->fileParser->getAlias();
        $uses = $this->fileParser->getUses();
        $this->assertArrayHasKey($testNamespace, $alias, '应该包含测试命名空间的alias信息');
        $this->assertArrayHasKey('DB', $alias[$testNamespace], '应该包含DB别名');
        $this->assertEquals('Database', $alias[$testNamespace]['DB'], 'DB别名应该对应Database类');
        // 验证使用了别名的原始类名是否在uses中
        $this->assertArrayHasKey('Database', $uses[$testNamespace], '应该在uses中包含Database类的信息');
        $this->assertEquals('App', $uses[$testNamespace]['Database'], 'Database类应该对应App命名空间');
        
        // 测试类定义行
        $this->assertTrue($this->fileParser->analysis($testNamespace, 'class TestClass extends BaseClass implements Interface {}'), '分析类定义行应该返回true');
    }
}
