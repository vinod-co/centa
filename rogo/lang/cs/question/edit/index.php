<?php          //cz Minor


//HTML5 part
require $configObject->get('cfg_web_root') . 'lang/' . $language . '/question/edit/hotspot_correct.txt';
require $configObject->get('cfg_web_root') . 'lang/' . $language . '/question/edit/area.txt';
require $configObject->get('cfg_web_root') . 'lang/' . $language . '/question/add/hotspot_add.txt';
require $configObject->get('cfg_web_root') . 'lang/' . $language . '/question/add/label_add.txt';
$jstring = $string; //to pass it to JavaScript HTML5 modules
//HTML5 part

require $configObject->get('cfg_web_root') . 'lang/' . $language . '/include/status.inc';
require $configObject->get('cfg_web_root') . 'lang/' . $language . '/include/blooms.inc';
require $configObject->get('cfg_web_root') . 'lang/' . $language . '/include/question_types.inc';
require $configObject->get('cfg_web_root') . 'lang/' . $language . '/question/sct_shared.php';
require $configObject->get('cfg_web_root') . 'lang/' . $language . '/question/info.php';
require $configObject->get('cfg_web_root') . 'lang/' . $language . '/question/edit/likert_scales.php';


$string['edit'] = 'Upravit';
$string['add'] = 'Přidat';
$string['type'] = 'Typ';
$string['owner'] = 'Vlastník';
$string['error'] = 'Chyba';
$string['question'] = 'Úloha';
$string['options'] = 'Volby';
$string['optionsmsg'] = '(Zobrazit pořadí)';
$string['theme'] = 'Téma/Nadpis';
$string['notes'] = 'Poznámky';
$string['notesmsg'] = '(viditelné pro studenty)';
$string['scenario'] = 'Scénář';
$string['scenariomsg'] = '(základní informace)';
$string['current'] = 'Aktuální';
$string['change'] = 'Změnit';
$string['media'] = 'Mediální soubory ';
$string['questionswf'] = 'Úloha SWF';
$string['answerswf'] = 'Odpověď SWF';
$string['leadin'] = 'Popis';
$string['leadinmsg'] = '(úloha)';
$string['presentation'] = 'Prezentace';
$string['includeother'] = 'zahrnout i políčko \'jiný\' <span class="note">(používáno při Průzkumech)</span>';
$string['vertical'] = 'Přepínače umístěné svisle';
$string['verticalother'] = "Přepínače umístěné svisle (s polem 'jiný')";
$string['horizontal'] = 'Přepínače umístěné vodorovně';
$string['dropdownlist'] = 'Rozbalovací nabídka';
$string['optionorder'] = 'Možnosti řazení';
$string['displayorder'] = 'Zobrazit řazení';
$string['alphabetic'] = 'Abecedně';
$string['random'] = 'Náhodně';
$string['image'] = 'Obrázek';
$string['upload_images'] = 'Nahrát obrázek pro popisky';
$string['maximum_size'] = 'Maximální velikost 200 pixels/square.';
$string['cancel'] = 'Zrušit';
$string['next'] = 'Dále &gt;';
$string['stem'] = 'Volba';
$string['value'] = 'Hodnota';
$string['questionstem'] = 'Úloha/Volba';
$string['availableoptions'] = 'Dostupné volby';
$string['variables'] = 'Proměnné';
$string['min'] = 'Minimum';
$string['max'] = 'Maximum';
$string['decimals'] = 'Desetinných míst';
$string['increment'] = 'Přírustek';
$string['formula'] = 'Vzorec';
$string['suppfunctions'] = 'podporované funkce';
$string['units'] = 'Jednotky';
$string['tolerance'] = 'Tolerance';
$string['tolerance_full'] = 'Odchylka pro úplné hodnocení';
$string['tolerance_partial'] = 'Odchylka pro částečné hodnocení';
$string['text'] = 'Text';
$string['markingmethod'] = 'Metoda známkování';
$string['markperoption'] = 'Známka za volbu';
$string['markperquestion'] = 'Známka za úlohu';
$string['allowpartial'] = 'Povolit částečné známkování';
$string['bonusmark'] = 'Bonusová známka';
$string['markscorrect'] = 'Za správnou';
$string['marksincorrect'] = 'Za chybnou';
$string['markspartial'] = 'Za částečnou';
$string['generalfeedback'] = 'Celkový Komentář';
$string['fbcorrect'] = 'Reakce na správnou odpověď';
$string['fbcorrectmsg'] = '(výchozí reakce)';
$string['fbincorrect'] = 'Reakce na chybnou odpověď';
$string['fbincorrectmsg'] = '(nechte prázdné pro výchozí)';
$string['tfnegativeabstain'] = 'Pravda/Nepravda bez odpovědi';
$string['tfpositive'] = 'Pravda/Nepravda';
$string['ynnegativeabstain'] = 'Ano/Ne bez odpovědi';
$string['ynpositive'] = 'Ano/Ne';
$string['yes'] = 'Ano';
$string['no'] = 'Ne';
$string['true'] = 'Pravda';
$string['false'] = 'Nepravda';
$string['feedback'] = 'Komentář';
$string['feedbackmsg'] = '(modelová odpověď pro vyhodnocení)';
$string['metadata'] = 'Metadata';
$string['keywords'] = 'Klíčová slova';
$string['teams'] = 'Týmy';
$string['answer'] = 'Odpověď';
$string['created'] = 'Vytvořeno:';
$string['modified'] = 'Upraveno:';
$string['editor'] = 'Editor';
$string['plaintext'] = 'Prostý text';
$string['wysiwyg'] = 'WYSIWYG';
$string['changes'] = 'Změny';
$string['comments'] = 'Poznámky';
$string['mapping'] = 'Mapování';
$string['performance'] = 'Výsledky';
$string['limitedsave'] = 'Omezené uložení';
$string['save'] = 'Uložit změny';
$string['questionlocked'] = 'Tato úloha je v současnosti uzamčena a nelze ji upravovat';
$string['isinreadonly'] = 'Tato úloha je aktuálně pouze ke čtení..';
$string['correct'] = 'Správně';
$string['lockedmsg'] = '<strong>Úloha uzamčena</strong> Tato úloha je v současnosti uzamčena a nelze ji upravovat.. <a href="#" class="help-link" rel="161">Více informací získáte zde.</a>';
$string['date'] = 'Datum';
$string['duration'] = 'Trvání';
$string['action'] = 'Akce';
$string['section'] = 'Sekce';
$string['old'] = 'Stará';
$string['new'] = 'Nová';
$string['nochangesrecorded'] = 'K této úloze nebyly zaznamenány žádné změny';
$string['reviewerscomments'] = 'Poznámky recenzenta/tů';
$string['reviewer'] = 'Recenzent';
$string['internal'] = 'Interní';
$string['external'] = 'Externí';
$string['actiontaken'] = 'Provedená akce';
$string['internalresponse'] = 'Interní odpověď';
$string['notactioned'] = 'Nespuštěno';
$string['readdisagree'] = 'Četl - nesouhlasím';
$string['readactioned'] = 'Četl - spuštěno';
$string['nocomments'] = 'Žádné komentáře';
$string['commentsmsg'] = 'K této úloze nebyly zaznamenány žádné komentáře.';
$string['objectives'] = 'Vzdělávací cíle';
$string['noobjectives'] = 'Nejsou mapovány žádné cíle';
$string['noneabove'] = 'Žádný z uvedených';
$string['noneabovemsg'] = 'Zkontrolujte, zda aktuální úloha skutečně neodpovídá žádnému z výše uvedených cílů %s.';
$string['mandatory'] = 'Označuje <strong>povinné</strong> pole, které musí být vyplněno.';
$string['typeundefined'] = 'Není definován typ úlohy.';
$string['typeinvalid'] = 'Neznámý typ úlohy <em>%s</em>.';
$string['questioninvalid'] = 'Neplatný ID úlohy.';
$string['optioninvalid'] = 'Neplatný ID volby.';
$string['mediauploaderror'] = 'Chyba při nahrávání mediálního souboru. Klikněte <a href="#" onclick="javascript: history.back();">Zpět</a> a zkuste znovu.';
$string['datasaveerror'] = 'Chyba při ukládání dat. Zkuste, prosím, znovu.';
$string['questionloaderror'] = 'Chyba při načítání dat úloh.';
$string['optionloaderror'] = 'Chyba při načítání dat voleb.';
$string['noclasserror'] = 'Nebyla nalezena třída splňující typ <code>%s</code>.';
$string['norecorderror'] = 'Úloha s ID %d nenalezena.';
$string['missingfieldserror'] = 'Nebyly poskytnuty následující povinná pole:';
$string['uploadimage'] = 'Nahrát obrázek';
$string['uploadinstructions'] = 'Vyberte, prosím, soubor obrázku, který chcete použít jako podklad pro tuto úlohu. Obrázky musí být ve formátu JPEG, GIF nebo PNG a nesmí být větší než 900x800 pixelů.';
$string['qeditsummary'] = 'Upravit detaily úlohy';
$string['oeditsummary'] = 'Upravit volby úlohy';
$string['qmetasummary'] = 'Upravit metadata úlohy';
$string['qassessmentsummary'] = 'Upravit data hodnocení úlohy';
$string['addoptions'] = 'Přidat další volby...';
$string['addreminders'] = 'Přidat více Upomínek...';
$string['correctanswer'] = 'Správná odpověď';
$string['correctanswers'] = 'Správné odpovědi';
$string['correctanswersmsg'] = '(K výběru více položek použijte &lt;ctrl&gt; a myš<br />)';
$string['onlinehelp'] = 'Online nápověda';
$string['blankinstructionsddl'] = 'Chceteli vytvořit prázdné doplňovací pole napište [blank] a [/blank] kolem voleb, které chcete přidat.<br />Správnou odpověď dejte vždy na misto <strong>první</strong> volby, následovanou distractory (všechny možnosti budou automaticky náhodně míchány).<br />např. Tyrannosaurus <span class="blank-tag">[blank]</span>Rex,Roger,Roderick,Ramsey<span class="blank-tag">[/blank]</span> byl větší než masožravý bipedal &hellip;';
$string['blankinstructionstextboxes'] = 'Chceteli vytvořit prázdné doplňovací pole [blank] a [/blank] tagy kolem voleb, které chcete přidat.<br />S [blank] tagem za správnou odpovědí jsou správné další alternativy (oddělené čárkou).<br />např. Ve které jssme zemi <span class="blank-tag">[blank]</span>UK,United Kingdom,Britain,Great Britain,GB<span class="blank-tag">[/blank]</span>?';
$string['dropdownlists'] = 'Rozbalovací nabídky (náhodné)';
$string['textboxes'] = 'Prázdné pole';
$string['rows'] = 'řádky';
$string['cols'] = 'sloupce';
$string['assessmentdata'] = 'Data hodnocení';
$string['terms'] = 'Terms';
$string['termsmsg'] = '(oddělené středníkem)';
$string['this'] = 'Toto';
$string['veryunlikely'] = 'velmi nepravděpodobné';
$string['unlikely'] = 'nepravděpodobné';
$string['neithernorlikely'] = 'není pravděpodobné ani nepravděpodobné';
$string['morelikely'] = 'pravděpodobné';
$string['verylikely'] = 'velmi pravděpodobné';
$string['useless'] = 'zbytečné';
$string['lessuseful'] = 'málo užitečné';
$string['neithernoruseful'] = 'ani více či méně užitečné';
$string['moreuseful'] = 'více užitečné';
$string['veryuseful'] = 'velmi užitečné';
$string['contraindicatedtotally'] = 'zcela kontraindikováno či téměř úplně';
$string['detrimental'] = 'není užitečné nebo dokonce škodlivé';
$string['useful'] = 'užitečné';
$string['necessary'] = 'absolutně nutné';
$string['contraindicated'] = 'kontraindikováno';
$string['lessindicated'] = 'indikováno méně';
$string['neithernorindicated'] = 'ani více či méně indikováno';
$string['indicated'] = 'indikováno';
$string['stronglyindicated'] = 'jednoznačně indikováno';
$string['oscescales'] = 'Stupnice stanice OSCE';
$string['pointscales'] = 'Bodová stupnice';
$string['scale'] = 'Stupnice';
$string['nacolumn'] = 'Sloupec N/A ';
$string['includena'] = "včetně volby 'neaplikováno' ";
$string['startyear'] = 'Rok zahájení';
$string['endyear'] = 'Rok ukončení';
$string['format'] = 'Formát';
$string['assessmentmsg'] = '(pouze pro hodnocení)';
$string['postexamchange'] = 'Změna odpovědi po zkoušce';
$string['correctoption'] = 'Správná volba';
$string['editquestion'] = 'Upravit úlohu';
$string['editscenario'] = 'Úprava scénáře';
$string['mediadeleted'] = 'Mediální soubory odstraněny';
$string['optionno'] = 'Volba #%d';
$string['option'] = 'Volba';
$string['minimum'] = 'Minimum';
$string['maximum'] = 'Maximum';
$string['optiontext'] = $string['optionno'] . ' Text';
$string['optionmedia'] = $string['optionno'] . ' Mediální soubory';
$string['optionanswer'] = $string['optionno'] . ' Odpověď';
$string['optionfbcorrect'] = $string['optionno'] . ' Reakce na správnou odpověď';
$string['optionfbincorrect'] = $string['optionno'] . ' Reakce na chybnou odpověď';
$string['newoption'] = 'Nová volba';
$string['deletedoption'] = 'Zrušená volba';
$string['now'] = 'nyní';
$string['never'] = 'nikdy';
$string['validationerror'] = 'S Vaší odpovědí byly potíže. Prostudujte si, prosím, formulář a zkuste znovu';
$string['enterleadin'] = 'Zadejte, prosím, hlavičku  úlohy';
$string['enterdescription'] = 'Zadejte, prosím, popis';
$string['enteroptiontext'] = 'Zadejte, prosím, hodnoty této volby';
$string['enteroption'] = 'Zadejte, prosím, pro tuto volbu některou z možností textu nebo mediálního souboru';
$string['enteroptionshort'] = 'Požadováno';
$string['enteroption_kw'] = 'Zadejte, prosím, klíčové slovo pro tuto úlohu';
$string['enterquestion'] = 'Zadejte, prosím, úlohu';
$string['enterformula'] = 'Zadejte, prosím, vzorec';
$string['entervignette'] = 'Zadejte, prosím, klinický medailonek pro tuto úlohu';
$string['validanswers'] = 'Zadejte, prosím,  nejméně %d správných odpovědí';
$string['selectarea'] = 'Vytyčte, prosím, plochu';
$string['randomenterquestion'] = 'Vyberte, prosím, úlohy, které budou zahrnuty do bloku';
$string['mrqconvert'] = 'Existuje jen jedna správná odpověď, bylo by lepší použít úlohy typu MCQ.\r\nChcete převést tuto úlohu na MCQ?';
$string['showmore'] = 'Zobrazit více';
$string['hidemore'] = 'Skrýt více';
$string['minor'] = 'Nevýznamné';
$string['major'] = 'Významné';
$string['cannot'] = 'Cannot Comment';
$string['pleaserank'] = 'Prosím o hodnocení těchto &hellip; počínaje &hellip; první:';
$string['addtobank'] = 'Přidat do banky';
$string['addtobankandpaper'] = 'Do banky &amp; dokumentu';
$string['na'] = 'N/A';
$string['description'] = 'Popis';
$string['questions'] = 'Úlohy';
$string['addquestions'] = 'Přidat úlohu(y)';
$string['questionbasedon'] = 'Úloha vychází z';
$string['keywordwarning'] = 'Varování: klíčové slovo nenalezeno, úlohu nelze vytvořit.';
$string['mappingwarning'] = 'Varování: Pokud není tato úloha přidána do dokumentu, budou veškerá mapování této úlohy ztracena!';
$string['markchangewarning'] = 'Varování: Měnit přidělené známky lze po zkoušce pouze pokud je to nezbytně nutné. Jste si jist, že chcete pokračovat?';
$string['percenttolerance'] = 'Odchylky mohou být nyní vyjádřeny v procentech (např. 5%) stejně tak v absolutních číslech';
$string['answercorrect'] = 'Správná odpověď';
$string['marks'] = 'Hodnocení';
$string['variable'] = 'Proměnné $';
$string['decimal'] = 'desetinné';
$string['addanswers'] = 'Přidat více odpovědí...';
$string['newvariable'] = 'Nová proměnná';
$string['newanswer'] = 'Nová odpověď';
$string['deletedvar'] = 'Odstranit proměnnou';
$string['deletedanswer'] = 'Odstranit odpověď';
$string['displayunits'] = 'Zobrazit jednotky úlohy';
$string['unitmarking'] = 'známka za jednotku';
$string['ifincorrect'] = '(když nesprávně)';
$string['precision'] = 'Přesnost';
$string['enforceto'] = 'Vynutit odpověď';
$string['sigfigure'] = 'důležité osoby';
$string['sigfigures'] = 'Důležité osoby';
$string['withzeros'] = 'vč. koncové nuly ';	
$string['enforcedisplay'] = 'Vynutit přesnost studentovi odpovědi';
$string['includetrailing0'] = 'Včetně koncové nuly ';	
$string['notenforced'] = 'Nevynuceno';
$string['tooltip_formula'] = 'Pro další podrobnosti klikněte na Odkaz v nápovědě online.';
// Textbox
$string['reminders'] = 'Upomínky';
$string['reminder_no'] = 'Upomínka #%d';
?>