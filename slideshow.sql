-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 20 Septembre 2012 à 17:49
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `nos_easy-life`
--

-- --------------------------------------------------------

--
-- Structure de la table `slideshow`
--

CREATE TABLE IF NOT EXISTS `nos_slideshow` (
  `slideshow_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slideshow_title` varchar(255) NOT NULL,
  `slideshow_context` varchar(25) NOT NULL,
  `slideshow_created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `slideshow_updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`slideshow_id`),
  KEY `slideshow_context` (`slideshow_context`),
  KEY `slideshow_created_at` (`slideshow_created_at`),
  KEY `slideshow_updated_at` (`slideshow_updated_at`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `slideshow_image`
--

CREATE TABLE IF NOT EXISTS `nos_slideshow_image` (
  `slidimg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slidimg_slideshow_id` varchar(255) NOT NULL,
  `slidimg_position` int(10) NOT NULL,
  `slidimg_title` varchar(255) DEFAULT NULL,
  `slidimg_description` text,
  `slidimg_link_to_page_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`slidimg_id`),
  KEY `slidimg_slideshow_id` (`slidimg_slideshow_id`,`slidimg_position`)
) DEFAULT CHARSET=utf8;
