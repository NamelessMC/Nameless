### Models

```php
class User extends Model
{
    protected static string $table = 'users';

    public function groups(): \Relation
    {
        return $this->belongsToMany(
            Group::class,
            'users_groups', // pivot without prefix
            'user_id',
            'group_id'
        );
    }
    public function wallets(): \Relation
    {
        return $this->belongsTo(Wallets::class, 'wallet_id');
    }

    public function projects(): \Relation
    {
        return $this->hasMany(Projects::class, 'user_id');
    }
}

class Group extends Model
{
    protected static string $table = 'groups';

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

```

### Usage Examples for `Model` Base Class

1. **Retrieve all records**

    ```php
    // returns array of all User instances
    $allUsers  = User::all();   // or User::get()
    // returns array of all Group instances
    $allGroups = Group::get();  // or Group::all()
    ```

2. **Find by primary key**

   ```php
   // returns a User or null
   $user1  = User::find(1);
   // returns a Group or null
   $group1 = Group::find(5);
   ```

3. **Find or fail**

   ```php
   try {
       // returns a User or throws RuntimeException
       $user2  = User::findOrFail(2);
       // returns a Group or throws RuntimeException
       $group2 = Group::findOrFail(2);
   } catch (RuntimeException $e) {
       echo $e->getMessage();
   }
   ```

4. **Create a new record**

   ```php
   // inserts and returns the new User
   $newUser = User::create([
       'username' => 'ivan',
       'email'    => 'ivan@example.com',
       'status'   => 'active',
   ]);
   // inserts and returns the new Group
   $newGroup = Group::create([
       'name'        => 'Moderators',
       'description' => 'Site moderators',
   ]);
   ```

5. **Add WHERE clause**

   ```php
   // only active users
   $activeUsers = User::where('status', '=', 'active')->get();
   // groups with “Admin” in the name
   $adminGroups = Group::where('name', 'LIKE', '%Admin%')->get();
   ```

6. **Add WHERE IN clause**

   ```php
   $userIds  = [1,2,3];
   $someUsers  = User::whereIn('id', $userIds)->get();

   $groupIds  = [1,4];
   $someGroups = Group::whereIn('id', $groupIds)->get();
   ```

7. **Pluck a single column**

   ```php
   // simple list of usernames
   $usernames      = User::pluck('username');
   // map of id => email
   $emailsByUserId = User::pluck('email', 'id');
   // list of group names
   $groupNames     = Group::pluck('name');
   ```

8. **Eager‐load relationships**

   ```php
   // assume User::groups() is a belongsToMany,
   // and Group::users() is the inverse belongsToMany
   $usersWithGroups  = User::with('groups')->get();
   $groupsWithUsers  = Group::with('users')->get();
   ```

9. **Update an existing record**

   ```php
   $user = User::findOrFail(1);
   $user->status = 'inactive';           // magic __set()
   $user->email  = 'new@domain.com';
   $user->save();

   $group = Group::findOrFail(3);
   $group->description = 'Updated text';
   $group->save();
   ```

10. **Delete a record**

    ```php
    $userToDelete  = User::find(4);
    $groupToDelete = Group::find(6);

    if ($userToDelete) {
        $userToDelete->delete();
    }
    if ($groupToDelete) {
        $groupToDelete->delete();
    }
    ```

11. **Fill & save in two steps**

    ```php
    // instantiate, fill attributes, then save
    $guest = new User();
    $guest->fill([
        'username' => 'guest',
        'email'    => 'guest@example.com',
        'status'   => 'pending',
    ]);
    $guest->save();

    $visitorGroup = new Group();
    $visitorGroup->fill([
        'name'        => 'Visitors',
        'description' => 'All site visitors',
    ]);
    $visitorGroup->save();
    ```

---
