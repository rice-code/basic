## php工具包 （php basic tool）

### 安装

```shell script
composer require rice/basic
```

### 目录

#### Common
公共函数目录,主要供其它模块进行调用

#### DTO
数据传输层对象目录，主要继承 `BaseDTO` 类。该层主要是聚合业务层中的多个参数变量，保证编写的代码更加整洁，
并且参数变量更加直观。

#### Entity
实体对象目录，主要继承 `BaseEntity` 类，业务逻辑中构建的具体实体模型。

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

use App\DTO\TestDTO;

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

    /**
     * @return TestDTO
     */
    public function newTestDTO(): TestDTO
    {
        return (new TestDTO())
            ->setName($this->name)
            ->setPassword($this->password);
    }
}
```

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use App\Logic\TestLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TestController extends BaseController
{
    public function test(Request $request): \Illuminate\Http\JsonResponse
    {
        $testRequest = new TestRequest($request->all());
        $testLogic   = (new TestLogic());
        
        $dto  = $testRequest->newTestDTO();
        $resp = $testLogic->doSomethink($dto);
        return Response::json($resp);
    }
}
```

这里面实例化 `TestRequest` 需要将全部参数作为参数，然后请求的参数命名默认采用需要采用蛇形，因为前端大部分是
蛇形命名规范。这里面默认会转为驼峰进行匹配 `TestRequest` 变量进行赋值。

> Request 对象相当于是一个防腐层一样，一个业务中会存在展示，修改，删除等功能。每一部分参数都有些许不一致，但
> 是不可能给增删改查单独写一个 Request 类，不然编码上面太多类了。

### 文章

[创建属于自己的 composer 包](https://dmf-code.github.io/posts/54650cde2a44/)


