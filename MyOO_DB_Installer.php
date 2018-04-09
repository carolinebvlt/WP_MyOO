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

      $sql_tartinette_child_params =
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tartinette_child_params (
                `id_child` INT NOT NULL ,
                `fruit` BOOLEAN NOT NULL ,
                `portion` ENUM('S','M','L') NOT NULL,
                `pain` ENUM('blanc','cereales','baguette') NOT NULL)
                ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;";

      $sql_tartinette_likes =
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tartinette_likes (
                `id_child` INT NOT NULL ,
                `classique` BOOLEAN NOT NULL ,
                `dago` BOOLEAN NOT NULL ,
                `fromage` BOOLEAN NOT NULL ,
                `autre_fromage` BOOLEAN NOT NULL ,
                `italien` BOOLEAN NOT NULL ,
                `halal` BOOLEAN NOT NULL )
                ENGINE = InnoDB;";

      $sql_tartinette_dislikes =
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tartinette_dislikes (
                `id_child` INT NOT NULL ,
                `beurre` BOOLEAN NOT NULL ,
                `salade` BOOLEAN NOT NULL ,
                `legume_grille` BOOLEAN NOT NULL ,
                `legumaise` BOOLEAN NOT NULL ,
                `pesto` BOOLEAN NOT NULL )
                ENGINE = InnoDB;";

      $sql_tartinette_single_orders =
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tartinette_single_orders (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `id_child` INT NOT NULL ,
                `pain` VARCHAR(10) NOT NULL,
                `portion` ENUM('S','M','L') NOT NULL,
                `fruit` BOOLEAN NOT NULL ,
                `next_monday` VARCHAR(20) NOT NULL ,
                `lun` BOOLEAN NOT NULL ,
                `mar` BOOLEAN NOT NULL ,
                `mer` BOOLEAN NOT NULL ,
                `jeu` BOOLEAN NOT NULL ,
                `ven` BOOLEAN NOT NULL ,
                `montant` FLOAT(11) NOT NULL ,
                PRIMARY KEY (`id`)) ENGINE = InnoDB;";

      $sql_tartinette_orders =
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wp_tartinette_orders (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `id_chef_tribu` INT NOT NULL ,
                `ids_orders` VARCHAR(50) NOT NULL ,
                `date_order` VARCHAR(50) NOT NULL ,
                `montant` FLOAT(11) NOT NULL ,
                PRIMARY KEY (`id`)) ENGINE = InnoDB;";

      $wpdb->query($sql_tartinette_users);
      $wpdb->query($sql_tartinette_children);
      $wpdb->query($sql_tartinette_child_params);
      $wpdb->query($sql_tartinette_likes);
      $wpdb->query($sql_tartinette_dislikes);
      $wpdb->query($sql_tartinette_single_orders);
      $wpdb->query($sql_tartinette_orders);
    }
} //end class
