
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
3. setting, getting 注释生成命令 [锚点](#访问器自动生成注释)
4. json 转 class 对象命令 [锚点](#json-转-class-对象)

工具包封装了 `DTO`, `Entity`, `Enum` 相关常用的抽象类，统一使用 `Base` 开头。

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
枚举类目录

#### Exception
异常类目录

#### phpunit 配置
添加测试用例，保证源代码流程跑通，修改后的代码主流程不会报错。

### 样例


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
 
 > 对象属性不能够使用 $_xxx 的形式定义，因为这个被 `basic` 包底层规则占用了
 
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
    /**
     * @var string 姓名
     */
    public $name;

    /**
     * @var string 密码
     */
    public $password;
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

### 访问器自动生成注释

以这个 `tests\Support\Annotation\Cat.php` 文件为例，我们使用了 `Accessor` 这个 `trait`。所以会
存在 `setxxx()` 和 `getxxx()`，但是这里面会造成实例化类后调用没有相关的函数提示。为了解决这个问题，可以
使用 `php generator.php rice:accessor xxx\tests\Support\Annotation\Cat.php` 去执行自动生成注释。

> 只会生成protected 属性的注释，如果属性没有指定类型，那么会查看注释是否有 @var 指定相关类型，有的
> 话自动获取

生成前：
```php
class Cat
{
    use AutoFillProperties;
    use Accessor;

    /**
     * 眼睛.
     *
     * @return $this
     *
     * @throws \Exception
     *
     * @var string
     * @Param $class
     */
    protected $eyes;

    /**
     * @var Eat
     */
    protected $eat;

    /**
     * @var S
     */
    protected $speak;

    /**
     * @var string[]
     */
    protected $hair;
}
```

生成后：
```php
/**
 * Class Cat.
 * @method self     setEyes(string $value)
 * @method string   getEyes()
 * @method self     setEat(Eat $value)
 * @method Eat      getEat()
 * @method self     setSpeak(S $value)
 * @method S        getSpeak()
 * @method self     setHair(string[] $value)
 * @method string[] getHair()
 */
class Cat
{
    use AutoFillProperties;
    use Accessor;

    /**
     * 眼睛.
     *
     * @return $this
     *
     * @throws \Exception
     *
     * @var string
     * @Param $class
     */
    protected $eyes;

    /**
     * @var Eat
     */
    protected $eat;

    /**
     * @var S
     */
    protected $speak;

    /**
     * @var string[]
     */
    protected $hair;
}

```

### json 转 class 对象

`_class_name`: 类名称
`_type`: 类的类型（DTO 或 Entity）
`_namespace`: 类的命名空间

调用 `php generator.php rice:json_to_class xxx\basic\tests\Generate\tsconfig.json xxx\basic\tests\Generate\`

第一个参数是输入的 `json` 文件路径，第二个参数是生成文件所在的目录

```json
{
  "_class_name": "Test",
  "_type": "Entity",
  "_namespace": "Tests\\Generate",
  "data": [
    {
      "insights": {
        "data": [
          {
            "name": "post_impressions",
            "period": "lifetime",
            "values": [
              {
                "value": 614
              }
            ],
            "title": "Lifetime Post Total Impressions",
            "description": "Lifetime: The number of times your Page's post entered a person's screen. Posts include statuses, photos, links, videos and more. (Total Count)"
          }
        ],
        "paging": {
          "previous": "xxxxxxxxxxxxxxx",
          "next": "yyyyyyyyyyyyyyy"
        }
      },
      "created_time": "2021-10-13T16:11:55+0000",
      "message": "Very important message"
    }
  ],
  "paging": {
    "cursors": {
      "before": "xxxxxxxxxxxxxxx",
      "after": "yyyyyyyyyyyyyyy"
    },
    "next": "zzzzzzzzzz"
  }
}
```

### 文章

[创建属于自己的 composer 包](https://dmf-code.github.io/posts/54650cde2a44/)


### 赞助

![](https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg)

