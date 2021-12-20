<?php 

namespace App\Repositories\User;

use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository {
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\Models\\User";
    }
}