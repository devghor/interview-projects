<?php


namespace App\Services\User;


use App\Models\Impersonate;
use App\Models\User;
use App\Services\Media\MediaService;
use Illuminate\Support\Facades\Hash;

class UserService
{

    /**
     * Generate username
     * @param $firstName
     * @param string $lastName
     * @return string
     */
    public function generateUserName($firstName, $lastName = '')
    {
        $userName = '';

        if ($firstName) {
            $userName .= strtolower(explode(" ", $firstName)[0]);
        }

        if ($lastName) {
            $userName .= '.' . strtolower(explode(" ", $lastName)[0]);
        }

        $countUser = User::whereRaw("user_name REGEXP '^{$userName}(\.[0-9]*)?$'")->count();

        if (($countUser + 1) > 1) {
            $suffix = $countUser + 1;
            $userName .= '.' . $suffix;
        }

        return $userName;

    }


    /**
     * @param $originalPassword
     * @return string
     */
    public function generatePassword($originalPassword)
    {
        return Hash::make($originalPassword);
    }

    /**
     * Get specific user information
     *
     * @param User $user
     * @param bool $isSwitched
     *
     * @return \stdClass|null
     */
    public function getUserInformation(User $user)
    {
        $data = [];

        if ($user) {
            $data["id"] = $user->id;
            $data["userName"] = $user->user_name ?? null;
            $data["firstName"] = $user->first_name;
            $data["lastName"] = $user->last_name;
            $data["email"] = $user->email;
            $data["roles"] = $user->roles->map(function($item){
                return $item->name;
            });
        }

        return $data;
    }

    /**
     * Active Rules
     * A coach profile can only be active in the marketplace if they have:
     * Profile
     *  - Profile picture
     *  - Profile name
     *  - An “About” text with a minimum of 150 characters
     *  - Phone number
     *  - Minimum one language
     *  - Minimum one category
     *  - Minimum three tags
     * Packages
     *  - An hourly rate
     *  - At least one package
     * Geography
     *  - A least one location
     */
    public function hasPermissionToChangeActiveStatus($user)
    {
        $hasPermission = true;
        $profile = $user->profile;
        if ($profile) {
            if (!$profile->image || !$profile->profile_name || strlen($profile->about_me) <= 149) {
                $hasPermission = false;
            }
        } else {
            $hasPermission = false;
        }

        if ($user->sportCategories()->count() == 0) {
            $hasPermission = false;
        }

        if ($user->sportTags()->count() < 3) {
            $hasPermission = false;
        }


        if ($user->packages()->count() == 0) {
            $hasPermission = false;
        }


        if ($user->locations()->count() == 0) {
            $hasPermission = false;
        }


        return $hasPermission;
    }


}
