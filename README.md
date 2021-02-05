# cache_manage 缓存管理器

## 特色
1. 专注与缓存管理（将缓存管理与逻辑分离）
2. 缓存标签
3. 标签关联（自动更新关联标签）
4. 不同的缓存可用不同的驱动
5. 标签分组（不同分组的关联标签不会关联更新）
6. 主动更新缓存

## 原始需求
1. 多个开发者针对同一数据内容进行了多个的缓存
2. 缓存雪崩的解决
3. 缓存失效与重建（`用户`数据更新，所有与用户关联的`班级`，`小组`缓存都要重建）
4. 单项目多缓存位置（有的缓存放`Memcached`有的放`File`）


## 使用案例
> 简单版本

```php
# 定义缓存驱动
# 详见 src/Driver/Symfony.php

# 定义缓存管理器


/**
 * Description of Time3
 * 三秒缓存
 * @author dongasai
 */
class Time3 extends \CacheManage\AbstractCache
{

    protected $ttl    = 3;
    protected $dirver = Symfony::class;

    public function handle()
    {
        return time();
    }

}

# 初始化标签储存驱动（也储存与缓存中默认使用`\CacheManage\Driver\Predis`驱动 ）

$config = [
    'host'   => 'redis',
    'port'   => 6379,
];
\CacheManage\Driver\Predis::getInstance($config);

# 使用缓存管理器，这个缓存管理器是无参数的,然后这个缓存也是无标签的，无需初始化标签储存也可
$time3 = new Time3();
$timeCache = $time3->get();

echo $timeCache;
```

> 带有标签的

```php

/**
 * Description of Team
 * 组 缓存管理
 * @method \test\Table\Team get()
 * @author dongasai
 */
class Team extends \CacheManage\AbstractCache
{
    protected $dirver = Symfony::class;
    /**
     * 缓存处理方法，缓存的获取，标签的处理都需要在这里进行
     */
    public function handle()
    {
        $id = $this->param_arr[0];
        $team = new \test\Table\Team(['id'=>$id]);
        // 设置`我`的标签，跟`我`有关的会关联更新
        $this->selfTags[] = "team_$id";
        return $team;
    }
}


/**
 * 用户 缓存管理
 * @method \test\Table\User get()
 */
class User extends AbstractCache
{

    protected $dirver = Symfony::class;

    /**
     * 缓存处理方法，缓存的获取，标签的处理都需要在这里进行
     */
    public function handle()
    {
        $id                  = $this->param_arr[0];
        $this->selfTags[]    = "user_$id";
        $user                = new \test\Table\User(['id' => $id]);
        $teamId              = $user->getTeamId();
        // 关联标签，当·它们·有更新是，本缓存会更新
        $this->relatedTags[] = "team_$teamId";
        $team = (new Team([$teamId]))->get();
        $user->team = $team;
        return $user;
    }

}

# 初始化标签储存驱动（也储存与缓存中默认使用`\CacheManage\Driver\Predis`驱动 ）

$config = [
    'host'   => 'redis',
    'port'   => 6379,
];
\CacheManage\Driver\Predis::getInstance($config);

$User = new User();
$UserCache = $User->get([1]);

// 更新 team 这个缓存的自我标签为 team_1 ,会更新关联的缓存
$Team = new Team([1]);
$Team->update();

// 更新标签，team_4 有关的缓存都会更新，（自我标签拥有者和关联标签拥有者）
\CacheManage\TagManager::getInstance()->updateTags(['team_4']);


```
> 标签储存驱动 
## 使用说明
1. 储存驱动
    * 继承`\CacheManage\AbstractDriver`
    * 实现所需要实现的方法，其中`getInstance`方法是静态的、公开的
2. 缓存管理类
    * 要继承`\CacheManage\AbstractCache`
    * 要设置`dirver`属性，`protected string $dirver = Symfony::class;`,这是设置改缓存管理器的缓存驱动
    * 默认标签储存的驱动为`Predis`,可修改为其他，示例：`test/Cache/One/AbstractCache.php`，需确保所有的缓存管理器类公用一个标签储存驱动，不公用一个标签储存驱动的话就不能自动更新关联缓存
    * 自我标签。`array $selfTags`属性储存自我标签，就是当前缓存的标签，当别的缓存关联了这个标签是，当前缓存更新则关联更新其他缓存,一个缓存可以有多个自我标签
    * 关联标签。`array $relatedTags`属性储存了关联标签，当这些标签的缓存有更新时，当前缓存会被关联更新
    * 标签可以是任意可序列化(`serialize`)的变量
    * 缓存获取的参数。`array $param_arr`属性储存了缓存获取参数，在实例化缓存管理、获取数据是可传入参数,例：
        ```php
        //实例化传入参数
        (new User([1]))->get();
        // 获取时传入参数，会覆盖原本的参数
         (new User())->get([1]);
        
        ```
    * 缓存过期时间。`int $ttl`属性储存了缓存的过期时间，是指在多少秒后过期。可直接定义缓存过期时间`protected $ttl = 60;`，也可在实例化管理管理类、获取数据的时候传入过期时间，例：
         ```php 
         // 实例化传入过期时间
         (new User([1],100))->get();
        // 获取数据时传入过期时间，，会覆盖原本的过期时间
         (new User())->get([1],100);
         
         ```
    * 缓存获取方法,`handle`方法为缓存的获取方法，可在此方法内根据参数获取待缓存数据，数据直接返回即可。例：
    ```php
    public function handle()
    {
        $offset = $this->param_arr[0];
        return time() + $offset;
    }

    ```
3. 标签储存驱动
    * 标签储存类文件为：`\CacheManage\TagManager`,默认标签储存驱动为`CacheManage\Driver\Predis`（记得初始化标签储存驱动）
    * 更改标签储存驱动，需重先定义新的标签管理器和重写`\CacheManage\AbstractDriver`的`__update`方法，例：
        ```php

       /**
        * Description of TagManager
        *
        * @author dongasai
        */
        class TagManager2 extends \CacheManage\TagManager
        {
            // 储存驱动类
            public $dirver = Predis::class;
            // 单例托盘
            public static $Instance;

            public function __construct()
            {
                $driver               = $this->dirver;
                $this->dirverInstance = $driver::getInstance();
            }

            /**
            * 单例模式获取
            * @return self
            */
            public static function getInstance()
            {
                if (!self::$Instance) {
                    self::$Instance = new self();
                }
                return self::$Instance;
            }

        }

       /**
        * Description of AbstractCache
        * 覆盖原始基类，tag储存在 Symfony 驱动
        * @author dongasai
        */
        abstract class AbstractCache extends \CacheManage\AbstractCache
        {    
            public function __update()
            {
                TagManager2::getInstance()->run($this);
            }
        }
        ```
3. 使用缓存管理器
    * 定义`储存驱动`
    * 定义`缓存`管理类
    * 修改`标签`储存驱动
    * 初始化`标签`储存驱动
    * 初始`缓存`所需储存驱动
    * 实例`化缓存管理类`，获取数据
    * 更新`缓存`




## 作者笔记
2021年02月05日02:50:00 不用静态的标签管理器

2021年02月05日18:51:54 Symfony缓存文件驱动有BUG，会丢数据，Symfony默认驱动改为其他驱动