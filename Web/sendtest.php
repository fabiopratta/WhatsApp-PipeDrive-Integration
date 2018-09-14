<?php
/**
 * @file           sendtest.php
 * @author         Fabio Pratta <fabiobrotas@hotmail.com>
 * @copyright      Copyright - WhatsApi | 16/05/2018
 * @since 16/05/2018
 */

$comando = "yowsup-cli demos -s 5514997157886 \"teste\" -c /etc/yowsup/config.conf";

passthru($comando);