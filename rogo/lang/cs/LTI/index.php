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

include 'lti_common.php';

$string['NoPapers'] = 'K tomuto modulu nejsou dostupné žádné dokumenty';
$string['NoPapersDesc'] = 'K tomuto modulu nejsou dostupné žádné dokumenty. Pravděpodobně je to způsobeno tím, že jste vytvořili nový odkaz z VLE z nového modulu, a proto nemáte v současnosti nakonfigurované žádné dokumety.<br /><br />K vytvoření dokumentu vyberte, prosím <a href="../" target="_blank">spustit Rogo</a>'; //zavřete prohlížeč (<strong>velice dùležité</strong>) následně přejděte na domovskou stránku Rogo a dokument vytvořte.

$string['NoModCreateTitle'] = 'Vytvoření nového modulu není povoleno';
$string['NoModCreate'] = 'Tvorba modulu z LTI není povolena v konfiguraci, proto nelze vytvořit modul s kódem kurzu: ';
$string['NotAddedToModuleTitle'] = 'Přidání do týmu modulu nebylo úspěšné';
$string['NotAddedToModule'] = 'Přidávání do týmu modulu není v LTI konfiguraci povoleno, tato závada se vyskytla u modulu: ';

$string['NoModCreateTitle2'] = 'Vytváření modulu neběží';
$string['NoModCreate2'] = 'Tvorba modulu z LTI neběží, jelikož uživatel nevlastní oprávnění, a proto nelze vytvořit modul s kódem kurzu: ';
?>
