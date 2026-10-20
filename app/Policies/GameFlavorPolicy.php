<?php

namespace App\Policies;

use App\Models\GameFlavor;
use App\Models\User;

class GameFlavorPolicy {
    /**
     * Read the game's files: everyone once it is published, before that only the creator or an admin.
     */
    public function view(?User $user, GameFlavor $gameFlavor): bool {
        return (bool) $gameFlavor->published || ($user !== null && $this->manage($user, $gameFlavor));
    }

    /**
     * Change the game, its card sets and its cards, or submit it for approval. Builds and the base version are
     * admin actions (manage-platform).
     */
    public function manage(User $user, GameFlavor $gameFlavor): bool {
        return $user->isAdmin() || $gameFlavor->creator_id === $user->id;
    }

    /**
     * Make the game public; only a built game can be published.
     */
    public function publish(User $user, GameFlavor $gameFlavor): bool {
        return $this->manage($user, $gameFlavor) && (bool) $gameFlavor->is_built;
    }

    /**
     * Copy the game into the user's own games.
     */
    public function clone(User $user, GameFlavor $gameFlavor): bool {
        return $this->manage($user, $gameFlavor) || ($gameFlavor->published && $gameFlavor->allow_clone);
    }
}
