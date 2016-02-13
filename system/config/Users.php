<?php

namespace Config;

class Users
{
    public static $allowChangeSex = false;
    public static $allowChangeStatus = true;
    public static $allowUploadAvatars = true;
    public static $allowUseGravatar = true;
    public static $allowNicknamesOfDigits = false;
    public static $allowToChangeNickname = true;
    public static $changeNicknamePeriod = 30;
    public static $allowGuestsOnlineLists = true;
    public static $allowGuestsToUserLists = true;
    public static $allowGuestsToViewProfiles = true;
    public static $antifloodMode = 1;
    public static $antifloodDayDelay = 10;
    public static $antifloodNightDelay = 40;
}
