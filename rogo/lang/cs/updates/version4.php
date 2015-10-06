<?php
// This file is part of Rogō
//
// Rogō is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Rogō is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Rogō.  If not, see <http://www.gnu.org/licenses/>.

$string['systemupdate'] = 'Aktualizace systému';
$string['actionrequired'] = 'Požadovaná akce';
$string['readonly'] = "Nezapomeňte zpřístupnit <strong>/config/config.inc.php</strong> pouze ke čtení! (chmod 444)";
$string['finished'] = 'Dokončeno!';
$string['couldnotwrite'] = 'Chyba: nelze zapsat konfigurační soubor!';
$string['msg1'] = 'Tento skript aktualizuje databázové struktury tak, aby odpovídaly novému % s kódu. Nevadí, pokud je tento skript spuštěn opakovaně, jelikož kontroluje aktuální strukturu databáze před aplikací jakýchkoli změn.';
$string['msg2'] = 'Aktualizační skript potřebuje k aktualizaci databáze uživatelů a tabulky přihlašovací jméno a heslo administrátora MySQL . Toto uživatelské jméno není na serveru uloženo a je použito pouze tímto aktualizačním skriptem.';
$string['databaseadminuser'] = 'Administrátor databáze';
$string['dbusername'] = 'DB uživatelské jméno';
$string['dbpassword'] = 'DB heslo';
$string['onlinehelpsystems'] = 'Systém online nápovědy';
$string['updatestaffhelp'] = 'Aktualizace nápovědy pro zaměstnance';
$string['updatestudenthelp'] = 'Aktualizace nápovědy pro studenty';
$string['startupdate'] = 'Zahájit aktualizaci';
$string['warning1'] = 'Tato aktualizace vyžaduje možnost zápisu do /config/config.inc.php.';
$string['warning2'] = 'Please chown the file to the webserver and chomod it 644';
$string['warning3'] = 'Tato aktualizace vyžaduje možnost zápisu do adresáře /config .';
$string['warning4'] = 'Please chown the file to the webserver and chomod it 744';
$string['updatefromversion'] = 'Aktualizovat verzi';
$string['home'] = 'Domů';
$string['startingupdate'] = 'Zahájit aktualizaci';
?>