<?php

namespace OAuth\Plugin;

use OAuth\OAuth2\Service\Discord;

/**
 * Class DiscordAdapter
 *
 * This is an example on how to implement your own adapter for making DokuWiki login against
 * a custom oAuth provider. The used Generic Service backend expects the authorization and
 * token endpoints to be configured in the DokuWiki backend.
 *
 * Your custom API to access user data has to be implemented in the getUser function. The one here
 * is setup to work with the demo setup of the "Discord" ruby gem.
 *
 * @link https://github.com/doorkeeper-gem/doorkeeper
 * @package OAuth\Plugin
 */
class DiscordAdapter extends AbstractAdapter {

    /**
     * Retrieve the user's data
     *
     * The array needs to contain at least 'user', 'mail', 'name' and optional 'grps'
     *
     * @return array
     */
    public function getUser() {
        $JSON = new \JSON(JSON_LOOSE_TYPE);
        $data = array();

        /** var OAuth\OAuth2\Service\Generic $this->oAuth */
        $guild_id = 225596062302208000;
        $guild_user_result = $JSON->decode($this->oAuth->request('https://discordapp.com/api/guilds/'.$guild_id.'/members/@me'));

        $data['user'] = 'discord-'.$guild_user_result['user']['id'];
        if (count($guild_user_result['nick']) > 0) {
            $data['name'] = $guild_user_result['nick'];
        }
        else {
            $data['name'] = $guild_user_result['user']['username'];
        }

        $data['mail'] = $guild_user_result['user']['email'];

        $data['grps'] = $guild_user_result['roles'];

        return $data;
    }
    
    
    /**
     * Access to user, email addresses, and guilds
     *
     * @return array
     */
    public function getScope() {
        return array(Discord::SCOPE_EMAIL, Discord::SCOPE_GUILDS);
    }

}
