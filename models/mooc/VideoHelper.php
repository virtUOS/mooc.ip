<?php

/**
 * File - description
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License
 * version 2 as published by the Free Software Foundation.
 *
 * @author      Till Glöggler <tgloeggl@uos.de>
 * @license     https://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 */
namespace Mooc;

class VideoHelper
{
    const YOUTUBE_PATTERN = '/^.*(youtu.be\/|v\/|embed\/|watch\?|youtube.com\/|user\|watch\?|feature=player_embedded\&|\/[^#]*#([^\/]*?\/)*)\??v?=?([^#\&\?]*).*/i';

     /**
     * Cleans up YouTube URLs if necessary.
     *
     * YouTube does not support URLs like http://www.youtube.com/watch?v=<ID>
     * to be embedded in iframes. The URL pattern has to be
     * http://www.youtube.com/embed/<ID>
     *
     * @param string $url The URL to clean up
     *
     * @return string The cleaned up URL
     */
    public static function cleanUpYouTubeUrl($url)
    {
        if (!preg_match(self::YOUTUBE_PATTERN, $url)) {
            return $url;
        }

        $parts = parse_url($url);

        if (!isset($parts['query'])) {
            return $url;
        }

        parse_str($parts['query'], $params);
        $parts['path'] = '/embed/'.$params['v'];
        unset($params['v']);

        $url = $parts['scheme'].'://'.$parts['host'].$parts['path'];

        if (count($params) > 0) {
            $url .= '?'.http_build_query($params);
        }

        return $url;
    }
}
