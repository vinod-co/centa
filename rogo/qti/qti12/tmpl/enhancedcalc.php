





















































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

require("header.php");

$settingsdecoded=json_decode($question->settings,true);


?>
			<qticomment><![CDATA[Question:<?php echo $question->origleadin ?>]]></qticomment>
			<qticomment><![CDATA[Formula:<?php echo $settingsdecoded['answers'][0]['formula']; ?>]]></qticomment>
			<qticomment><![CDATA[Units:<?php echo $settingsdecoded['answers'][0]['units']; ?>]]></qticomment>
			<qticomment><![CDATA[Decimals:<?php echo $settingsdecoded['dp']; ?>]]></qticomment>
			<qticomment><![CDATA[Tolerance:<?php echo $settingsdecoded['tolerance_full']; if ($settingsdecoded['fulltoltyp']=='%') echo '%'; ?>]]></qticomment>
			<qticomment><![CDATA[Settings:<?php echo $question->settings ?>]]></qticomment>
<?php foreach ($settingsdecoded['vars'] as $id => $var) : ?>
			<qticomment><![CDATA[Variable:<?php echo $id ?>|<?php echo $var['min'] ?>|<?php echo $var['max'] ?>|<?php echo $var['dec'] ?>|<?php echo $var['inc'] ?>]]></qticomment>
<?php endforeach; ?>

			<?php echo $headertext ?>
			
			<response_num ident="1" rcardinality="Single">
				<render_fib fibtype="Decimal" prompt="Box" rows="1" columns="10" maxchars="10"/>
			</response_num>
		</presentation>
		
		<resprocessing>
			<outcomes>
				<decvar/>
			</outcomes>
			<respcondition title="right">
				<conditionvar>
					<varequal respident="1"><?php echo $real_answer ?></varequal>
				</conditionvar>
				<setvar action="Set"><?php echo $settingsdecoded['marks_correct'] ?></setvar>
				<displayfeedback linkrefid="general"/>
			</respcondition>
			
<?php if ($question->marks_partial != 0) : ?>
			<respcondition title="Within range" >
				<conditionvar>
					<vargte respident="1"><?php echo $uansdata['tolerance_partialans'] ?></vargte>
					<varlte respident="1"><?php echo $uansdata['tolerance_partialansneg'] ?></varlte>
				</conditionvar>
				<setvar action="Set"><?php echo $settingsdecoded['marks_partial'] ?></setvar>
				<displayfeedback linkrefid="general"/>
			</respcondition>
<?php elseif ($question->tolerance > 0) : ?>
      <respcondition title="Within range" >
				<conditionvar>
					<vargte respident="1"><?php echo $uansdata['tolerance_fullansneg'] ?></vargte>
					<varlte respident="1"><?php echo $uansdata['tolerance_fullans'] ?></varlte>
				</conditionvar>
				<setvar action="Set"><?php echo $settingsdecoded['marks_correct'] ?></setvar>
				<displayfeedback linkrefid="general"/>
			</respcondition>
<?php endif; ?>

			<respcondition title="wrong">
				<conditionvar>
					<not>
            <varequal respident="1"><?php echo $real_answer ?></varequal>
          </not>
				</conditionvar>
				<setvar action="Set"><?php echo $settingsdecoded['marks_incorrect'] ?></setvar>
				<displayfeedback linkrefid="general"/>
			</respcondition>
      
			<!-- force general feedback to output -->
			<respcondition title="General Feedback">
				<conditionvar>
					<or>
						<other/>
						<not>
							<other/>
						</not>
					</or>
				</conditionvar>
				<setvar action="Add">0</setvar>
				<displayfeedback linkrefid="general"/>
			</respcondition>
		</resprocessing>
		
		<itemfeedback ident="general" view="Candidate">
			<material>
				<mattext texttype="text/html"><![CDATA[<?php echo $question->feedback ?>]]></mattext>
			</material>
		</itemfeedback>
	</item>
