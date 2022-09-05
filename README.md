## php工具包 （php basic tool）

### 目录

#### Common
公共函数目录,主要供其它模块进行调用

#### DTO
数据传输层对象目录，主要继承 `DTOBase` 类。该层主要是聚合业务层中的多个参数变量，保证编写的代码更加整洁，
并且参数变量更加直观。

#### Entity
实体对象目录，业务逻辑中的具体实体。

#### Enum
枚举类目录

#### Exception
异常类目录

#### phpunit 配置
添加测试用例，保证源代码不会报错

### 样例

#### 请求参数自动数据填充
`Laravel` 和 `Tp` 框架现在都支持自定义 `Request` 对象，所以这里我们可以定义所有的入参对象。然后使用 `basic`
 包的 `AutoFillTrait` 类就能实现参数自动填充到 `Request` 对象的类属性中去了。
 
 > 对象属性不能够使用 $_xxx 的形式定义，因为这个被 `basic` 包底层规则占用了
 
`Laravel` 例子：

```php
<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Rice\Basic\Support\Traits\Accessor;
use Rice\Basic\Support\Traits\AutoFillTrait;

class BaseRequest extends FormRequest
{
    use AutoFillTrait, Accessor;

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
     * @var string $name
     */
    public $name;

    /**
     * @var string $password
     */
    public $password;
}
```

```php
<?php
namespace App\Http\Controllers;

use App\DTO\TestDTO;
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
        $dto         = new TestDTO($testRequest->toArray());
        $resp        = $testLogic->doSomethink($dto);
        return Response::json($resp);
    }
}
```

这里面实例化 `TestRequest` 需要将全部参数作为参数，然后请求的参数命名默认采用需要采用蛇形，因为前端大部分是
蛇形命名规范。这里面默认会转为驼峰进行匹配 `TestRequest` 变量进行赋值。

### 文章

[创建属于自己的 composer 包](https://dmf-code.github.io/posts/54650cde2a44/)


