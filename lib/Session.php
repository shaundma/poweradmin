<?php
/*  Poweradmin, a friendly web-based admin tool for PowerDNS.
 *  See <https://www.poweradmin.org> for more details.
 *
 *  Copyright 2007-2009  Rejo Zenger <rejo@zenger.nl>
 *  Copyright 2010-2023 Poweradmin Development Team
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Poweradmin;

class Session
{

    public static function encrypt_password($password, $session_key): string
    {
        $key = self::computeKey($session_key);
        $iv = self::computeIV($key);
        return openssl_encrypt($password, 'aes-256-cbc', $key, 0, $iv);
    }

    public static function decrypt_password($password, $session_key): string
    {
        $key = self::computeKey($session_key);
        $iv = self::computeIV($key);
        return rtrim(openssl_decrypt($password, 'aes-256-cbc', $key,0, $iv), "\0");
    }

    private static function computeKey($session_key): string
    {
        return hash('sha256', $session_key);
    }

    private static function computeIV(string $key): string
    {
        return substr(hash('sha256', $key, TRUE), 0, 16);
    }
}