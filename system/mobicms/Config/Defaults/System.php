<?php


namespace Mobicms\Config\Defaults;

/**
 * Class System
 *
 * @package Mobicms\Config\Defaults
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-08-09
 */
class System
{
    public static $aclDownloads = 2;
    public static $aclDownloadsComm = 1;
    public static $aclForum = 2;
    public static $aclGuestbook = 2;
    public static $aclLibrary = 2;
    public static $copyright = 'Powered by mobiCMS';
    public static $email = 'user@example.com';
    public static $filesize = 2100;
    public static $homeTitle = 'mobiCMS!';
    public static $lng = 'ru';
    public static $lngSwitch = 1;
    public static $metaDesc = 'mobiCMS mobile content management system http://mobicms.net';
    public static $metaKey = 'mobicms';
    public static $profilingGeneration = 1;
    public static $profilingMemory = 1;
    public static $sitemapBrowsers = 1;
    public static $sitemapForum = 1;
    public static $sitemapLibrary = 1;
    public static $sitemapUsers = 1;
    public static $themeCurrent = 'thundercloud';
    public static $timeshift = 4;
    public static $usrChangeNickname = 1;
    public static $usrChangeNicknamePeriod = 30;
    public static $usrChangeSex = 1;
    public static $usrChangeStatus = 1;
    public static $usrFloodDay = 10;
    public static $usrFloodMode = 2;
    public static $usrFloodNight = 30;
    public static $usrNicknameDigitsOnly = 0;
    public static $usrQuarantine = 0;
    public static $usrRegAllow = 1;
    public static $usrRegEmail = 1;
    public static $usrRegModeration = 0;
    public static $usrSetGravatar = 1;
    public static $usrUploadAvatars = 1;
    public static $usrUseGravatar = 1;
    public static $usrViewOnline = 1;
    public static $usrViewProfiles = 0;
    public static $usrViewUserlist = 1;

    /**
     * Returns the default system configuration
     *
     * @return array
     */
    public static function getDefault()
    {
        return get_class_vars(__CLASS__);
    }
}
