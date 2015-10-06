<?php
// This file is part of Rogo
//
// Rogo is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Rogo is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Rogo.  If not, see <http://www.gnu.org/licenses/>.

$string['company'] = 'Instytucja';
$string['companyname'] = 'Nazwa instytucji:';
$string['databaseadminuser'] = 'Administrator bazy danych';
$string['server'] = 'Serwer';
$string['tempdirectory'] = 'Katalog Tymczasowy';
$string['needusername'] = 'Ten instalator wymaga nazwy użytkownika administracyjnego bazy danych MySQL oraz jego hasła aby utworzyć bazę danych i wymagane tablice. Dane tego użytkownika nie są zapisywane, a tylko zastosowane w instalacji.';
$string['dbusername'] = 'Użytkownik:';
$string['dbpassword'] = 'Hasło:';
$string['databasesetup'] = 'Ustawienia bazy danych';
$string['databasehost'] = 'Adres:';
$string['webhost'] = 'host WebSerwera';
$string['databaseport'] = 'Port:';
$string['databasename'] = 'Nazwa:';
$string['databasecharset'] = 'Kodowanie znaków w bazie danych';
$string['databaseuser'] = 'Użytkownik bazy danych Rogō';
$string['pagecharset'] = 'Kodowanie znaków na stronach';
$string['rdbusername'] = 'Użytkownik';
$string['rdbpassword'] = 'Hasło:';
$string['timedateformats'] = 'Formaty czasu/daty';
$string['date'] = 'Data (%s)';
$string['shortdatetime'] = 'Krótka forma Daty/Czasu (%s)';
$string['longdatetime'] = 'Długa forma Daty/Czasu (%s)';
$string['longdatephp'] = 'Długi format daty (PHP)'; 
$string['shortdatephp'] = 'Krótki format daty (PHP)';
$string['longtimephp'] = 'Długi format czasu (PHP)'; 
$string['shorttimephp'] = 'Krótki format czasu (PHP)'; 
$string['currenttimezone'] = 'Aktualna strefa czasowa';
$string['authentication'] = 'Uwierzytelnianie';

$string['allowlti'] = 'Pozwól przez LTI';
$string['allowintdb'] = 'Wewnętrzna baza danych';
$string['allowguest'] = 'Logowanie przez konto gościa (dla egzaminów sumatywnych)';
$string['allowimpersonation'] = 'podszywanie się pod użytkownika (tylko SysAdmin)';
$string['useldap'] = 'Zastosuj LDAP:';

$string['lookup'] = 'Źródła danych odnośników';
$string['allowlookupXML'] = 'użyj XML (wymaga dostosowania w pliku konfiguracyjnym)';
$string['rdbbasename'] = 'część bazowa nazwy użytkownika';

$string['ldapserver'] = 'Serwer LDAP:';
$string['searchdn'] = 'Wyszukaj dn:';
$string['bindusername'] = 'użytkownik:';
$string['bindpassword'] = 'hasło:';
$string['userprefix'] = 'Prefiks nazwy użytkownika';
$string['userprefixtip'] = 'Prefiks nazwy użytkownika w wyszukiwaniach LDAP, np. &quot;sAMAccountName=&quot;';
$string['sysadminuser'] = 'Administrator systemu Rogō';
$string['initialsysadmin'] = 'Wymagane jest wyjściowe konto administratora systemu, aby móc zalogować się i utworzyć zwykłe konta użytkowników i zarządzać systemem.';
$string['title'] = 'Tytuł:';
$string['title_types'] = "Pani,Pan,Mgr,Dr,Prof.";
$string['firstname'] = 'Imię:';
$string['surname'] = 'Nazwisko:';
$string['emailaddress'] = 'Adres Email:';
$string['username'] = 'Użytkownik:';
$string['password'] = 'Hasło:';
$string['helpdb'] = 'Baza danych pomocy Rogō';
$string['loadhelp'] = 'Załaduj pomoc:';
$string['supportemaila'] = 'Email wsparcia' ;
$string['supportemail'] = 'Email wsparcia:';
$string['supportnumbers'] = 'Numer telefonów wsparcia';
$string['name'] = 'Nazwisko:';
$string['number'] = 'Numer:';
$string['install'] = 'Instalacja Rog&#333;';
$string['installed'] = 'Rog&#333; jest skutecznie zainstalowane.';
$string['deleteinstall'] = 'Ze względów bezpieczeństwa usuń katalog install.';
$string['staffhomepage'] = 'Przejdź do strony startowej kadry';

