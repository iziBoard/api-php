<?php

class AdminSeeder extends Seeder {

    public function run()
    {
        try
        {
            // Create the user
            $user = Sentry::createUser(array(
                'email'     => 'admin@admin.se',
                'password'  => 'admin',
                'activated' => true,
            ));

            // Find the group using the group id
            $userGroup = Sentry::findGroupByName("Administrators");

            // Assign the group to the user
            $user->addGroup($userGroup);

            return Response::json(['result' => true, 'data' => $user], 200);
        }
        catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            $data[] = 'Login field is required.';
        }
        catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
        {
            $data[] = 'Password field is required.';
        }
        catch (Cartalyst\Sentry\Users\UserExistsException $e)
        {
            $data[] = 'User with this login already exists.';
        }
        catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
            $data[] = 'Group was not found.';
        }
    }

}