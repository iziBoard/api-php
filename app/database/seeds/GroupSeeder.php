<?php

class GroupSeeder extends Seeder {

    public function run()
    {
        DB::table('groups')->delete();

        try
        {
            // Create the group
            $group = Sentry::createGroup(array(
                'name'        => 'Administrators',
                'permissions' => array(
                    'admin' => 1,
                    'user' => 1,
                ),
            ));
        }
        catch (Cartalyst\Sentry\Groups\NameRequiredException $e)
        {
            echo 'Name field is required';
        }
        catch (Cartalyst\Sentry\Groups\GroupExistsException $e)
        {
            echo 'Group already exists';
        }

        try
        {
            // Create the group
            $group = Sentry::createGroup(array(
                'name'        => 'Users',
                'permissions' => array(
                    'admin' => 0,
                    'user' => 1,
                ),
            ));
        }
        catch (Cartalyst\Sentry\Groups\NameRequiredException $e)
        {
            echo 'Name field is required';
        }
        catch (Cartalyst\Sentry\Groups\GroupExistsException $e)
        {
            echo 'Group already exists';
        }
    }

}