CREATE TABLE IF NOT EXISTS `rbac_controllers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `rbac_groups` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `created` datetime NOT NULL,
    `modified` datetime NOT NULL,
    `is_deletable` tinyint(1) NOT NULL,
    `is_assignable` tinyint(1) NOT NULL,
    `is_editable` tinyint(1) NOT NULL,
    `guest_default` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

INSERT INTO `rbac_groups` (`id`, `title`, `created`, `modified`, `is_deletable`, `is_assignable`, `is_editable`, `guest_default`) VALUES
(1, 'Administrator', '2014-12-22 22:52:14', '2015-01-11 13:17:16', 1, 0, 0, 0),
(2, 'Staff', '2015-01-01 23:36:07', '2015-01-11 13:17:16', 1, 0, 0, 0),
(3, 'User', '2015-01-01 23:36:11', '2015-01-11 13:17:16', 1, 0, 0, 0),
(4, 'Guest', '2015-01-11 00:00:00', '2015-01-11 00:00:00', 1, 0, 0, 1);

CREATE TABLE IF NOT EXISTS `rbac_groups_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `value` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `rbac_permissions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `controller_id` int(11) NOT NULL,
    `action` varchar(255) NOT NULL,
    `active` tinyint(4) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rbac_users_groups` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `group_id` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


INSERT INTO `rbac_users_groups` (`id`, `user_id`, `group_id`) VALUES (1, 1, 1);

CREATE TABLE IF NOT EXISTS `rbac_users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


INSERT INTO `rbac_users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1);
