### Models

```php
// Simple User model with all relation types and casts
class User extends Model
{
    protected static string $table = 'users';
    protected static array $casts = [
        'is_active' => 'bool',
        'settings'  => 'array',
        'created_at' => 'datetime',
        'country_id' => 'int',
        'email'      => 'string',
    ];

    // One-to-many: User has many Posts
    public function posts(): \Relation
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    // Many-to-many: User belongs to many Groups via pivot
    public function groups(): \Relation
    {
        return $this->belongsToMany(
            Group::class,
            'users_groups', // pivot table (without prefix)
            'user_id',
            'group_id'
        );
    }

    // Inverse: User belongs to a Country
    public function country(): \Relation
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    // Event: before saving
    protected function saving() {
        // e.g. hash password before save
        if (isset($this->attributes['password'])) {
            $this->attributes['password'] = password_hash($this->attributes['password'], PASSWORD_DEFAULT);
        }
    }
}

class Post extends Model
{
    protected static string $table = 'posts';

    // Inverse: Post belongs to User
    public function user(): \Relation
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Post has many Comments
    public function comments(): \Relation
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
}

class Comment extends Model
{
    protected static string $table = 'comments';

    public function post(): \Relation
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}

class Group extends Model
{
    protected static string $table = 'groups';

    // Many-to-many: Group has many Users
    public function users(): \Relation
    {
        return $this->belongsToMany(
            User::class,
            'users_groups',
            'group_id',
            'user_id'
        );
    }
}

class Country extends Model
{
    protected static string $table = 'countries';

    // Country has many Users
    public function users(): \Relation
    {
        return $this->hasMany(User::class, 'country_id');
    }
}
```

---

### Using models

#### 1. Get all the records

```php
$users = User::all();
$groups = Group::get();
$posts = Post::all();
```

#### 2. Search by primary key

```php
$user = User::find(1);
$post = Post::find(10);
```

#### 3. Search or error

```php
try {
    $user = User::findOrFail(2);
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
```

#### 4. Creating a new entry

```php
$newUser = User::create([
    'username' => 'ivan',
    'email'    => 'ivan@example.com',
    'is_active'=> true,
    'settings' => ['theme' => 'dark'],
]);
$newGroup = Group::create([
    'name' => 'Moderators',
]);
```

#### 5. WHERE та WHERE IN

```php
$activeUsers = User::where('is_active', '=', true)->get();
$somePosts = Post::whereIn('id', [1,2,3])->get();
```

#### 6. Pluck

```php
$usernames = User::pluck('username');
$emailsById = User::pluck('email', 'id');
```

#### 7. Low loading of connections (with)

```php
$users = User::with('posts')->get();
$posts = Post::with(['user', 'comments'])->get();
$groups = Group::with('users')->get();
```

#### 8. Update record

```php
$user = User::findOrFail(1);
$user->is_active = false;
$user->save();
```

#### 9. Delete record

```php
$post = Post::find(5);
if ($post) $post->delete();
```

#### 10. Insert related records

```php
$comment = new Comment();
$comment->fill([
    'post_id' => 10,
    'text'    => 'Nice post!',
]);
$comment->save();
```

#### 11. Using casts

```php
$user = User::find(1);
$isActive = $user->is_active; // bool
$settings = $user->settings;  // array (JsonCaster)
```

#### 12. Events

```php
class Example extends Model {
    protected static string $table = 'examples';
    protected function saving() {
        // Called before preserving
    }
    protected function saved() {
        // Called after storage
    }
    protected function deleting() {
        // before removing
    }
    protected function deleted() {
        // after removal
    }
}
```

#### 13. Many-to-many relationships

```php
$user = User::with('groups')->find(1);
foreach ($user->groups as $group) {
    echo $group->name;
}
$group = Group::with('users')->find(2);
foreach ($group->users as $user) {
    echo $user->username;
}
```

#### 14. Inverse relationships

```php
$users = User::with('posts.comments')->get();
foreach ($users as $user) {
    foreach ($user->posts as $post) {
        foreach ($post->comments as $comment) {
            echo $comment->text;
        }
    }
}
```

---
