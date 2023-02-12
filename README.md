
![image](./doc/imgs/rice_basic.png)

[![License](https://img.shields.io/badge/license-Apache%202-4EB1BA.svg)](https://www.apache.org/licenses/LICENSE-2.0.html)
[![github star](https://img.shields.io/github/stars/dmf-code/basic.svg)]('https://github.com/dmf-code/basic/stargazers')
[![github fork](https://img.shields.io/github/forks/dmf-code/basic.svg)]('https://github.com/dmf-code/basic/members')
[![.github/workflows/ci.yml](https://github.com/rice-code/basic/actions/workflows/ci.yml/badge.svg)](https://github.com/rice-code/basic/actions/workflows/ci.yml)

## php工具包 （php basic tool）

### 安装

```shell script
composer require rice/basic
```

### 功能点
1. 提供基础抽象类 [锚点](#常用的继承对象类)
2. 参数自动填充 [锚点](#请求参数自动数据填充)

工具包封装了 `DTO`, `Entity`, `Enum` 相关常用的抽象类，统一使用 `Base` 开头。

### 使用场景
1. 数组替换为对象进行管理
2. 转换为对象后需要填充属性，可以使用参数自动填充功能
3. 封装字段

### 常用的继承对象类
```text
BaseAssembler
BaseDTO
BaseEntity
BaseEnum
BaseException
```

#### Assembler
数据装配器，主要继承 `BaseAssembler` 类。该层主要是统一将 `DTO` 和 `Entity` 相互转换，如果缺少了
装配这一层，大部分代码可能就会落在 `Service` 层里面，而且参数这些会比较多，就会造成函数膨胀起来。代码
整洁的原理就是尽量细分，归类，所以提供装配器接口（面向接口编程而非实现）。

> 可选，代码重构时可做优化，提高代码可读性

```php
<?php

namespace App\Assembler;

use App\DTO\TestDTO;
use Illuminate\Http\Request;

class TestAssembler implements BaseAssembler
{
    public function toDTO(Request $request)
    {
        return (new TestDTO())
            ->setName($request->name)
            ->setPassword($request->password);
    }
}

```

#### DTO
数据传输层对象，主要继承 `BaseDTO` 类。该层主要是聚合业务层中的多个参数变量，保证编写的代码更加整洁，
并且参数变量更加直观。

> 采用失血模型，基本上只做数据传输，不存在业务行为

![dto](./doc/imgs/dto.png)

#### Entity
实体对象目录，主要继承 `BaseEntity` 类，业务逻辑中构建的具体实体模型。继承该抽象类的主体是业务中的
实体对象，主要考验个人对于建模的能力。这里和数据库的模型区别在于，模型是基于数据表进行建模的，实体是
基于业务进行建模的。

> 采用充血模型，提高实体的内聚性

#### Enum
枚举类目录，通常存放 `const` 变量, `ReturnCodeEnum` 类，按照阿里巴巴Java手册（泰山版）进行设计。

```php
class ReturnCodeEnum extends BaseEnum
    implements ClientErrorCode, SystemErrorCode, ServiceErrorCode
{
    /**
     * @default OK
     */
    public const OK = '00000';
}
```
使用该包，默认强制要求使用枚举类进行定义返回码和异常码。这样子做可以使代码更可读，并且国际化的信息也能够
与枚举类配合使用。例如：
```php
    /**
     * @level 一级宏观错误码
     * @zh-CN 用户端错误
     */
    public const CLIENT_ERROR = 'A0001';
```
`@zh-CN` 就是中文的描述,具体的标识可以参考国际化地区码。之前有使用过文件配置的方式进行配置结果发现，
使用起来不方便。需要新建不同地区码文件，而且 `Enum` 类对应相关国际化文件过于分散，导致不直观。现在
使用注解的形式进行捆绑在一起，变量与国际化信息更加聚合。


##### 使用场景
对接第三方接口会存在请求 `uri` ，大多数时候我们可能会直接写在了 `service`
类中。这样子写其实就把该变量耦合到该类中了，会导致如果我要做一个并发请求的
`service` 类的话，那么我要么定义多次 `uri` 路由。要么就直接用 `service::const`
直接从 `service2` 调用 `service1` 的代码。
为了更好的解耦代码，我们就需要使用到 `Enum` 类，因为枚举类只保存数据，而没有
业务行为，所以可以给多个 `service` 进行调用。

> 为变量调用，提供解耦

#### Exception
异常类目录, 与 `Enum` 类配合使用。按照功能模块等进行类的细化，做到单一责任。这样
可以更好的在异常抛出后做出不同的兜底措施。

#### phpunit 配置
添加测试用例，保证源代码流程跑通，修改后的代码主流程不会报错。

### 样例

#### 字段封装
在类里面使用 `use Accessor` 对类的字段属性进行封装，之前设置为
`public` 权限的全部改为 `protected` 或 `private`。
当属性为对象时，`getter` 会获取其克隆对象。这样子做是为了避免对象
暴漏出去后，不小心修改值，导致破坏内部封装，增加心智负担。

> `Accessor` 类默认 `setter`, `getter` 都启用，如果只需要 `setter`
> 或者 `getter` 的话，可以再 `use Setter` 或 `use Getter`

##### bad
```php
$cat->speak;
```

##### better
```php
$cat->getSpeak();
$cat->setSpeak($val);
```

> 面向对象三大特性之一封装，即隐藏对象内部数据的能力。如果都是公共属性的话，
> 就会造成该对象没有任何限制的进行获取和修改属性数据，导致后续维护变得复杂。

#### 注解使用

```php
class Cat
{
    use AutoFillProperties;

    /**
     * @var string
     */
    public $eyes;

    /**
     * @var Eat
     */
    public $eat;

    /**
     * @var Speak
     */
    public $speak;

    /**
     * @var string[]
     */
    public $hair;
}
```

引入 `AutoFillProperties` 类,然后使用 `@var` 进行编写注解，第一个参数是变量类型，第二个就是注释。这里面
实现原理是使用类反射获取到相关注释的内容，正则进行匹配相关的值。最后判断这个类型是系统类型还是自定义类，是类的
话就需要读取文件的命名空间，获取到相关对象的命名空间，从而实例化对象。这里面提供了缓存，因为类的改动只会在编写
时经常变动。

#### 请求参数自动数据填充
`Laravel` 和 `Tp` 框架现在都支持自定义 `Request` 对象，所以这里我们可以定义所有的入参对象。然后使用 `basic`
 包的 `AutoFillProperties` 类就能实现参数自动填充到 `Request` 对象的类属性中去了。

`trait` `AutoFillProperties` 已使用类属性,使用该类必须避免重写问题。

`src/Entity/FrameEntity.php`: 

```php
    private static array $_filter = [
        '_setter',
        '_getter',
        '_readOnly',
        '_params',
        '_properties',
        '_alias',
        '_cache',
        '_idx',
    ];
```

`Laravel` 例子：

```php
<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Rice\Basic\Support\Traits\Accessor;
use Rice\Basic\Support\Traits\AutoFillProperties;

class BaseRequest extends FormRequest
{
    use AutoFillProperties, Accessor;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
```

```php
<?php

namespace App\Http\Requests;

class TestRequest extends BaseRequest
{
    use AutoFillProperties;
    
    /**
     * @var string 姓名
     */
    protected $name;

    /**
     * @var string 密码
     */
    protected $password;
}
```

```php
<?php

namespace App\Http\Controllers;

use App\Logic\TestLogic;
use Illuminate\Http\Request;
use App\Assembler\TestAssembler;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\Response;

class TestController extends BaseController
{
    public function test(Request $request): \Illuminate\Http\JsonResponse
    {
        $testRequest = new TestRequest($request->all());
        $testRequest->check();
        $testLogic = (new TestLogic());
        
        $dto  = TestAssembler::toDTO($request);
        $resp = $testLogic->doSomethink($dto);

        return Response::json($resp);
    }
}
```

这里面实例化 `TestRequest` 需要将全部参数作为参数，然后请求的参数命名默认采用需要采用蛇形，因为前端大部分是
蛇形命名规范。这里面默认会转为驼峰进行匹配 `TestRequest` 变量进行赋值。

> Request 对象相当于是一个防腐层一样，一个业务中会存在展示，修改，删除等功能。每一部分参数都有些许不一致，但
> 是不可能给增删改查单独写一个 Request 类，不然编码上面太多类了。


### 配套工具

> 配合工具包使用更佳

#### rice/ctl

1. setting, getting 注释生成命令 [锚点](https://github.com/rice-code/ctl#%E8%AE%BF%E9%97%AE%E5%99%A8%E8%87%AA%E5%8A%A8%E7%94%9F%E6%88%90%E6%B3%A8%E9%87%8A)
2. json 转 class 对象命令 [锚点](https://github.com/rice-code/ctl#json-%E8%BD%AC-class-%E5%AF%B9%E8%B1%A1)

```shell script
composer require rice/ctl
```


### 相关链接

[创建属于自己的 composer 包](https://dmf-code.github.io/posts/54650cde2a44/)

[国际化地区码](./doc/国际化地区码.md)

[阿里巴巴Java手册（泰山版）](https://developer.aliyun.com/article/766288)


### 感谢 JetBrains 赞助

![](https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg)

