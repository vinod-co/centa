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

$string['forgottenpassword'] = 'Zapomenuté heslo';
$string['emailaddress'] = 'E-mailová adresa';
$string['emailaddressinvalid'] = 'Zadejte, prosím, platnou e-mailovou adresu';
$string['emailaddressnotfound'] = 'E-mailová adresa nenalezena';
$string['emailaddressininstitutionaldomains'] = 'Váš účet je veden u instituce, jež je řízena pomocí centrální autentizační služby. Kontaktujte, prosím, IT podporu pro postup při obnovení hesla.';
$string['passwordreset'] = 'Obnovení hesla';
$string['emailhtml'] = <<< EMAIL_HTML
<p>Vážený %s %s,</p>
<p>Obdrželi jsme žádost o obnovení hesla na  Rog&#333;. Chcete-li  požadavek dokončit, klikněte na odkaz uvedený níže:</p>
<p><a href="https://%s/users/reset_password.php?token=%s">Obnovení hesla</a></p>
<p>Pokud jste o obnovení hesla nežadali:  <a href="mailto:%s">napište nám</a>. Vaše stávající uživatelské jméno a heslo vám i tak umožní přihlášení do Rog&#333;.</p>

EMAIL_HTML;
$string['couldntsendemail'] = 'Nelze odeslat e-mail <strong>%s</strong>';
$string['emailsentmsg'] = 'Byl odeslán e-mail na <em>%s</em>  obsahující odkaz, který vám umožní  heslo obnovit. Tento odkaz bude platný pouze <strong>24 hodin</strong>.';
$string['intromsg'] = 'Zadejte svoji e-mailovou adresu a my vám zašleme e-mail, který vám umožní heslo obnovit.';
$string['send'] = 'Odeslat';
?>