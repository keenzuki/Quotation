<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;

class ClientPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function agentManageClient(User $user, Client $client){
        return $user->id === $client->agent_id;
    }
}
