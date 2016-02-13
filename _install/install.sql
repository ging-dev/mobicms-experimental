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
-- Структура таблицы `sys__sessions`
--
DROP TABLE IF EXISTS `sys__sessions`;
CREATE TABLE `sys__sessions` (
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
-- Структура таблицы `usr__users`
--
DROP TABLE IF EXISTS `usr__users`;
CREATE TABLE `usr__users` (
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
-- Структура таблицы `usr__activations`
--
DROP TABLE IF EXISTS `usr__activations`;
CREATE TABLE `usr__activations` (
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
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Структура таблицы `usr__reputation`
--
DROP TABLE IF EXISTS `usr__reputation`;
CREATE TABLE `usr__reputation` (
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
INSERT INTO `usr__users`
SET `nickname` = 'admin',
  `password`   = '$2a$09$3dc6eee4535ff2912c44fO4djfEMWdsfFM9dw4NKsWCaeLIRyzB6u',
  `email`      = 'admin@test.com',
  `rights`     = 9,
  `activated`  = 1,
  `approved`   = 1,
  `sex`        = 'm',
  -- `about`      = '',
  `config`     = '';
