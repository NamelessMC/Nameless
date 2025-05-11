### Models

```php
<?php

// Simple User model with various relations and casts
class User extends Model
{
    protected static string $table = 'nl2_users';
    protected static array $casts = [
        'is_active'  => 'bool',
        'settings'   => 'array',
        'created_at' => 'datetime',
        'country_id' => 'int',
        'email'      => 'string',
    ];

    // One-to-many: User has many Posts
    public function posts(): \Relation
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    // Many-to-many: User belongs to many Groups via pivot table
    public function groups(): \Relation
    {
        return $this->belongsToMany(
            Group::class,
            'users_groups', // pivot table name
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
    protected function saving()
    {
        // e.g. hash the password automatically
        if (isset($this->attributes['password'])) {
            $this->attributes['password'] = password_hash(
                $this->attributes['password'],
                PASSWORD_DEFAULT
            );
        }
    }
}

class Post extends Model
{
    protected static string $table = 'nl2_posts';

    // Inverse: Post belongs to a User
    public function user(): \Relation
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // One-to-many: Post has many Comments
    public function comments(): \Relation
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
}

class Comment extends Model
{
    protected static string $table = 'nl2_comments';

    // Inverse: Comment belongs to a Post
    public function post(): \Relation
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}

class Group extends Model
{
    protected static string $table = 'nl2_groups';

    // Many-to-many: Group belongs to many Users via pivot table
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
    protected static string $table = 'nl2_countries';

    // One-to-many: Country has many Users
    public function users(): \Relation
    {
        return $this->hasMany(User::class, 'country_id');
    }
}
```

---

### Usage Examples

#### 1. Retrieve all records

```php
$users  = User::all();    // Collection of User
$groups = Group::get();   // Collection of Group
$posts  = Post::all();
```

#### 2. Find by primary key

```php
$user = User::find(1);
$post = Post::find(10);
```

#### 3. Find or throw

```php
try {
    $user = User::findOrFail(2);
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
```

#### 4. Create a new record

```php
$newUser = User::create([
    'username'  => 'ivan',
    'email'     => 'ivan@example.com',
    'is_active' => true,
    'settings'  => ['theme' => 'dark'],
]);
$newGroup = Group::create([
    'name' => 'Moderators',
]);
```

#### 5. WHERE and WHERE IN

```php
$activeUsers = User::where('is_active', true)->get();    // "=" assumed
$somePosts   = Post::whereIn('id', [1,2,3])->get();
```

#### 6. Pluck

```php
$usernames  = User::pluck('username');
$emailsById = User::pluck('email', 'id');
```

#### 7. Eager loading relations

```php
$users     = User::with('posts')->get();
$posts     = Post::with(['user', 'comments'])->get();
$dataArray = User::with(['country', 'groups'])->toArray();
$dataJson  = User::with('groups')->toJson();
```

#### 8. Update a record

```php
$user = User::findOrFail(1);
$user->is_active = false;
$user->save();
```

#### 9. Delete a record

```php
$post = Post::find(5);
if ($post) {
    $post->delete();
}
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
$user     = User::find(1);
$isActive = $user->is_active;   // bool
$settings = $user->settings;    // array
```

#### 12. Events (saving, saved, deleting, deleted)

```php
class Example extends Model {
    protected static string $table = 'examples';

    protected function saving()  { /* before save */ }
    protected function saved()   { /* after save */ }
    protected function deleting(){ /* before delete */ }
    protected function deleted() { /* after delete */ }
}
```

#### 13. Many-to-many

```php
$user  = User::with('groups')->find(1);
foreach ($user->groups as $group) {
    echo $group->name;
}
```

#### 14. Deep nested eager loading

```php
$users = User::with('posts.comments')->get();
foreach ($users as $u) {
    foreach ($u->posts as $p) {
        foreach ($p->comments as $c) {
            echo $c->text;
        }
    }
}
```

#### 15. Existence check

```php
$exists = User::where('email', 'ivan@example.com')->exists();
```

#### 16. Pagination

```php
$page1 = Post::paginate(10);
$page2 = Post::paginate(10, 2);
```

#### 17. Chunk processing

```php
User::chunk(100, function($batch) {
    foreach ($batch as $user) {
        // ...
    }
});
```

#### 18. Conditional queries

```php
$active = true;
$users  = User::when($active, fn($q)=> $q->where('is_active', true))
               ->orderBy('created_at', 'DESC')
               ->get();
```
