# 视图

视图组件由 [hyperf/view](https://github.com/hyperf-cloud/view) 实现并提供使用，满足您对视图渲染的需求，组件默认支持 `Blade` 和 `Smarty` 两种模板引擎。

## 安装

```bash
composer require hyperf/view
```

## 配置

View 组件的配置文件位于 `config/autoload/view.php`，若配置文件不存在可自行创建，以下为相关配置的说明：

|       配置        |  类型  |                 默认值                 |       备注       |
|:-----------------:|:------:|:--------------------------------------:|:----------------:|
|      engine       | string | Hyperf\View\Engine\BladeEngine::class |   视图渲染引擎   |
|       mode        | string |               Mode::TASK               |   视图渲染模式   |
| config.view_path  | string |                   无                   | 视图文件默认地址 |
| config.cache_path | string |                   无                   | 视图文件缓存地址 |

配置文件格式示例：

```php
<?php
declare(strict_types=1);

use Hyperf\View\Mode;
use Hyperf\View\Engine\BladeEngine;

return [
    // 使用的渲染引擎
    'engine' => BladeEngine::class,
    // 不填写则默认为 Task 模式，推荐使用 Task 模式
    'mode' => Mode::TASK,
    'config' => [
        // 若下列文件夹不存在请自行创建
        'view_path' => BASE_PATH . '/storage/view/',
        'cache_path' => BASE_PATH . '/runtime/view/',
    ],
];
```

> 使用 `Task` 模式时，需引入 [hyperf/task](https://github.com/hyperf-cloud/task) 组件且必须配置 `task_enable_coroutine` 为 `false`，否则会出现协程数据混淆的问题，更多请查阅 [Task](zh/task.md) 组件文档。

> 若使用 `Sync` 模式渲染视图时，请确保相关引擎是协程安全的，否则会出现数据混淆的问题，建议使用更加数据安全的 `Task` 模式。

## 使用

以下以 `BladeEngine` 为例，首先在对应的目录里创建视图文件 `index.blade.php`。

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hyperf</title>
</head>
<body>
Hello, {{ $name }}. You are using blade template now.
</body>
</html>
```

控制器中获取 `Hyperf\View\Render` 示例，然后调用 `render` 方法并传递视图文件地址 `index` 和 `渲染数据` 即可，文件地址忽略视图文件的后缀名。

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\View\RenderInterface;

/**
 * @AutoController
 */
class ViewController
{
    public function index(RenderInterface $render)
    {
        return $render->render('index', ['name' => 'Hyperf']);
    }
}

```

访问对应的 URL，即可获得如下所示的视图页面：

```
Hello, Hyperf. You are using blade template now.
```

## 视图渲染引擎

官方目前支持 `Blade` 和 `Smarty` 两种模板，默认安装 [hyperf/view](https://github.com/hyperf-cloud/view) 时不会自动安装任何模板引擎，需要您根据自身需求，自行安装对应的模板引擎，使用前必须安装任一模板引擎。

### 安装 Blade 引擎

```bash
composer require duncan3dc/blade
```

### 安装 Smarty 引擎

```bash
composer require smarty/smarty
```

### 接入其他模板

假设我们想要接入一个虚拟的模板引擎名为 `TemplateEngine`，那么我们需要在任意地方创建对应的 `TemplateEngine` 类，并实现 `Hyperf\View\Engine\EngineInterface` 接口。

```php
<?php

declare(strict_types=1);

namespace App\Engine;

class TemplateEngine implements EngineInterface
{
    public function render($template, $data, $config): string
    {
        // 实例化对应的模板引擎的实例
        $engine = new TemplateInstance();
        // 并调用对应的渲染方法
        return $engine->render($template, $data);
    }
}

```

然后修改视图组件的配置：

```php
<?php

use App\Engine\TemplateEngine;

return [
    // 将 engine 参数改为您的自定义模板引擎类
    'engine' => TemplateEngine::class,
    'mode' => Mode::TASK,
    'config' => [
        'view_path' => BASE_PATH . '/storage/view/',
        'cache_path' => BASE_PATH . '/runtime/view/',
    ],
];
```
