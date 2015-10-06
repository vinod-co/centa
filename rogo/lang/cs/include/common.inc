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

$string['administrativetools'] = 'Nastavení';
$string['helpsupport'] = 'Nápověda a podpora';
$string['aboutrogo'] = 'O programu Rog&#333; %s';
$string['furtherassistance'] = 'V případě potřeby další pomoci, prosím, kontaktujte: <a href="mailto:%s">%s</a>';
$string['pagenotfound'] = 'Stránka nebyla nalezena';
$string['signinmsg'] = '<span style="color:#1f497d;font-weight:bold;">Vítejte v systému elektronického testování</span><br />
    V případě zájmu o vytvoření účtu kontaktujte <a href="mailto:ovt@lf1.cuni.cz">ovt@lf1.cuni.cz</a>.<hr /><br />
    Stránka, na kterou se pokoušíte vstoupit, vyžaduje ověření. Přihlaste se, prosím, pomocí svého uživatelského jména a hesla:';
$string['username'] = 'Uživatelské jméno';
$string['password'] = 'Heslo';
$string['signin'] = 'Přihlásit';
$string['signout'] = 'Odhlásit';
$string['home'] = 'Domů';
$string['help'] = 'Pomoc';
$string['line'] = 'Řádek';
$string['cancel'] = 'Zrušit';
$string['ok'] = 'OK';
$string['save'] = 'Uložit';
$string['accessdenied'] = 'Přístup odepřen';
$string['accessdeniedadmin'] = '<strong>Přístup odepřen</strong><br />pokoušíte se vstoupit na správcovskou stránku.';
$string['accessdeniedsysadmin'] = '<strong>Přístup odepřen</strong><br />pokoušíte se vstoupit na správcovskou stránku.';
$string['denied_paper'] = 'Pokoušíte se o vstup <strong>do dokumentu, ke kterému nemáte přístupová práva</strong>!';
$string['denied_folder'] = 'Pokoušíte se o vstup <strong>do složky, ke které nemáte přístupová práva</strong>!';
$string['denied_question'] = 'Pokoušíte se o vstup <strong>do úlohy, ke které nemáte přístupová práva</strong>!';
$string['denied_team'] = 'Nejste členem <strong>%s</strong> týmu modulu';
$string['noldapusernamesupplied'] = 'Nebylo zadáno uživatelské LDAP jméno a heslo';
$string['incorrectpassword'] = 'Zadáno chybné heslo';
$string['ldapserverunavailable'] = 'LDAP Server není dostupný';
$string['noldapaccount'] = 'Nebyl nalezen žádný LDAP účet!';
$string['ldapservernosearch'] = 'LDAP Server není dostupný: nelze vyhledávat';
$string['nodatabaseconnection'] = ' K databázi se nelze připojit, obraťte se, prosím, na správce.';
$string['as'] = 'jako';
$string['localtsauthfailed'] = 'Místní autentizace Rogō se nezdařila.';
$string['notsaccount'] = 'Účet nebyl nalezen!';
$string['tempaccount'] = 'Dočasný účet';
$string['authenticationfailed'] = 'Autentizace Rogō selhala, nezapomeňte:';
$string['usernamecasesensitive'] = 'U přihlašovacího jména/hesla rozlišujte VELKÁ a malá písmena. ';
$string['pressf5'] = 'Stiskněte na klávesnici tlačítko \'F5\' a zkuste se přihlásit znovu';
$string['tsonldap'] = 'Rogō využívá LDAP hesla';
$string['ifstuckinvigilator'] = 'Pokud se opakovaně nedaří, požádejte dohlížející osobu o dočasný účet';
$string['forgottenpassword'] = 'Zapomenuté heslo';
$string['warning'] = 'Varování';
$string['secureconnection'] = 'Vyžadováno bezpečné připojení';
$string['secureconnectionmsg'] = 'Rogō je přístupné pouze prostřednictvím zabezpečeného webového připojení. Místo toho použijte:';
$string['errormsg'] = 'Pokud se tato chyba objevuje opakovaně, kontaktujte, prosím:';
$string['ltifirstlogin'] = 'Rogō autorizace';
$string['ltifirstlogindesc'] = 'Systém e-hodnocení Rogō musí, k zajištění správného oprávnění, Vaši identitu potvrdit. Potvrzení proběhne pouze při Vašem prvním přihlášení a následně, k zajištění bezpečnosti, v občasných intervalech .<br /><br /><input type="button" value=" Přihlásit " onclick="window.location=\'./\'" />';
$string['ltinotconfigured'] = 'Použitý odkaz nebyl dosud konfigurován.';
$string['LTIFAILURE'] = 'Chyba LTI ';
$string['expiredtimestamp'] = 'Časový limit vypršel, přihlašte se, prosím, znovu (nepoužívejte tlačítko aktualizace v prohlížeči!).';
$string['UserMissing']='Přidružený uživatel chybí';
$string['UserMissingDesc'] = 'Pokud přidružený uživatel zmizí, značí to vážnou chybu a je potřeba obrátit se na podporu';
$string['impersonatepriv'] = 'Nemáte dostatečná práva k imitaci uživatelů.';
$string['NoAuthenticationConfigured'] = 'Ověřování nenastaveno';
$string['NoAuthenticationConfiguredmessage'] = 'V config souboru nebylo nastaveno žádné ověřování. Kontaktujte, prosím, systémového správce.';
$string['Authentication_callback_failure1'] = 'Nepodařilo se zapsat sekční zpětnou vazbu';
$string['Authentication_callback_failure2'] = ' s funkcí';
$string['Authentication_issue1'] = 'Problémy s ověřováním';
$string['Authentication_issue2'] = 'Ověřovací pluginy vás nemohly přihlásit a neposkytly žádný další formulář ani přesměrování. Aktualizujte pomocí stisknutím klávesy F5, pokud se stále nedaří, kontaktujte podporu : <a href="mailto:%s">%s</a>.<p>Do e-mailu, prosím uveďte následující debug:</p><div style="margin-left:100px;">%s</div>';
$string['Authentication_issue2nodebug'] = 'Ověřovací pluginy vás nemohly přihlásit a neposkytly žádný další formulář ani přesměrování. Aktualizujte pomocí stisknutím klávesy F5, pokud se stále nedaří, kontaktujte podporu: <a href="mailto:%s">%s</a>.';
$string['Authentication_notloggedin1'] = 'Problémy s ověřováním';
$string['Authentication_notloggedin2'] = 'Nejste přihlášen/a. Aktualizujte stránku pomocí stisknutím klávesy F5, pokud se stále nedaří, kontaktujte podporu: <a href="mailto:%s">%s</a><p>Do e-mailu, prosím uveďte následující debug:</p><div style="margin-left:100px;">%s</div>';
$string['Authentication_notloggedin2nodebug'] = 'Nejste přihlášen/a. Aktualizujte stránku pomocí stisknutím klávesy F5, pokud se stále nedaří, kontaktujte podporu: <a href="mailto:%s">%s</a>';
$string['NoLookupConfigured'] = 'Vyhledávání nenastaveno';
$string['NoLookupConfiguredmessage'] = 'V config souboru nebylo nastaveno žádné vyhledávání. Kontaktujte, prosím, systémového správce.';
$string['guestbutton'] = 'Dočasný účet';
$string['authentication_lti_authmessage'] = 'Přihlaste se, prosím, k ověření spojení LTI.';
$string['authentication_lti_reauthmessage'] = 'Přihlaste se, prosím, znovu k ověření spojení LTI.';
$string['lti_not_allow_add_selfreg'] = 'Automatické přidávání studentů, do tohoto typu modulu, nebylo v configu modulu povoleno : ';
$string['denied_role'] = 'Vaše uživatelská role %s Vám přístup neumožňuje.';
$string['Error'] = 'Chyba';
$string['NoAcademicSession'] = 'Není nastaven akademický rok.';
$string['NoFieldMetadata'] = 'Žádná pole v metadatech skupin.';
$string['NoGroup'] = 'K aktuálnímu uživateli nelze nalézt žádnou skupinu.';
$string['new'] = 'Nový';
$string['old'] = 'Starý';
$string['note'] = 'Poznámka';
$string['studentname'] = 'Jméno studenta';
$string['html5warn'] = 'Rog&#333; vyžaduje podporu HTML5, aktualizujte, prosím, Váš prohlížeč.';
$string['colours'] = 'Barvy';
$string['Objectives Feedback report'] = 'Komentář k vzdělávacích cílům';
$string['Questions Feedback report'] = 'Komentář k úlohám';
$string['loggedinas'] = 'Přihlášen jako';
$string['crondisallowed'] = 'Démonek se pokusil spustit nepovolený skript';
$string['register'] = 'Registrovat jako Zaměstnance';
$string['register1'] = 'Registrovat jako ';
$string['demomodule'] = 'Demo Modul';
$string['name'] = 'Jméno';
$string['eassessmentmanagementsystem'] = 'Systém Elektronického Testování';
?>