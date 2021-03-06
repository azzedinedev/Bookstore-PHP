-- phpMyAdmin SQL Dump
-- version 2.11.0
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Jeu 16 Décembre 2021 à 19:03
-- Version du serveur: 4.1.22
-- Version de PHP: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `bookstore`
--

-- --------------------------------------------------------

--
-- Structure de la table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `account_id` bigint(20) NOT NULL auto_increment,
  `login` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `role` varchar(255) NOT NULL default '',
  `token` varchar(255) NOT NULL default '',
  `status` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`account_id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `author`
--

CREATE TABLE IF NOT EXISTS `author` (
  `author_id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`author_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Structure de la table `book`
--

CREATE TABLE IF NOT EXISTS `book` (
  `book_id` bigint(20) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `is_activated` tinyint(1) NOT NULL default '0',
  `description` longtext,
  `image_src` varchar(255) default NULL,
  `isbn` varchar(255) NOT NULL default '',
  `purchase_price` decimal(19,2) NOT NULL default '0.00',
  `sale_price` decimal(19,2) NOT NULL default '0.00',
  `quantity` int(11) NOT NULL default '0',
  `nbOfPages` varchar(255) NOT NULL default '',
  `genre_id` bigint(20) NOT NULL default '0',
  `publisher_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`book_id`),
  UNIQUE KEY `title` (`title`),
  KEY `FK-Book-Genre` (`genre_id`),
  KEY `FK-Book-Publisher` (`publisher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Structure de la table `book_author`
--

CREATE TABLE IF NOT EXISTS `book_author` (
  `book_author_id` bigint(20) NOT NULL auto_increment,
  `book_id` bigint(20) NOT NULL default '0',
  `author_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`book_author_id`),
  UNIQUE KEY `book_author_unique` (`book_id`,`author_id`),
  KEY `FK-BookAuthor-Author` (`author_id`),
  KEY `FK-BookAuthor-Book` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Structure de la table `cart_book`
--

CREATE TABLE IF NOT EXISTS `cart_book` (
  `cart_book_id` bigint(20) NOT NULL auto_increment,
  `cart_qty` int(11) NOT NULL default '0',
  `cart_id` bigint(20) NOT NULL default '0',
  `book_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`cart_book_id`),
  KEY `FK-CartBook-Cart` (`cart_id`),
  KEY `FK-CartBook-Book` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE IF NOT EXISTS `genre` (
  `genre_id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`genre_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `order_id` bigint(20) NOT NULL auto_increment,
  `Order_TotalAmount` decimal(19,2) default NULL,
  `current_status` int(11) default NULL,
  `shipping_address` varchar(255) default NULL,
  `billing_address` bigint(20) NOT NULL default '0',
  `user_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`order_id`),
  KEY `FK-Order-User` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `order_book`
--

CREATE TABLE IF NOT EXISTS `order_book` (
  `order_book_id` bigint(20) NOT NULL auto_increment,
  `qty` int(11) NOT NULL default '0',
  `amounts` decimal(19,2) default NULL,
  `book_id` bigint(20) NOT NULL default '0',
  `order_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`order_book_id`),
  KEY `FK-OrderBook-Order` (`order_id`),
  KEY `FK-OrderBook-Book` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Structure de la table `order_history`
--

CREATE TABLE IF NOT EXISTS `order_history` (
  `order_history_id` bigint(20) NOT NULL auto_increment,
  `status` varchar(255) NOT NULL default '',
  `date` date default NULL,
  `order_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`order_history_id`),
  KEY `FK-OrderHistory-Order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Structure de la table `owner`
--

CREATE TABLE IF NOT EXISTS `owner` (
  `owner_id` bigint(20) NOT NULL auto_increment,
  `firstname` varchar(255) NOT NULL default '',
  `lastname` varchar(255) NOT NULL default '',
  `account_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`owner_id`),
  KEY `FK-Account-Owner` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `publisher`
--

CREATE TABLE IF NOT EXISTS `publisher` (
  `publisher_id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `phone_number` varchar(255) NOT NULL default '',
  `banking_account` varchar(255) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`publisher_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Structure de la table `shopping_cart`
--

CREATE TABLE IF NOT EXISTS `shopping_cart` (
  `cart_id` bigint(20) NOT NULL auto_increment,
  `user_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`cart_id`),
  KEY `FK-shoppingCart-User` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` bigint(20) NOT NULL auto_increment,
  `firstname` varchar(255) NOT NULL default '',
  `middleInitial` varchar(255) NOT NULL default '',
  `lastname` varchar(255) NOT NULL default '',
  `Phone1` varchar(255) default NULL,
  `Phone2` varchar(255) default NULL,
  `credit_card_name` varchar(255) default NULL,
  `credit_card_number` varchar(255) default NULL,
  `credit_card_security_code` varchar(255) default NULL,
  `credit_card_expiry_date` date default NULL,
  `account_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`user_id`),
  KEY `FK-Account-User` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_address`
--

CREATE TABLE IF NOT EXISTS `user_address` (
  `address_id` bigint(20) NOT NULL auto_increment,
  `streetnumber` varchar(255) NOT NULL default '',
  `streetname` varchar(255) NOT NULL default '',
  `apart_num` varchar(255) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `state` varchar(255) NOT NULL default '',
  `zip` varchar(255) NOT NULL default '',
  `user_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`address_id`),
  KEY `FK-address-User` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `FK-Book-Genre` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`genre_id`),
  ADD CONSTRAINT `FK-Book-Publisher` FOREIGN KEY (`publisher_id`) REFERENCES `publisher` (`publisher_id`);

--
-- Contraintes pour la table `book_author`
--
ALTER TABLE `book_author`
  ADD CONSTRAINT `FK-BookAuthor-Author` FOREIGN KEY (`author_id`) REFERENCES `author` (`author_id`),
  ADD CONSTRAINT `FK-BookAuthor-Book` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`);

--
-- Contraintes pour la table `cart_book`
--
ALTER TABLE `cart_book`
  ADD CONSTRAINT `FK-CartBook-Book` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`),
  ADD CONSTRAINT `FK-CartBook-Cart` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart` (`cart_id`);

--
-- Contraintes pour la table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK-Order-User` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `order_book`
--
ALTER TABLE `order_book`
  ADD CONSTRAINT `FK-OrderBook-Book` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`),
  ADD CONSTRAINT `FK-OrderBook-Order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`);

--
-- Contraintes pour la table `order_history`
--
ALTER TABLE `order_history`
  ADD CONSTRAINT `FK-OrderHistory-Order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`);

--
-- Contraintes pour la table `owner`
--
ALTER TABLE `owner`
  ADD CONSTRAINT `FK-Account-Owner` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`);

--
-- Contraintes pour la table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `FK-shoppingCart-User` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK-Account-User` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`);

--
-- Contraintes pour la table `user_address`
--
ALTER TABLE `user_address`
  ADD CONSTRAINT `FK-address-User` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
