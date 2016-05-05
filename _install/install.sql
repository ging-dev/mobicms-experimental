--
-- Структура таблицы `news`
--
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `time`        INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `author`      VARCHAR(50)      NOT NULL DEFAULT '',
  `author_id`   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `title`       VARCHAR(200)     NOT NULL DEFAULT '',
  `text`        TEXT             NOT NULL,
  `comm_enable` TINYINT(1)       NOT NULL DEFAULT '0',
  `comm_count`  INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Структура таблицы `sessions`
--
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id`        VARBINARY(128)   NOT NULL,
  `data`      BLOB             NOT NULL,
  `timestamp` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `ip`        VARCHAR(50)      NOT NULL DEFAULT '',
  `userAgent` VARCHAR(255)     NOT NULL DEFAULT '',
  `place`     VARCHAR(200)     NOT NULL DEFAULT '',
  `views`     INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `movings`   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `userId`    INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `online` (`userId`, `timestamp`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Структура таблицы `uusers`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`           INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `email`        VARCHAR(50)         NOT NULL DEFAULT '',
  `nickname`     VARCHAR(50)         NOT NULL DEFAULT '',
  `password`     VARCHAR(255)        NOT NULL DEFAULT '',
  `token`        VARCHAR(100)        NOT NULL DEFAULT '',
  `activated`    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `approved`     TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `quarantine`   TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `rights`       TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `sex`          ENUM('m', 'w')      NOT NULL DEFAULT 'm',
  `config`       TEXT                NOT NULL,
  `showEmail`    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `avatar`       VARCHAR(255)        NOT NULL DEFAULT '',
  `status`       VARCHAR(255)        NOT NULL DEFAULT '',
  `joinDate`     INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `lastVisit`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `lastActivity` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `ip`           VARCHAR(50)         NOT NULL DEFAULT '',
  `userAgent`    VARCHAR(255)        NOT NULL DEFAULT '',
  `reputation`   TEXT                NOT NULL,
  --
  -- Следующие поля будут перенесены в отдельную таблицу
  --
  -- `imname`       VARCHAR(100)        NOT NULL DEFAULT '',
  -- `birth`        DATE                NOT NULL DEFAULT '0000-00-00',
  -- `nickChanged`  INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  -- `icq`          INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  -- `skype`        VARCHAR(50)         NOT NULL DEFAULT '',
  -- `siteurl`      VARCHAR(100)        NOT NULL DEFAULT '',
  -- `about`        TEXT                NOT NULL,
  -- `live`         VARCHAR(100)        NOT NULL DEFAULT '',
  -- `tel`          VARCHAR(100)        NOT NULL DEFAULT '',
  -- `mailvis`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  --
  PRIMARY KEY (`id`),
  UNIQUE KEY `nickname` (`nickname`),
  UNIQUE KEY `email` (`email`),
  KEY `lastVisit` (`lastVisit`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Структура таблицы `users_data`
--
DROP TABLE IF EXISTS `users_data`;
CREATE TABLE `users_data` (
  `id`      INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId`  INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `section` VARCHAR(50)      NOT NULL DEFAULT '',
  `key`     VARCHAR(50)      NOT NULL DEFAULT '',
  `value`   TEXT             NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `section` (`section`),
  UNIQUE KEY `userKey` (`userId`, `section`, `key`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Структура таблицы `users_activations`
--
DROP TABLE IF EXISTS `users_activations`;
CREATE TABLE `users_activations` (
  `id`         INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  -- `type` field values:
  -- 0 - self activation by link
  -- 1 - Email address activation
  -- 2 - restore password
  `type`       TINYINT(1)          NOT NULL DEFAULT '0',
  `userId`     INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `activation` VARCHAR(100)        NOT NULL DEFAULT '',
  `timestamp`  INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `isValid`    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `activation` (`activation`),
  KEY `userId` (`userId`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Структура таблицы `users_reputation`
--
DROP TABLE IF EXISTS `users_reputation`;
CREATE TABLE `users_reputation` (
  `from`  INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `to`    INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `value` TINYINT(4)       NOT NULL DEFAULT '0',
  PRIMARY KEY (`from`, `to`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Создаем суперпользователя
-- LOGIN:    admin
-- PASSWORD: admin
--
INSERT INTO `users`
SET `nickname` = 'admin',
  `password`   = '$2a$09$3dc6eee4535ff2912c44fO4djfEMWdsfFM9dw4NKsWCaeLIRyzB6u',
  `email`      = 'admin@test.com',
  `rights`     = 9,
  `activated`  = 1,
  `approved`   = 1,
  `sex`        = 'm',
  `config`     = '';
