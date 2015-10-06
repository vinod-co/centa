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

//HTML5 part
require '../../lang/' . $language . '/question/edit/hotspot_correct.txt';
require '../../lang/' . $language . '/question/edit/area.txt';
require '../../lang/' . $language . '/question/add/hotspot_add.txt';
require '../../lang/' . $language . '/question/add/label_add.txt';
$jstring = $string; //to pass it to JavaScript HTML5 modules
//HTML5 part

require '../../lang/' . $language . '/include/status.inc';
require '../../lang/' . $language . '/include/blooms.inc';
require '../../lang/' . $language . '/include/question_types.inc';
require '../../lang/' . $language . '/question/sct_shared.php';
require '../../lang/' . $language . '/question/info.php';
require '../../lang/' . $language . '/question/edit/likert_scales.php';

$string['edit'] = 'Edytuj';
$string['add'] = 'Dodaj';
$string['type'] = 'Typ';
$string['owner'] = 'Właściciel';
$string['error'] = 'Błąd';
$string['question'] = 'Pytanie';
$string['options'] = 'Opcje';
$string['optionsmsg'] = '(wg. kolejności wersji)';
$string['theme'] = 'Motyw/Nagłówek';
$string['notes'] = 'Uwagi';
$string['notesmsg'] = '(widoczne dla studentów)';
$string['scenario'] = 'Wersja';
$string['scenariomsg'] = '(informacje wstępne)';//background info
$string['current'] = 'Aktualny';
$string['change'] = 'Zmień';
$string['media'] = 'plik medialny';
$string['questionswf'] = 'pytanie SWF';
$string['answerswf'] = 'odpowiedź SWF';
$string['leadin'] = 'Sformułowanie pytania';
$string['leadinmsg'] = '(pytanie)';
$string['presentation'] = 'Prezentacja';
$string['includeother'] = 'zawiera pole tekstowe \'inne\' <span class="note">(stosowane w ankietach)</span>';
$string['vertical'] = 'Pionowy układ przycisków opcji';
$string['verticalother'] = "Pionowy układ przycisków opcji (wraz z polem tekstowym 'inne')";
$string['horizontal'] = 'Poziomy układ przycisków opcji';
$string['dropdownlist'] = 'Lista rozwijana';
$string['optionorder'] = 'Kolejność opcji';
$string['displayorder'] = 'Nie zmieniona';
$string['alphabetic'] = 'Alfabetyczna';
$string['random'] = 'Losowa';
$string['image'] = 'Obraz';
$string['upload_images'] = 'Załaduj obrazy dla etykiet';
$string['maximum_size'] = 'Maksymalny rozmiar 200 pikseli kwadratowych.';
$string['cancel'] = 'Anuluj';
$string['next'] = 'Dalej &gt;';
$string['stem'] = 'Opcja';//??
$string['value'] = 'wartość';
$string['questionstem'] = 'Pytanie/Opcja'; //??
$string['availableoptions'] = 'Dostępne opcje';
$string['variables'] = 'Zmienne';
$string['min'] = 'Minimum'; //cognate
$string['max'] = 'Maksimum';
$string['decimal'] = 'dziesiętnej'; 
$string['decimals'] = 'Dziesiętnych';
$string['increment'] = 'Przyrost';
$string['formula'] = 'Wzór';
$string['suppfunctions'] = 'obsługiwane funkcje';
$string['units'] = 'Jednostki';
$string['tolerance'] = 'Tolerancja';
$string['tolerance_full'] = 'dla pełnych punktów';
$string['tolerance_partial'] = 'dla punktów ułamkowych';
$string['text'] = 'Tekst';
$string['markingmethod'] = 'Metoda punktacji';
$string['markperoption'] = 'Punkty za opcję';
$string['markperquestion'] = 'Punkty za pytanie';
$string['allowpartial'] = 'Zezwalaj na punktację ułamkową';
$string['bonusmark'] = 'bonus za kolejność';
$string['markscorrect'] = 'pkt. za poprawną';
$string['marksincorrect'] = 'pkt. za niepoprawną';
$string['markspartial'] = 'pkt. ułamkowe';
$string['generalfeedback'] = 'Ogólny odzew';
$string['fbcorrect'] = 'Odzew na poprawną';
$string['fbcorrectmsg'] = '(Odzew domyślny)';
$string['fbincorrect'] = 'Odzew na niepoprawną';
$string['fbincorrectmsg'] = '(pozostaw puste by zastosować domyślną)';
$string['tfnegativeabstain'] = 'Prawda/Fałsz z odmową';
$string['tfpositive'] = 'Prawda/Fałsz';
$string['ynnegativeabstain'] = 'Tak/Nie z odmową';
$string['ynpositive'] = 'Tak/Nie';
$string['yes'] = 'Tak';
$string['no'] = 'Nie';
$string['true'] = 'Prawda';
$string['false'] = 'Fałsz';
$string['feedback'] = 'Odzew';
$string['feedbackmsg'] = '(model odzewu dla sprawdzianów)';
$string['metadata'] = 'Metadane';
$string['keywords'] = 'Słowa kluczowe';
$string['teams'] = 'Zespoły';
$string['answer'] = 'Odpowiedź';
$string['created'] = 'Utworzone:';
$string['modified'] = 'Zmodyfikowane:';
$string['editor'] = 'Edytor';
$string['plaintext'] = 'Czysty tekst';
$string['wysiwyg'] = 'WYSIWYG'; //cognate
$string['changes'] = 'Zmiany';
$string['comments'] = 'Komentarze';
$string['mapping'] = 'Odwzorowanie'; //Mapowanie,odwzorowanie, projekcja
$string['performance'] = 'Osiągnięcia';
$string['limitedsave'] = 'Zapisz (limit)';
$string['save'] = 'Zapisz zmiany';
$string['correct'] = 'Poprawnie';
$string['questionlocked'] = 'To pytanie jest zablokowane dla edycji przez';
$string['isinreadonly'] = 'Jest ono dostępne tylko do odczytu.';
$string['lockedmsg'] = '<strong>Pytanie zablokowane</strong> To pytanie jest zablokowane i nie może być zmienione. <a href="#" class="help-link" rel="161">Kliknij aby dowiedzieć się więcej.</a>';
$string['date'] = 'Data';
$string['duration'] = 'czas trwania';
$string['action'] = 'Działanie';
$string['section'] = 'Sekcja';
$string['old'] = 'Stare';
$string['new'] = 'Nowe';
$string['nochangesrecorded'] = 'Nie zarejestrowano zmian w tym pytaniu';
$string['reviewerscomments'] = 'Komentarze recenzenta';
$string['reviewer'] = 'Recenzent';
$string['internal'] = 'Wewnętrzny';
$string['external'] = 'Zewnętrzny';
$string['actiontaken'] = 'Działania podjęte';
$string['internalresponse'] = 'Wewnętrzna odpowiedź';
$string['notactioned'] = 'Nie podjęto działań';//Not actioned
$string['readdisagree'] = 'Przeczytano - brak zgody';
$string['readactioned'] = 'Przeczytano - podjęto działania';
$string['nocomments'] = 'Brak komentarzy';
$string['commentsmsg'] = 'Nie zanotowano komentarzy dla tego pytania.';
$string['objectives'] = 'Cele';
$string['noobjectives'] = 'Nie odwzorowano żadnych celów';
$string['noneabove'] = 'Żaden z powyższych';
$string['noneabovemsg'] = 'Odznacz tu jeśli aktualne pytanie nie spełnia żadnego z powyższych celów %s.';
$string['mandatory'] = 'Wypełnienie tego pola jest <strong>wymagane</strong>.';
$string['typeundefined'] = 'Nie zdefiniowano typu pytania.';
$string['typeinvalid'] = 'Niewłaściwy typ pytania <em>%s</em>.';
$string['questioninvalid'] = 'Niewłaściwy identyfikator pytania.';
$string['optioninvalid'] = 'Niewłaściwy identyfikator opcji.';
$string['mediauploaderror'] = 'Błąd przesyłania pliku medialnego. Wybierz przycisk <a href="#" onclick="javascript: history.back();">Wstecz</a> w przeglądarce i spróbuj ponownie.';
$string['datasaveerror'] = 'Błąd zapisywania danych. Spróbuj ponownie';
$string['questionloaderror'] = 'Błąd odczytywania danych pytania.';
$string['optionloaderror'] = 'Błąd odczytywania danych opcji.';
$string['noclasserror'] = 'Typ <code>%s</code> nie pasuje do żadnej z klas.';
$string['norecorderror'] = 'Nie znaleziono pytań z identyfikatorem %d.';
$string['missingfieldserror'] = 'Następujące pola wymagane nie zostały wypełnione:';
$string['uploadimage'] = 'Załaduj obraz';
$string['uploadinstructions'] = 'Wybierz plik obrazu, który chciałbyś użyć jako podstawę dla tego nowego pytania. Obrazy muszą mieć format JPEG, GIF lub PNG i nie mogą mieć rozmiarów większych niż 900x800 pikseli.';
$string['qeditsummary'] = 'Edytuj szczegóły pytania';
$string['oeditsummary'] = 'Edytuj szczegóły opcji';
$string['qmetasummary'] = 'Edytuj podstawowe metadane pytania';
$string['qassessmentsummary'] = 'Edytuj dane oceny pytania';
$string['addoptions'] = 'Dodaj więcej opcji...';
$string['addreminders'] = 'Dodaj więcej przypomnień...';
$string['correctanswer'] = 'Poprawna odpowiedź';
$string['correctanswers'] = 'Poprawne odpowiedzi';
$string['correctanswersmsg'] = '(Zastosuj kliknięcie z &lt;CTRL&gt;<br />by zaznaczyć kilka elementów)';
$string['onlinehelp'] = 'Pomoc online';
$string['blankinstructionsddl'] = 'Aby utworzyć puste pole umieść znaczniki [blank] oraz [/blank] przed i po opcji, którą chcesz dodać.<br />Zawsze umieszczaj poprawną odpowiedź jako <strong>pierwszą</strong> opcję, po której umieść dystraktory (kolejność wszystkich opcji jest losowana automatycznie).<br />np. Bolesław <span class="blank-tag">[blank]</span>Chrobry,Krzywousty,Wstydliwy,Pobożny<span class="blank-tag">[/blank]</span> był pierwszym koronowanym władcą Polski;';
$string['blankinstructionstextboxes'] = 'Aby utworzyć puste pole umieść znaczniki [blank] oraz [/blank] przed i po opcji, którą chcesz dodać.<br />Pomiędzy znacznikami [blank] dodaj odpowiedź poprawną i wszystkie odpowiedzi alternatywne, które brzmią prawdopodobnie (rozdzielone przecinkami).<br />np. Londyn jest stolicą <span class="blank-tag">[blank]</span>Anglii,Wielkiej Brytanii,Zjednoczonego Królestwa,UK,GB<span class="blank-tag">[/blank]</span>?';
$string['dropdownlists'] = 'Lista wyboru (kolejność losowa)';
$string['textboxes'] = 'Puste pole tekstowe';
$string['rows'] = 'wiersze(y)';
$string['cols'] = 'kolumn';
$string['assessmentdata'] = 'Dane oceny';
$string['terms'] = 'Hasła';
$string['termsmsg'] = '(rozdzielone przecinkami)';
$string['this'] = 'To';//??
$string['veryunlikely'] = 'zdecydowanie niemożliwa';
$string['unlikely'] = 'niemożliwa';
$string['neithernorlikely'] = 'niewykluczona';
$string['morelikely'] = 'możliwa';
$string['verylikely'] = 'bardzo możliwa';
$string['useless'] = 'bezużyteczny';
$string['lessuseful'] = 'mało użyteczny';
$string['neithernoruseful'] = 'użyteczność niewykluczone';
$string['moreuseful'] = 'użyteczny';
$string['veryuseful'] = 'bardzo użyteczny';
$string['contraindicatedtotally'] = 'całkowicie lub znacząco przeciwwskazane';
$string['detrimental'] = 'nie użyteczne lub niekorzystne';
$string['useful'] = 'użyteczne';
$string['necessary'] = 'absolutnie niezbędne';
$string['contraindicated'] = 'przeciwwskazana';
$string['lessindicated'] = 'mniej wskazana';
$string['neithernorindicated'] = 'brak wskazań';
$string['indicated'] = 'wskazana';
$string['stronglyindicated'] = 'zdecydowanie wskazana';
$string['oscescales'] = 'Skale stacji OSCE';
$string['pointscales'] = '-punktowa skala';
$string['scale'] = 'Skala';
$string['nacolumn'] = 'Kolumna nie dotyczy';
$string['includena'] = "uwzględnij opcję 'nie dotyczy'";
$string['startyear'] = 'Rok początkowy';
$string['endyear'] = 'Rok końcowy';
$string['format'] = 'Format'; //cognate
$string['assessmentmsg'] = '(tylko dla oceny)';
$string['postexamchange'] = 'Zmiana odpowiedzi po egzaminie';
$string['correctoption'] = 'Poprawna opcja';
$string['editquestion'] = 'Edytuj pytanie';
$string['editscenario'] = 'Edytuj scenariusz';
$string['mediadeleted'] = 'Media usunięte';
$string['optionno'] = 'Opcja nr. %d';
$string['option'] = 'Opcja';
$string['minimum'] = 'Minimum'; //cognate
$string['maximum'] = 'Maksimum';
$string['optiontext'] = $string['optionno'] . ' - tekst';
$string['optionmedia'] = $string['optionno'] . ' - pliki medialne';
$string['optionanswer'] = $string['optionno'] . ' - odpowiedź';
$string['optionfbcorrect'] = $string['optionno'] . ' - odzew poprawny';
$string['optionfbincorrect'] = $string['optionno'] . ' - odzew niepoprawny';
$string['newoption'] = 'Nowa opcja';
$string['deletedoption'] = 'Opcja usunięta';
$string['now'] = 'teraz';
$string['never'] = 'nigdy';
$string['validationerror'] = 'Wystąpił problem podczas wysyłania. Przejrzyj formularz i spróbuj ponownie';
$string['enterleadin'] = 'Sformułuj pytanie';
$string['enterdescription'] = 'Wprowadź opis';
$string['enteroptiontext'] = 'Wprowadź wartość dla tej opcji';
$string['enteroption'] = 'Wprowadź tekst opcji lub plik medialny dla tej opcji';
$string['enteroptionshort'] = 'Wymagane';
$string['enteroption_kw'] = 'Wybierz słowo kluczowe dla tego pytania';
$string['enterquestion'] = 'Wprowadź pytanie';
$string['enterformula'] = 'Wprowadź wzór';
$string['entervignette'] = 'Wprowadź przypadek kliniczny dla tego pytania';
$string['validanswers'] = 'Wprowadź przynajmniej %d właściwą odpowiedź/odpowiedzi';
$string['selectarea'] = 'Wybierz obszar';
$string['randomenterquestion'] = 'Wybierz pytania dla tego zestawu';
$string['mrqconvert'] = 'Jest tylko jedna poprawna odpowiedź - do tego celu lepsze będzie pytanie wielokrotnego wyboru.\r\nCzy chcesz przekonwertować to pytanie na pytanie wielokrotnego wyboru?';
$string['showmore'] = 'Pokaż więcej';
$string['hidemore'] = 'Ukryj nadmiar';
$string['minor'] = 'Drobne';
$string['major'] = 'Znaczące';
$string['cannot'] = 'Cannot Comment';
$string['pleaserank'] = 'Oceń co następuje &hellip; zaczynając od &hellip; pierwszego:';
$string['addtobank'] = 'Dodaj do banku';
$string['addtobankandpaper'] = 'Do banku i arkusza';
$string['na'] = 'Brak';
$string['description'] = 'Opis';
$string['questions'] = 'Pytania';
$string['addquestions'] = 'Dodaj pytanie(a)';
$string['questionbasedon'] = 'Pytanie bazujące na';
$string['keywordwarning'] = 'Uwaga: nie znaleziono słów kluczowych, utworzenie pytania jest niemożliwe.';
$string['mappingwarning'] = 'Uwaga: wszystkie odwzorowania będą utracone jeśli to pytanie nie zostanie dodane do arkusza!';
$string['markchangewarning'] = 'Uwaga: zmiana parametrów oceny po egzaminie powinna być podejmowana tylko w razie najwyższej konieczności. Czy na pewno kontynuować?';
$string['percenttolerance'] = 'Tolerancja może być wyrażona procentowo (np. 5%) jak i wartościami bezwzględnymi';
$string['answercorrect'] = 'Odpowiedź poprawna';
$string['marks'] = 'Punkty';
// Extended calc
$string['addanswers'] = 'Dodaj więcej pytań...';
$string['newvariable'] = 'Nowa zmienna';
$string['variable'] = 'Zmienna $';
$string['newanswer'] = 'Nowa odpowiedź';
$string['deletedvar'] = 'Usunięta zmienna';
$string['deletedanswer'] = 'Usunięta odpowiedź';
$string['displayunits'] = 'Pokaż jednostki dla pytania';
$string['unitmarking'] = 'Ocena jednostek';
$string['ifincorrect'] = '(jeli nieprawidłowe)';
$string['precision'] = 'Precyzja';
$string['enforceto'] = 'Wymuś odpowiedź do';
$string['sigfigure'] = 'cyfry znaczącej';
$string['sigfigures'] = 'Cyfr znaczących';
$string['withzeros'] = 'z uwzględnieniem zer wiodących';
$string['enforcedisplay'] = 'Wymuś precyzję w odpowiedziach studenckich';
$string['includetrailing0'] = 'Uwzględniając zera wiodące';
$string['notenforced'] = 'Nie wymuszone';
$string['tooltip_formula'] = 'Kliknij na link by uzyskać więcej informacji z systemu pomocy.';
// Textbox
$string['reminders'] = 'Przypomnienia';
$string['reminder_no'] = 'Przypomnienie #%d';
?>
