<?php
class MyOO_DB_Installer
{
    public static function install_db(){
      global $wpdb;
      $sql_tartinette_users =
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tartinette_users (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `last_name` VARCHAR(20) NOT NULL ,
                `first_name` VARCHAR(20) NOT NULL ,
                `phone` VARCHAR(10) NOT NULL ,
                `email` VARCHAR(30) NOT NULL ,
                `pass_h` TEXT NOT NULL ,
                `tribu` VARCHAR(20) NOT NULL ,
                PRIMARY KEY (`id`),
                UNIQUE (`email`)) ENGINE = InnoDB;" ;

      $sql_tartinette_children =
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tartinette_children (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `tribu` VARCHAR(20) NOT NULL ,
                `first_name` VARCHAR(20) NOT NULL ,
                `last_name` VARCHAR(20) NOT NULL ,
                `school` VARCHAR(40) NOT NULL ,
                `classroom` VARCHAR(10) NOT NULL ,
                PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;";

      $sql_tartinette_preferences =
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tartinette_preferences (
                `id_child` INT NOT NULL ,
                `likes` TEXT NOT NULL ,
                `dislikes` TEXT NOT NULL ,
                `fruit` BOOLEAN NOT NULL ,
                `portion` ENUM('S','M','L') NOT NULL )
                ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;";

      $sql_tartinette_already_had =
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tartinette_already_had (
                `week_nbr` TINYINT NOT NULL ,
                `id_child` INT NOT NULL ,
                `tartines` TEXT NOT NULL )
                ENGINE = InnoDB;";

      $sql_tartinette_orders =
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tartinette_orders (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `id_child` INT NOT NULL ,
                `week_nbr` TINYINT NOT NULL ,
                `days` SMALLINT NOT NULL ,
                PRIMARY KEY (`id`)) ENGINE = InnoDB;";

      $wpdb->query($sql_tartinette_users);
      $wpdb->query($sql_tartinette_children);
      $wpdb->query($sql_tartinette_preferences);
      $wpdb->query($sql_tartinette_already_had);
      $wpdb->query($sql_tartinette_orders);
    }
} //end class