$string['logwarning1'] = 'nie załadowano staff_help.sql, nie można było zainstalować pomocy dla kadry';
$string['logwarning2'] = 'nie znaleziono staff_help.sql, nie można było zainstalować pomocy dla kadry';
$string['logwarning3'] = 'nie załadowano student_help.sql, nie można było zainstalować pomocy dla studentów';
$string['logwarning4'] = 'nie znaleziono student_help.sql, nie można było zainstalować pomocy dla studentów';
$string['displayerror1'] = 'baza danych o nazwie \'%s\' jest już wykorzystywana - użyj innej nazwy';
$string['displayerror2'] = 'baza danych \'%s\' nie mogła być utworzona - zweryfikuj przywileje administratora';
$string['displayerror3'] = 'nie można było utworzyć tablic.';
$string['wdatabaseuser'] = 'Konto użytkownika bazy danych ';
$string['wnotcreated'] = ' nie mogło być utworzone';
$string['wnotpermission'] = ' nie ma ustawionych przywilejów';
$string['logwarning20'] = 'Nie można było skasować przywilejów';
$string['errors1'] = 'Rog&#333; było już zainstalowane! Usuń lub zmień nazwę %s aby móc uruchomić instalację ponownie. Lub przejdź do interfejsu dla kadry: %s';
$string['errors3'] = 'Rog&#333; wymaga aby istniał %s i był dostępny do zapisu przez webserver';
$string['errors4'] = 'Rog&#333; wymaga aby istniał %s/media i był zapisywalny przez webserver';
$string['errors5'] = 'Rog&#333; wymaga aby istniał %s/qti/imports i był zapisywalny przez webserver';
$string['errors6'] = 'Rog&#333; wymaga aby istniał %s/qti/exports i był zapisywalny przez webserver';
$string['errors7'] = 'Rog&#333; wymaga aby istniał %s/temp i był zapisywalny przez webserver';
$string['errors8'] = 'Rog&#333; wymaga serwera Apache w wersji $apache_min_ver';
$string['errors9'] = 'Rog&#333; wymaga serwera Apache w wersji $apache_min_ver lub wyższej';
$string['errors10'] = 'Rog&#333; wymaga PHP w wersji $php_min_ver lub wyższej';
$string['errors11'] = 'Rog&#333; wymaga aby moduł PHP mysqli funkcjonował - zainstaluj go lub aktywuj.';
$string['errors12'] = 'Dostęp do Rogō jest możliwy wyłącznie przez https. Zaktualizuj konfigurację serwera.';
$string['errors13'] = 'Błąd';
$string['errors14'] = 'Wygenerowano następujące ostrzeżenia';
$string['errors15'] = 'Uwaga';
$string['errors16'] = 'Rog&#333; wymaga prawa zapisu w swym pliku konfiguracyjnym %s/config/config.inc.php. Jednym ze sposobów realizacji tego jest tymczasowe udzielenie praw zapisu do  %s/config i przywrócenie oryginalnych przywilejów zaraz po dokonaniu aktualizacji.';
$string['installscript'] = 'Skrypt instalacyjny Rog&#333;';
$string['systeminstallation'] = 'Instalacja systemu';

$string['interactivequestions'] = "Ustawienia dotyczące pytań interaktywnych";
$string['flash'] = "Adobe Flash"; //cognate
$string['html5'] = "HTML5"; //cognate

$string['labsecuritytype'] = "Bezpieczeństwo pracowni egzaminów końcowych";
$string['IP'] = "Adresy IP";
$string['hostname'] = "Nazwa hosta maszyny";
?>
