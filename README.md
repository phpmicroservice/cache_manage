# cache_manage 缓存管理器

## 特色
1. 专注与缓存管理（将缓存管理与逻辑分离）
2. 缓存标签
3. 标签关联（自动更新关联标签）

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


# 使用缓存管理器，这个缓存管理器是无参数的
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
    
    public function handle()
    {
        $id = $this->param_arr[0];
        $team = new \test\Table\Team(['id'=>$id]);
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

    public function selfTags(): array
    {
        return $this->selfTags;
    }

    public function handle()
    {
        $id                  = $this->param_arr[0];
        $this->selfTags[]    = "user_$id";
        $user                = new \test\Table\User(['id' => $id]);
        $teamId              = $user->getTeamId();
        $this->relatedTags[] = "team_$teamId";

        return $user;
    }

}
$User = new User();
$UserCache = $User->get([1]);



```

## 使用说明
1. 缓存驱动
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
    * 缓存过去时间。`int $ttl`属性储存了缓存的过期时间，是指在多少秒后过期。可直接定义缓存获取可时间`protected $ttl = 60;`，也可在实例化管理管理类、获取数据的时候传入过期时间，例：
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
3. 使用缓存管理器
    * 定义驱动
    * 定义缓存管理类
    * 实例化缓存管理类，获取数据




## 作者笔记
2021年02月05日02:50:00 不用静态的标签管理器